<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Auth' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
    body {
        background: linear-gradient(to bottom right, #000, #111827, #000);
    }
    .glass {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    </style>
</head>

<body class="text-white flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 glass rounded-2xl shadow-xl">

        <h1 class="text-2xl font-bold mb-6 text-center">
            <?= $title ?? '' ?>
        </h1>

        <?= $content ?>

    </div>

</body>
</html>