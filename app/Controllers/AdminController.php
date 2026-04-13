<?php
declare(strict_types=1);

final class AdminController
{
    private ContentRepository $repo;

    public function __construct()
    {
        $this->repo = new ContentRepository();
    }

    public function dashboard(): void
    {
        if (!$this->isLoggedIn()) {
            View::render('admin/login', ['seo' => ['meta_title' => 'Yönetim']], 'layouts/admin');
            return;
        }

        View::render('admin/dashboard', [
            'settings' => $this->repo->siteSettings(),
            'modules' => $this->repo->modules(false),
            'faqs' => $this->repo->faqs(false),
            'posts' => $this->repo->blogPosts(false),
            'seoRows' => Database::pdo()->query('SELECT * FROM seo_meta ORDER BY page ASC, slug ASC')->fetchAll(),
            'landingRows' => Database::pdo()->query('SELECT * FROM landing_blocks ORDER BY id ASC')->fetchAll(),
            'adminUsers' => Database::pdo()->query('SELECT id, username, created_at FROM admin_users ORDER BY id ASC')->fetchAll(),
        ], 'layouts/admin');
    }

    public function login(): void
    {
        verify_csrf();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = $this->repo->adminUser($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int) $user['id'];
            $_SESSION['flash'] = 'Yönetim paneline hoş geldiniz.';
        } else {
            $_SESSION['flash'] = 'Kullanıcı adı veya şifre hatalı.';
        }

