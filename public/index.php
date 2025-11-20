<?php

declare(strict_types=1);

use BridgeBoard\Controllers\AuthController;
use BridgeBoard\Controllers\DashboardController;
use BridgeBoard\Controllers\HomeController;
use BridgeBoard\Controllers\MessageController;
use BridgeBoard\Controllers\PostController;
use BridgeBoard\Controllers\ProfileController;
use BridgeBoard\Controllers\SearchController;
use BridgeBoard\Core\Router;

require __DIR__ . '/../bootstrap.php';

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/posts', [PostController::class, 'browse']);
$router->get('/posts/create', [PostController::class, 'create']);
$router->post('/posts', [PostController::class, 'store']);
$router->get('/posts/{id}', [PostController::class, 'show']);
$router->get('/posts/{id}/edit', [PostController::class, 'edit']);
$router->post('/posts/{id}/update', [PostController::class, 'update']);
$router->post('/posts/{id}/delete', [PostController::class, 'destroy']);

$router->get('/search', [SearchController::class, 'handle']);

$router->get('/profile', [ProfileController::class, 'show']);
$router->get('/profile/{id}', [ProfileController::class, 'show']);
$router->post('/profile', [ProfileController::class, 'update']);

$router->get('/contact', [MessageController::class, 'index']);
$router->post('/contact', [MessageController::class, 'send']);

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');

clear_old_input();
