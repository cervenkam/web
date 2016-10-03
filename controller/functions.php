<?php
	/**
	 * Escapes HTML entities in both POST and GET values
	 */
	function clean(){
		foreach(array_keys($_POST) as $key){
			$_POST[$key] = htmlspecialchars($_POST[$key]);
		}
		foreach(array_keys($_GET) as $key){
			$_GET[$key] = htmlspecialchars($_GET[$key]);
		}
	}
	clean(); // CLEAN $_GET and $_POST

	/**
	 * Determines if current user can do specific action
	 *
	 * @param int $type Specific action
	 * @return bool Can current user do $type action
	 */
	function can_i_do_it($type){
		if($type < 1){
			return true;
		}
		if(!logged_in()){
			return false;
		}
		return can_he_do_it($type,$_SESSION['user_id'][0]);
	}
	/**
	 * Determines if some user can do specific action(s)
	 *
	 * @param int|array $types Specific action(s)
	 * @param int $user User's ID
	 * @return bool Can $user do $types action(s)
	 */
	function can_he_do_it($types,$user){
		if(!is_array($types)){
			$types = array($types);
		}
		foreach($types as $type){
			if($type < 1){
				return true;
			}
			$pool = DatabasePool::instance();
			$list = $pool->query('GET_PRIVILEGE',$user);
			DatabasePool::kill();
			if($list[0][0]{$type-1} === "1"){
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns true if somebody is logged in
	 *
	 * @return bool Is somebody logged in?
	 */
	function logged_in(){
		return isset($_SESSION['user_name']);
	}
	
	/**
	 * Should be returned only part of webpage
	 *
	 * @return bool Only part of webpage?
	 */
	function part_only(){
		return isset($_POST['part_only']) && $_POST['part_only'] == 'yes';
	}

	/**
	 * Returns array of all privileges
	 *
	 * @return array Array of all privileges
	 */
	function get_all_privileges(){
		if(logged_in()){
			$pool = DatabasePool::instance();
			$list = $pool->query('LIST_OF_PRIVILEGES');
			$all = $pool->query('GET_ALL_PRIVILEGES');
			DatabasePool::kill();
		}else{
			return array();
		}
		$arr = array();
		for($usr=0; $usr<count($all); $usr++){
			for($a=0; $a<count($list); $a++){
				$arr[$all[$usr]['ID']][$list[$a]['ID']] = array(
					'name' => $list[$a]['type'],
					'value' => (int)$all[$usr]['type']{$list[$a]['ID']-1}
				);
			}
		}
		//my_var_dump($arr);
		return $arr;
	}

	/**
	 * Returns full name of currently logged user
	 *
	 * @return string Full name of currently logged user
	 */
	function get_full_name(){
		return get_full_name_id($_SESSION['user_id'][0]);
	}

	/**
	 * Returns full name of specific user
	 *
	 * @param int $id Id of the specific user
	 * @return string Full name of user $id
	 */
	function get_full_name_id($id){
		$pool = DatabasePool::instance();
		$text = $pool->query('GET_FULL_NAME',$id);
		DatabasePool::kill();
		return $text[0]['full_name'];
	}

	/**
	 * Returns name of specific text
	 *
	 * @param int $id Id of the text
	 * @return string Name of text with id $id
	 */
	function get_text_name($id){
		$pool = DatabasePool::instance();
		$text = $pool->query('GET_TEXT',$id);
		DatabasePool::kill();
		return $text[0]['name'];
	}

	/**
	 * Returns array of all users
	 *
	 * @return array Array of all users
	 */
	function get_all_users(){
		if(!logged_in()){
			return array();
		}
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_USERS');
		DatabasePool::kill();
		$arr = array();
		for($line=0; $line<count($list); $line++){
			$arr[$list[$line]['ID']] = $list[$line];	
		}
		//my_var_dump($arr);
		return $arr;
	}

	/**
	 * Returns array of all rating types
	 * Rating types means e.g. Language quality, style quality ...
	 *
	 * @return array Array of all rating types
	 */
	function get_all_rating_types(){
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_RATING_TYPES');
		DatabasePool::kill();
		return $list;
	}

	/**
	 * Returns array of all texts
	 *
	 * @return array Array of all texts
	 */
	function get_all_texts(){
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_TEXTS');
		for($line=0; $line<count($list); $line++){
			$text = $list[$line]['abstract'];
			$text = html_entity_decode($text); //dangerous - but will be corected with following lines of code
			$text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is','<b>scripts not allowed</b><br>',$text);
			$text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is','<b>iframes not allowed</b><br>',$text);
			$text = preg_replace('/(on.*?)=".*?"/','',$text);
			$list[$line]['abstract'] = $text;
		}
		DatabasePool::kill();
		return $list;
	}

	/**
	 * Returns array of all texts which should be rated
	 *
	 * @return array Array of all texts which should be rated
	 */
	function get_texts_to_rate(){
		if(!logged_in()){
			return array();
		}
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_TEXTS_TO_RATE');
		DatabasePool::kill();
		$arr = array();
		for($line=0; $line<count($list); $line++){
			$arr[$list[$line]['user_id']][] = $list[$line];
		}
		//my_var_dump($arr);
		return $arr;

	}
	
	/**
	 * Returns array of all reviews of currently logged user
	 *
	 * @return array Array of all reviews of currently logged user
	 */
	function get_my_reviews(){
		if(!logged_in()){
			return array();
		}
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_TEXTS_TO_REVIEW');
		DatabasePool::kill();
		$arr = array();
		for($line=0; $line<count($list); $line++){
			$arr[$list[$line]['text_id']][] = $list[$line];
			//unset($list[$line]['text_id']);
		}
		return $arr;
	}
	
	//DEBUG_ONLY
	/**
	 * Wraps var_dump to &lt;pre&gt; HTML tag
	 * Output then looks like tree (no more inline prints)
	 *
	 * @param whatever $x Variable to dump
	 */
	function my_var_dump($x){
		echo '<pre>';
		var_dump($x);
		echo '</pre>';
	}

	/**
	 * Sends message to specific user, but not to himself
	 *
	 * @param int $user_id Specific user
	 * @param string $message The message
	 */
	function unicast($user_id,$message){
		unicast_id($user_id,$message,$_SESSION['user_id'][0]);
	}

	/**
	 * Sends message to specific user, but not if the users id are same
	 *
	 * @param int $user_id Specific user
	 * @param string $message The message
	 * @param int $my_id User id to compare
	 */
	function unicast_id($user_id,$message,$my_id){
		if($user_id != $my_id){
			$pool = DatabasePool::instance();
			$pool->query('ADD_NEWS',$user_id,$message);
			DatabasePool::kill();
		}
	}

	/**
	 * Sends message to everyone except the logged user
	 *
	 * @param string $message Message to send
	 */
	function broadcast($message){
		broadcast_id($message,$_SESSION['user_id'][0]);
	}

	/**
	 * Sends message to everyone except the specific user
	 *
	 * @param string $message Message to send
	 * @param int $my_id Id of the specific user
	 */
	function broadcast_id($message,$my_id){	
		//sending messages
		$users = get_all_users();
		$pool = DatabasePool::instance();
		foreach($users as $key => $value){
			if($key != $my_id){
				$pool->query('ADD_NEWS',$key,$message);
			}
		}
		DatabasePool::kill();
	}
?>
