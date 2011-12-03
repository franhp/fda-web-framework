<?php
$blog = new Blog();
$user = new Login();
$settings = new Settings();
if ($user->isLogged() && $user->getUserRole() > 1) {

form_decorator();

    echo '<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Posts</a></li>
        <li><a href="#tabs-2">Add</a></li>
        <li><a href="#tabs-3">Categories</a></li>
    </ul>
    ';
    
    echo '<div id="tabs-3">';
    
    echo '<ul>';
    foreach($blog->getCategories() as $cat){
        echo '<li id="'.$cat.'">'.$cat.'<img src="'.$settings->siteurl.'/img/admin/delete.gif" onclick="
         $.ajax({
                    type: \'POST\',
                    url: \'' . $settings->siteurl . '/controllers/admin_blog_controller.php\' ,
                    data:  { categoryDelete : \''.$cat.'\' },
                    success: function(data) {
                            if(data==\'OK\'){
                                $(\'#'.$cat.'\').hide();
                            }
                            else{
                                alert(data);
                            }
                    }
            });
        "></li>';
    }
    echo '</ul>';
    
    
    
    echo '</div>';


    echo '<div id="tabs-2">
    <div id="spinner" style="display: none;"> <img src="'.$settings->siteurl.'/img/loading-big.gif"> </div>
    <div id="form">
    <br>Title: <input type="text" id="title">
    <br>Body: <textarea id="body"></textarea>
    <br>Author: <select id="author">';
    foreach ($user->listUsers(false) as $author){
        if($_SESSION['userid'] == $author->id) {
            echo '<option value="'.$author->id.'" selected>'.$author->username.'</option>';
        }
        else echo '<option value="'.$author->id.'">'.$author->username.'</option>';
    }
    
    echo '</select>
    <br>Categories: <input type="text" id="categories">
    <br><input type="button" id="send" value="send">
    
    Possible categories (click to add):';
    $categories = $blog->getCategories();
    foreach($categories as $category){
        echo '<a onclick="$(\'#categories\').val($(\'#categories\').val() + $(this).html() + \',\')">'.$category.'</a> ';
    }
    
    

    echo '</div></div>';
    
    
    
    echo '<div id="tabs-1">';
    $posts = $blog->getAllPosts();
    echo '<table><tr style="background: blue;"><td><b>Title</b></td><td><b>Body</b></td><td><b>Author</b></td><td></td><td></td></tr>';
    $i=0;
    foreach($posts as $post){
        if($i%2==0){    
            echo '<tr  style="background: lightblue;" id="post'.$post->post->id.'">';
        }
        else {
            echo '<tr id="post'.$post->post->id.'">';
        }
        echo '<td><b>'.$post->post->title.'</b></td>';
        if(strlen($post->post->body)>50) echo '<td><i>'.substr($post->post->body,0,50).'...</i></td>';
        else echo '<td><i>'.$post->post->body.'</i></td>';
        echo '<td><i>'.$post->post->username.'</i></td>';
        echo '<td><img src="'.$settings->siteurl.'/img/admin/edit.gif" onclick="$(\'#post'.$post->post->id.'hidden\').slideDown()"></td>';
        echo '<td><img src="'.$settings->siteurl.'/img/admin/delete.gif" onclick="
        var answer = confirm(\'Are you sure?\');
        if(answer){
            $.ajax({
                    type: \'POST\',
                    url: \'' . $settings->siteurl . '/controllers/admin_blog_controller.php\' ,
                    data:  { del : \'true\' ,
                            id : \''.$post->post->id.'\'},
                    success: function(data) {
                            if(data==\'OK\'){
                                $(\'#post'.$post->post->id.'\').hide();
                            }
                            else{
                                alert(data);
                            }
                    }
            })}"></td>';
        echo '</tr>';
        
        //HIDDEN FOR EDITING
        echo '<tr  style="background: yellow;display: none;" id="post'.$post->post->id.'hidden">';
        echo '<td colspan="2"><b>Title: <input type="text" id="title'.$post->post->id.'" value="'.$post->post->title.'"></b>';
        echo '<textarea id="body'.$post->post->id.'" class="editor">'.$post->post->body.'</textarea>';
        foreach($post->post->categories as $category){
            $postCategories[] = $category->category_name;
        }
        
        if(count($postCategories)>0) echo 'Categories: <input type="text" id="categoriesEdit'.$post->post->id.'" value="'.implode(',',$postCategories).'">';
        else echo 'Categories: <input type="text" id="categoriesEdit'.$post->post->id.'">';
        echo 'Author:<select id="author'.$post->post->id.'">';
        foreach ($user->listUsers(false) as $author){
            if($post->post->username == $author->username) {
                echo '<option value="'.$author->id.'" selected>'.$author->username.'</option>';
            }
            else echo '<option value="'.$author->id.'">'.$author->username.'</option>';
        }
    
        echo '</select>
        <br>Possible categories (click to add):';
        $categories = $blog->getCategories();
        foreach($categories as $category){
            echo '<a onclick="$(\'#categoriesEdit'.$post->post->id.'\').val($(\'#categoriesEdit'.$post->post->id.'\').val() + $(this).html() + \',\')">'.$category.'</a> ';
        }
        echo '</td>';
        echo '<td><img src="'.$settings->siteurl.'/img/admin/ok.png" onclick="
        $.ajax({
                    type: \'POST\',
                    url: \'' . $settings->siteurl . '/controllers/admin_blog_controller.php\' ,
                    data:  { update : \'true\' ,
                            categories : $(\'#categoriesEdit'.$post->post->id.'\').val(),
                            title : $(\'#title'.$post->post->id.'\').val(),
                            body : $(\'#body'.$post->post->id.'\').val(),
                            author : $(\'#author'.$post->post->id.'\').val(),
                            id : \''.$post->post->id.'\'},
                    success: function(data) {
                            if(data==\'OK\'){
                                window.location.reload();
                            }
                            else{
                                alert(data);
                            }
                    }
            })
        ">
                </td><td><img src="'.$settings->siteurl.'/img/admin/error.png" onclick="$(\'#post'.$post->post->id.'hidden\').hide()"></td>';
        echo '<td><img src="'.$settings->siteurl.'/img/admin/delete.gif" onclick="
        var answer = confirm(\'Are you sure?\');
        if(answer){
            $.ajax({
                    type: \'POST\',
                    url: \'' . $settings->siteurl . '/controllers/admin_blog_controller.php\' ,
                    data:  { del : \'true\' ,
                            id : \''.$post->post->id.'\'},
                    success: function(data) {
                            if(data==\'OK\'){
                                $(\'#post'.$post->post->id.'\').hide();
                            }
                            else{
                                alert(data);
                            }
                    }
            })}"></td>';
        echo '</tr>';
        $i++;
        $postCategories = null;
    }
    echo '</table></div></div>';
    
} else {
    echo ERROR;
}


function form_decorator() {    
    $settings = new Settings();
    echo "
	<script lang=\"javascript\">
        $(document).ready(function() {
                $('#body').ckeditor();
                $('.editor').ckeditor({ toolbar: 'Basic'})
        	$(function() {
                    $( \"#tabs\" ).tabs();
                });
		$('#send').click(function() {
                                $('#form').hide();
                                $('#spinner').show()
				$.ajax({
					type: 'POST',
					url: '" . $settings->siteurl . "/controllers/admin_blog_controller.php' ,
					data:  {title : $('#title').val() ,
                                                body : $('#body').val() ,
                                                author : $('#author').val(),
                                                categories : $('#categories').val(),
                                                add : \"true\"
                                                },
					success: function(data) {
						if(data=='OK'){
                                                    $('#spinner').html('<h1>Added!</h1><a href=\"".$settings->siteurl."/".$settings->urlParameters(1)."/admin/blog\">Write another?</a>');
						}
						else{
                                                    $('#spinner').hide();
                                                    $('#form').show();
                                                    alert(data);
						}
					}
				})
			return false;
		});
        });
	</script>";
}
?>