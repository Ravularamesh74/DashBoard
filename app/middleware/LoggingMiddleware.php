<?php

class LoggingMiddleware implements Middleware {

    public function handle($request, $next) {

        $log = date("Y-m-d H:i:s") . " | " .
               $_SERVER['REQUEST_METHOD'] . " " .
               $_SERVER['REQUEST_URI'] . " | IP: " .
               $_SERVER['REMOTE_ADDR'] . PHP_EOL;

        file_put_contents("../storage/logs/access.log", $log, FILE_APPEND);

        return $next($request);
    }
}