#!/usr/bin/env sh
set -e

# Set defaults for Railway deployment
# These should be set in Railway Variables, but provide sensible defaults
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="true"
export APP_URL="${APP_URL:-https://amiga-travel-production.up.railway.app}"
export APP_NAME="${APP_NAME:-Amiga Travel}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export CACHE_STORE="${CACHE_STORE:-database}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

# Database - fallback to Railway MYSQL env vars if DB_* not set explicitly
export DB_CONNECTION="${DB_CONNECTION:-mysql}"
export DB_HOST="${DB_HOST:-${MYSQLHOST:-${MYSQL_HOST:-sakura.proxy.rlwy.net}}}"
export DB_PORT="${DB_PORT:-${MYSQLPORT:-${MYSQL_PORT:-43993}}}"
export DB_DATABASE="${DB_DATABASE:-${MYSQLDATABASE:-${MYSQL_DATABASE:-railway}}}"
export DB_USERNAME="${DB_USERNAME:-${MYSQLUSER:-${MYSQL_USER:-root}}}"
export DB_PASSWORD="${DB_PASSWORD:-${MYSQLPASSWORD:-${MYSQL_ROOT_PASSWORD:-BIMPMSZRxyaizrljoaKdBoAixcTWShuP}}}"

# Mail settings
export MAIL_MAILER="${MAIL_MAILER:-smtp}"
export MAIL_HOST="${MAIL_HOST:-smtp.gmail.com}"
export MAIL_PORT="${MAIL_PORT:-587}"
export MAIL_ENCRYPTION="${MAIL_ENCRYPTION:-tls}"
export MAIL_USERNAME="${MAIL_USERNAME}"
export MAIL_PASSWORD="${MAIL_PASSWORD}"
export MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS}"
export RESEND_API_KEY="${RESEND_API_KEY}"

export NOCAPTCHA_SITEKEY="${NOCAPTCHA_SITEKEY}"
export NOCAPTCHA_SECRET="${NOCAPTCHA_SECRET}"
export MAIL_FROM_NAME="${MAIL_FROM_NAME}"
export MAIL_SCHEME="${MAIL_SCHEME}"

# Handle Firebase Credentials safely to avoid .env parsing errors
if [ -n "$FIREBASE_CREDENTIALS" ]; then
    echo "=== Writing Firebase credentials via PHP parser ==="
    # Use PHP to properly parse the JSON blob (handles actual newlines in private_key)
    # and write a clean, valid JSON file.
    php -r "
\$raw = getenv('FIREBASE_CREDENTIALS');
// Strip surrounding double-quotes Railway may add
\$raw = trim(\$raw);
if (isset(\$raw[0]) && \$raw[0] === '\"') { \$raw = substr(\$raw, 1); }
if (strlen(\$raw) > 0 && substr(\$raw, -1) === '\"') { \$raw = substr(\$raw, 0, -1); }

// First try: parse as-is
\$decoded = json_decode(\$raw, true);

