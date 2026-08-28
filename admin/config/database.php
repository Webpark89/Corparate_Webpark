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
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
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
