<?php

define('AP_ROOT_FOLDER', dirname(__DIR__, 3));

require AP_ROOT_FOLDER . '/src/boostrap.php';

is_loggedIn($session->id);
isMember($session->role);

$user_id = $session->id ?? '';


$data = [];

//CHECK IF SESSION HAS MESSAGE
$message = $session->message ?? '';
$data = [
    'sessionData' => [
        'message' => $message
    ]
];
//CLEAR SESSION MESSAGE
$session->clearMessage();
if ($user_id) {
    $userObj = $cms->getUser();
    $articleObj = $cms->getArticle();

    //FETCH SPECIFIC USER BASED ON ID
    try {
        $author = $userObj->getUserById($user_id);
        $author_articles = $articleObj->getArticlesForAuthor($user_id);

        if (empty($author)) {
            echo 'AUTHOR WAS NOT FOUND';
            exit;
        }
        if ($author_articles) {
            $data['articles'] = $author_articles;
        }
        $data['author'] = $author;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

echo $twig->render('pages/member/profile.html', $data);
