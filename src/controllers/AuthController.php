<?php

declare(strict_types=1);

namespace BridgeBoard\Controllers;

use BridgeBoard\Models\User;
use BridgeBoard\Services\AuthService;
use BridgeBoard\Services\ImageService;
use BridgeBoard\Services\Validation;

final class AuthController
{
    public function showRegister(): void
    {
        render('pages/register', ['title' => 'Join BridgeBoard']);
    }

    public function register(): void
    {
        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid session token.');
            redirect('/register');
        }

        $input = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? '',
            'bio' => trim($_POST['bio'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
        ];

        set_old_input($input);

        $errors = Validation::make($input, [
            'name' => 'required|min:3|max:120',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        if (User::findByEmail($input['email'])) {
            $errors['email'][] = 'An account with this email already exists.';
        }

        if (!empty($errors)) {
            flash('errors', $errors);
            redirect('/register');
        }

        $avatarPath = null;
        try {
            if (isset($_FILES['avatar'])) {
                $avatarPath = ImageService::handleUpload($_FILES['avatar']);
            }
        } catch (\Throwable $exception) {
            flash('error', $exception->getMessage());
            redirect('/register');
        }

        $userId = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password_hash' => password_hash($input['password'], PASSWORD_BCRYPT),
            'bio' => $input['bio'] ?: null,
            'location' => $input['location'] ?: null,
            'avatar_path' => $avatarPath,
        ]);

        clear_old_input();
        flash('success', 'Account created successfully!');

        AuthService::attempt($input['email'], $input['password']);
        redirect('/dashboard');
    }

    public function showLogin(): void
    {
        render('pages/login', ['title' => 'Welcome back']);
    }

    public function login(): void
    {
        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid session token.');
            redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        set_old_input(['email' => $email]);

        if (!$email || !$password) {
            flash('error', 'Email and password are required.');
            redirect('/login');
        }

        if (!AuthService::attempt($email, $password)) {
            flash('error', 'Invalid credentials.');
            redirect('/login');
        }

        clear_old_input();
        redirect('/dashboard');
    }

    public function logout(): void
    {
        AuthService::logout();
        flash('success', 'You have been logged out.');
        redirect('/');
    }
}
