<?php

class Blog{
	
	
	public function getPosts(){
		$db = &$GLOBALS['db'];
		
		$db->query('select title,body,date from posts');
		$posts = $db->obj();
		foreach($posts as $post){
			echo '<h1>'.$post->title.'</h1><br><small>'.$post->date.'</small><br>'.$post->body.'<br>';
		}
	}
	
	public function getPost($idPost){
		
	}
	
	public function getComments($idPost){
		
	}
	
	public function addComment($idPost){
		
	}
	
	public function addPost($title,$content,$categories,$tags,$date,$author){
		
	}
	
}
?>