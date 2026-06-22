<?php

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function query_string(array $params = []): string
{
    $current = $_GET;
    foreach ($params as $key => $value) {
        $current[$key] = $value;
    }
    return http_build_query($current);
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function view(string $path, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../Views/' . $path . '.php';
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
        view('errors/403');
        exit;
    }
}
