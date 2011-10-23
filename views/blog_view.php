<?php
$blog = new Blog();
$settings = new Settings();

$options = $settings->urlParameters(4);
if(!empty($options)){
	/* Tags & Categories */
	if($options == "tags") $posts = $blog->getPostsByTag($settings->urlParameters(5));
	else if($options == "categories") $posts = $blog->getPostsByCategory($settings->urlParameters(5));
	/* If "all" is set, show all posts */
	else if($options == "all") $posts = $blog->getAllPosts();
	/* If a range is set in the url, display that range of posts */
	else if ($options == "from") {
		$posts = $blog->getPosts($settings->urlParameters(5), $settings->urlParameters(7));
		nextPreviousButtons();
	}
	/* If the SEO url of a post is specified, show the post */
	else if($options == "post") $posts[] = $blog->getPostBySeoUrl($settings->urlParameters(5));
}
/* By default show first 5 posts */
else {
	$posts = $blog->getPosts(0,5);
	nextPreviousButtons();
}

/* Show all posts in the object */
echo '<div id="posts"><h2>Developers Blog</h2>';
if($posts){
	foreach($posts as $post){
			/* The post */
			echo '<div class="post" id="postID'.$post->post->id.'">';
			
			echo '<h3><a href="'.$settings->siteurl.'/'.$settings->urlParameters(2).'/blog/post/
					'.$post->post->seourl.'">
					'.$post->post->title.'</a></h3>';
			echo '<small>'.WRITTENBY.' '.$post->post->username.' '.ON.' '.$post->post->date.'</small>';
			echo '<p>'.$post->post->body.'</p>';
		
			
			/* Categories and tags */
			/*$tagsCount = count((array)$post->post->tags);
			if($tagsCount>0){
				echo '<p>Tags: ';
				foreach($post->post->tags as $tags){
					echo '<a href="'.$settings->siteurl.'/'.$settings->urlParameters(2).'/blog/tags/'.$tags->tag_name.'">'.$tags->tag_name.'</a> ';
				}
				echo '</p>';
			}
			else echo '<p>No tags</p>';*/
			$categoriesCount = count((array)$post->post->categories);
			if($categoriesCount>0){
				echo '<p>Categories: ';
				foreach($post->post->categories as $categories){
					echo '<a href="'.$settings->siteurl.'/'.$settings->urlParameters(2).'/blog/categories/'.$categories->category_name.'">'.$categories->category_name.'</a> ';
				}
				echo '<p>';
			}
			else echo '<p>No categories</p>';
			
			
			/* Comments */
			$commentCount = count((array)$post->post->comments);
			echo '<a onclick="$(\'#comments'.$post->post->id.'\').slideDown()">
					'.$commentCount.' comments on this post</a>';
			
			echo '<div id="comments'.$post->post->id.'" class="comments" style="display: none;">';
			
			/* New comment button */
			echo '<input type="button" value="'.NEWCOMMENT.'" id="newButton'.$post->post->id.'"
						onclick="createEditor('.$post->post->id.')">';
			echo '<input type="button" value="'.CANCEL.'"
						onclick="$(\'#comments'.$post->post->id.'\').slideUp()">';
			
			/* All the comments */
			if($commentCount>0){
				foreach($post->post->comments as $comment){
					echo '<div class="comment"><hr>';
					echo COMMENTEDBY.' '.$comment->username.' '.ON.' '.$comment->date;
					echo '<p>'.$comment->comment.'</p>';
					echo '</div>';
				}
			}
			else echo '<div class="comment" id="NoComment'.$post->post->id.'"><hr>'.NOCOMMENTS.'</div>';
			
			/* Loading gif */
			echo '<img id="loadingGif'.$post->post->id.'" src="'.$settings->siteurl.'/img/loading.gif" style="display: none;">';
			
			/* Add Comment form */
			echo '
			<div id="addComment'.$post->post->id.'" style="display: none;">
				<textarea id="textarea'.$post->post->id.'"></textarea>
			 	<input id="add'.$post->post->id.'" type="button" value="'.ADDCOMMENT.'" onClick="postComment('.$post->post->id.')">
			 	<input id="cancel'.$post->post->id.'" type="button" value="'.CANCEL.'" onClick="removeEditor('.$post->post->id.')">
			</div>';
			
			/* Another New comment button */
			echo '<input type="button" value="'.NEWCOMMENT.'" id="newButton'.$post->post->id.'two"
						onclick="createEditor('.$post->post->id.')">';
			
			
			
			echo '</div></div>';
			


		}
		script();
}
else echo NOPOSTS;

if($options == "from" || $options == "") nextPreviousButtons();
echo '</div>';


/**
 * This function displays the required javascript functions
 */
function script(){
	$settings = new Settings();
	echo "
	<script language=\"javascript\">
	//<![CDATA[
	function createEditor(id){
		$('#textarea'+id).ckeditor({ toolbar: 'Basic'});
		$('#addComment'+id).slideDown();
		$('#newButton'+id).hide();
		$('#newButton'+id+'two').hide();
		$.scrollTo('#addComment'+id, {offset : -75 });
	}
	
	function removeEditor(id){
		$('#newButton'+id).show();
		$('#newButton'+id+'two').show();
		$('#addComment'+id).slideUp();
	}
	
	function postComment(id){
		$('#loadingGif'+id).show()
		var html = $('#textarea'+id).val()
		$.ajax({
			type: 'POST',
			url: '".$settings->siteurl."/controllers/blog_controller.php' ,
			data: { id: id, text:  html },
			success: function(data) {
				if(data!=''){
					$('#NoComment'+id).fadeOut();
					$('<div class=\"comment\"><hr>'+data+'</div>').insertBefore($('#addComment'+id));
					$('#textarea'+id).val('');
					removeEditor(id);
					$('#loadingGif'+id).hide()
				}
				else{
					$('.comments').append('".ERROR."');
					$('#loadingGif'+id).hide()
				}
			}
		})
	
	}
	//]]>
	</script>";
}

/**
 * This function displays the suitable Next and Previous buttons
 */
function nextPreviousButtons(){	
	$blog = new Blog();
	$totalPosts = $blog->getTotalPosts();
	$settings = new Settings();
	$param1 = $settings->urlParameters(5);
	$param2 = $settings->urlParameters(7);
	$lang = $settings->urlParameters(2);
	
	if($totalPosts>5){	
		if(empty($param1)||($param1 > 0 && $param1 < 4)){
				echo '<a href="'.$settings->siteurl.'/'.$lang.'/blog/from/5/to/10">'.NEXT.'</a>';
		}
		else if(!empty($param2)&&$param1>1){
			/* PREV */ 
			echo '<a href="'.$settings->siteurl.'/'.$lang.'/blog/from/'.($param1-5).'/to/'.$param1.'">'.PREVIOUS.'</a>';
			
			/* NEXT */
			if ($param2<$totalPosts){
				echo '&nbsp;';
				echo '<a href="'.$settings->siteurl.'/'.$lang.'/blog/from/'.$param2.'/to/'.($param2+5).'">'.NEXT.'</a>';
			}
			
		}
	}
	
	
	
	
}

?>