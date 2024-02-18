<?php
define('APl_ROOT', dirname(__DIR__, 2));

require APl_ROOT . '/src/boostrap.php';


$data = [];

//PASS SESSION TO DATA
$data['session'] = $session;
$session->clearMessage();

//FETCH ALL CATEGORIES
$categories = $cms->getCategory()->getAllCategories();
$data['categories'] = $categories;
echo $twig->render('pages/page-not-found.html', $data);
