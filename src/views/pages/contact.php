<?php
$pageTitle = $title ?? 'Messages';
$messages = $messages ?? [];
$errors = flash('errors') ?? [];
?>
<?php ob_start(); ?>
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-sm uppercase text-indigo-500 font-semibold">Inbox</p>
                <h1 class="text-3xl font-semibold text-slate-900">Messages</h1>
            </div>
            <a href="/posts" class="text-sm text-slate-500">Find more collaborators</a>
        </div>
        <?php if ($messages): ?>
            <div class="space-y-4 max-h-96 overflow-auto pr-2">
                <?php foreach ($messages as $message): ?>
                    <div class="border border-slate-100 rounded-2xl p-4 flex gap-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 font-semibold flex items-center justify-center">
                            <?= strtoupper(substr($message['sender_name'] ?? 'BB', 0, 2)); ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                <span><?= htmlspecialchars($message['sender_name'] ?? 'Community member'); ?></span>
                                <span>&bull;</span>
                                <span><?= date('M d, Y \a\t g:ia', strtotime($message['created_at'])); ?></span>
                            </div>
                            <?php if (!empty($message['skill_title'])): ?>
                                <p class="text-xs text-indigo-600 font-semibold">Regarding <?= htmlspecialchars($message['skill_title']); ?></p>
                            <?php endif; ?>
                            <p class="text-slate-700 mt-2 leading-relaxed"><?= nl2br(htmlspecialchars($message['content'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-slate-500">
                <img src="<?= asset('assets/images/empty-state.png'); ?>" alt="Empty" class="h-32 mx-auto mb-4">
                You have no messages yet.
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-8">
        <h2 class="text-2xl font-semibold text-slate-900 mb-6">Send a message</h2>
        <form action="/contact" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
            <div>
                <label class="text-sm font-semibold text-slate-700">Recipient user ID</label>
                <input type="number" name="recipient_id" value="<?= htmlspecialchars($_GET['recipient'] ?? ''); ?>" class="mt-1 w-full px-4 py-3 border border-slate-200 rounded-xl">
                <?php if (!empty($errors['recipient_id'][0])): ?>
                    <p class="text-xs text-rose-500"><?= htmlspecialchars($errors['recipient_id'][0]); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Skill post ID (optional)</label>
                <input type="number" name="skill_post_id" value="<?= htmlspecialchars($_GET['skill'] ?? ''); ?>" class="mt-1 w-full px-4 py-3 border border-slate-200 rounded-xl">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Message</label>
                <textarea name="content" class="mt-1 w-full min-h-[140px] px-4 py-3 border border-slate-200 rounded-xl" placeholder="Introduce yourself and share what you're hoping to collaborate on."></textarea>
                <?php if (!empty($errors['content'][0])): ?>
                    <p class="text-xs text-rose-500"><?= htmlspecialchars($errors['content'][0]); ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="w-full rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold">Send message</button>
        </form>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include view_path('layouts/main'); ?>
