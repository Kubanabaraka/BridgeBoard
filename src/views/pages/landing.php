<?php
$pageTitle = $title ?? 'BridgeBoard';
$categories = $categories ?? [];
$posts = $posts ?? [];
?>
<?php ob_start(); ?>
<section class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-teal-500 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex flex-col-reverse lg:flex-row items-center gap-10">
        <div class="flex-1 space-y-6">
            <p class="inline-flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full text-sm">Community skill exchange</p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">Trade skills, grow together, and unlock opportunities in your city.</h1>
            <p class="text-lg text-indigo-100">BridgeBoard helps neighbors offer lessons, services, and mentorship in exchange for other skills or fair rates.</p>
            <div class="flex flex-wrap gap-4">
                <a href="/posts" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-6 py-3 rounded-xl font-semibold">Explore skills</a>
                <a href="/register" class="inline-flex items-center gap-2 border border-white/50 px-6 py-3 rounded-xl">Create a post</a>
            </div>
            <div class="flex items-center gap-6 text-sm">
                <div>
                    <p class="text-3xl font-bold">1.4k+</p>
                    <p class="text-indigo-100">Active members</p>
                </div>
                <div>
                    <p class="text-3xl font-bold">8.9/10</p>
                    <p class="text-indigo-100">Avg. rating</p>
                </div>
            </div>
        </div>
        <div class="flex-1">
            <img src="<?= asset('assets/images/hero.png'); ?>" alt="Community collaborating" class="w-full rounded-3xl shadow-2xl border border-white/20">
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-semibold text-slate-900">Top categories</h2>
        <a href="/posts" class="text-indigo-600 font-semibold">View all</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php foreach ($categories as $category): ?>
            <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-4 shadow-sm">
                <img src="<?= asset($category['icon_path'] ?? 'assets/images/category-music.png'); ?>" alt="<?= htmlspecialchars($category['name']); ?> icon" class="h-12 w-12 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-slate-900"><?= htmlspecialchars($category['name']); ?></p>
                    <p class="text-sm text-slate-500 line-clamp-2"><?= htmlspecialchars($category['description'] ?? ''); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-semibold text-slate-900">Featured skill posts</h2>
            <a href="/posts" class="text-sm text-slate-500 hover:text-slate-900">See everything &rarr;</a>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($posts as $post): ?>
                <?php include view_path('partials/post_card'); ?>
            <?php endforeach; ?>
            <?php if (empty($posts)): ?>
                <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <img src="<?= asset('assets/images/empty-state.png'); ?>" alt="Empty state" class="mx-auto h-40 mb-6">
                    <p class="text-lg font-semibold text-slate-900">No posts yet</p>
                    <p class="text-slate-500">Be the first to create a skill offer.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid gap-10 md:grid-cols-3">
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <p class="text-sm font-semibold text-indigo-600 mb-2">Step 01</p>
            <h3 class="text-xl font-semibold text-slate-900 mb-3">Create your profile</h3>
            <p class="text-slate-500">Share the skills you offer and the help you are seeking with a friendly profile.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <p class="text-sm font-semibold text-indigo-600 mb-2">Step 02</p>
            <h3 class="text-xl font-semibold text-slate-900 mb-3">Publish a skill post</h3>
            <p class="text-slate-500">Add visuals, describe your sessions, and set expectations around exchanges.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <p class="text-sm font-semibold text-indigo-600 mb-2">Step 03</p>
            <h3 class="text-xl font-semibold text-slate-900 mb-3">Chat & collaborate</h3>
            <p class="text-slate-500">Respond to messages, schedule sessions, and leave feedback.</p>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
