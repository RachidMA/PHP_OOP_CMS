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

        if (!$article) {;
            $session->setMessage('ARTICLE NOT FOUND FROM ARTICLE PAGE');
            redirect(DOC_ROOT);
        }
    } catch (PDOException $e) {
        $session->setMessage('SEVER ERROR. NO ARTICLE FOUND');
        redirect(DOC_ROOT);
    }
}

$data = [
    'article' => $article
];

echo $twig->render('/pages/article.html', $data);
