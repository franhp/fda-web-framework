<?php 
include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$user = new Login();
$blog = new Blog();

if(!empty($_POST['add'])){
    if(empty($_POST['title'])||empty($_POST['body'])||empty($_POST['author'])){
        echo 'Missing Data';
    }
    else if($blog->addPost($_POST['title'],$_POST['body'],$_POST['categories'],$_POST['author'])){
        echo 'OK';
    }
    else echo 'Post already exists';
}

else if(!empty($_POST['del'])){
    if($blog->deletePost($_POST['id'])) echo 'OK';
    else echo 'There was a problem';
}

else if(!empty($_POST['update'])){
    if($blog->modPost($_POST['id'],$_POST['title'],$_POST['body'],$_POST['categories'],$_POST['author'])) echo 'OK';
    else echo 'There was a problem';
}

else if(!empty($_POST['categoryDelete'])){
    if($blog->deleteCategory($_POST['categoryDelete'])) echo 'OK';
    else echo 'There was a problem';
}

else if(!empty($_POST['commentDelete'])){
    if($blog->delComment($_POST['commentDelete'])) echo 'OK';
    else echo 'There was a problem';
}

?>