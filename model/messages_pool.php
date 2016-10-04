<?php
	/**
	 * Class to separate texts
	 * It brings posibility to translate or simply change the messages
	 *
	 * @author Martin Cervenka A14B0239P
	 * @version 10/03/2016
	 */
	class MessagesPool{
		
		/** Singleton - reference to own instance */
		private static $inst = null;
		
		/**
		 * Returns the instance of this singleton
		 * Lazy initialization
		 *
		 * @return MessagesPool Singleton instance
		 */
		public static function instance(){
			if(MessagesPool::$inst === null){
				MessagesPool::$inst = new MessagesPool();
			}
			return MessagesPool::$inst;
		}
		
		/** Texts for string substitutions */
		private $MESSAGES = array(
			'TEXT_DELETED' => 'Byl smaz&aacute;n text ?',
			'TEXT_EDITED' => 'Upraven p&rcaron;&iacute;sp&ecaron;vek ? od ?',
			'TEXT_EDITED_FILE' => 'Upraven p&rcaron;&iacute;sp&ecaron;vek ? od ? i se souborem PDF',
			'TEXT_CREATED' => 'P&rcaron;id&aacute;n nov&yacute; p&rcaron;&iacute;sp&ecaron;vek ? od ?',
			'NO_PRIVILEGES' => 'Nem&aacute;te dostate&ccaron;n&aacute opr&aacute;vn&ecaron;n&iacute;',
			'PRIVILEGES_CHANGED' => 'Byla v&aacute;m upravena opr&aacute;vn&eacute;n&iacute;',
			'REVIEWER_SET' => 'Byl jste ur&ccaron;en jako recenzent p&rcaron;&iacute;sp&ecaron;vku ?',
			'REVIEWER_UNSET' => 'Byl jste odstran&ecaron;n jako recenzent p&rcaron;&iacute;sp&ecaron;vku ?',
			'TEXT_STATUS' => 'P&rcaron;&iacute;sp&ecaron;vek ? byl ? u&zcaron;ivatelem ?',
			'MARK_DELETED' => 'U&zcaron;ivatel ? zru&scaron;il hodnocen&iacute; u p&rcaron;&iacute;sp&ecaron;vku ?',
			'MARK_ADDED' => 'U&zcaron;ivatel ? ohodnotil p&rcaron;&iacute;sp&ecaron;vek ?',
			'USER_NEW' => 'Nov&yacute; &ccaron;len ?',
			'NOT_PUBLISHED' => 'skryt',
			'PUBLISHED' => 'publikov&aacute;n',
			'USER_EXISTS' => 'U&zcaron;ivatel ji&zcaron; existuje',
			'USER_BLOCKED' => 'Tento u&zcaron;ivatel byl zablokov&aacute;n',
			'USER_BAD' => '&Scaron;patn&eacute; jm&eacute;no nebo heslo',
			'USER_LOGOUT' => 'Byl jste odhl&aacute;&scaron;en'
		);

		/**
		 * Creates the message from parameters
		 *
		 * @param string $message Message with question marks
		 * @param array $data Data for substitution
		 * @return string Substituted message
		 */
		private function message_array($message,$parameters){
			$output = $message;
			for($param=0; $param<count($parameters); $param++){
				$output = preg_replace('/\?/',$parameters[$param],$output,1);
			}
			return $output;
		}	

		/**
		 * Variable parameters function, not listed parameters are data for substitution
		 *
		 * @param string $message Short string which will be substituted internally
		 * @param ... Data for substitution
		 * @return string Substituted message
		 */
		public function message($message){
			$numargs = func_num_args();
			$arr = array();
			for($a = 1; $a < $numargs; $a++){
				$arr[$a-1] = func_get_arg($a);
			}
			return $this->message_array($this->MESSAGES[$message],$arr);
		}
	}
?>
