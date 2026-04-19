<?php

function renderNavbar($config = []) {

    $user = $_SESSION['user'] ?? null;
    $title = $config['title'] ?? 'Dashboard';

    ?>

    <!-- NAVBAR -->
    <div class="glass sticky top-0 z-50 px-6 py-4 flex items-center justify-between">

        <!-- LEFT: TITLE -->
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-semibold"><?= htmlspecialchars($title) ?></h1>
        </div>

        <!-- CENTER: SEARCH -->
        <div class="hidden md:flex items-center w-1/3">
            <input 
                type="text" 
                placeholder="Search..."
                class="w-full px-4 py-2 rounded-xl bg-white/10 outline-none focus:ring-2 focus:ring-blue-500 transition"
            >
        </div>

        <!-- RIGHT: ACTIONS -->
        <div class="flex items-center gap-4">

            <!-- THEME TOGGLE -->
            <button onclick="toggleTheme()" 
                class="p-2 rounded-xl hover:bg-white/10 transition">
                🌙
            </button>

            <!-- NOTIFICATIONS -->
            <div class="relative">
                <button class="p-2 rounded-xl hover:bg-white/10 transition">
                    🔔
                </button>

                <!-- Notification Badge -->
                <span class="absolute -top-1 -right-1 text-xs bg-red-500 px-1 rounded-full">
                    3
                </span>
            </div>

            <!-- USER DROPDOWN -->
            <div class="relative group">

                <button class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition">
                    <span class="text-sm"><?= htmlspecialchars($user['email'] ?? 'Guest') ?></span>
                    <span>▼</span>
                </button>

                <!-- DROPDOWN -->
                <div class="absolute right-0 mt-2 w-48 glass rounded-xl opacity-0 group-hover:opacity-100 transition">

                    <a href="/profile" class="block px-4 py-2 hover:bg-white/10">
                        Profile
                    </a>

                    <a href="/settings" class="block px-4 py-2 hover:bg-white/10">
                        Settings
                    </a>

                    <a href="/logout" class="block px-4 py-2 text-red-400 hover:bg-white/10">
                        Logout
                    </a>

                </div>
            </div>

        </div>

    </div>

    <!-- THEME SCRIPT -->
    <script>
    function toggleTheme() {
        document.documentElement.classList.toggle('dark');
    }
    </script>

    <?php
}