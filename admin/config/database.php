<?php

/**
 * PDO database connection singleton and db() helper for the admin panel.
 */
require_once __DIR__ . '/config.php';

class Database
{
    private static ?PDO $instance = null;

    public static function conn(): PDO
    {
        if (self::$instance === null) {
            $port = defined('DB_PORT') ? (string) DB_PORT : '3306';
            $charset = defined('DB_CHARSET') ? (string) DB_CHARSET : 'utf8mb4';
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                defined('DB_HOST') ? DB_HOST : '127.0.0.1',
                $port,
                defined('DB_NAME') ? DB_NAME : 'corparate_webpark',
                $charset
            );
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $exception) {
                http_response_code(500);
                exit('Database connection failed: ' . htmlspecialchars($exception->getMessage()));
            }
        }
        return self::$instance;
    }
}

function db(): PDO
{
    return Database::conn();
}
