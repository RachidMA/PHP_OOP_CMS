<?php

namespace MyApp\Classes\classes;

use MyApp\Classes\Database\Database;
use PDOException;

class ProfileImage
{
    protected $db = null;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function saveUserImage($data): bool
    {
        try {
            // Prepare the SQL statement.

            $sql = 'INSERT INTO profileImages (user_id, path, imageAlt) VALUES (:user_id, :image_name, :imageAlt)';
            $stmt = $this->db->prepare($sql);

            $params = [':user_id' => $data['user_id'], ':image_name' => $data['path'], ':imageAlt' => 'PROFILE IMAGE TEXT'];
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
