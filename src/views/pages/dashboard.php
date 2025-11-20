<?php
$pageTitle = $title ?? 'Dashboard';
$user = $user ?? null;
$posts = $posts ?? [];
$messages = $messages ?? [];
?>
<?php ob_start(); ?>
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        <div class="relative overflow-hidden rounded-3xl border border-slate-100 bg-gradient-to-r from-indigo-600 via-indigo-500 to-teal-500 text-white p-8">
            <div class="flex flex-col lg:flex-row gap-6 items-center">
                <img src="<?= asset('assets/images/dashboard-banner.png'); ?>" alt="Dashboard" class="w-full lg:w-1/2 rounded-2xl shadow-2xl border border-white/30">
                <div class="flex-1 space-y-4">
                    <p class="text-sm uppercase tracking-widest text-white/70">Welcome back</p>
                    <h1 class="text-3xl font-semibold">Hey <?= htmlspecialchars($user['name'] ?? 'there'); ?> 👋</h1>
                    <p class="text-white/80">Keep your skill posts fresh, respond to new requests, and explore new opportunities.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="/posts/create" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-5 py-3 rounded-xl font-semibold">Create new post</a>
                        <a href="/posts" class="inline-flex items-center gap-2 border border-white/50 px-5 py-3 rounded-xl">Browse community</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-2xl font-semibold text-indigo-700">
                        <?= strtoupper(substr($user['name'] ?? 'BB', 0, 2)); ?>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-slate-900"><?= htmlspecialchars($user['name'] ?? 'Member'); ?></p>
                        <p class="text-sm text-slate-500"><?= htmlspecialchars($user['location'] ?? 'Remote'); ?></p>
                    </div>
                </div>
                <p class="text-slate-600 mt-4"><?= htmlspecialchars($user['bio'] ?? 'Share a short introduction to tell the community what you love to teach.'); ?></p>
                <a href="/profile" class="inline-flex items-center gap-2 text-indigo-600 font-semibold mt-4">Edit profile</a>
            </div>
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-slate-900">Your posts</h2>
                    <a href="/posts/create" class="text-sm text-indigo-600 font-semibold">Add new</a>
                </div>
                <?php if ($posts): ?>
                    <div class="space-y-4">
                        <?php foreach ($posts as $post): ?>
                            <div class="border border-slate-100 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-start md:items-center">
                                <div class="flex-1">
                                    <p class="text-lg font-semibold text-slate-900"><?= htmlspecialchars($post['title']); ?></p>
                                    <p class="text-sm text-slate-500 line-clamp-2 mt-1"><?= htmlspecialchars($post['description']); ?></p>
                                </div>
                                <div class="flex gap-3">
                                    <a href="/posts/<?= (int) $post['id']; ?>/edit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold">Edit</a>
                                    <form action="/posts/<?= (int) $post['id']; ?>/delete" method="POST" onsubmit="return confirm('Delete this post?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-rose-200 text-sm font-semibold text-rose-600">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-16">
                        <img src="<?= asset('assets/images/empty-state.png'); ?>" alt="Empty" class="h-40 mx-auto mb-4">
                        <p class="text-lg font-semibold text-slate-900">No posts yet</p>
                        <p class="text-slate-500">Share your first skill offering to connect with members.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-slate-900">Recent messages</h2>
                <a href="/contact" class="text-sm text-indigo-600 font-semibold">Open inbox</a>
            </div>
            <?php if ($messages): ?>
                <div class="space-y-4">
                    <?php foreach ($messages as $message): ?>
                        <div class="border border-slate-100 rounded-2xl p-4 flex gap-4">
                            <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">
                                <?= strtoupper(substr($message['sender_name'] ?? 'BB', 0, 2)); ?>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500"><?= htmlspecialchars($message['sender_name'] ?? 'Community member'); ?> • <?= date('M d, Y', strtotime($message['created_at'])); ?></p>
                                <?php if (!empty($message['skill_title'])): ?>
                                    <p class="text-xs text-indigo-600 font-semibold">Regarding: <?= htmlspecialchars($message['skill_title']); ?></p>
                                <?php endif; ?>
                                <p class="text-slate-700 mt-1"><?= nl2br(htmlspecialchars($message['content'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-10 text-slate-500">No messages yet.</div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
