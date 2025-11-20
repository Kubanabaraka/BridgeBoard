<?php
/** @var array $post */
$images = $post['images'] ?? [];
$cover = $images[0] ?? 'assets/images/empty-state.png';
$priceMin = $post['price_min'] ?? null;
$priceMax = $post['price_max'] ?? null;
?>
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
    <div class="relative">
        <img src="<?= asset($cover); ?>" alt="<?= htmlspecialchars($post['title']); ?>" class="h-48 w-full object-cover">
        <?php if (!empty($post['category_name'])): ?>
            <span class="absolute top-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-slate-700">
                <?= htmlspecialchars($post['category_name']); ?>
            </span>
        <?php endif; ?>
    </div>
    <div class="p-6 flex flex-col gap-3">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">
                <?= strtoupper(substr($post['user_name'] ?? $post['title'], 0, 2)); ?>
            </div>
            <div>
                <p class="text-lg font-semibold text-slate-900 line-clamp-1"><?= htmlspecialchars($post['title']); ?></p>
                <p class="text-sm text-slate-500">By <?= htmlspecialchars($post['user_name'] ?? 'Community member'); ?></p>
            </div>
        </div>
        <p class="text-sm text-slate-600 line-clamp-3"><?= htmlspecialchars($post['description']); ?></p>
        <div class="flex items-center justify-between text-sm text-slate-500">
            <span><?= htmlspecialchars($post['location'] ?? 'Remote'); ?></span>
            <?php if (!empty($priceMin)): ?>
                <span class="font-semibold text-slate-900">
                    $<?= htmlspecialchars(number_format((float) $priceMin, 0)); ?> -
                    $<?= htmlspecialchars(number_format((float) ($priceMax ?: $priceMin), 0)); ?>
                </span>
            <?php else: ?>
                <span class="font-semibold text-indigo-600">Open to exchange</span>
            <?php endif; ?>
        </div>
        <div class="flex gap-3">
            <a href="/posts/<?= (int) $post['id']; ?>" class="flex-1 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-white font-semibold">View details</a>
            <a href="/contact?recipient=<?= (int) $post['user_id']; ?>&skill=<?= (int) $post['id']; ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-slate-700">Message</a>
        </div>
    </div>
</div>
