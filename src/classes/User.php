<?php

namespace MyApp\Classes\classes;

use MyApp\Classes\Database\Database;
use PDOException;

class User
{

    protected $db = null;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    //CREATE NEW MEMBER
    public function createMember(array $member)
    {
        $member['password'] = password_hash($member['password'], PASSWORD_BCRYPT);
        //SQL QUERY
        $sql = 'INSERT INTO users (firstName, lastName, email, password) 
        VALUE (:firstName, :lastName, :email, :password)';
        try {
            $stmt = $this->db->runSQL($sql, $member);
            return true;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {

                return false;
            } else {

                throw $e;
            }
        }
    }

    //FETCH MEMBER FOR LOGIN
    public function getMemberForLogin($data)
    {
        try {
            $sql = "SELECT CONCAT(firstName,' ',LastName) as name, id, password, role FROM users WHERE email=:email";
            $userData = $this->db->runSQL($sql,  ['email' => $data['email']])->fetch();
            //IF USER DOESNOT EXIST OR PASSWORD  IS WRONG RETURN FALSE OTHERWISE THE DATA OF THE USER
            if (!$userData || !password_verify($data['password'], $userData['password'])) {
                return false;
            } else {
                unset($userData['password']);
                return $userData;
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    //FETCH USER BASED ON ID
    public function getUserById(INT $user_id)
    {
        try {
            $sql = "SELECT au.id as author_id,  CONCAT(au.firstName, ' ' , au.lastName) as author_name, au.role,  pro_img.path as image_path, pro_img.imageAlt as image_alt
            FROM users as au
            LEFT JOIN profileimages as pro_img
            ON au.id = pro_img.user_id
            WHERE au.id = :userId";
            $result = $this->db->runSQL($sql, [':userId' => $user_id])->fetch();

            if (!empty($result)) {

                return $result;
            } else {
                throw new PDOException('No User Found');
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            die;
        }
    }
}
