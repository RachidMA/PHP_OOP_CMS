<?php

namespace MyApp\Classes\CMS;

use MyApp\Classes\classes\Article;
use MyApp\Classes\classes\Category;
use MyApp\Classes\classes\Image;
use MyApp\Classes\Database\Database;
use MyApp\Classes\classes\User;
use MyApp\Classes\Session\Session;

class CMS
{
    protected $db = null;
    protected $user = null;
    protected $session = null;
    protected $article = null;
    protected $category = null;
    protected $image = null;

    public function __construct($dsn, $user, $pass)
    {
        $this->db = new Database($dsn, $user, $pass);
    }

    //THIS DB WILL BE USED FOR CREATING MEMBERS USING THE SQL BEGINTRANSACTION
    public function getDB()
    {
        return $this->db;
    }
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = new User($this->db);
        }
        return $this->user;
    }

    public function getArticle()
    {
        if ($this->article === null) {
            $this->article = new Article($this->db);
        }
        return $this->article;
    }

    public function getSession()
    {
        if ($this->session === null) {
            $this->session = new Session();
        }
        return $this->session;
    }

    public function getCategory()
    {
        if ($this->category === null) {
            return $this->category = new Category($this->db);
        }
        return $this->category = new Category($this->db);
    }

    public function getImage()
    {
        if ($this->image === null) {
            return $this->image = new Image($this->db);
        }
        return $this->image = new Image($this->db);
    }
}
