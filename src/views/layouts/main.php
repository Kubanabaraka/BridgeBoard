<?php
/** @var string $pageTitle */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'BridgeBoard'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ["Inter", "system-ui", "-apple-system", "BlinkMacSystemFont", "Segoe UI", "sans-serif"],
                    },
                    colors: {
                        primary: {
                            50: '#ecfeff',
                            100: '#cffafe',
                            200: '#a5f3fc',
                            300: '#67e8f9',
                            400: '#22d3ee',
                            500: '#06b6d4',
                            600: '#0891b2',
                            700: '#0e7490',
                            800: '#155e75',
                            900: '#164e63',
                        },
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,line-clamp"></script>
    <link rel="stylesheet" href="<?= asset('assets/css/tailwind.css'); ?>">
    <script defer src="<?= asset('assets/js/app.js'); ?>"></script>
</head>
<body class="min-h-screen bg-slate-50 font-[Inter] text-slate-900">
<div class="relative min-h-screen flex flex-col">
    <?php include view_path('partials/nav'); ?>

    <?php if ($message = flash('success')): ?>
        <div class="fixed top-4 right-4 z-50 bg-emerald-500 text-white px-4 py-3 rounded-lg shadow-lg">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($message = flash('error')): ?>
        <div class="fixed top-4 right-4 z-50 bg-rose-500 text-white px-4 py-3 rounded-lg shadow-lg">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <main class="flex-1">
        <?= $content ?? ''; ?>
    </main>

    <?php include view_path('partials/footer'); ?>
</div>
</body>
</html>
