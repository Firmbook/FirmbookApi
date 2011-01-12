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
	
	static public function newTicket($displayName, $tag) {
		return new Firmbook_Ticket($displayName, $tag);
	}
	
	static public function existingTicket($displayName, $tag, $id) {
		return new Firmbook_Ticket($displayName, $tag, $id);
	}
}
?>