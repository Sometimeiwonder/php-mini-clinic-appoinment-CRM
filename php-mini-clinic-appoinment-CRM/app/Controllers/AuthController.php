<?php

class AuthController
{
    private AuthService $service;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = Database::connect($config);
        $this->service = new AuthService(new UserRepository($pdo));
    }

    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            redirect('/dashboard');
        }

        if (!empty($_COOKIE['remember_me'])) {
            if ($this->service->loginWithRememberToken($_COOKIE['remember_me'])) {
                redirect('/dashboard');
            }
        }

        render('auth/login', [
            'title' => 'Login',
            'errors' => [],
            'old' => ['email' => ''],
        ]);
    }

    public function handleLogin(): void
    {
        verify_csrf();
        $remember = !empty($_POST['remember']);
        $result = $this->service->login(
            $_POST['email'] ?? '',
            $_POST['password'] ?? '',
            $remember
        );

        if (!$result['success']) {
            render('auth/login', [
                'title' => 'Login',
                'errors' => $result['errors'],
                'old' => ['email' => trim($_POST['email'] ?? '')],
            ]);
            return;
        }

        flash('success', 'Đăng nhập thành công.');
        redirect('/dashboard');
    }

    public function logout(): void
    {
        verify_csrf();
        $this->service->logout();
        redirect('/login?msg=logout');
    }
}
