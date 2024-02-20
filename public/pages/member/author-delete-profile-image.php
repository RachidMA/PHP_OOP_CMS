<?php

define('AP_ROOT_FOLDER_5', dirname(__DIR__, 3));

require AP_ROOT_FOLDER_5 . '/src/boostrap.php';

is_loggedIn($session->id);
isMember($session->role);

$user_id = $session->id ?? '';


$profileImgObj = $cms->getProfileImage();
$pdo = $cms->getDB();

$image_path = $profileImgObj->userHasImage($user_id)['image_path'];

$imageDestination = UPLOADS . '/profile/' . $image_path;


if ($user_id) {

    try {
        $pdo->beginTransaction();
        $imageRemoved = $profileImgObj->deleteProfileImage($user_id);
        if ($imageRemoved) {
            $pdo->commit();
            if (file_exists($imageDestination)) {
                unlink($imageDestination);
            }
            redirect('upload-profile-image.php');
        } else {
            $pdo->rollback();
            echo 'CAN NOT REMOVE IMAGE';
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}
