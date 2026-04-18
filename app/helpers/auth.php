<?php

function isAuth() {
    return !empty($_SESSION['user']);
}

function user() {
    return $_SESSION['user'] ?? null;
}

function requireAuth() {
    if (!isAuth()) {
        redirect('/login');
    }
}

function requireRole($role) {
    if (!isAuth() || $_SESSION['user']['role'] !== $role) {
        die("Access denied");
    }
}

function logout() {
    session_unset();
    session_destroy();
}