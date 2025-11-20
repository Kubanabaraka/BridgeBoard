<?php use BridgeBoard\Services\AuthService; ?>
<header class="bg-white/90 backdrop-blur border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4">
            <a href="/" class="flex items-center gap-2">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-semibold">BB</span>
                <div>
                    <p class="text-lg font-semibold text-slate-900">BridgeBoard</p>
                    <p class="text-xs text-slate-500">Skill Exchange Community</p>
                </div>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="/posts" class="hover:text-slate-900">Browse</a>
                <a href="/search" class="hover:text-slate-900">Search</a>
                <a href="/contact" class="hover:text-slate-900">Contact</a>
                <?php if (AuthService::check()): ?>
                    <a href="/dashboard" class="hover:text-slate-900">Dashboard</a>
                    <form action="/logout" method="POST" class="inline">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-white">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="/login" class="text-indigo-600">Login</a>
                    <a href="/register" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white">Join</a>
                <?php endif; ?>
            </nav>
            <button class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200" data-mobile-toggle>
                <span class="sr-only">Toggle menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
        <div class="md:hidden" data-mobile-menu hidden>
            <div class="flex flex-col gap-4 pb-4 text-slate-700">
                <a href="/posts" class="hover:text-slate-900">Browse</a>
                <a href="/search" class="hover:text-slate-900">Search</a>
                <a href="/contact" class="hover:text-slate-900">Contact</a>
                <?php if (AuthService::check()): ?>
                    <a href="/dashboard" class="hover:text-slate-900">Dashboard</a>
                    <form action="/logout" method="POST" class="inline">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-white">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="/login" class="text-indigo-600">Login</a>
                    <a href="/register" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white">Join</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
