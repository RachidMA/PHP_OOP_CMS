<?php

define('APP_FOLDER_PATH', dirname(__DIR__, 3));
require APP_FOLDER_PATH . '/src/boostrap.php';

is_loggedIn($session->id);
isMember($session->role);
