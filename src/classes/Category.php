<?php


namespace MyApp\Classes\classes;

use MyApp\Classes\Database\Database;
use PDO;
use PDOException;

class Category
{
    protected $db = null;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllCategories()
    {
        try {
            $sql = 'SELECT id, name FROM categories WHERE navigation IS NOT NULL';
            return $this->db->runSQL($sql)->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
