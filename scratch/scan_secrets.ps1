# Secret / API-key scanner
# Scans source-like files for common API key / token / secret patterns.
# Usage: powershell -NoProfile -ExecutionPolicy Bypass -File scratch/scan_secrets.ps1

$ErrorActionPreference = 'SilentlyContinue'

$patterns = '(sk[-_])?(live|test)_[A-Za-z0-9]{20,}|AKIA[0-9A-Z]{16}|AIza[0-9A-Za-z_-]{30,}|xox[baprs]-|ghp_[0-9A-Za-z]{30,}|eyJ[A-Za-z0-9_-]{20,}\.[A-Za-z0-9_-]{10,}'
$exclude  = 'node_modules|vendor|flutter_app/build|\.git|scratch|graphify-out|dump\.rdb'

$files = Get-ChildItem -Recurse -Include *.php,*.env,*.example,*.sh,*.dart,*.py,*.js -Path . -ErrorAction SilentlyContinue |
    Where-Object { $_.FullName -notmatch $exclude }

$results = $files | Select-String -Pattern $patterns -ErrorAction SilentlyContinue

if ($results) {
    Write-Host "=== POTENTIAL SECRETS FOUND ($($results.Count) match(es)) ==="
    $results | Select-Object -First 40 Path, LineNumber, Line | Format-List
} else {
    Write-Host "No potential secrets found."
}

