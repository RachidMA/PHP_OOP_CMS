<?php


function redirect(string $location, array $params = [], INT $response_code = 302)
{
    $qr = $params ? '?' . http_build_query($params) : '';
    $location = $location . $qr;

    header('location:' . $location, $response_code);
    exit;
}

function is_loggedIn(INT $id)
{
    if (!$id) {
        redirect(DOC_ROOT . '/pages/auth/login.php');
    }
}
function isAdmin(string $role)
{
    if ($role !== 'admin') {
        redirect(DOC_ROOT);
    }
}
function isMember(string $role)
{
    if ($role !== 'member') {
        redirect(DOC_ROOT . '/pages/auth/login.php');
    }
}

function is_text(string $text, INT $min = 1,  INT $max = 255)
{
    $text_len = mb_strlen($text);
    return (($text_len >= $min && $text_len <= $max) ? true : false);
}

function create_image_path(string $filename, string $uploads_path)
{
    $filebase = pathinfo($filename, PATHINFO_FILENAME);
    $fileExtension = mb_strtolower((pathinfo($filename, PATHINFO_EXTENSION)));
    $filebase = preg_replace('/[^A-z0-9]/', '-', $filebase);
    $i = 1;
    while (file_exists($uploads_path . $filename)) {
        $i = $i + 1;
        $filename = $filebase . '-' . $i  . '.' . $fileExtension;
    }
    return $filename;
}

function is_category_exist($category_id, $categories)
{
    $categorieListIds = array_column($categories,  'id');
    return (in_array($category_id, $categorieListIds));
}

//CREATE FUNCTION TO CHECK IF PASSWORD IS VALID
function validatePassword($password, $min = 5, $max = 50)
{
    if (strlen($password) < $min || strlen($password) >= $max) {
        return false;
    };
    //CHECK IF PASSWORD CONTAINS AT LEAST ONE UPPER LETTER
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    //CHECK IF PASSWORD CONTAINS AT LEAST ONE LOWER LETTER
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }

    //CHECK IF PASSWORD CONTAINS A NUMBER
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }

    //IF PASSWORD PASSED ALL CHECKS FUNCTIONS SHOULD RETURN TRUE
    return true;
}

//3) isEmail METHOD
function isEmail(string $email)
{

    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
