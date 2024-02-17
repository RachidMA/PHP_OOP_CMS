<?php

define('APP_ROOT_PATH_2', dirname(__DIR__, 3));

include APP_ROOT_PATH_2 . '/src/boostrap.php';


$session = $cms->getSession();
//INITIALIZE MEMBER AND ERRORS
$member = [];
$errors = [
    'warning' => '',
    'firstName' => '',
    'lastName' => '',
    'email' => '',
    'password' => '',
    'confirm' => ''
];

//IF REQUEST METHOD POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $member['firstName'] = $_POST['firstName'];
    $member['lastName'] = $_POST['lastName'];
    $member['email'] = strtolower(trim($_POST['email']));
    $member['password'] = $_POST['password'];
    $confirm = $_POST['confirm'];



    //VERIFY RECEIVED DATA
    $errors['firstName'] = is_text($member['firstName']) ? '' : 'First name is required must be between 3 and 100 characters.<br>';
    // if (!isset($member['firstName']) || empty($member['firstName']) || is_text($member['firstName'], 3, 100)) {
    //     $errors['firstName'] .= "First name is required must be between 3 and 100 characters.<br>";
    // } else {
    //     $member['firstName'] = ucwords($member['firstName']);
    // }
    $errors['lastName'] = is_text($member['lastName']) ? '' : 'Last Name is required must be between 3 and 100 characters.<br>';
    // if (!isset($member['lastName']) || empty($member['lastName']) || is_text($member['lastName'], 3, 100)) {
    //     $errors['lastName'] .= "Last Name is required must be between 3 and 100 characters.<br>";
    // } else {
    //     $member['lastName'] = ucwords($member['lastName']);
    // }

    $errors['email'] =  isEmail($member['email']) ? "" : "Invalid email format.<br>";
    $errors['password'] = validatePassword($member['password']) ? '' : '<div class="password-error">
    <ul>
        <li>PASSWORD MUST BE MORE THAN 8-50 CHARACTERS</li>
        <li>PASSWORD MUST CONTAIN ONE UPPER LETTER</li>
        <li>PASSWORD MUST CONTAIN ONE LOWER LETTER</li>
        <li>PASSWORD MUST CONTAIN ONE NUMBER</li>
    </ul>
</div>';

    $errors['confirm'] = ($member['password'] == $confirm) ? '' : 'PASSWORD DO NOT MATCH';
    // if (empty($member['password']) || empty($confirm) || validatePassword($member['password'])) {
    //     $errors['password'] = "Password is required.<br>Most be 8 till 50 charecters long.<br>At least one upper letter.<br>At least one number<br>Please enter your password and confirm it. ";
    //     $errors['confirm'] = "Confirmation field can not be blank.";
    // } else {
    //     if ($member['password'] !== $confirm) {
    //         $errors['password'] = "Passwords do not match!<br>Please try again.";
    //         $errors['confirm'] = "";
    //     }
    // }

    //CREATE NEW USER IF NO ERRORS FOUND
    if (!implode($errors)) {
        try {
            $savedMember = $cms->getUser()->createMember($member);
            if ($savedMember === false) {
                $errors['email'] = 'EMAIL ALREADY EXIST';
                $errors['warning'] = 'PLEASE. CORRECT MISSING DATA.';
            } elseif ($savedMember === true) {
                //SET SESSION SUCCESSFUL MESSAGE AND NEW MEMBER EMAIL
                $session->setMessage('YOU HAVE BEEN SUCCESSFULLY REGISTERED');
                $session->setEmail(htmlspecialchars($member['email']));
                redirect(DOC_ROOT . '/pages/auth/login.php');
            }
        } catch (PDOException $e) {
            $errors['warning'] = 'PLEASE. TRY AGAIN';
        }
    } else {
        $errors['warning'] = 'PLEASE. CORRECT MISSING DATA.';
    }
}

$data = [
    'member' => $member,
    'errors' => $errors
];


echo $twig->render('pages/auth/register.html', $data);
