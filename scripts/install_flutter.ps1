# Automated Flutter Installer for Windows

$installDir = "C:\flutter"
$zipPath = "$env:TEMP\flutter_sdk.zip"
$flutterUrl = "https://storage.googleapis.com/flutter_infra_release/releases/stable/windows/flutter_windows_3.22.2-stable.zip"

Write-Host "==========================================" -ForegroundColor Green
Write-Host "Starting Automated Flutter SDK Installation" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green

# 1. Check if already exists
if (Test-Path $installDir) {
    Write-Host "Folder $installDir already exists. Skipping download." -ForegroundColor Yellow
} else {
    Write-Host "Downloading Flutter SDK (~1 GB)... This might take a few minutes depending on your internet connection." -ForegroundColor Cyan
    try {
        [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
        Invoke-WebRequest -Uri $flutterUrl -OutFile $zipPath -UserAgent "Mozilla/5.0 (Windows NT 10.0; Win64; x64)" -TimeoutSec 600
        Write-Host "Download complete!" -ForegroundColor Green
    } catch {
        Write-Host "Download failed. Retrying using fallback WebClient..." -ForegroundColor Yellow
        try {
            $webClient = New-Object System.Net.WebClient
            $webClient.Headers.Add("User-Agent", "Mozilla/5.0")
            $webClient.DownloadFile($flutterUrl, $zipPath)
            Write-Host "Download complete!" -ForegroundColor Green
        } catch {
            Write-Error "Failed to download Flutter SDK. Please check your internet connection."
            exit 1
        }
    }

    Write-Host "Extracting Flutter SDK to C:\..." -ForegroundColor Cyan
    try {
        Expand-Archive -Path $zipPath -DestinationPath "C:\" -Force
        Write-Host "Extraction complete!" -ForegroundColor Green
        # Clean up zip
        Remove-Item $zipPath -Force
    } catch {
        Write-Error "Failed to extract zip file."
        exit 1
    }
}

# 2. Add to PATH
Write-Host "Adding Flutter to User Environment PATH..." -ForegroundColor Cyan
$userPath = [System.Environment]::GetEnvironmentVariable("Path", "User")
if ($userPath -like "*C:\flutter\bin*") {
    Write-Host "Flutter is already in your PATH." -ForegroundColor Yellow
} else {
    $newPath = $userPath + ";C:\flutter\bin"
    [System.Environment]::SetEnvironmentVariable("Path", $newPath, "User")
    Write-Host "Added C:\flutter\bin to User Environment PATH successfully!" -ForegroundColor Green
    Write-Host "NOTE: You will need to restart your terminal or VS Code for the changes to take effect." -ForegroundColor Yellow
}

Write-Host "==========================================" -ForegroundColor Green
Write-Host "Installation Complete! Please restart VS Code." -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green
