<?php
/**
 * Description of Firmbook_Serializer_UrlEncode
 *
 * @author Stas
 */
class Firmbook_Serializer_UrlEncode extends Firmbook_Serializer_Abstract {
	protected $content;
	protected $data;
	
	public function  __construct(array $data) {
		$this->data = $data;
		$this->content = null;
	}
	
	protected function serialize() {
		$resultValue = array();
		foreach ($this->data as $key => $value) {
			if (is_array($value))
				$this->serializeArray($key, $value, $resultValue);
			elseif ($value instanceof DateTime)
				$resultValue[$key] = $value->format(DateTime::ISO8601);
			elseif (is_bool($value))
				$this->serializeBoolean($key, $value, $resultValue);
			else
				$resultValue[$key] = $value;
		}
		return $resultValue;
	}
	
	protected function serializeBoolean($key, $value, array &$resultValue) {
		if ($value)
			$resultValue[$key] = "true";
		else
			$resultValue[$key] = "false";
	}

	protected function serializeArray($key, $value, array &$resultValue) {
		$arrayLength = count($value);
		for ($i = 0; $i < $arrayLength; $i ++) {
			if ($value[$i] instanceof Firmbook_Ticket)
				$this->serializeTicket($key, $i, $resultValue, $value[$i]);
			else
				$resultValue[$key.'['.$i.']'] = $value[$i];	
		}
	}
	
	protected function serializeTicket($key, $index, array &$resultValue, Firmbook_Ticket $ticket) {
		foreach ($ticket->serializeToArray() as $dataKey => $dataValue)
			$resultValue[$key.'['.$index.'].'.$dataKey] = $dataValue;
	}
	
	public function getMimeType() {
		return 'application/x-www-form-urlencoded';
	}
	
	public function getContent() {
		if ($this->content == null)
			$this->content = $this->serialize();
		return $this->content;
	}
}

?>