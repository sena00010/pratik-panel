<?php
declare(strict_types=1);

final class AdminController
{
    private ContentRepository $repo;

    public function __construct()
    {
        $this->repo = new ContentRepository();
    }

    public function redirectToAdmin(): void
    {
        redirect(config('app.admin_path'));
    }

    public function dashboard(): void
    {
        if (!$this->isLoggedIn()) {
            View::render('admin/login', ['seo' => ['meta_title' => 'Yönetim']], 'layouts/admin');
            return;
        }

        $role = $_SESSION['admin_role'] ?? 'admin';

        // Blogger: only sees their own blog posts
        if ($role === 'blogger') {
            $myPosts = $this->repo->blogPostsByAuthor((int) $_SESSION['admin_id']);
            View::render('admin/blogger-dashboard', [
                'posts' => $myPosts,
                'role' => $role,
            ], 'layouts/admin');
            return;
        }

        // Pending posts for admin approval queue
        $pendingPosts = Database::pdo()->query("SELECT bp.*, au.display_name as author_name, au.username as author_username FROM blog_posts bp LEFT JOIN admin_users au ON bp.author_id = au.id WHERE bp.status = 'pending' ORDER BY bp.created_at DESC")->fetchAll();

        View::render('admin/dashboard', [
            'settings' => $this->repo->siteSettings(),
            'modules' => $this->repo->modules(false),
            'faqs' => $this->repo->faqs(false),
            'posts' => $this->repo->blogPosts(false),
            'pendingPosts' => $pendingPosts,
            'integrations' => $this->repo->integrations(false),
            'audienceCards' => $this->repo->audienceCards(false),
            'testimonials' => $this->repo->testimonials(false),
            'seoRows' => Database::pdo()->query('SELECT * FROM seo_meta ORDER BY page ASC, slug ASC')->fetchAll(),
            'landingRows' => Database::pdo()->query('SELECT * FROM landing_blocks ORDER BY id ASC')->fetchAll(),
            'adminUsers' => Database::pdo()->query('SELECT id, username, role, display_name, auto_approve, created_at FROM admin_users ORDER BY id ASC')->fetchAll(),
            'role' => $role,
        ], 'layouts/admin');
    }

    public function blogApprovalPage(): void
    {
        if (!$this->isLoggedIn()) {
            redirect(config('app.admin_path'));
            return;
        }
        if (($_SESSION['admin_role'] ?? '') === 'blogger') {
            header('Location: ' . admin_url(''));
            exit;
        }
        $pendingPosts = Database::pdo()->query("SELECT bp.*, au.display_name as author_name, au.username as author_username FROM blog_posts bp LEFT JOIN admin_users au ON bp.author_id = au.id WHERE bp.status = 'pending' ORDER BY bp.created_at DESC")->fetchAll();
        $allPosts = Database::pdo()->query("SELECT bp.*, au.display_name as author_name, au.username as author_username FROM blog_posts bp LEFT JOIN admin_users au ON bp.author_id = au.id ORDER BY bp.created_at DESC")->fetchAll();

        View::render('admin/blog-approval', [
            'pendingPosts' => $pendingPosts,
            'allPosts' => $allPosts,
            'role' => $_SESSION['admin_role'] ?? 'admin',
        ], 'layouts/admin');
    }

