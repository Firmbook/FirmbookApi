<?php
/**
 * Description of Firmbook_Ticket
 *
 * @author Stas
 */
class Firmbook_Ticket {
	protected $displayName;
	protected $tag;
	protected $id;
	
	protected function __construct($displayName, $tag = null, $id = null) {
		$this->tag = $tag;
		$this->displayName = $displayName;
		$this->id = $id;
	}
	
	public function serializeToArray() {
		return array(
			'displayName' => $this->displayName,
			'tag' => $this->tag,
			'id' => $this->id
		);
	}
	
	/**
	 * Создать новый билет для пользователя
	 * 
	 * @param string $displayName	Имя пользователя в билете
	 * @param string $tag			Метка (для внутреннего айди)
	 * @return Firmbook_Ticket		Билет пользователя
	 */
	static public function newTicket($displayName, $tag = null) {
		return new Firmbook_Ticket($displayName, $tag);
	}
	
	/**
	 * Создать билет который уже присутствует
	 * 
	 * @param string $displayName	Новое имя пользователя
	 * @param string $tag			Метка (для внутренного айди)
	 * @param string $id			Идентификатор билета на фирмбуке
	 * @return Firmbook_Ticket		Билет пользователя
	 */	
	static public function existingTicket($displayName, $tag, $id) {
		return new Firmbook_Ticket($displayName, $tag, $id);
	}
}
?>