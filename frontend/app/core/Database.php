<?php

declare(strict_types=1);

/**
 * Singleton PDO connection shared by frontend models.
 *
 * Reads credentials from admin-style constants when defined at bootstrap.
 */
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
        $host = (string) (defined('DB_HOST') ? DB_HOST : '127.0.0.1');
        $db = (string) (defined('DB_NAME') ? DB_NAME : 'WEBPARK');
        $user = (string) (defined('DB_USER') ? DB_USER : 'root');
        $password = (string) (defined('DB_PASS') ? DB_PASS : 'root');
        $port = (string) (defined('DB_PORT') ? DB_PORT : '');
        $charset = (string) (defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');

        $portPart = $port !== '' ? ";port={$port}" : '';
        $dsn = "mysql:host={$host}{$portPart};dbname={$db};charset={$charset}";

        try {
            return new PDO(
                $dsn,
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    // Prevent Thai mojibake when server default charset differs.
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
                ]
            );
        } catch (PDOException $exception) {
            die('Database connection failed: ' . $exception->getMessage());
        }
    }
}
