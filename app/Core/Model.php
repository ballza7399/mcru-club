<?php
namespace App\Core;

use PDO;

/**
 * Base Model — ให้ model ลูกสืบทอด
 */
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::instance();
    }
}
