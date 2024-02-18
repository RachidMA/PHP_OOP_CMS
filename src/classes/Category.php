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

    //FETCH ALL ARTICLES BY CATEGORY
    public function articlesByCategory(INT $id)
    {
        if (!is_int($id)) {
            throw new \InvalidArgumentException('Id must be an integer');
        }
        $sql = "SELECT a.id as article_id, a.title, a.summary, a.content, a.user_id as author_id,
        a.category_id, ca.name as category_name, a.image_id, a.published,  a.created_at as created_at,  CONCAT(u.firstName,' ' , u.lastName) as author_name, i.image_path, i.image_alt 
        FROM articles as a 
        JOIN  users as u ON a.user_id = u.id
        JOIN categories AS ca ON  a.category_id = ca.id
        LEFT JOIN images AS i ON a.image_id = i.id
        WHERE a.category_id=:cat_id
        AND u.role !='suspended'
        AND a.published IS TRUE
        ORDER BY a.created_at  DESC";

        $sth = $this->db->runSQL($sql, [':cat_id' => $id]);

        return $sth->fetchAll();
    }
}
