<?php

namespace App\Database;

use \PDO;
use \PDOException;
use \App\Config;

class Connection
{
    private static ?PDO $pdo = null;

    public static function init()
    {
        // to satisfy singleton pattern
        if (self::$pdo !== null) {
            return;
        }

        try {
            self::$pdo = new PDO("mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8", Config::DB_USERNAME, Config::DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function pdo (): PDO {
        return self::$pdo;
    }
}
