<?php

define('APP_ROOT_PATH', dirname(__DIR__, 3));

//IMPORT BOOSTRAP AND FUNCTIONS FILES
include APP_ROOT_PATH . '/src/boostrap.php';

//INICIALIZE THE SESSION
$session = $cms->getSession();


$member = [
    'email' => $session->email ?? null
];
$errors = [
    'warning' => ''
];




//CLEAR SESSION AFTER GET THE DATA NEEDED
$session->clearMessage();
$session->clearEmail();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $memberData['email'] = $_POST['email']  ?? '';
    $memberData['password'] = $_POST['password']  ?? '';

    //VALIDATE EMAIL
    if (!isEmail(trim($memberData['email']))) {
        $errors['email'] = 'INVALID EMAIL FORMAT';
    } else {
        //FETCH MEMBER FROM DATABASE
        $userOjb = $cms->getUser();
        $member = $userOjb->getMemberForLogin($memberData);
        if (!$member) {
            $errors['warning'] = 'PLEASE TRY LOGIN AGAIN';
        } else {
            if ($member && $member['role'] == 'susponded') {
                $errors['warning'] = 'YOUR ACCOUNT HAS BEEN SUSPENDED';
            } else {
                //SAVE MEMBER TO SESSION
                $session->createMember($member);
                //REDIRECT MEMBER TO  HOME PAGE
                redirect(DOC_ROOT);
            }
        }
    }
}

$data = [
    'session' => $session,
    'errors' => $errors
];


//CLEAR SESSION AFTER GET THE DATA NEEDED
$session->clearMessage();
$session->clearEmail();

echo $twig->render('pages/auth/login.html', $data);
