<?php

function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

function formatDate($date) {
    return date("d M Y", strtotime($date));
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}