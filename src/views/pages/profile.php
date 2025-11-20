<?php
$pageTitle = $title ?? 'Profile';
$profileUser = $profileUser ?? null;
$posts = $posts ?? [];
$canEdit = $canEdit ?? false;
$errors = flash('errors') ?? [];
?>
<?php ob_start(); ?>
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-10 flex flex-col md:flex-row gap-8">
        <div class="flex items-start gap-6 flex-1">
            <div class="w-32 h-32 rounded-3xl bg-slate-100 overflow-hidden">
                <?php if (!empty($profileUser['avatar_path'])): ?>
                    <img src="<?= asset($profileUser['avatar_path']); ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-3xl font-semibold text-slate-400"><?= strtoupper(substr($profileUser['name'] ?? 'BB', 0, 2)); ?></div>
                <?php endif; ?>
            </div>
            <div class="space-y-2">
                <h1 class="text-3xl font-semibold text-slate-900"><?= htmlspecialchars($profileUser['name'] ?? 'Member'); ?></h1>
                <p class="text-slate-500">Located in <?= htmlspecialchars($profileUser['location'] ?? 'Remote'); ?></p>
                <p class="text-slate-600 leading-relaxed"><?= htmlspecialchars($profileUser['bio'] ?? 'This member has not added a bio yet.'); ?></p>
                <div class="flex gap-3">
                    <a href="mailto:hello@bridgeboard.local" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Contact</a>
                    <a href="/contact?recipient=<?= (int) ($profileUser['id'] ?? 0); ?>" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Message</a>
                </div>
            </div>
        </div>
        <?php if ($canEdit): ?>
            <div class="md:w-80 bg-slate-50 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick update</h2>
                <form action="/profile" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($profileUser['name'] ?? ''); ?>" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                        <?php if (!empty($errors['name'][0])): ?>
                            <p class="text-xs text-rose-500"><?= htmlspecialchars($errors['name'][0]); ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Location</label>
                        <input type="text" name="location" value="<?= htmlspecialchars($profileUser['location'] ?? ''); ?>" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Bio</label>
                        <textarea name="bio" class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-200 min-h-[100px]"><?= htmlspecialchars($profileUser['bio'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Avatar</label>
                        <input type="file" name="avatar" accept="image/*" class="mt-1 w-full text-xs">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-2 text-white font-semibold">Save</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-slate-900">Skill posts</h2>
            <?php if ($canEdit): ?>
                <a href="/posts/create" class="text-sm text-indigo-600 font-semibold">Add new</a>
            <?php endif; ?>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <?php foreach ($posts as $post): ?>
                <?php $post['user_name'] = $profileUser['name'] ?? 'Member'; ?>
                <?php include view_path('partials/post_card'); ?>
            <?php endforeach; ?>
            <?php if (empty($posts)): ?>
                <div class="bg-white rounded-3xl border border-dashed border-slate-200 flex flex-col items-center justify-center text-center py-16">
                    <img src="<?= asset('assets/images/empty-state.png'); ?>" alt="Empty" class="h-40 mb-6">
                    <p class="text-lg font-semibold text-slate-900">No posts yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
