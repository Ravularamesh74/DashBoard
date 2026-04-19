<?php

// Default values
$title = $title ?? "Auth";
$content = $content ?? "";
$extraStyles = $extraStyles ?? "";
$extraScripts = $extraScripts ?? "";

// Flash message (optional)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- GLOBAL STYLES -->
    <style>
        body {
            background: linear-gradient(to bottom right, #000, #111827, #000);
        }

        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
            outline: none;
            transition: all 0.2s ease;
        }

        .input:focus {
            box-shadow: 0 0 0 2px #3b82f6;
        }
    </style>

    <?= $extraStyles ?>

</head>

<body class="text-white min-h-screen flex items-center justify-center px-4">

    <!-- BACKGROUND EFFECT -->
    <div class="absolute inset-0 -z-10 opacity-20 blur-3xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500"></div>

    <!-- AUTH CARD -->
    <div class="w-full max-w-md glass rounded-2xl p-8 shadow-xl">

        <!-- HEADER -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold"><?= htmlspecialchars($title) ?></h1>
        </div>

        <!-- FLASH MESSAGE -->
        <?php if ($flash): ?>
            <div class="mb-4 p-3 rounded-xl 
                <?= $flash['type'] === 'error' ? 'bg-red-500/20 text-red-300' : 'bg-green-500/20 text-green-300' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- CONTENT SLOT -->
        <?= $content ?>

    </div>

    <!-- FOOTER -->
    <div class="absolute bottom-4 text-xs text-gray-500">
        © <?= date('Y') ?> <?= APP_NAME ?? 'App' ?>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Example: Auto-hide flash messages
        setTimeout(() => {
            const flash = document.querySelector('[class*="bg-red"], [class*="bg-green"]');
            if (flash) flash.style.display = 'none';
        }, 4000);
    </script>

    <?= $extraScripts ?>

</body>
</html>