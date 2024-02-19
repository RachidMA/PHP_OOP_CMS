<?php

define('AP_ROOT_FOLDER_ABS_PATH', dirname(__DIR__, 3));

require AP_ROOT_FOLDER_ABS_PATH . '/src/boostrap.php';

is_loggedIn($session->id);
isMember($session->role);

$article_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? NULL;

//SET ARTICLE OBJECT
$articleOjb = $cms->getArticle();

$img_ID = null;
$image_path = '';

if (!$article_id) {
    $session->setMessage('ARTICLE NOT FOUND');
    redirect(DOC_ROOT . '/pages/page-not-found.php');
} else {
    //GET IMAGE ID
    $image_Data = $articleOjb->getImageIdAndPath($article_id);
    var_dump($image_Data);
    if (!$image_Data['image_path']) {
        echo 'NO RECORD FOUND';
        exit();
    }
    $img_ID = $image_Data['image_id'];
    $image_path = $image_Data['image_path'];
}


try {
    $pdo = $cms->getDB();
    $pdo->beginTransaction();
    //1) SET ARTICLE IMAGE_ID TO NULL
    $idSetNull = $articleOjb
        ->setImageIdToNull($article_id);
    if ($idSetNull === FALSE) {
        die("Error");
    }

    //2)REMOVE THE IMAGE FROM IMAGES TABLE
    $imageRemoved = $cms->getImage()->removeImageById($img_ID);

    if (!$imageRemoved) {
        echo 'The image was not removed from the images table!';
        exit();
    }
    //3) REMOVE THE SAVED IMAGE FROM UPLOADS FOLDER
    $destination = UPLOADS . 'blogPost\\' . $image_path;

    if (file_exists($destination) && $idSetNull && $imageRemoved) {
        unlink(UPLOADS . 'blogPost\\' . $image_path);
        $pdo->commit();
        redirect('create-article.php', ['id' => $article_id]);
    } else {
        $pdo->rollBack();
        throw new Exception('An error occurred while deleting the file. Please try again later.');
    }

    //4 RETURN TO UPDATE ARTICLE WITH ARTICLE ID
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}
