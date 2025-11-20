<?php
$pageTitle = $title ?? 'Browse skills';
$categories = $categories ?? [];
$posts = $posts ?? [];
$filters = $filters ?? ['q' => '', 'category' => null, 'location' => ''];
?>
<?php ob_start(); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <p class="text-sm uppercase text-indigo-500 font-semibold">Find your next collab</p>
                <h1 class="text-3xl font-semibold text-slate-900">Browse skill listings</h1>
            </div>
            <a href="/posts/create" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-white font-semibold">Create post</a>
        </div>
        <form action="/search" method="GET" class="mt-6 grid gap-4 md:grid-cols-4">
            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-slate-600 mb-1 block">Keyword</label>
                <input type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? ''); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100" placeholder="Guitar, UX design...">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600 mb-1 block">Category</label>
                <select name="category" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100">
                    <option value="">All</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id']; ?>" <?= (int) ($filters['category'] ?? 0) === (int) $category['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600 mb-1 block">Location</label>
                <input type="text" name="location" value="<?= htmlspecialchars($filters['location'] ?? ''); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100" placeholder="Remote / Austin">
            </div>
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-white font-semibold">Search</button>
                <a href="/posts" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-slate-700">Reset</a>
            </div>
        </form>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($posts as $post): ?>
            <?php include view_path('partials/post_card'); ?>
        <?php endforeach; ?>
        <?php if (empty($posts)): ?>
            <div class="bg-white rounded-3xl border border-dashed border-slate-200 flex flex-col items-center justify-center text-center py-16">
                <img src="<?= asset('assets/images/empty-state.png'); ?>" alt="Empty" class="h-40 mb-6">
                <p class="text-lg font-semibold text-slate-900">No matches</p>
                <p class="text-slate-500">Try adjusting filters or add a new post.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
