<?php

namespace App\Controllers;

class AuthController
{
    public function loginForm(): string
    {
        if (is_authenticated()) {
            header('Location: ' . base_url('/admin'));
            return '';
        }
        $title = 'Connexion';
        return view('auth/login', compact('title'));
    }

    public function login(): string
    {
        require_csrf();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = 'Email et mot de passe requis';
            $title = 'Connexion';
            return view('auth/login', compact('title', 'error'));
        }

        $st = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $user = $st->fetch();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Identifiants invalides';
            $title = 'Connexion';
            return view('auth/login', compact('title', 'error'));
        }

        login_user($user);
        header('Location: ' . base_url('/admin'));
        return '';
    }

    public function logout(): string
    {
        logout_user();
        header('Location: ' . base_url('/login'));
        return '';
    }
}
