<?php
	class DatabasePool{
		private static $inst = null;

		public static function init($dns,$username,$password){
			DatabasePool::$inst = new DatabasePool($dns,$username,$password);
			DatabasePool::$inst->query('SET_UTF8');
		}
		public static function instance(){
			if(DatabasePool::$inst === null){
				DatabasePool::init('mysql:host=localhost;dbname=web','web','password');
			}
			return DatabasePool::$inst;
		}
		public static function kill(){
			DatabasePool::$inst = null;
		}

		private $QUERIES = array(
			'SET_UTF8' => 'SET NAMES utf8',
			'ADD_REGULAR_USER' => 'INSERT INTO users VALUES(NULL, NULL, ?, ?, ?, ?, ?)',
			'LIST_OF_MY_TEXTS' => 'SELECT * FROM texts WHERE user_id=?',
			'GET_ID' => 'SELECT ID FROM users WHERE username=?',
			'GET_USER_ID' => 'SELECT ID FROM users WHERE username=? AND password=?',
			'GET_ALL_PRIVILEGES' => 'SELECT username,type FROM users',
			'LIST_OF_PRIVILEGES' => 'SELECT * FROM privileges',
			'SET_PRIVILEGE' => 'UPDATE users SET type=? WHERE username=?',
			'GET_PRIVILEGE' => 'SELECT type FROM users WHERE username=?'
		);

		public $db;

		private function __construct($dns,$username,$password){
			$this->db = new PDO($dns,$username,$password);
		}

		private function query_array($query,$data){
			try{
				$stmt = $this->db->prepare($query);
				$stmt->execute($data);
				return $stmt->fetchAll();
			}catch(PDOException $e){
				return false;
			}	
		}

		public function query($query){
			$numargs = func_num_args();
			$arr = array();
			for($a = 1; $a < $numargs; $a++){
				$arr[$a-1] = func_get_arg($a);
			}
			return $this->query_array($this->QUERIES[$query],$arr);
		}
	}
?>