    public function login(): void
    {
        verify_csrf();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $loginRole = ($_POST['login_role'] ?? 'admin');
        $user = $this->repo->adminUser($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            $actualRole = $user['role'] ?? 'admin';

            // Role mismatch check
            if ($loginRole === 'blogger' && $actualRole !== 'blogger') {
                $_SESSION['flash'] = 'Bu hesap blog yazarı değil. Yönetim paneli olarak giriş yapın.';
                redirect(config('app.admin_path'));
                return;
            }
            if ($loginRole === 'admin' && $actualRole === 'blogger') {
                $_SESSION['flash'] = 'Bu hesap yönetici değil. Blog yazarı olarak giriş yapın.';
                redirect(config('app.admin_path'));
                return;
            }

            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int) $user['id'];
            $_SESSION['admin_role'] = $actualRole;
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_profile_photo'] = $user['profile_photo'] ?? null;
            $_SESSION['flash'] = $actualRole === 'blogger'
                ? 'Blog paneline hoş geldiniz.'
                : 'Yönetim paneline hoş geldiniz.';
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
        $role = $_SESSION['admin_role'] ?? 'admin';
        $data = $this->postData(['title', 'slug', 'summary', 'content', 'cover_image', 'meta_title', 'meta_description', 'published_at', 'is_published']);
        $slug = $data['slug'] !== '' ? slugify($data['slug']) : slugify($data['title']);
        $publishedAt = $data['published_at'] !== '' ? $data['published_at'] : date('Y-m-d H:i:s');
        $authorId = (int) ($_SESSION['admin_id'] ?? 0);

        // Handle cover image upload
        $coverImage = $data['cover_image'];
        if (!empty($_FILES['cover_image_file']['tmp_name']) && $_FILES['cover_image_file']['error'] === UPLOAD_ERR_OK) {
            $coverImage = $this->handleUpload($_FILES['cover_image_file']);
        }

        // Determine status based on role and auto_approve setting
        $status = 'draft';
        if (!empty($data['is_published'])) {
            if ($role === 'admin') {
                $status = 'approved';
            } else {
                // Check if blogger has auto_approve
                $autoCheck = Database::pdo()->prepare('SELECT auto_approve FROM admin_users WHERE id = ?');
                $autoCheck->execute([$authorId]);
                $autoApprove = (int) ($autoCheck->fetchColumn() ?: 0);
                $status = $autoApprove ? 'approved' : 'pending';
            }
        }
        $isPublished = ($status === 'approved') ? 1 : 0;

        if (!empty($_POST['id'])) {
            // Blogger can only edit their own posts
            if ($role === 'blogger') {
                $check = Database::pdo()->prepare('SELECT author_id, status FROM blog_posts WHERE id = ?');
                $check->execute([(int) $_POST['id']]);
                $existing = $check->fetch();
                if (!$existing || (int) $existing['author_id'] !== $authorId) {
                    $_SESSION['flash'] = 'Bu yazıyı düzenleme yetkiniz yok.';
                    redirect(config('app.admin_path'));
                    return;
                }
                // If post was approved, editing resets to pending (unless auto_approve)
                if ($existing['status'] === 'approved' && $status === 'pending') {
                    $status = 'pending';
                }
            }
            $stmt = Database::pdo()->prepare('UPDATE blog_posts SET title=?, slug=?, summary=?, content=?, cover_image=?, meta_title=?, meta_description=?, published_at=?, is_published=?, status=?, updated_at=NOW() WHERE id=?');
            $stmt->execute([$data['title'], $slug, $data['summary'], $data['content'], $coverImage, $data['meta_title'] ?: null, $data['meta_description'] ?: null, $publishedAt, $isPublished, $status, (int) $_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO blog_posts (title, slug, summary, content, cover_image, meta_title, meta_description, published_at, is_published, status, author_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$data['title'], $slug, $data['summary'], $data['content'], $coverImage, $data['meta_title'] ?: null, $data['meta_description'] ?: null, $publishedAt, $isPublished, $status, $authorId]);
        }

        $msg = $status === 'pending' ? 'Blog yazısı kaydedildi ve onay bekliyor.' : 'Blog yazısı kaydedildi.';
        $this->done($msg);
    }

    public function approveBlog(): void
    {
        $this->guardAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        Database::pdo()->prepare('UPDATE blog_posts SET status = "approved", is_published = 1 WHERE id = ?')->execute([$id]);
        cache_clear();
        $_SESSION['flash'] = 'Blog yazısı onaylandı ve yayına alındı.';
        redirect(config('app.admin_path') . '/blog-onay');
    }

    public function rejectBlog(): void
    {
        $this->guardAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        Database::pdo()->prepare('UPDATE blog_posts SET status = "rejected", is_published = 0 WHERE id = ?')->execute([$id]);
        cache_clear();
        $_SESSION['flash'] = 'Blog yazısı reddedildi.';
        redirect(config('app.admin_path') . '/blog-onay');
    }

    public function toggleAutoApprove(): void
    {
        $this->guardAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        Database::pdo()->prepare('UPDATE admin_users SET auto_approve = IF(auto_approve = 1, 0, 1) WHERE id = ? AND role = "blogger"')->execute([$id]);
        $this->done('Otomatik onay ayarı güncellendi.');
    }

    public function saveProfile(): void
    {
        $this->guard();
        $id = $_SESSION['admin_id'];
        $photo = trim((string)($_POST['profile_photo'] ?? ''));
        Database::pdo()->prepare('UPDATE admin_users SET profile_photo = ? WHERE id = ?')->execute([$photo ?: null, $id]);
        $_SESSION['admin_profile_photo'] = $photo ?: null;
        $this->done('Profil güncellendi.');
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
        $this->guardAdmin();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $role = in_array($_POST['role'] ?? '', ['admin', 'blogger'], true) ? $_POST['role'] : 'admin';
        $displayName = trim((string) ($_POST['display_name'] ?? ''));

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
        $autoApprove = !empty($_POST['auto_approve']) ? 1 : 0;
        $stmt = Database::pdo()->prepare('INSERT INTO admin_users (username, password_hash, role, display_name, auto_approve, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$username, $hash, $role, $displayName ?: null, $autoApprove]);

        $this->done(($role === 'blogger' ? 'Blogger' : 'Admin') . ' kullanıcı oluşturuldu.');
    }

    public function saveIntegration(): void
    {
        $this->guardAdmin();
        $data = $this->postData(['title', 'description', 'accent', 'status', 'sort_order', 'is_active']);

        if (!empty($_POST['id'])) {
            $stmt = Database::pdo()->prepare('UPDATE integrations SET title=?, description=?, accent=?, status=?, sort_order=?, is_active=? WHERE id=?');
            $stmt->execute([$data['title'], $data['description'], $data['accent'], $data['status'], (int) $data['sort_order'], (int) !empty($data['is_active']), (int) $_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO integrations (title, description, accent, status, sort_order, is_active) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$data['title'], $data['description'], $data['accent'], $data['status'], (int) $data['sort_order'], (int) !empty($data['is_active'])]);
        }
        $this->done('Entegrasyon kaydedildi.');
    }

    public function deleteIntegration(): void
    {
        $this->guardAdmin();
        Database::pdo()->prepare('DELETE FROM integrations WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        $this->done('Entegrasyon silindi.');
    }

    public function saveAudience(): void
    {
        $this->guardAdmin();
        $data = $this->postData(['title', 'description', 'accent', 'features', 'sort_order', 'is_active']);

        if (!empty($_POST['id'])) {
            $stmt = Database::pdo()->prepare('UPDATE audience_cards SET title=?, description=?, accent=?, features=?, sort_order=?, is_active=? WHERE id=?');
            $stmt->execute([$data['title'], $data['description'], $data['accent'], $data['features'], (int) $data['sort_order'], (int) !empty($data['is_active']), (int) $_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO audience_cards (title, description, accent, features, sort_order, is_active) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$data['title'], $data['description'], $data['accent'], $data['features'], (int) $data['sort_order'], (int) !empty($data['is_active'])]);
        }
        $this->done('Hedef kitle kartı kaydedildi.');
    }

    public function deleteAudience(): void
    {
        $this->guardAdmin();
        Database::pdo()->prepare('DELETE FROM audience_cards WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        $this->done('Hedef kitle kartı silindi.');
    }

    public function saveTestimonial(): void
    {
        $this->guardAdmin();
        $data = $this->postData(['author_name', 'author_title', 'author_location', 'author_initials', 'quote', 'rating', 'plan_badge', 'badge_color', 'sort_order', 'is_active']);

        if (!empty($_POST['id'])) {
            $stmt = Database::pdo()->prepare('UPDATE testimonials SET author_name=?, author_title=?, author_location=?, author_initials=?, quote=?, rating=?, plan_badge=?, badge_color=?, sort_order=?, is_active=? WHERE id=?');
            $stmt->execute([$data['author_name'], $data['author_title'], $data['author_location'], $data['author_initials'], $data['quote'], (int)($data['rating'] ?: 5), $data['plan_badge'], $data['badge_color'], (int)$data['sort_order'], (int)!empty($data['is_active']), (int)$_POST['id']]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO testimonials (author_name, author_title, author_location, author_initials, quote, rating, plan_badge, badge_color, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$data['author_name'], $data['author_title'], $data['author_location'], $data['author_initials'], $data['quote'], (int)($data['rating'] ?: 5), $data['plan_badge'], $data['badge_color'], (int)$data['sort_order'], (int)!empty($data['is_active'])]);
        }
        $this->done('Yorum kaydedildi.');
    }

    public function deleteTestimonial(): void
    {
        $this->guardAdmin();
        Database::pdo()->prepare('DELETE FROM testimonials WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
        $this->done('Yorum silindi.');
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

    private function guardAdmin(): void
    {
        $this->guard();
        if (($_SESSION['admin_role'] ?? 'admin') !== 'admin') {
            $_SESSION['flash'] = 'Bu işlem için yetkiniz yok.';
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
