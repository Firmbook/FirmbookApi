<?php
/**
 * Description of Firmbook_Command_Result
 *
 * @author Stas
 */
class Firmbook_Command_Result {	
	protected $resultArray;

	public function  __construct($rawJsonData) {
		$this->resultArray = json_decode($rawJsonData, true);		
	}

	public function getCode() {
		return $this->resultArray['Code'];
	}
	
	public function isOk() {
		return $this->getCode() == 200;
	}

	public function getMessage() {
		return $this->resultArray['Message'];
	}
	
	public function getData() {
		return $this->resultArray;
	}
	
	public function getId() {
		return $this->resultArray['Id'];
	}
}
?>