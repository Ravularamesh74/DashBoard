<?php

function renderSidebar($config = []) {

    $user = $_SESSION['user'] ?? null;
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // NAV ITEMS (can be dynamic later)
    $items = [
        ["name" => "Dashboard", "icon" => "🏠", "path" => "/"],
        ["name" => "Users", "icon" => "👥", "path" => "/users"],
        ["name" => "Analytics", "icon" => "📊", "path" => "/analytics"],
        ["name" => "Settings", "icon" => "⚙️", "path" => "/settings"],
    ];

    ?>

    <!-- SIDEBAR -->
    <div id="sidebar"
         class="glass h-screen w-64 p-6 flex flex-col justify-between
                transition-all duration-300">

        <!-- TOP -->
        <div>

            <!-- LOGO -->
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold">⚡ Admin</h2>

                <!-- TOGGLE BUTTON -->
                <button onclick="toggleSidebar()" 
                        class="p-2 rounded-lg hover:bg-white/10 transition">
                    ☰
                </button>
            </div>

            <!-- NAVIGATION -->
            <nav class="space-y-2">

                <?php foreach ($items as $item): 
                    $active = $currentUri === $item['path'];
                ?>

                <a href="<?= $item['path'] ?>"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                          transition-all duration-200
                          <?= $active 
                              ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' 
                              : 'hover:bg-white/10 text-white/80' ?>">

                    <span class="text-lg"><?= $item['icon'] ?></span>
                    <span class="sidebar-text"><?= $item['name'] ?></span>

                </a>

                <?php endforeach; ?>

            </nav>

        </div>

        <!-- BOTTOM USER -->
        <div class="mt-6">

            <div class="glass p-4 rounded-xl flex items-center gap-3">

                <!-- AVATAR -->
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <?= strtoupper(substr($user['email'] ?? 'G', 0, 1)) ?>
                </div>

                <!-- USER INFO -->
                <div class="sidebar-text">
                    <p class="text-sm"><?= htmlspecialchars($user['email'] ?? 'Guest') ?></p>
                    <p class="text-xs text-gray-400"><?= $user['role'] ?? '' ?></p>
                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPT -->
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');

        document.querySelectorAll('.sidebar-text').forEach(el => {
            el.classList.toggle('hidden');
        });
    }
    </script>

    <?php
}