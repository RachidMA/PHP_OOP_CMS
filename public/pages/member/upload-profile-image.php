<?php

define('AP_ROOT_FOLDER_4', dirname(__DIR__, 3));

require AP_ROOT_FOLDER_4 . '/src/boostrap.php';

is_loggedIn($session->id);
isMember($session->role);

$user_id = $session->id ?? '';
$data = [];
$errors = [];
$destination = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image'])) {
        $errors['image_file'] = ($_FILES['image']['error'] === 1) ? 'FILE TOO BIG' : '';
    }

    //IF IMAGE UPLOADED AND THERE ARE NO ERRORS
    if ($_FILES['image']['error'] === 0) {

        //CHECKING IMAGE TYPE
        $imageType = mime_content_type($_FILES['image']['tmp_name']);
        $errors['image_file'] .= (in_array($imageType, MEDIA_TYPE)) ? '' : 'IMAGE HAS WRONG TYPE';
        //CHECKING IMAGE EXTENSION
        $image_ex = mb_strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $errors['image_file'] .= (in_array($image_ex, FILE_EXTENSION)) ? '' : 'WRONG EXTENSION';
        //CHECKING IMAGE SIZE
        $image_size = $_FILES['image']['size'];
        $errors['image_file'] = ($image_size < MEDIA_SIZE) ? '' : 'IMAGE TOO LARGE';


        //IF NO ERRORS. CREATE THE DESTINATION PATH
        if ($errors['image_file'] == '') {
            //CALL FUNCTION TO CHECK IF PATH FOR IMAGE ALREADY EXIST
            $image_name = $_FILES['image']['name'];
            $profileImage['path'] = create_image_path($image_name, UPLOADS . 'profile\\');
            $destination = UPLOADS . 'profile\\' .  $profileImage['path'];

            //CHECK IF USER HAS PROFILE IMAGE SAVED ALREADY IN THE DATABASE
        }
        //IF DESTINAITON IS CREATED
        if ($destination) {
            try {
                $profileImage['user_id'] = $user_id;
                //PROFILE IMAGE CLASS OBJECT
                $profileImageObj = $cms->getProfileImage();
                $pdo = $cms->getDB();
                $pdo->beginTransaction();
                var_dump($profileImage);
                $image_saved = $profileImageObj->saveUserImage($profileImage);

                if ($image_saved) {
                    move_uploaded_file($_FILES["image"]["tmp_name"], $destination);
                    $pdo->commit();
                }
            } catch (PDOException $e) {
                echo "Error: " + $e->getMessage();
                $pdo->roleback();
                if (file_exists(UPLOADS . 'profile/' .  $profileImage['path'])) {
                    unlink(UPLOADS . "profile/" . $profileImage['path']);
                }
            }
        } else {
            $errors['image_path'] = 'PLEASE UPLOAD YOUR IMAGE';
        }
    }
}
$data['errors'] = $errors;


echo $twig->render('pages/member/upload-profile-image.html', $data);
