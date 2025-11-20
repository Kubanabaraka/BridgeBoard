<?php

declare(strict_types=1);

namespace BridgeBoard\Controllers;

use BridgeBoard\Models\SkillPost;
use BridgeBoard\Models\User;
use BridgeBoard\Services\AuthService;
use BridgeBoard\Services\ImageService;
use BridgeBoard\Services\Validation;

final class ProfileController
{
    public function show(?int $userId = null): void
    {
        $user = $userId ? User::findById($userId) : AuthService::user();
        if (!$user) {
            http_response_code(404);
            render('pages/404', ['title' => 'Profile not found']);
            return;
        }

        $posts = SkillPost::forUser((int) $user['id']);

        render('pages/profile', [
            'title' => $userId ? $user['name'] : 'My profile',
            'profileUser' => $user,
            'posts' => $posts,
            'canEdit' => AuthService::check() && (int) AuthService::id() === (int) $user['id'],
        ]);
    }

    public function update(): void
    {
        $this->guard();

        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid session token.');
            redirect('/profile');
        }

        $input = [
            'name' => trim($_POST['name'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
        ];

        $errors = Validation::make($input, [
            'name' => 'required|min:3|max:120',
        ]);

        if (!empty($errors)) {
            flash('errors', $errors);
            redirect('/profile');
        }

        $avatarPath = null;
        try {
            if (isset($_FILES['avatar'])) {
                $avatarPath = ImageService::handleUpload($_FILES['avatar']);
            }
        } catch (\Throwable $exception) {
            flash('error', $exception->getMessage());
            redirect('/profile');
        }

        User::updateProfile(AuthService::id(), [
            'name' => $input['name'],
            'bio' => $input['bio'] ?: null,
            'location' => $input['location'] ?: null,
            'avatar_path' => $avatarPath ?: (AuthService::user()['avatar_path'] ?? null),
        ]);

        flash('success', 'Profile updated.');
        redirect('/profile');
    }

    private function guard(): void
    {
        if (!AuthService::check()) {
            flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }
}
