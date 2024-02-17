<?php

define('APP_ROOT_PA', dirname(__DIR__, 3));

//IMPORT BOOSTRAP AND FUNCTIONS FILES
include APP_ROOT_PA . '/src/boostrap.php';

//INICIALIZE THE SESSION
$session = $cms->getSession();


$session->delete();
redirect(DOC_ROOT);
