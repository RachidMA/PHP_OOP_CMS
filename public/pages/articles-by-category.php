<?php

define('APP_ROOT_FOLDER_PATH', dirname(__DIR__, 2));

require  APP_ROOT_FOLDER_PATH . '/src/boostrap.php';

$session = $cms->getSession();



echo 'THIS IS PAGE FOR ARTICLES BY CATEGORY:';
