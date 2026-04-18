<?php

function logMessage($message, $type = "info") {

    $file = "../storage/logs/app.log";

    $date = date("Y-m-d H:i:s");

    $log = "[$date][$type] $message" . PHP_EOL;

    file_put_contents($file, $log, FILE_APPEND);
}