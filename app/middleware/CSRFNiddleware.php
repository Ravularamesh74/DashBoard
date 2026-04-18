<?php

class CSRFMiddleware implements Middleware {

    public function handle($request, $next) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $token = $_POST['csrf'] ?? '';

            if (
                empty($_SESSION['csrf']) ||
                !hash_equals($_SESSION['csrf'], $token)
            ) {
                die("CSRF validation failed");
            }
        }

        return $next($request);
    }
}