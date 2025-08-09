# PowerShell script to fix SSL certificate issue for DeepL API
Write-Host "🔧 Fixing SSL Certificate Configuration for DeepL API..." -ForegroundColor Yellow

# PHP ini file path
$phpIniPath = "C:\Php\php-8.4.3-Win32-vs17-x64\php.ini"
$cacertPath = "C:\laragon\etc\ssl\cacert.pem"

# Check if files exist
if (!(Test-Path $phpIniPath)) {
    Write-Host "❌ PHP ini file not found at: $phpIniPath" -ForegroundColor Red
    exit 1
}

if (!(Test-Path $cacertPath)) {
    Write-Host "❌ CA certificate file not found at: $cacertPath" -ForegroundColor Red
    exit 1
}

# Backup the original php.ini
$backupPath = $phpIniPath + ".backup_" + (Get-Date -Format "yyyyMMdd_HHmmss")
Copy-Item $phpIniPath $backupPath
Write-Host "✅ Backup created: $backupPath" -ForegroundColor Green

# Read current php.ini content
$iniContent = Get-Content $phpIniPath

# Check if SSL settings already exist
$curlCaInfoExists = $iniContent | Where-Object { $_ -match "^curl\.cainfo\s*=" }
$opensslCaFileExists = $iniContent | Where-Object { $_ -match "^openssl\.cafile\s*=" }

# Add or update SSL settings
$newContent = @()
$added = $false

foreach ($line in $iniContent) {
    if ($line -match "^;?\s*curl\.cainfo\s*=") {
        $newContent += "curl.cainfo = `"$cacertPath`""
        Write-Host "✅ Updated curl.cainfo setting" -ForegroundColor Green
    }
    elseif ($line -match "^;?\s*openssl\.cafile\s*=") {
        $newContent += "openssl.cafile = `"$cacertPath`""
        Write-Host "✅ Updated openssl.cafile setting" -ForegroundColor Green
    }
    else {
        $newContent += $line
    }
}

# If settings don't exist, add them at the end
if (!$curlCaInfoExists) {
    $newContent += ""
    $newContent += "; SSL Certificate settings for DeepL API"
    $newContent += "curl.cainfo = `"$cacertPath`""
    Write-Host "✅ Added curl.cainfo setting" -ForegroundColor Green
}

if (!$opensslCaFileExists) {
    $newContent += "openssl.cafile = `"$cacertPath`""
    Write-Host "✅ Added openssl.cafile setting" -ForegroundColor Green
}

# Write the updated content back to php.ini
Set-Content -Path $phpIniPath -Value $newContent

Write-Host ""
Write-Host "🎉 SSL Certificate configuration completed!" -ForegroundColor Green
Write-Host "📝 Next steps:" -ForegroundColor Yellow
Write-Host "   1. Restart Laragon (stop and start Apache/Nginx)" -ForegroundColor White
Write-Host "   2. Run: php artisan deepl:test" -ForegroundColor White
Write-Host ""
Write-Host "🔙 If there are issues, restore from backup:" -ForegroundColor Cyan
Write-Host "   Copy-Item backup_file php_ini_file" -ForegroundColor White
