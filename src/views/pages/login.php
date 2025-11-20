<?php
$pageTitle = $title ?? 'Login';
$errors = flash('errors') ?? [];
$inlineError = flash_peek('error');
?>
<?php ob_start(); ?>
<section class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-10 space-y-6">
        <div class="text-center space-y-2">
            <p class="text-sm uppercase tracking-wider text-indigo-500 font-semibold">Welcome back</p>
            <h1 class="text-3xl font-bold text-slate-900">Log in to continue</h1>
        </div>
        <form method="POST" action="/login" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars(old('email')); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100">
            </div>
            <?php if ($inlineError): ?>
                <p class="text-sm text-rose-500"><?= htmlspecialchars($inlineError); ?></p>
            <?php endif; ?>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-6 py-3 font-semibold">Login</button>
        </form>
        <p class="text-sm text-center text-slate-500">No account yet? <a href="/register" class="text-indigo-600 font-semibold">Join BridgeBoard</a></p>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
