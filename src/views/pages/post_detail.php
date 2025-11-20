<?php
$pageTitle = $title ?? 'Post';
$post = $post ?? [];
$images = $post['images'] ?? [];
?>
<?php ob_start(); ?>
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid lg:grid-cols-2 gap-8">
        <div class="space-y-4">
            <?php if ($images): ?>
                <div class="rounded-3xl overflow-hidden border border-slate-100 h-80">
                    <img src="<?= asset($images[0]); ?>" alt="<?= htmlspecialchars($post['title']); ?>" class="w-full h-full object-cover">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <?php foreach (array_slice($images, 1) as $img): ?>
                        <img src="<?= asset($img); ?>" alt="Gallery" class="h-24 w-full object-cover rounded-2xl border border-slate-100">
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="rounded-3xl overflow-hidden border border-slate-100">
                    <img src="<?= asset('assets/images/empty-state.png'); ?>" alt="Default" class="w-full h-80 object-cover">
                </div>
            <?php endif; ?>
        </div>
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-8 space-y-4">
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1">
                    <?= htmlspecialchars($post['category_name'] ?? 'General'); ?>
                </span>
                <span>&bull;</span>
                <span><?= htmlspecialchars($post['location'] ?? 'Remote'); ?></span>
            </div>
            <h1 class="text-3xl font-semibold text-slate-900"><?= htmlspecialchars($post['title']); ?></h1>
            <p class="text-slate-600 leading-relaxed whitespace-pre-line"><?= htmlspecialchars($post['description']); ?></p>
            <div class="border-t border-slate-100 pt-4 flex items-center gap-3">
                <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">
                    <?= strtoupper(substr($post['user_name'] ?? 'BB', 0, 2)); ?>
                </div>
                <div>
                    <p class="font-semibold text-slate-900"><?= htmlspecialchars($post['user_name'] ?? 'Community member'); ?></p>
                    <p class="text-sm text-slate-500">Posted on <?= date('M d, Y', strtotime($post['created_at'] ?? 'now')); ?></p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <p class="text-sm text-slate-500">Range</p>
                <?php if (!empty($post['price_min'])): ?>
                    <p class="text-2xl font-semibold text-slate-900">$<?= number_format((float) $post['price_min'], 0); ?> - $<?= number_format((float) ($post['price_max'] ?: $post['price_min']), 0); ?></p>
                <?php else: ?>
                    <p class="text-lg font-semibold text-indigo-600">Open to exchange</p>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap gap-4">
                <a href="/contact?recipient=<?= (int) $post['user_id']; ?>&skill=<?= (int) $post['id']; ?>" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold">Message</a>
                <a href="/posts" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-6 py-3 text-slate-700">Back</a>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
