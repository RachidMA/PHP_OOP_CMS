<?php

define('AP_ROOT_FOLDER_4', dirname(__DIR__, 3));

require AP_ROOT_FOLDER_4 . '/src/boostrap.php';

is_loggedIn($session->id);
isMember($session->role);

$user_id = $session->id ?? '';
$data = [];

if ($_REQUEST['REQUEST_METHOD'] === 'POST') {
    //VALIDATE DATA OF THE UPLOADED IMAGE
}

echo $twig->render('pages/member/upload-profile-image.html', $data);
