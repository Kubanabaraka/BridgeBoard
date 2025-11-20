<?php

declare(strict_types=1);

namespace BridgeBoard\Controllers;

use BridgeBoard\Models\Message;
use BridgeBoard\Models\SkillPost;
use BridgeBoard\Models\User;
use BridgeBoard\Services\AuthService;
use BridgeBoard\Services\Validation;

final class MessageController
{
    public function index(): void
    {
        $this->guard();
        $messages = Message::forUser(AuthService::id(), 25);
        render('pages/contact', [
            'title' => 'Messages',
            'messages' => $messages,
        ]);
    }

    public function send(): void
    {
        $this->guard();

        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid session token.');
            redirect('/contact');
        }

        $input = [
            'recipient_id' => (int) ($_POST['recipient_id'] ?? 0),
            'skill_post_id' => isset($_POST['skill_post_id']) ? (int) $_POST['skill_post_id'] : null,
            'content' => trim($_POST['content'] ?? ''),
        ];

        $errors = Validation::make($input, [
            'recipient_id' => 'required',
            'content' => 'required|min:5',
        ]);

        if (!empty($errors)) {
            flash('errors', $errors);
            redirect('/contact');
        }

        if (!User::findById($input['recipient_id'])) {
            flash('error', 'Recipient not found.');
            redirect('/contact');
        }

        if ($input['skill_post_id'] && !SkillPost::findById($input['skill_post_id'])) {
            flash('error', 'Skill post not found.');
            redirect('/contact');
        }

        Message::create([
            'sender_id' => AuthService::id(),
            'recipient_id' => $input['recipient_id'],
            'skill_post_id' => $input['skill_post_id'],
            'content' => $input['content'],
        ]);

        flash('success', 'Message sent.');
        redirect('/contact');
    }

    private function guard(): void
    {
        if (!AuthService::check()) {
            flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }
}
