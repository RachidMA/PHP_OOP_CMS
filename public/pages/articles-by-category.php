<?php

define('APP_ROOT_FOLDER_PATH', dirname(__DIR__, 2));

require  APP_ROOT_FOLDER_PATH . '/src/boostrap.php';

$category_id = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT) ?? '';

$data = [];




if (!empty($category_id)) {
    try {
        $categoryObj = $cms->getCategory();

        $articles_by_category = $categoryObj->articlesByCategory($category_id);
        if (!count($articles_by_category)) {
            echo 'NO ARTICLES FOUND FOR THE CATEGORY';
            exit;
        }
        $data['count'] = count($articles_by_category);
        $data['articles'] = $articles_by_category;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    echo 'NO ARTICLES FOUND';
}

echo $twig->render('pages/articles-by-category.html', $data);
