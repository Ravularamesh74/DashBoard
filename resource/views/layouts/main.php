<?php

// Defaults
$title = $title ?? "Dashboard";
$content = $content ?? "";
$extraStyles = $extraStyles ?? "";
$extraScripts = $extraScripts ?? "";

// Flash message
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require_once "../resources/components/navbar.php";
require_once "../resources/components/sidebar.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }
    </style>

    <?= $extraStyles ?>

</head>

<body class="text-white">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <?php renderSidebar(); ?>

    <!-- MAIN AREA -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- NAVBAR -->
        <?php renderNavbar(["title" => $title]); ?>

        <!-- CONTENT AREA -->
        <main class="flex-1 overflow-y-auto p-6 scrollbar">

            <!-- FLASH MESSAGE -->
            <?php if ($flash): ?>
                <div class="mb-4 p-4 rounded-xl 
                    <?= $flash['type'] === 'error'
                        ? 'bg-red-500/20 text-red-300'
                        : 'bg-green-500/20 text-green-300' ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>

            <!-- PAGE CONTENT -->
            <?= $content ?>

        </main>

    </div>

</div>

<!-- GLOBAL SCRIPTS -->
<script>
    // Auto-hide flash messages
    setTimeout(() => {
        const flash = document.querySelector('[class*="bg-red"], [class*="bg-green"]');
        if (flash) flash.style.display = 'none';
    }, 4000);
</script>

<?= $extraScripts ?>

</body>
</html>