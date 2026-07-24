<?php

class Database
{
    private static ?PDO $connection = null;

    /**
     * Return a single PDO connection instance.
     */
    public static function connection(): PDO
    {
        if (self::$connection === null) {

            require __DIR__ . '/../../config/database.php';

            self::$connection = $pdo;
        }

        return self::$connection;
    }
}