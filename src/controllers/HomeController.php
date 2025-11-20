<?php

declare(strict_types=1);

namespace BridgeBoard\Controllers;

use BridgeBoard\Models\Category;
use BridgeBoard\Models\SkillPost;

final class HomeController
{
    public function index(): void
    {
        $posts = SkillPost::latest(6);
        $categories = Category::all();

        render('pages/landing', [
            'title' => 'BridgeBoard',
            'posts' => array_map(function (array $post): array {
                $post['images'] = $post['images'] ? json_decode($post['images'], true) : [];
                return $post;
            }, $posts),
            'categories' => $categories,
        ]);
    }
}
