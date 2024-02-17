<?php

define('PATH_APP_FOLDER', dirname(__DIR__, 2));

require  PATH_APP_FOLDER . '/src/boostrap.php';

//INITIALIZE article array and article id 
$article_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? '';
$article = [];
$errors = [];

if ($article_id) {
    //FETECH THE ARTICLE FROM DATABASE
    $articleObj = $cms->getArticle();
    try {
        $article = $articleObj->getArticleById($article_id);
    } catch (PDOException $e) {
        $session->setMessage('ARTICLE NOT FOUND');
        redirect(APP_ROOT);
    }
}

$data = [];

echo $twig->render('/pages/article.html', $data);
