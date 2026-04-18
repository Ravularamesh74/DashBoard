<?php

ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => env("SESSION_LIFETIME") * 60,
    'path' => '/',
    'secure' => false, // set true if HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}