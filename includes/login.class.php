<?php
class Login{
	
	public function login($username,$password){
		
	}
	
	public function logged($username){
		if(isset($_SESSION['userid'])){
			return true;
		}
		return false;
	}
	
	public function logout(){
		
	}
}
?>