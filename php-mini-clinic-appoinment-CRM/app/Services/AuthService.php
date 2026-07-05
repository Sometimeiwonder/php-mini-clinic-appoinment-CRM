<?php

class AuthService
{
    public function __construct(private UserRepository $repo) {}

    public function login(string $email, string $password, bool $remember = false): array
    {
        $email = trim($email);
        if ($email === '' || $password === '') {
            return ['success' => false, 'errors' => ['general' => 'Vui lòng nhập email và mật khẩu.']];
        }

        $user = $this->repo->findActiveByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            log_login_failed($email);
            return ['success' => false, 'errors' => ['general' => 'Email hoặc mật khẩu không đúng.']];
        }

        
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->repo->setRememberToken($user['id'], $token);
            setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
        }

        return ['success' => true, 'user' => $user];
    }

    public function loginWithRememberToken(string $token): bool
    {
        $user = $this->repo->findByRememberToken($token);
        if (!$user) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        return true;
    }

    public function logout(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->repo->clearRememberToken($_SESSION['user_id']);
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path']);
        }
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        session_destroy();
    }
}
