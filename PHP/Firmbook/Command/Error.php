<?php
/**
 * Description of Firmbook_Command_Error
 *
 * @author Stas
 */
class Firmbook_Command_Error extends Firmbook_Command_Result {	
	protected $code;
	protected $message;

	public function __construct($code, $message) {
		$this->code = $code;
		$this->message = $message;
	}

	public function getCode() {
		return $this->code;
	}

	public function getMessage() {
		return $this->message;
	}
	
	public function getId() {
		return null;
	}
}
?>