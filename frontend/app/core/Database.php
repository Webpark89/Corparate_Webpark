<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }
        return self::$instance;
    }

    private static function connect(): PDO
    {
        // Prefer config constants (admin/config.php style) so all pages share the same DB credentials.
        $host = (string) (defined('DB_HOST') ? DB_HOST : '127.0.0.1');
        $db = (string) (defined('DB_NAME') ? DB_NAME : 'WEBPARK');
        $user = (string) (defined('DB_USER') ? DB_USER : 'root');
        $password = (string) (defined('DB_PASS') ? DB_PASS : 'root');
        $port = (string) (defined('DB_PORT') ? DB_PORT : '');
        $charset = (string) (defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');

        $portPart = $port !== '' ? ";port={$port}" : '';
        $dsn = "mysql:host={$host}{$portPart};dbname={$db};charset={$charset}";

        try {
            $pdo = new PDO(
                $dsn,
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            // Fail fast with readable message so you can see the exact DB issue.
            die('Database connection failed: ' . $e->getMessage());
        }
    }
}