// Second try: replace actual newlines with \\n escape sequences
if (!\$decoded) {
    \$fixed = str_replace(\"\\n\", \"\\\\n\", \$raw);
    \$decoded = json_decode(\$fixed, true);
}

// Third try: replace \\\\n with \\n (double-escaped)
if (!\$decoded) {
    \$fixed2 = str_replace('\\\\n', \"\\n\", \$raw);
    \$decoded = json_decode(\$fixed2, true);
}

if (is_array(\$decoded)) {
    file_put_contents('/var/www/html/storage/firebase-auth.json', json_encode(\$decoded));
    echo 'Firebase: credentials written as valid JSON (' . strlen(json_encode(\$decoded)) . ' bytes)' . PHP_EOL;
} else {
    // Fallback: write raw content (Firebase SDK will report the parse error)
    file_put_contents('/var/www/html/storage/firebase-auth.json', \$raw);
    echo 'Firebase WARNING: could not parse as JSON (' . json_last_error_msg() . '), wrote raw content' . PHP_EOL;
}
"
    export FIREBASE_CREDENTIALS_PATH="/var/www/html/storage/firebase-auth.json"
    # CRITICAL: unset the raw JSON blob from the process environment.
    # phpdotenv does NOT override existing env vars, so if FIREBASE_CREDENTIALS
    # is still set as the blob, env('FIREBASE_CREDENTIALS') would return the blob
    # instead of the file path we set in .env. Unsetting forces phpdotenv to use
    # the file path value from .env at runtime.
    unset FIREBASE_CREDENTIALS
    echo "=== FIREBASE_CREDENTIALS unset from process env (PHP-FPM will use .env value) ==="
else
    echo "=== WARNING: FIREBASE_CREDENTIALS env var is empty! Push notifications will not work. ==="
    export FIREBASE_CREDENTIALS_PATH=""
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
  echo "=== APP_KEY not found in environment, generating one... ==="
  # Write a temporary .env so key:generate has something to modify
  echo "APP_KEY=" > /var/www/html/.env
  php artisan key:generate --force --no-ansi 2>&1 || true
  # Read the freshly generated key back
  if [ -f /var/www/html/.env ]; then
    APP_KEY=$(grep "^APP_KEY=" /var/www/html/.env | sed 's/^APP_KEY=//')
  fi
  echo "=== Generated APP_KEY: ${APP_KEY:0:20}... ==="
fi

# Validate APP_KEY is not empty before proceeding
if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY is still empty after generation attempt. Set APP_KEY in Railway Variables!" >&2
fi

# Create .env file in container from Railway environment variables
# This overrides any local .env that was copied into the image.
# IMPORTANT: APP_KEY must be set as a Railway Variable to persist across deploys.
#
# NOTE: We write the .env through PHP to guarantee valid Dotenv syntax.
# A plain heredoc in POSIX sh cannot safely escape arbitrary values that
# may contain whitespace, double-quotes, backslashes or dollar signs
# (e.g. MAIL_FROM_NAME, DB_PASSWORD, long API keys), and Dotenv's parser
# will reject the whole file with "Encountered unexpected whitespace ...".
# PHP's var_export() with PREG escaping for the key produces correct output
# for every possible value type.
php -r '
function envv($k, $default = "") {
    $v = getenv($k);
    if ($v === false || $v === "") {
        $v = $default;
    }
    return (string) $v;
}
$env = [
    "APP_NAME"            => envv("APP_NAME", "Amiga Travel"),
    "APP_ENV"             => envv("APP_ENV", "production"),
    "APP_DEBUG"           => envv("APP_DEBUG", "true"),
    "APP_KEY"             => envv("APP_KEY"),
    "APP_URL"             => envv("APP_URL", "https://amiga-travel-production.up.railway.app"),
    "APP_LOCALE"          => envv("APP_LOCALE", "en"),
    "APP_FALLBACK_LOCALE" => envv("APP_FALLBACK_LOCALE", "en"),
    "APP_FAKER_LOCALE"    => envv("APP_FAKER_LOCALE", "en_US"),
    "APP_MAINTENANCE_DRIVER" => envv("APP_MAINTENANCE_DRIVER", "file"),

    "BCRYPT_ROUNDS" => "12",
    "LOG_CHANNEL"   => envv("LOG_CHANNEL", "stack"),
    "LOG_LEVEL"     => envv("LOG_LEVEL", "debug"),

    "DB_CONNECTION" => envv("DB_CONNECTION", "mysql"),
    "DB_HOST"       => envv("DB_HOST"),
    "DB_PORT"       => envv("DB_PORT"),
    "DB_DATABASE"   => envv("DB_DATABASE"),
    "DB_USERNAME"   => envv("DB_USERNAME"),
    "DB_PASSWORD"   => envv("DB_PASSWORD"),

    "SESSION_DRIVER"  => envv("SESSION_DRIVER", "database"),
    "CACHE_STORE"     => envv("CACHE_STORE", "database"),
    "QUEUE_CONNECTION" => envv("QUEUE_CONNECTION", "database"),

    "MAIL_MAILER"      => envv("MAIL_MAILER", "smtp"),
    "MAIL_HOST"        => envv("MAIL_HOST", "smtp.gmail.com"),
    "MAIL_PORT"        => envv("MAIL_PORT", "587"),
    "MAIL_USERNAME"    => envv("MAIL_USERNAME"),
    "MAIL_PASSWORD"    => envv("MAIL_PASSWORD"),
    "MAIL_ENCRYPTION"  => envv("MAIL_ENCRYPTION", "tls"),
    "MAIL_FROM_ADDRESS"=> envv("MAIL_FROM_ADDRESS"),
    "RESEND_API_KEY"   => envv("RESEND_API_KEY"),

    "NOCAPTCHA_SITEKEY"    => envv("NOCAPTCHA_SITEKEY"),
    "NOCAPTCHA_SECRET"     => envv("NOCAPTCHA_SECRET"),
    "FIREBASE_CREDENTIALS" => envv("FIREBASE_CREDENTIALS_PATH"),
    "MAIL_FROM_NAME"       => envv("MAIL_FROM_NAME"),
    "MAIL_SCHEME"          => envv("MAIL_SCHEME"),
    "SENDGRID_API_KEY"     => envv("SENDGRID_API_KEY"),

    "FILESYSTEM_DISK"     => envv("FILESYSTEM_DISK", "local"),
    "BROADCAST_CONNECTION"=> envv("BROADCAST_CONNECTION", "log"),
];
$out = "";
foreach ($env as $k => $v) {
    // Dotenv double-quoted value: escape \ and ", then wrap in "..."
    $escaped = str_replace(["\\", "\""], ["\\\\", "\\\""], (string) $v);
    $out .= $k . "=\"" . $escaped . "\"\n";
}
file_put_contents("/var/www/html/.env", $out);
echo ".env written via PHP writer. APP_KEY length: " . strlen($env["APP_KEY"]) . " chars\n";
'

unset -v APP_NAME_TMP 2>/dev/null || true
echo "=== .env regeneration complete ==="

# Dynamically configure Nginx to listen on Railway's assigned $PORT
PORT="${PORT:-10000}"
echo "=== Configuring Nginx port to $PORT ==="
sed -i "s/listen [0-9]*;/listen ${PORT};/g" /etc/nginx/http.d/default.conf 2>/dev/null || true

# Run migrations and setup
timeout 60 php artisan migrate --force --no-interaction || echo "Migrations skipped or timed out"
php artisan storage:link || true

echo "=== Reached config cache step ==="
php artisan clear-compiled || true
php artisan config:clear || true
php artisan config:cache || true
php artisan route:clear || true
php artisan view:cache || true
php artisan event:clear || true
php artisan package:discover --ansi || true

echo "=== Starting Supervisor (Nginx + PHP-FPM + Queue Worker) ==="
exec supervisord -c /var/www/html/supervisord.conf
