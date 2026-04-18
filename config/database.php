<?php

class DB {

    private static $conn;

    public static function connect() {

        if (!self::$conn) {

            self::$conn = new mysqli(
                env("DB_HOST"),
                env("DB_USER"),
                env("DB_PASS"),
                env("DB_NAME")
            );

            if (self::$conn->connect_error) {
                die("Database connection failed");
            }
        }

        return self::$conn;
    }
}