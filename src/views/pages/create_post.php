<?php
$pageTitle = $title ?? 'Create post';
$categories = $categories ?? [];
$errors = flash('errors') ?? [];
?>
<?php ob_start(); ?>
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-sm uppercase text-indigo-500 font-semibold">New skill offer</p>
                <h1 class="text-3xl font-semibold text-slate-900">Craft your post</h1>
            </div>
            <a href="/dashboard" class="text-sm text-slate-500">Back to dashboard</a>
        </div>
        <form action="/posts" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars(old('title')); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100">
                <?php if (!empty($errors['title'][0])): ?>
                    <p class="text-sm text-rose-500 mt-1"><?= htmlspecialchars($errors['title'][0]); ?></p>
                <?php endif; ?>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100">
                        <option value="">Select category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id']; ?>" <?= old('category_id') == $category['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Location</label>
                    <input type="text" name="location" value="<?= htmlspecialchars(old('location')); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100" placeholder="Remote / Austin, TX">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" class="w-full min-h-[160px] px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100" placeholder="Share what you offer, the format, and what you expect in return."><?= htmlspecialchars(old('description')); ?></textarea>
                <?php if (!empty($errors['description'][0])): ?>
                    <p class="text-sm text-rose-500 mt-1"><?= htmlspecialchars($errors['description'][0]); ?></p>
                <?php endif; ?>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Price min ($)</label>
                    <input type="number" step="0.01" name="price_min" value="<?= htmlspecialchars(old('price_min')); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Price max ($)</label>
                    <input type="number" step="0.01" name="price_max" value="<?= htmlspecialchars(old('price_max')); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Gallery images</label>
                <input type="file" name="images[name][]" hidden>
                <input type="file" name="images[]" accept="image/*" multiple class="w-full text-sm">
                <p class="text-xs text-slate-500 mt-1">PNG, JPG, WEBP up to 5MB each.</p>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-6 py-3 font-semibold">Publish post</button>
        </form>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
