<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function config(string $key, $default = null)
{
    static $configs = [];
    if (str_contains($key, '.')) {
        [$file, $nested] = explode('.', $key, 2);
        if (!isset($configs[$file])) {
            $configs[$file] = require __DIR__ . '/../../config/' . $file . '.php';
        }
        return $configs[$file][$nested] ?? $default;
    }
    if (!isset($configs[$key])) {
        $configs[$key] = require __DIR__ . '/../../config/' . $key . '.php';
    }
    return $configs[$key];
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function render(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data);
    ob_start();
    require __DIR__ . '/../Views/' . $view . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../Views/' . $layout . '.php';
}

function partial(string $name): void
{
    require __DIR__ . '/../Views/partials/' . $name . '.php';
}

function flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string
{
    if (empty($_SESSION['flash'][$key])) return null;
    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $message;
}

function old(string $key, string $default = ''): string
{
    return $_SESSION['old_input'][$key] ?? $default;
}

function set_old(array $data): void
{
    $_SESSION['old_input'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['old_input']);
}

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        flash('error', 'Vui lòng đăng nhập để truy cập trang này.');
        redirect('/login');
    }
    $timeout = 1800;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        redirect('/login?msg=timeout');
    }
    $_SESSION['last_activity'] = time();
}

function require_admin(): void
{
    require_login();
    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        http_response_code(403);
        render('errors/403', ['title' => '403 Forbidden']);
        exit;
    }
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function log_error(string $message, ?Throwable $exception = null): void
{
    $logFile = __DIR__ . '/../../storage/logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
    $uri = $_SERVER['REQUEST_URI'] ?? '-';

    $entry = "[{$timestamp}] [{$ip}] [{$method}] {$uri} - {$message}";
    if ($exception) {
        $entry .= " | Exception: " . get_class($exception) . ": " . $exception->getMessage();
        $entry .= " | File: " . $exception->getFile() . ":" . $exception->getLine();
    }
    $entry .= PHP_EOL;

    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

function log_login_failed(string $email): void
{
    $logFile = __DIR__ . '/../../storage/logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
    $uri = $_SERVER['REQUEST_URI'] ?? '-';

    $entry = "[{$timestamp}] [{$ip}] [{$method}] {$uri} - LOGIN FAILED for email: {$email}" . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        log_error('CSRF token mismatch');
        render('errors/403', ['title' => '403 Forbidden']);
        exit;
    }
}

function honeypot_field(): string
{
    return '<div style="position:absolute;left:-9999px;" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>';
}

function check_honeypot(): void
{
    if (!empty($_POST['website'])) {
        log_error('Honeypot triggered');
        http_response_code(403);
        render('errors/403', ['title' => '403 Forbidden']);
        exit;
    }
}

function check_rate_limit(int $seconds = 5, string $backTo = '/'): void
{
    $now = time();
    if (isset($_SESSION['last_submit']) && ($now - $_SESSION['last_submit']) < $seconds) {
        flash('error', 'Vui lòng chờ ' . $seconds . ' giây trước khi gửi lại.');
        redirect($backTo);
    }
    $_SESSION['last_submit'] = $now;
}
