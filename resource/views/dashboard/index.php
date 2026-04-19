<?php

require "../resources/components/navbar.php";
require "../resources/components/sidebar.php";
require "../resources/components/cards.php";

// Data passed from controller
$stats = $stats ?? [
    "totalUsers" => 1200,
    "activeUsers" => 845,
    "newUsers" => 120
];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<body class="text-white">

<div class="flex">

    <!-- SIDEBAR -->
    <?php renderSidebar(); ?>

    <!-- MAIN CONTENT -->
    <div class="flex-1">

        <!-- NAVBAR -->
        <?php renderNavbar(["title" => "Dashboard"]); ?>

        <!-- CONTENT -->
        <div class="p-6 space-y-6">

            <!-- KPI CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <?php
                renderCard([
                    "title" => "Total Users",
                    "value" => $stats['totalUsers'],
                    "icon"  => "👥",
                    "color" => "from-purple-500 to-pink-500",
                    "trend" => 12
                ]);

                renderCard([
                    "title" => "Active Users",
                    "value" => $stats['activeUsers'],
                    "icon"  => "⚡",
                    "color" => "from-blue-500 to-cyan-500",
                    "trend" => 8
                ]);

                renderCard([
                    "title" => "New Users",
                    "value" => $stats['newUsers'],
                    "icon"  => "🆕",
                    "color" => "from-green-400 to-emerald-600",
                    "trend" => -3
                ]);
                ?>

            </div>

            <!-- CHART + RECENT USERS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- CHART -->
                <div class="lg:col-span-2 glass p-6 rounded-2xl">
                    <h2 class="text-lg mb-4">User Growth</h2>
                    <canvas id="chart"></canvas>
                </div>

                <!-- RECENT USERS -->
                <div class="glass p-6 rounded-2xl">
                    <h2 class="text-lg mb-4">Recent Users</h2>

                    <div id="recent-users" class="space-y-3 text-sm text-gray-300">
                        <!-- Filled via JS -->
                        <p>Loading...</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- CHART SCRIPT -->
<script>
const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
            label: 'Users',
            data: [10, 20, 15, 30, 25, 40, 35],
            borderColor: '#3b82f6',
            tension: 0.4
        }]
    }
});
</script>

<!-- AJAX DATA LOADER -->
<script>
fetch('/dashboard-data')
.then(res => res.json())
.then(data => {

    // Update recent users
    let html = '';

    data.recentUsers.forEach(user => {
        html += `
            <div class="flex justify-between">
                <span>${user.email}</span>
                <span class="text-xs text-gray-500">${user.created_at}</span>
            </div>
        `;
    });

    document.getElementById('recent-users').innerHTML = html;
});
</script>

</body>
</html>