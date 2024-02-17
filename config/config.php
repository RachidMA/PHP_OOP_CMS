<?php


//SET THE DOCUMENT ROOT
define('APPL_ROOT', dirname(__FILE__, 2));


//SET THE ROOT FOLDER
define('ROOT_FOLDER', 'public');
//SET THE ROOT PATH
define('DOC_ROOT',  '/PHP_OOP_CMS_PROJECT/public');

define('DEV', true);

//2) #CLASSES
use Dotenv\Dotenv;

//3) WITH DOTENV CAN ACCESS .ENV FILE VARIABLES USING EITHER GETENV METHOD OR SUPERGLOBEL ARRAY $_ENV, $_SERVER
$dotenv = Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

//SET UP DATABASE CONNECTION USING ENV VARIABLES
$type = $_ENV['DB_TYPE'];
$server = $_ENV['DB_SERVER'];
$db = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASSWORD'];
$charset = 'utf8mb4';
$port = $_ENV['DB_PORT'];


//SET THE DSN FOR DATABASE CONNECTION
$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";


//SET THE IMAGE TYPE ALLOWED AND SIZE AND EXTENSION
define('MEDIA_TYPE', ['image/jpeg', 'image/png', 'image/gif',]);
define('FILE_EXTENSION', ['jpeg', 'png', 'gif',]);
define('MEDIA_SIZE', '5242880');

define('UPLOADS', APPL_ROOT . DIRECTORY_SEPARATOR . ROOT_FOLDER . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
