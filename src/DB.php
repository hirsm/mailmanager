<?php

namespace App;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = $_ENV['DB_HOST'] ?? 'localhost';
		$port = $_ENV['DB_PORT'] ?? '3306';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? null;
        $name = $_ENV['DB_NAME'] ?? 'mailserver';

        if (str_starts_with($host, '/')) {
            // Unix-Socket
            $dsn = "mysql:unix_socket=$host;dbname=$name;charset=utf8mb4";
        } else {
            // TCP-Verbindung
            $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        if ($pass === '' || $pass === null) {
            self::$pdo = new PDO($dsn, $user, null, $options);
        } else {
            self::$pdo = new PDO($dsn, $user, $pass, $options);
        }

        return self::$pdo;
    }
}

