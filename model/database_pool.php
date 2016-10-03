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
		//SET
				//no params
			'SET_UTF8' => 'SET NAMES utf8',
		//SELECT
			//no params
			'GET_ALL_TEXTS' =>        'SELECT * FROM texts',
			'GET_ALL_USERS' =>        'SELECT * FROM users',
			'GET_ALL_RATING_TYPES' => 'SELECT * FROM rating_types',
			'LIST_OF_PRIVILEGES' =>   'SELECT * FROM privileges',
			'GET_TEXTS_TO_REVIEW' =>  'SELECT * FROM reviewers',
			'GET_ALL_PRIVILEGES' =>   'SELECT ID,type FROM users',
			'GET_TEXTS_TO_RATE' =>    'SELECT users.ID AS user_id, texts.ID AS text_id, ratings.type AS type, ratings.mark AS mark FROM texts INNER JOIN ratings ON ratings.text_id = texts.ID INNER JOIN users ON ratings.user_id = users.ID',
			//one param
			'LIST_OF_MY_TEXTS' =>     'SELECT * FROM texts WHERE user_id=?',
			'GET_PRIVILEGE' =>        'SELECT type FROM users WHERE ID=?',
			'GET_ID' =>               'SELECT ID FROM users WHERE username=?',
			'GET_FULL_NAME' =>        'SELECT full_name FROM users WHERE ID=?',
			'GET_PDF' =>              'SELECT filename, LENGTH(filename) AS size FROM texts WHERE texts.ID=?',
			'GET_PUBLISHED_PDF' =>    'SELECT filename, LENGTH(filename) AS size FROM texts WHERE texts.ID=? AND published IS NOT NULL',
			'GET_NEWS' =>             'SELECT message FROM news WHERE user_id=?',
			'GET_TEXT' =>             'SELECT name FROM texts WHERE ID=?',
			//two params
			'GET_USER_ID' =>          'SELECT ID FROM users WHERE username=? AND password=?',
		//INSERT
			//one param
			'ADD_REVIEWER' =>     'INSERT INTO reviewers VALUES( ?, ?)',
			//two params
			'ADD_NEWS' =>         'INSERT INTO news VALUES(NULL, ?, ?)',
			//four params
			'RATE' =>             'INSERT INTO ratings VALUES( ?, ?, ?, ?)',
			//five params
			'ADD_REGULAR_USER' => 'INSERT INTO users VALUES(NULL, DEFAULT, ?, ?, ?, ?, ?)',
			'ADD_TEXT' =>         'INSERT INTO texts VALUES(NULL, ?, ?, ?, ?, ?,NULL)',
		//UPDATE
			//one param
			'PUBLISH' =>         'UPDATE texts SET published=NOW() WHERE ID=?',
			'NOT_PUBLISH' =>     'UPDATE texts SET published=NULL WHERE ID=?',
			//two params
			'SET_PRIVILEGE' =>   'UPDATE users SET type=? WHERE ID=?',
			//four params
			'UPDATE_TEXT' =>     'UPDATE texts SET name=?, authors=?, abstract=?, published=NULL WHERE ID=?',
			//five params
			'UPDATE_TEXT_PDF' => 'UPDATE texts SET name=?, authors=?, abstract=?, filename=?, published=NULL WHERE ID=?',
		//DELETE
			//one param
			'REMOVE_NEWS' =>            'DELETE FROM news WHERE user_id=?',
			'REMOVE_TEXT' =>            'DELETE FROM texts WHERE ID=?',
			//two params
			'REMOVE_REVIEWER' =>        'DELETE FROM reviewers WHERE user_id=? AND text_id=?',
			'REMOVE_REVIEWERS_RATES' => 'DELETE FROM ratings WHERE user_id=? AND text_id=?',
			//three params
			'NOT_RATE' =>               'DELETE FROM ratings WHERE ratings.user_id=? AND ratings.text_id=? AND ratings.type=?'
		);

		public $db;

		private function __construct($dns,$username,$password){
			$this->db = new PDO($dns,$username,$password);
		}

		private function query_array($query,$data){
			try{
				$stmt = $this->db->prepare($query);
				if(!$stmt){
					echo $this->db->errorInfo()[2];
				}
				$stmt->execute($data);
				$err = $stmt->errorInfo();
				if(!empty($err)){
					echo $err[2];
				}
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
