<?php
define('APP_ROOT', dirname(__DIR__, 1) . '\\');


//1) YOUR IMPORTS BELLOW
// #FILES
require APP_ROOT . '/vendor/autoload.php';
require APP_ROOT . '/config/config.php';
require 'functions.php';

use MyApp\Classes\CMS\CMS;
// use Twig\TokenParser\ApplyTokenParser;

//TO UPLOAD AND USE .ENV VARIABLES
//2) #CLASSES
use Dotenv\Dotenv;
//3) WITH DOTENV CAN ACCESS .ENV FILE VARIABLES USING EITHER GETENV METHOD OR SUPERGLOBEL ARRAY $_ENV, $_SERVER
$dotenv = Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

//SET UP TWIG ENVIREMENT
// $twig_options['cache'] = APP_ROOT . '/public/templates/cache';
$twig_options['debug'] = DEV;

$loader = new Twig\Loader\FilesystemLoader([APP_ROOT . 'public\templates\views']);
$twig = new Twig\Environment($loader, $twig_options);

$twig->addGlobal('doc_root', DOC_ROOT);
$twig->addGlobal('app_root', APP_ROOT);
$twig->addGlobal('root_folder', ROOT_FOLDER);



if (DEV === true) {
    $twig->addExtension(new \Twig\Extension\DebugExtension());
}
//END TWIG SETUP

//HANDLING ERRORS AND EXCEPTION IN PRODUCTION MODE
if (DEV !== true) {
    set_exception_handler('handle_exception');
    set_error_handler('handle_error');
    register_shutdown_function('handle_shutdown');
}


//INITIALIZE CMS OBJECT
try {
    $cms = new CMS($dsn, $user, $pass);
    // var_dump('CONNECTION IS CORRECT');
} catch (Exception $e) {
    throw $e;
}


//START SESSION 
$session = $cms->getSession();


//ADD SESSION TWIG GLOBAL ENVIREMENT
$twig->addGlobal('session', $session);





//STOP THE CONNECTION
unset($dsn, $user, $pass);
