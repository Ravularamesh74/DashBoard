<?php

require_once __DIR__ . "/env.php";

loadEnv(__DIR__ . "/../.env");

/* LOAD ALL CONFIG FILES */
require_once "constants.php";
require_once "app.php";
require_once "database.php";
require_once "session.php";
require_once "services.php";

/* LOAD HELPERS */
require_once "../app/helpers/functions.php";

/* AUTOLOAD MODELS & CONTROLLERS */
spl_autoload_register(function ($class) {

    $paths = [
        "../app/models/$class.php",
        "../app/controllers/$class.php",
        "../app/middleware/$class.php"
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});