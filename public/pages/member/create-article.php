<?php

define('AP_ROOT', dirname(__DIR__, 3));

require AP_ROOT . '/src/boostrap.php';




//IN CASE OF UPDATING THE ARTICLE
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? '';

$destination = '';
$temp = $_FILES['image']['tmp_name'] ?? '';


//INITIALIZING ARTICLE AND ERRORS
$article = [
    'article_id' => $id,
    'title' => '',
    'summary' => '',
    'content' => '',
    'user_id' => $session->id ?? null,
    'category_id' => 0,
    'image_id' => null,
    'published' => false,
    'image_path' => '',
    'image_alt' => ''
];

//INITIALIZE ERRORS
$errors = [
    'warning' => '',
    'title' => '',
    'summary' => '',
    'content' => '',
    'category' => '',
    'image_file' => '',
    'image_alt' => ''
];


//FETCH ARTICLE FOR UPDATE
if ($id) {

    $article = $cms->getArticle()->getArticleById($id);
    if (!$article) {
        redirect(DOC_ROOT . '/pages/page-not-found.php', response_code: 404);
    }
}

$section = $article['category_id'] ? $article['category_id'] : null;
//FETCH ALL CATEGORIES FROM DATABASE


//FETCH ALL CATEGORIES
$categories = $cms->getCategory()->getAllCategories();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump('POST METHOD: ');
    if (isset($_FILES['image'])) {
        $errors['image_file'] = ($_FILES['image']['error'] === 1) ? 'FILE TOO BIG' : '';
    }

    //IF IMAGE UPLOADED AND THERE ARE NO ERRORS
    if ($temp  && $_FILES['image']['error'] === 0) {
        $article['image_alt'] = $_POST['image_alt'] ?? '';
        //CHECKING IMAGE TYPE
        $imageType = mime_content_type($temp);
        $errors['image_file'] .= (in_array($imageType, MEDIA_TYPE)) ? '' : 'IMAGE HAS WRONG TYPE';
        //CHECKING IMAGE EXTENSION
        $image_ex = mb_strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $errors['image_file'] .= (in_array($image_ex, FILE_EXTENSION)) ? '' : 'WRONG EXTENSION';
        //CHECKING IMAGE SIZE
        $image_size = $_FILES['image']['size'];
        $errors['image_file'] = ($image_size < MEDIA_SIZE) ? '' : 'IMAGE TOO LARGE';
        //CHECKING ALT TEXT
        $errors['image_alt'] = is_text($article['image_alt'], 1, 255) ? '' : 'ADD IMAGE DESCRIPTION';

        //IF NO ERRORS. CREATE THE DESTINATION PATH
        if ($errors['image_file'] == '' && $errors['image_alt'] == '') {
            //CALL FUNCTION TO CHECK IF PATH FOR IMAGE ALREADY EXIST
            $image_name = $_FILES['image']['name'];
            $article['image_path'] = create_image_path($image_name, UPLOADS);
            $destination = UPLOADS . 'blogPost\\' . $article['image_path'];
            var_dump($destination);
        }
    }
    //GET THE REST OF DATA FROM THE FORM
    $article['title'] = $_POST['article-title'];
    $article['summary'] = $_POST['article-summary'];
    $article['content'] = $_POST['article-content'];
    $article['category_id'] = $_POST['category_id'];
    $article['published'] = (isset($_POST['published']) && $_POST['published'] == 1) ? 1 : 0;

    //VERIFY ARTICLE DATA
    $errors['title'] = is_text($article['title'], 1, 255) ? '' : 'TITLE MOST BE BETWEEN 1 AND 255 CHARECTERS';
    $errors['summary'] = is_text($article['summary'], 20, 900) ? '' : 'SUMMARY MOST BE BETWEEN 20 AND 900 CHARECTERS';
    $errors['content'] = is_text($article['content'], 100, 100000) ? '' : 'CONTENT MOST BE BETWEEN 100 AND 100000 CHARECTERS';

    //VERIFY THAT THE CATEGORY ID EXIST IN THE DATABASE
    $verify_category = is_category_exist($article['category_id'], $categories);

    $errors['category_id'] = $verify_category ? '' : 'PLEASE SELECT CATEGORY ';

    $invalid = implode($errors);

    if ($invalid) {
        $errors['warning'] = 'PLEASE CORRECT MISSING DATA';
    } else {
        try {
            //SET DATE AND ADD NEW POST TO DATABASE
            $pdo = $cms->getDB();
            $pdo->beginTransaction();
            //UPLOAD IMAGE TO THE UPLAODS FOLDER
            if ($destination) {
                //INSERT THE IMAGE PATH IN THE IMAGES TABLE
                $save_image = move_uploaded_file($_FILES['image']['tmp_name'], $destination);

                //GET THE SAVED IMAGE ID
                $image_id = $cms->getImage()->storeImage([$article['image_path'], $article['image_alt']]);
                if ($image_id) {
                    unset($article['image_path'], $article['image_alt']);
                    $article['image_id'] = $image_id;
                }
            }
            //CHECK IF WE HAVE ARTICLE ID, IF TRUE MEANS THERE IS UPDATE ARTICLE
            if ($id) {

                unset($article['image_path'], $article['image_alt']);
                $sql = "UPDATE articles SET 
                title = :title,
                summary = :summary,
                content = :content,
                user_id =:user_id,
                category_id = :category_id,
                image_id = :image_id,
                published = :published
                WHERE id = :article_id";
            } else {
                //ELSE IT WILL BE NEW ARTICLE BEEN CREATED

                unset($article['article_id'], $article['image_path'], $article['image_alt']);
                $sql = "INSERT INTO articles (
                    title,
                    summary,
                    content,
                    user_id,
                    category_id,
                    image_id,
                    published
                    ) VALUES(
                        :title,
                        :summary,
                        :content,
                        :user_id,
                        :category_id,
                        :image_id,
                        :published
                        );";
            }
            $smt = $cms->getDB()->prepare($sql);
            $result = $smt->execute($article);
            $cms->getDB()->commit();
            $session->setMessage('ARTICLE SUCCESSFULLY CREATED');
            redirect(DOC_ROOT . '\pages\member\profile.php');
        } catch (PDOException $e) {

            //ROLL PDO SAVING IMAGE AND ARTICLE
            $cms->getDB()->rollBack();
            //REMOVE UPLOADED IMAGE PATH
            if (file_exists($destination)) {
                unlink($destination);
            }
            //CHECK THE TYPE OF ERROR
            if ($e->errorInfo[1] === 1064) {
                $errors['warning'] = "ERROR PROCESSING DATA";
            } else {
                return $e->getMessage();
            }
        }
    }
    //SET ARTICLE['IMAGE_PATH'] TO THE NEW IMAGE


    //WHEN ALL DATA IS SAVED CORRECTLY WE NEED TO REDIRECT TO MEMBER DASHBOARD WITH SUCCESS MESSAGE
}

$data = [
    'article' => $article,
    'section' => $section,
    'errors' => $errors
];

$data['categories'] = $categories;

echo $twig->render('pages/member/create-article.html', $data);
