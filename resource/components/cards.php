<?php

/**
 * Render KPI Card Component
 *
 * @param array $config
 *  - title
 *  - value
 *  - icon (SVG / emoji / HTML)
 *  - color (tailwind gradient)
 *  - trend (optional)
 */

function renderCard($config = []) {

    $title = $config['title'] ?? 'Title';
    $value = $config['value'] ?? 0;
    $icon  = $config['icon'] ?? '📊';
    $color = $config['color'] ?? 'from-blue-500 to-indigo-600';
    $trend = $config['trend'] ?? null;

    ?>

    <div class="relative overflow-hidden rounded-2xl p-6 glass
                hover:scale-105 transition duration-300
                bg-gradient-to-br <?= $color ?> shadow-lg">

        <!-- Glow Effect -->
        <div class="absolute inset-0 opacity-20 blur-2xl bg-white"></div>

        <!-- Content -->
        <div class="relative z-10 flex justify-between items-center">

            <div>
                <p class="text-sm text-white/70"><?= htmlspecialchars($title) ?></p>
                <h2 class="text-3xl font-bold mt-1"><?= htmlspecialchars($value) ?></h2>

                <?php if ($trend): ?>
                    <p class="text-xs mt-2 <?= $trend > 0 ? 'text-green-300' : 'text-red-300' ?>">
                        <?= $trend > 0 ? '↑' : '↓' ?> <?= abs($trend) ?>%
                    </p>
                <?php endif; ?>
            </div>

            <div class="text-4xl opacity-80">
                <?= $icon ?>
            </div>

        </div>

    </div>

    <?php
}