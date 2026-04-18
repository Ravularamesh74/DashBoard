<?php

function jsonResponse($data = [], $message = "OK", $success = true, $code = 200) {

    http_response_code($code);
    header('Content-Type: application/json');

    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);

    exit();
}

function redirect($url) {
    header("Location: $url");
    exit();
}