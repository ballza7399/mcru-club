<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * PDO singleton — แทน db.php เดิม
 * ใช้ prepared statement ทุกที่เพื่อกัน SQL injection
 */
class Database
{
    private static ?PDO $instance = null;

    public static function connect(array $cfg): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['name']};charset={$cfg['charset']}";

        try {
            self::$instance = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        return self::$instance;
    }

    public static function instance(): PDO
    {
        if (self::$instance === null) {
            throw new \RuntimeException('Database not connected. Call Database::connect() first.');
        }
        return self::$instance;
    }
}
