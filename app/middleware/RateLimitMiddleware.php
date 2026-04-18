<?php

class RateLimitMiddleware implements Middleware {

    private $limit = 10;
    private $timeWindow = 60; // seconds

    public function handle($request, $next) {

        $ip = $_SERVER['REMOTE_ADDR'];

        if (!isset($_SESSION['rate_limit'][$ip])) {
            $_SESSION['rate_limit'][$ip] = [];
        }

        $_SESSION['rate_limit'][$ip] = array_filter(
            $_SESSION['rate_limit'][$ip],
            fn($timestamp) => $timestamp > time() - $this->timeWindow
        );

        if (count($_SESSION['rate_limit'][$ip]) >= $this->limit) {
            http_response_code(429);
            die("Too many requests");
        }

        $_SESSION['rate_limit'][$ip][] = time();

        return $next($request);
    }
}