<?php

define('APPLICATION_ROOT', dirname(__DIR__, 2));


require APPLICATION_ROOT . '/src/boostrap.php';



$cms->getSession()->clearMessage();


// //RENDER THE HTML VIEW PAGE
echo $twig->render('pages/admin/dashboard.html');
// $cms->getSession()->delete();