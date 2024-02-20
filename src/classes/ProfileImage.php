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

    //CHECK IF USER(AUTHOR AS PROFILE IMAGE)
    public function userHasImage(int $user_id)
    {
        try {
            $sql = 'SELECT path as image_path FROM profileimages WHERE user_id=:userId';
            $image_path = $this->db->runSQL($sql, [':userId' => $user_id])->fetchColumn();
            $saved = $this->db->runSQL($sql, [':userId' => $user_id])->rowCount() > 0 ? true : false;
            $result['saved'] = $saved;
            $result['image_path'] = $image_path;
            return $result;
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    //DELETE USER PROFILE IMAGE
    public function deleteProfileImage(int $profileId)
    {
        try {
            $sql = 'DELETE FROM profileimages WHERE user_id = :profileId';
            $smt = $this->db->prepare($sql);
            $smt->execute(["profileId" => $profileId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
