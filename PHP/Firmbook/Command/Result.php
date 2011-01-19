<?php
/**
 * Результат выполнения команды или запроса к Firmbook.ru
 *
 * @author Stas
 */
class Firmbook_Command_Result {	
	protected $code;
	protected $message;
	protected $resultArray = null;

	public function __construct($code, $message, $rawJsonData = null) {
		$this->code = $code;
		$this->message = $message;
		if ($rawJsonData != null)
			$this->resultArray = json_decode($rawJsonData, true);		
	}
	
	/**
	 * Проверить успешен ли результат выполенения команды
	 * @return bool Возвращает true в случае успеха
	 */
	public function isOk() {
		return $this->getCode() == 200;
	}

	/**
	 * Получит код результата выполнения (или ошибки)
	 * @return int Код результата выполнения (или ошибки)
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * Получить сообщение об ошибке
	 * @return string Сообщение об ошибке 
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * Получить сырые данные ответа
	 * @return array Дессериализованные данные ответа
	 */	
	public function getData() {
		return $this->resultArray;
	}
	
	/**
	 * Получить результат выполнения запроса
	 * @return mixed Результат выполнения запроса 
	 */
	public function getContent() {
		return $this->resultArray['Content'];
	}
		
	/**
	 * Получить возвращенный Id если он возвращен
	 * @return string Id если он возвращен
	 */
	public function getId() {
		return $this->resultArray['Id'];
	}
}
?>