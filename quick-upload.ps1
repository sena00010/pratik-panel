$localFile = Join-Path $PSScriptRoot "index.php"
$host_ = "ftp.mayancur.com"
$user = "pratikgumrukuser@pratikgumruk.com" 
$pass = "Krallar.2026*-"

$ftpUri = "ftp://${host_}/index.php" 

Write-Host "Yükleme deneniyor: $user" -ForegroundColor Cyan

try {
    $webclient = New-Object System.Net.WebClient
    $webclient.Credentials = New-Object System.Net.NetworkCredential($user, $pass)
    
    # Pasif mod varsayilan olarak kullanilir
    
    $webclient.UploadFile($ftpUri, $localFile)
    Write-Host "BAŞARILI! Pratik Gümrük güncellendi." -ForegroundColor Green
} catch {
    Write-Host "Hata Mesajı: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.Exception.InnerException) {
        Write-Host "Detay: $($_.Exception.InnerException.Message)" -ForegroundColor Yellow
    }
}