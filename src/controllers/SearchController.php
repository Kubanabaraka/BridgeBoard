<?php

declare(strict_types=1);

namespace BridgeBoard\Controllers;

use BridgeBoard\Models\Category;
use BridgeBoard\Models\SkillPost;

final class SearchController
{
    public function handle(): void
    {
        $term = trim($_GET['q'] ?? '');
        $categoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;
        $location = trim($_GET['location'] ?? '');

        $results = SkillPost::search($term ?: null, $categoryId ?: null, $location ?: null);
        foreach ($results as &$result) {
            $result['images'] = $result['images'] ? json_decode($result['images'], true) : [];
        }

        render('pages/browse', [
            'title' => 'Search results',
            'posts' => $results,
            'categories' => Category::all(),
            'filters' => [
                'q' => $term,
                'category' => $categoryId,
                'location' => $location,
            ],
        ]);
    }
}
