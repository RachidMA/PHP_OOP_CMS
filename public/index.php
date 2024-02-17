<?php

define('APPLICATION_ROOT', dirname(__DIR__, 1));

require APPLICATION_ROOT . '/src/boostrap.php';

//2) #CLASSES
use Dotenv\Dotenv;


//3) WITH DOTENV CAN ACCESS .ENV FILE VARIABLES USING EITHER GETENV METHOD OR SUPERGLOBEL ARRAY $_ENV, $_SERVER
$dotenv = Dotenv::createImmutable(APPLICATION_ROOT);
$dotenv->load();


//FETCH ALL ARTICLES
$articles = $cms->getArticle()->getAllArticles();

$data['articles'] = $articles;

//GET ALL CATEGORIES
$categories = $cms->getCategory()->getAllCategories();
$data['categories'] = $categories;

$session = $cms->getSession();


// //RENDER THE HTML VIEW PAGE
echo $twig->render('pages/index.html', $data);
