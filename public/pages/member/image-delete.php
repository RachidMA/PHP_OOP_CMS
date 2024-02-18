<?php

define('AP_ROOT_FOLDER_ABS_PATH', dirname(__DIR__, 3));

require AP_ROOT_FOLDER_ABS_PATH . '/src/boostrap.php';


$article_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? NULL;


if (!$article_id) {
    $session->setMessage('ARTICLE NOT FOUND');
    redirect(DOC_ROOT . '/pages/page-not-found.php');
}
echo 'ARTICLE ID FOUND';
