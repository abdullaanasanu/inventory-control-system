<?php

class Login {
	public static function isLoggedIn(){
		
		if(isset($_COOKIE['ICS'])){
			
			if(DB::query('SELECT user_id FROM user_login WHERE token = :token', array(':token'=>sha1($_COOKIE['ICS'])))) {
				
				$user_id = DB::query('SELECT user_id FROM user_login WHERE token = :token', array(':token'=>sha1($_COOKIE['ICS'])))[0]['user_id'];
				
				return $user_id;
				
			}
			
		}
		return false;
		
	}
	
	public static function isAdmin() {
		
		$user_id = DB::query('SELECT user_id FROM user_login WHERE token = :token', array(':token'=>sha1($_COOKIE['ICS'])))[0]['user_id'];
		
			$admin = DB::query('SELECT admin FROM users WHERE id = :id', array(':id'=>$user_id))[0]['admin'];
		
		return $admin;
		
	}
	
}

?>