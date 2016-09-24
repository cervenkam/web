<?php
	class DatabasePool{
		private static $inst = null;

		public static function init($dns,$username,$password){
			DatabasePool::$inst = new DatabasePool($dns,$username,$password);
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
			'ADD_REGULAR_USER' => 'INSERT INTO users VALUES(NULL, 1, ?, ?, ?, ?, ?)',
			'LIST_OF_MY_TEXTS' => 'SELECT * FROM texts WHERE user_id=?',
			'GET_USER_ID' => 'SELECT ID FROM users WHERE username=? AND password=?'
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
