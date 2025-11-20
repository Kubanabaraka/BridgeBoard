<?php

declare(strict_types=1);

namespace BridgeBoard\Controllers;

use BridgeBoard\Models\Message;
use BridgeBoard\Models\SkillPost;
use BridgeBoard\Services\AuthService;

final class DashboardController
{
    public function index(): void
    {
        $this->guard();

        $user = AuthService::user();
        $posts = SkillPost::forUser((int) $user['id']);
        $messages = Message::forUser((int) $user['id']);

        render('pages/dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'posts' => $posts,
            'messages' => $messages,
        ]);
    }

    private function guard(): void
    {
        if (!AuthService::check()) {
            flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }
}
