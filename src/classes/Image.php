<?php

namespace MyApp\Classes\classes;

use MyApp\Classes\Database\Database;
use PDOException;

class Image
{
    protected $db = null;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function storeImage(array $params)
    {
        try {
            $sql = 'INSERT INTO images (image_path, image_alt) VALUES (:image_path, :image_alt)';
            $smt = $this->db->prepare($sql);
            $smt->execute($params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
