<?php
/**
 * Blog Class para FilaDeAtras Framework
 * 
 * Esta clase permite gestionar todo lo relacionado <br>
 * con los posts de un blog, mostrarlos, añadirlos, borrarlos, ...
 * @author Fran Hermoso
 * @version 1.0
 */
class Blog{
	/**
	 * Muestra todos los posts de la base de datos
	 * @return stdClass
	 */
	public function getAllPosts(){
		$totalPosts = $this->getTotalPosts();
		if($totalPosts<=0) return false;
		else {
			$postIds = $this->getPostIds();
			foreach($postIds as $postId){
				$posts[] = $this->getPostById($postId->id);
			}
			return $posts;
		}
	}
	
	/**
	 * Muestra el rango de posts entre $start y end
	 * @param  $start
	 * @param  $end
	 * @return stdClass
	 */
	public function getPosts($start,$end){
		$totalPosts = $this->getTotalPosts();
		if($start>$end || $start < 0) return false;
		else if($start>$totalPosts) return false;
		else {
			$postIds = $this->getPostIdsRange($start, $end);
			foreach($postIds as $postId){
				$posts[] = $this->getPostById($postId->id);
			}
			if(empty($posts)) return false;
			else return $posts;
		}
	}
	
	public function getPostsByTag($tag){
		$db = &$GLOBALS['db'];
		$db->query('select p.id from posts p, posts_tags, tags
					where 
					p.id=posts_tags.id_post
					and
					posts_tags.id_tag=tags.id
					and
					tags.tag_name=\''.$db->clean($tag).'\'');
		$result = $db->obj();
		foreach($result as $post){
			$posts[] = $this->getPostById($post->id);
		}
		if(empty($posts)) return false;
		else return $posts;
	}
	
	public function getPostsByCategory($category){
		$db = &$GLOBALS['db'];
		$db->query('select p.id from posts p, posts_categories, categories
					where 
					p.id=posts_categories.id_post
					and
					posts_categories.id_tag=categories.id
					and
					categories.tag_name=\''.$db->clean($category).'\'');
		$result = $db->obj();
		foreach($result as $post){
			$posts[] = $this->getPostById($post->id);
		}
		if(empty($posts)) return false;
		else return $posts;
	}
	
	/**
	 * Retorna un post con sus comentarios en forma de objeto
	 * @param $idPost
	 * @return stdClass
	 */
	public function getPostById($idPost){
		$db = &$GLOBALS['db'];
		$db->query('select p.id,p.title,p.body,p.date,p.seourl,u.username
					from posts p, users u
					where 
					u.id=p.userid
					and 
					p.id='.$idPost);
		$result = $db->obj();
		foreach($result as $item){
			$post = new stdClass();
			$post->post = $item;
			$post->post->comments = $this->getPostComments($idPost);
			$post->post->categories = $this->getPostCategories($idPost);
			$post->post->tags = $this->getPostTags($idPost);
		}
		return $post;
	}
	
	/**
	 * Retorna un post y sus comentarios en un objeto
	 * @param $seourl
	 * @return stdClass
	 */
	public function getPostBySeoUrl($seourl){
		$db = &$GLOBALS['db'];
		$db->query('select p.id,p.title,p.body,p.date,p.seourl,u.username
					from posts p, users u
					where 
					u.id=p.userid
					and
					p.seourl=\''.$db->clean($seourl).'\'');
		$result = $db->obj();
		foreach($result as $item){
			$post = new stdClass();
			$post->post = $item;
			$post->post->comments = $this->getPostComments($item->id);
			$post->post->categories = $this->getPostCategories($item->id);
			$post->post->tags = $this->getPostTags($item->id);
		}
		return $post;
	}
	

	
	
	/**
	 * Retorna todos los comentarios de un post en un objeto
	 * @param $idPost
	 * @return stdClass
	 */
	public function getPostComments($idPost){
		$db = &$GLOBALS['db'];
		$db->query('select u.username,c.comment,c.date,c.id
					from users u, comments c
					where
					u.id=c.userid
					and
					postid='.$idPost.'
					order by c.date');
		return $db->obj();

	}
	
	public function getPostCategories($idPost){
		$db = &$GLOBALS['db'];
		$db->query('select c.category_name from categories c, posts_categories
					where c.id=posts_categories.id_category
					and
					posts_categories.id_post='.$idPost);
		return $db->obj();
	}
	
	public function getPostTags($idPost){
		$db = &$GLOBALS['db'];
		$db->query('select t.tag_name from tags t, posts_tags
					where t.id=posts_tags.id_tag
					and
					posts_tags.id_post='.$idPost);
		return $db->obj();
	}
	

	public function getCategories(){
		
	}
	
	public function getTags(){
		
	}
	
	/**
	 * Retorna el numero de posts existentes en la base de datos
	 * @return int
	 */
	public function getTotalPosts(){
		$db = &$GLOBALS['db'];
		$db->query('select id from posts');
		return $db->num_rows();
	}
	
	/**
	 * Retorna las IDs de todos los posts ordenados por fecha
	 * @return stdClass
	 */
	public function getPostIds(){
		$db = &$GLOBALS['db'];
		$db->query('select id from posts order by date desc');
		return $db->obj();
	}
	
	/**
	 * Retorna las IDs de todos los posts ordenados por fecha y limitados por $start y $end
	 * @param  $start
	 * @param  $end
	 * @return stdClass
	 */
	public function getPostIdsRange($start,$end){
		$db = &$GLOBALS['db'];
		$db->query('select id from posts
					order by date desc 
					limit '.$start.','.$end);
		return $db->obj();
	}
	
	public function addComment($idPost, $text, $idUser = FALSE){
		$db = &$GLOBALS['db'];
		if($idUser) {
			/* User comment */
			$db->query('insert into comments (userid,postid,comment) 		
					values 
					('.$db->clean($idUser).','.$db->clean($idPost).',\''.$db->clean($text).'\')');
		}
		else {
			/* Anonymous comment */ 
			$db->query('insert into comments (userid,postid,comment) 		
					values 
					(17,'.$db->clean($idPost).',\''.$db->clean($text).'\')');
		}
		if($db->getResult()) return true;
		else return false;
	}
	
	public function addPost($title,$body,$categories,$tags,$author, $date = FALSE){
		
	}
	
	public function modPost($idPost,$field,$modifiedField){
		
	}
	
	public function delPost($idPost){
		
	}
	
	public function delComment($idComment){
		
	}
	
	public function searchPost($string){
		
	}
	
}
?>