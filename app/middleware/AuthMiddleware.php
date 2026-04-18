<?php

class AuthMiddleware implements Middleware {

    public function handle($request, $next) {

        if (empty($_SESSION['user'])) {
            header("Location: /login");
            exit();
        }

        return $next($request);
    }
}