<?php
$pageTitle = $title ?? 'Not found';
?>
<?php ob_start(); ?>
<section class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center space-y-4">
        <img src="<?= asset('assets/images/empty-state.png'); ?>" alt="Not found" class="h-40 mx-auto">
        <p class="text-sm font-semibold text-indigo-500 uppercase tracking-widest">404</p>
        <h1 class="text-3xl font-semibold text-slate-900">Page not found</h1>
        <p class="text-slate-500">The page you are looking for might have been moved.</p>
        <a href="/" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-white font-semibold">Go home</a>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
