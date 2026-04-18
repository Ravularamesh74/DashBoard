<?php

function loadEnv($path) {

    if (!file_exists($path)) {
        die(".env file missing");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (str_starts_with(trim($line), '#')) continue;

        list($key, $value) = explode("=", $line, 2);

        $_ENV[$key] = trim($value);
    }
}

function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}