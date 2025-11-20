<?php

declare(strict_types=1);

namespace BridgeBoard\Controllers;

use BridgeBoard\Models\Category;
use BridgeBoard\Models\SkillPost;
use BridgeBoard\Services\AuthService;
use BridgeBoard\Services\ImageService;
use BridgeBoard\Services\Validation;

final class PostController
{
    public function browse(): void
    {
        $posts = SkillPost::latest(12);
        $categories = Category::all();

        render('pages/browse', [
            'title' => 'Browse skills',
            'posts' => $this->mapPostImages($posts),
            'categories' => $categories,
        ]);
    }

    public function show(int $postId): void
    {
        $post = SkillPost::findById($postId);
        if (!$post) {
            http_response_code(404);
            render('pages/404', ['title' => 'Not found']);
            return;
        }

        $post['images'] = $post['images'] ? json_decode($post['images'], true) : [];

        render('pages/post_detail', [
            'title' => $post['title'],
            'post' => $post,
        ]);
    }

    public function create(): void
    {
        $this->guard();
        render('pages/create_post', [
            'title' => 'Create skill post',
            'categories' => Category::all(),
        ]);
    }

    public function store(): void
    {
        $this->guard();

        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid session token.');
            redirect('/posts/create');
        }

        $userId = AuthService::id();
        $input = [
            'title' => trim($_POST['title'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'price_min' => $_POST['price_min'] ?? null,
            'price_max' => $_POST['price_max'] ?? null,
        ];

        set_old_input($input);

        $errors = Validation::make($input, [
            'title' => 'required|min:4|max:200',
            'description' => 'required|min:20',
        ]);

        if (!empty($errors)) {
            flash('errors', $errors);
            redirect('/posts/create');
        }

        try {
            $images = isset($_FILES['images']) ? ImageService::handleMultiple($_FILES['images']) : [];
        } catch (\Throwable $exception) {
            flash('error', $exception->getMessage());
            redirect('/posts/create');
        }

        SkillPost::create([
            'user_id' => (int) $userId,
            'category_id' => $input['category_id'] ?: null,
            'title' => $input['title'],
            'description' => $input['description'],
            'location' => $input['location'] ?: null,
            'price_min' => $input['price_min'] ?: null,
            'price_max' => $input['price_max'] ?: null,
            'images' => $images ? json_encode($images) : null,
            'status' => 'active',
        ]);

        clear_old_input();
        flash('success', 'Skill post published!');
        redirect('/dashboard');
    }

    public function edit(int $postId): void
    {
        $this->guard();
        $post = SkillPost::findById($postId);
        if (!$post || (int) $post['user_id'] !== AuthService::id()) {
            flash('error', 'Unable to edit this post.');
            redirect('/dashboard');
        }

        $post['images'] = $post['images'] ? json_decode($post['images'], true) : [];

        render('pages/edit_post', [
            'title' => 'Edit post',
            'post' => $post,
            'categories' => Category::all(),
        ]);
    }

    public function update(int $postId): void
    {
        $this->guard();
        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid session token.');
            redirect('/posts/' . $postId . '/edit');
        }

        $post = SkillPost::findById($postId);
        if (!$post || (int) $post['user_id'] !== AuthService::id()) {
            flash('error', 'Unable to update this post.');
            redirect('/dashboard');
        }

        $input = [
            'title' => trim($_POST['title'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'price_min' => $_POST['price_min'] ?? null,
            'price_max' => $_POST['price_max'] ?? null,
            'status' => $_POST['status'] ?? 'active',
        ];

        $errors = Validation::make($input, [
            'title' => 'required|min:4|max:200',
            'description' => 'required|min:20',
        ]);

        if (!empty($errors)) {
            flash('errors', $errors);
            redirect('/posts/' . $postId . '/edit');
        }

        $existingImages = $post['images'] ? json_decode($post['images'], true) : [];
        try {
            if (isset($_FILES['images'])) {
                $newImages = ImageService::handleMultiple($_FILES['images']);
                $existingImages = array_merge($existingImages, $newImages);
            }
        } catch (\Throwable $exception) {
            flash('error', $exception->getMessage());
            redirect('/posts/' . $postId . '/edit');
        }

        SkillPost::update($postId, [
            'category_id' => $input['category_id'] ?: null,
            'title' => $input['title'],
            'description' => $input['description'],
            'location' => $input['location'] ?: null,
            'price_min' => $input['price_min'] ?: null,
            'price_max' => $input['price_max'] ?: null,
            'images' => $existingImages ? json_encode($existingImages) : null,
            'status' => $input['status'],
        ]);

        flash('success', 'Post updated successfully.');
        redirect('/dashboard');
    }

    public function destroy(int $postId): void
    {
        $this->guard();
        if (!verify_csrf($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            redirect('/dashboard');
        }

        SkillPost::delete($postId, AuthService::id());
        flash('success', 'Post removed.');
        redirect('/dashboard');
    }

    private function guard(): void
    {
        if (!AuthService::check()) {
            flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }

    private function mapPostImages(array $posts): array
    {
        return array_map(function (array $post): array {
            $post['images'] = $post['images'] ? json_decode($post['images'], true) : [];
            return $post;
        }, $posts);
    }
}
