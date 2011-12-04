<?php
/**
 * Blog Class para FilaDeAtras Framework
 * 
 * Esta clase permite gestionar todo lo relacionado <br>
 * con los posts de un blog, mostrarlos, a�adirlos, borrarlos, ...
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
	/*
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
	 */
	public function getPostsByCategory($category){
		$db = &$GLOBALS['db'];
		$db->query('select p.id from posts p, posts_categories, categories
					where 
					p.id=posts_categories.postid
					and
					posts_categories.categoryid=categories.id
					and
					categories.category_name=\''.$db->clean($category).'\'');
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
			//$post->post->tags = $this->getPostTags($idPost);
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
			//$post->post->tags = $this->getPostTags($item->id);
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
	
	/**
	 *Returns the categories from an specified post
	 *@return stdClass
	 */
	public function getPostCategories($idPost){
		$db = &$GLOBALS['db'];
		$db->query('select c.category_name from categories c, posts_categories
					where c.id=posts_categories.categoryid
					and
					posts_categories.postid='.$idPost);
		return $db->obj();
	}
	
	/* (Tags are out of this release)
	 public function getPostTags($idPost){
		$db = &$GLOBALS['db'];
		$db->query('select t.tag_name from tags t, posts_tags
					where t.id=posts_tags.id_tag
					and
					posts_tags.id_post='.$idPost);
		return $db->obj();
	}
	*/

	/**
	 * Returns an array of all the categories
	 * @return array
	 */
	public function getCategories(){
		$db = &$GLOBALS['db'];
		$db->query('select category_name from categories');
		$result = $db->obj();
		foreach ($result as $category){
			$categories[] = $category->category_name;
		}
		return $categories;
	}
	
	/**
	 *Deletes a category from the database
	 *@return true
	 */
	public function deleteCategory($categoryName){
		$db = &$GLOBALS['db'];
		$db->query('delete from categories where category_name=\''.$categoryName.'\'');
		return true;
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
	
	public function addPost($title,$body,$categories,$author){
		$db = &$GLOBALS['db'];
		
		//Check if the post exists
		$db->query('select id from posts where
			   title=\''.$db->clean($title).'\'
			   and body=\''.$db->clean($body).'\'
			   and userid = \''.$db->clean($author).'\'');
		
		if($db->num_rows() == 0){	

			$db->query('insert into posts (title,body,userid,seourl) 
			values (\''.$title.'\',\''.$body.'\','.$author.',
			\''.strtolower(str_replace(' ','-',$db->clean($title))).'\')');
			
			
			//Get the ID
			$db->query('select id from posts where
			   title=\''.$db->clean($title).'\'
			   and body=\''.$db->clean($body).'\'
			   and userid = \''.$db->clean($author).'\'');
			$result = $db->obj();
			foreach ( $result as $post){
				$postID = $post->id;
			}
			
			if(!empty($categories)){
				$categoriesExploded = explode(",",$categories);
				//Write the categories
				foreach ($categoriesExploded as $category){
						if(!$this->categoryExists(trim($category))){
							$this->addCategory(trim($category));
						}
						$this->addPostToCategory($postID, trim($category));
				}
			}
			return true;
		}
		else return false;
		
		
	}
	
	private function addCategory($categoryName){
		$db = &$GLOBALS['db'];
		if(!empty($categoryName))
			$db->query('insert into categories (category_name) values 
						(\''.$db->clean($categoryName).'\')');

	}
	
	private function addPostToCategory($postID, $categoryName){
		$db = &$GLOBALS['db'];
		$db->query('select postid from posts_categories where postid='.$postID.' and categoryid='.$categoryName);
		if($db->num_rows() == 0){
			$db->query('insert into posts_categories (postid,categoryid)
			   values ('.$postID.',
			   (select id from categories where category_name = \''.$categoryName.'\'))');
		}
		
	}
	
	private function clearCategoriesFromPost($idPost){
		$db = &$GLOBALS['db'];
		$db->query('delete from posts_categories where postid='.$idPost);
	}
	
	private function categoryExists($categoryName){
		$db = &$GLOBALS['db'];
		$db->query('select id from categories where category_name=\''.$categoryName.'\'');
		if($db->num_rows() == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	/**
	 *Modifies a post
	 *@return true
	 */
	public function modPost($idPost,$title,$body,$categories,$author){
		$db = &$GLOBALS['db'];
		$db->query('update posts set title=\''.$db->clean($title).'\',body=\''.$db->clean($body).'\',
			   userid=\''.$db->clean($author).'\' where id=\''.$db->clean($idPost).'\'');
		
		if(!empty($categories)){
			$this->clearCategoriesFromPost($idPost);
			$categoriesExploded = explode(",",$categories);
			//Write the categories
			foreach ($categoriesExploded as $category){
				if(!$this->categoryExists(trim($category))){
					$this->addCategory(trim($category));
				}
				$this->addPostToCategory($idPost, trim($category));
			}
		}
		else {
			$db->query('delete from posts_categories where postid='.$idPost);
		}
		return true;
	}
	
	/**
	 *Deletes a post
	 *@return true
	 */
	public function deletePost($idPost){
		$db = &$GLOBALS['db'];
		$db->query('delete from posts where id = '.$db->clean($idPost));
		return true;
	}
	
	/**
	 *Deletes a comment
	 *@return true
	 */
	public function delComment($idComment){
		$db = &$GLOBALS['db'];
		$db->query('delete from comments where id = '.$db->clean($idComment));
		return true;
	}
	
	/**
	 *Returns a list of the comments
	 *@return stdClass
	 */
	public function getComments(){
		$db = &$GLOBALS['db'];
		$db->query('select users.username,comments.id,comments.comment,comments.date from comments, users
			   where comments.userid=users.id order by comments.date');
		return $db->obj();
	}
	
	public function searchPost($string){
		
	}
	
}
?>