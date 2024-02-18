<?php

namespace MyApp\Classes\classes;


use MyApp\Classes\Database\Database;
use PDOException;

class Article
{

    protected $db = null;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    //FETCH ALL ARTICLES
    public function getAllArticles()
    {
        try {
            $sql = 'SELECT a.id as article_id, a.title, a.summary, a.content, a.user_id as author_id,
            a.category_id, ca.name as category_name, a.image_id, a.published,  a.created_at as created_at,  CONCAT(u.firstName," ", u.lastName) as author_name, i.image_path, i.image_alt 
            FROM articles as a 
            JOIN  users as u ON a.user_id = u.id
            JOIN categories as ca ON a.category_id=ca.id
            LEFT JOIN images AS i ON a.image_id = i.id';
            return $this->db->runSQL($sql)->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    //FETCH SINGLE ARTICLE
    public function getArticleById($id)
    {
        $sql = 'SELECT a.id as article_id, a.title, a.summary, a.content, a.user_id,
    a.category_id, ca.name as category_name , a.image_id, a.published, a.created_at as created_at, CONCAT(u.firstName," ", u.lastName) as author_name,
    i.image_path, i.image_alt 
    FROM articles as a 
    JOIN users as u ON a.user_id = u.id
    JOIN categories as ca ON a.category_id=ca.id
    LEFT JOIN images AS i ON a.image_id = i.id 
    WHERE a.id=:id AND a.published IS NOT NULL';

        try {
            $stmt = $this->db->runSQL($sql, [':id' => $id]);
            $article = $stmt->fetch();

            return $article;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    //FETCH ALL ARTICLES FOR SPECIFIC AUTHOR
    public function getArticlesForAuthor($author_id, $published = true)
    {
        try {
            //GET 6 NEWLY ADD ARTICLES
            $sql = "SELECT art.id as article_id, art.title, art.summary ,
        art.user_id, art.category_id, art.created_at, ca.name, img.image_path as image_path, img.image_alt as image_alt
        FROM articles as art
        
        JOIN categories as ca ON art.category_id = ca.id
        LEFT JOIN images as img ON art.image_id = img.id";

            //ADD PUBLISHED TO QUERY IF PUBLISHED ID TRUE
            if (!$published) {
                $sql .= " WHERE art.published IS FALSE ";
            } else {
                $sql .= " WHERE art.published IS TRUE";
            }

            $sql .= " AND art.user_id = :author_id ORDER BY created_at DESC";

            //ADD LIMIT AND OFFSET
            // $sql .= " ORDER BY art.created_at DESC LIMIT :limit OFFSET :offset";

            $result = $this->db->runSQL($sql, [":author_id" => $author_id])->fetchAll();

            return $result;
        } catch (\PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    //THIS FUNCTION RETURNS THE NUMBER OF ARTICLES FOUND BASED ON TERM SEARCHED
    public function getResultCount(string $term)
    {
        $term = mb_strtolower($term);
        $arguments['term1'] = '%' . $term . '%';
        $arguments['term2'] = '%' . $term . '%';
        $arguments['term3'] = '%' . $term . '%';
        $arguments['term4'] = '%' . $term . '%';

        $sql = 'SELECT COUNT(*) FROM articles as a
        LEFT JOIN users as u ON  a.user_id=u.id 

        WHERE a.title LIKE :term1
        OR a.summary LIKE :term2
        OR a.content LIKE :term3
        OR CONCAT(u.firstName, " ", u.lastName)  LIKE  :term4 
        AND published IS TRUE';
        try {
            $count = $this->db->runSQL($sql, $arguments)->fetchColumn();

            return $count;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    //FETCH ARTICLES BASED ON SEARCH TERM
    public function fetchArticlesWithTerm(string $term, INT $show, INT $from)
    {
        $term = mb_strtolower($term);
        $arguments['term1'] = '%' . $term . '%';
        $arguments['term2'] = '%' . $term . '%';
        $arguments['term3'] = '%' . $term . '%';
        $arguments['term4'] = '%' . $term . '%';
        $arguments['show'] = $show;
        $arguments['from'] = $from;

        $sql = 'SELECT a.id as article_id, a.title, a.summary, a.content, a.user_id as author_id,
        a.category_id, a.image_id, a.published , a.created_at as created_at,  CONCAT(u.firstName," ", u.lastName) as author_name, i.image_path, i.image_alt 
        FROM articles as a
        JOIN users as u ON  a.user_id=u.id 
        LEFT JOIN  images AS i ON a.image_id = i.id

        WHERE a.title LIKE :term1
        OR a.summary LIKE :term2
        OR a.content LIKE :term3
        OR CONCAT(u.firstName, " ", u.lastName)  LIKE  :term4 
        AND published IS TRUE
        
        ORDER BY a.created_at DESC
        LIMIT :show  OFFSET :from';
        try {
            $articlesFound = $this->db->runSQL($sql, $arguments)->fetchAll();

            return $articlesFound;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