        redirect(config('app.admin_path'));
    }

    public function logout(): void
    {
        verify_csrf();
        unset($_SESSION['admin_id']);
        redirect(config('app.admin_path'));
    }

    public function saveModule(): void
    {
        $this->guard();
        $data = $this->postData(['title', 'slug', 'eyebrow', 'icon', 'accent', 'summary', 'features', 'detail_content', 'sort_order', 'is_active']);
        $slug = $data['slug'] !== '' ? slugify($data['slug']) : slugify($data['title']);
        $features = $this->linesToJson($data['features']);

        if (!empty($_POST['id'])) {
            $stmt = Database::pdo()->prepare('UPDATE modules SET title=?, slug=?, eyebrow=?, icon=?, accent=?, summary=?, features=?, detail_content=?, sort_order=?, is_active=? WHERE id=?');
            $stmt->execute([$data['title'], $slug, $data['eyebrow'], $data['icon'], $data['accent'], $data['summary'], $features, $data['detail_content'], (int) $data['sort_order'], (int) !empty($data['is_active']), (int) $_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO modules (title, slug, eyebrow, icon, accent, summary, features, detail_content, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$data['title'], $slug, $data['eyebrow'], $data['icon'], $data['accent'], $data['summary'], $features, $data['detail_content'], (int) $data['sort_order'], (int) !empty($data['is_active'])]);
        }

        $this->done('Modül kaydedildi.');
    }

    public function deleteModule(): void
    {
        $this->guard();
        Database::pdo()->prepare('DELETE FROM modules WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        $this->done('Modül silindi.');
    }

    public function saveFaq(): void
    {
        $this->guard();
        $data = $this->postData(['question', 'answer', 'sort_order', 'is_active']);
        if (!empty($_POST['id'])) {
            $stmt = Database::pdo()->prepare('UPDATE faqs SET question=?, answer=?, sort_order=?, is_active=? WHERE id=?');
            $stmt->execute([$data['question'], $data['answer'], (int) $data['sort_order'], (int) !empty($data['is_active']), (int) $_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO faqs (question, answer, sort_order, is_active) VALUES (?,?,?,?)');
            $stmt->execute([$data['question'], $data['answer'], (int) $data['sort_order'], (int) !empty($data['is_active'])]);
        }

        $this->done('SSS kaydedildi.');
    }

    public function deleteFaq(): void
    {
        $this->guard();
        Database::pdo()->prepare('DELETE FROM faqs WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        $this->done('SSS silindi.');
    }

    public function saveBlog(): void
    {
        $this->guard();
        $data = $this->postData(['title', 'slug', 'summary', 'content', 'cover_image', 'published_at', 'is_published']);
        $slug = $data['slug'] !== '' ? slugify($data['slug']) : slugify($data['title']);
        $publishedAt = $data['published_at'] !== '' ? $data['published_at'] : date('Y-m-d H:i:s');

        // Handle cover image upload
        $coverImage = $data['cover_image'];
        if (!empty($_FILES['cover_image_file']['tmp_name']) && $_FILES['cover_image_file']['error'] === UPLOAD_ERR_OK) {
            $coverImage = $this->handleUpload($_FILES['cover_image_file']);
        }

        if (!empty($_POST['id'])) {
            $stmt = Database::pdo()->prepare('UPDATE blog_posts SET title=?, slug=?, summary=?, content=?, cover_image=?, published_at=?, is_published=? WHERE id=?');
            $stmt->execute([$data['title'], $slug, $data['summary'], $data['content'], $coverImage, $publishedAt, (int) !empty($data['is_published']), (int) $_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO blog_posts (title, slug, summary, content, cover_image, published_at, is_published) VALUES (?,?,?,?,?,?,?)');
            $stmt->execute([$data['title'], $slug, $data['summary'], $data['content'], $coverImage, $publishedAt, (int) !empty($data['is_published'])]);
        }

        $this->done('Blog yazısı kaydedildi.');
    }

    public function uploadImage(): void
    {
        $this->guard();
        header('Content-Type: application/json');

        if (empty($_FILES['image']['tmp_name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['error' => 'Dosya yüklenemedi.']);
            exit;
        }

        $path = $this->handleUpload($_FILES['image']);
        echo json_encode(['url' => asset($path)]);
        exit;
    }

    private function handleUpload(array $file): string
    {
        $uploadDir = __DIR__ . '/../../public/assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (!in_array($ext, $allowed, true)) {
            $_SESSION['flash'] = 'Desteklenmeyen dosya formatı: ' . $ext;
            redirect(config('app.admin_path'));
        }

        $filename = uniqid('img_', true) . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return 'uploads/' . $filename;
    }

    public function deleteBlog(): void
    {
        $this->guard();
        Database::pdo()->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        $this->done('Blog yazısı silindi.');
    }

    public function saveSeo(): void
    {
        $this->guard();
        $data = $this->postData(['page', 'slug', 'meta_title', 'meta_description', 'meta_keywords', 'og_image']);
        $slug = $data['slug'] !== '' ? $data['slug'] : '';

        if (!empty($_POST['id'])) {
            $stmt = Database::pdo()->prepare('UPDATE seo_meta SET page=?, slug=?, meta_title=?, meta_description=?, meta_keywords=?, og_image=? WHERE id=?');
            $stmt->execute([$data['page'], $slug, $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['og_image'], (int) $_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO seo_meta (page, slug, meta_title, meta_description, meta_keywords, og_image) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$data['page'], $slug, $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['og_image']]);
        }

        $this->done('SEO kaydı güncellendi.');
    }

    public function saveLanding(): void
    {
        $this->guard();

        try {
            // Handle structured hero inputs
            foreach ($_POST['landing_fields'] ?? [] as $id => $fields) {
                $id = (int) $id;
                if ($id <= 0) continue;
                $payload = json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                $stmt = Database::pdo()->prepare('UPDATE landing_blocks SET payload = ? WHERE id = ?');
                $stmt->execute([$payload, $id]);
            }

            // Handle raw JSON payloads (fallback for non-hero blocks)
            foreach ($_POST['payload'] ?? [] as $id => $payload) {
                $id = (int) $id;
                $payload = trim((string) $payload);
                if ($id <= 0 || $payload === '') {
                    continue;
                }

                json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
                $stmt = Database::pdo()->prepare('UPDATE landing_blocks SET payload = ? WHERE id = ?');
                $stmt->execute([$payload, $id]);
            }
        } catch (JsonException $exception) {
            $_SESSION['flash'] = 'JSON formatı hatalı: ' . $exception->getMessage();
            redirect(config('app.admin_path'));
        }

        $this->done('Landing sayfası blokları güncellendi.');
    }

    public function saveAdmin(): void
    {
        $this->guard();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['flash'] = 'Kullanıcı adı ve şifre gereklidir.';
            redirect(config('app.admin_path'));
        }

        $existing = $this->repo->adminUser($username);
        if ($existing) {
            $_SESSION['flash'] = 'Bu kullanıcı adı zaten mevcut.';
            redirect(config('app.admin_path'));
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = Database::pdo()->prepare('INSERT INTO admin_users (username, password_hash, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$username, $hash]);

        $this->done('Yeni admin kullanıcı oluşturuldu.');
    }

    public function deleteAdmin(): void
    {
        $this->guard();
        $id = (int) ($_POST['id'] ?? 0);

        // Prevent deleting yourself
        if ($id === (int) ($_SESSION['admin_id'] ?? 0)) {
            $_SESSION['flash'] = 'Kendi hesabınızı silemezsiniz.';
            redirect(config('app.admin_path'));
        }

        Database::pdo()->prepare('DELETE FROM admin_users WHERE id = ?')->execute([$id]);
        $this->done('Admin kullanıcı silindi.');
    }

    private function isLoggedIn(): bool
    {
        return !empty($_SESSION['admin_id']);
    }

    private function guard(): void
    {
        verify_csrf();
        if (!$this->isLoggedIn()) {
            redirect(config('app.admin_path'));
        }
    }

    private function postData(array $keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = trim((string) ($_POST[$key] ?? ''));
        }
        return $data;
    }

    private function linesToJson(string $text): string
    {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\R/u', $text) ?: [])));
        return json_encode($lines, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    private function done(string $message): never
    {
        cache_clear();
        $_SESSION['flash'] = $message;
        redirect(config('app.admin_path'));
    }
}
