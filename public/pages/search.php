<?php

define('APPLICATION_PATH', dirname(__DIR__, 2));

require  APPLICATION_PATH . '/src/boostrap.php';


$articleObj = $cms->getArticle();
//INITIALIZE TERM , SHOW , FROM
$term = filter_input(INPUT_GET, 'term');
$show = filter_input(INPUT_GET, 'show', FILTER_VALIDATE_INT) ?? 3;
$from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT) ?? 0;

$data = [];
$count = 0;
$current_page = 0;
$articlesFound = [];

if ($term) {
    //CHECK IF WE HAVE RESULTS IN THE DATABASE AND GET THE COUNT
    try {
        /*GETRESULTCOUNT function is search term in articles and user as author
        TO MAKE SURE GIVE THE POSSIBLE TO SEARCH BY AUTHOR TOO*/
        $count = $articleObj->getResultCount($term);
        $data['count'] = $count;
    } catch (Exception $e) {
        echo 'NO RESULTS FOUND';
        exit();
    }
}

//CHECK IF WE HAVE COUNT>0;
if ($count > 0) {
    //FETCH ARTICLES BASED ON SEARCH TERM
    try {
        $result = $articleObj->fetchArticlesWithTerm($term, $show, $from);
        if (!empty($result)) {
            $articlesFound = $result;
        } else {
            throw new Exception('No results found');
        }
    } catch (PDOException $ex) {
        die("Error: " . $ex->getMessage());
    }
}



$data = [
    'term' => $term,
    'count' => $count,
    'articles' => $articlesFound
];

echo $twig->render('/pages/search.html', $data);
