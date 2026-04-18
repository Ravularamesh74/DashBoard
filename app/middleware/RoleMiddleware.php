<?php

class RoleMiddleware implements Middleware {

    private $role;

    public function __construct($role = 'admin') {
        $this->role = $role;
    }

    public function handle($request, $next) {

        if (
            empty($_SESSION['user']) ||
            $_SESSION['user']['role'] !== $this->role
        ) {
            die("Access denied");
        }

        return $next($request);
    }
}