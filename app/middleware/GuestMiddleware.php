<?php

class GuestMiddleware implements Middleware {

    public function handle($request, $next) {

        if (!empty($_SESSION['user'])) {
            header("Location: /");
            exit();
        }

        return $next($request);
    }
}