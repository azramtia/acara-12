<?php

class AuthController
{
    public function loginForm()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function process()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === '12345') {
            $_SESSION['user'] = $username;

            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $error = 'Username atau password salah.';
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function logout()
    {
        session_destroy();

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}