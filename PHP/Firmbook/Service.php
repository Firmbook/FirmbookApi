<?php
/**
 * Класс работы с сервисами Фирмбук
 *
 * @author Stas
 */
class Firmbook_Service {
	const TYPE_STRING = 'string';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_DATE	= 'date';
	const TYPE_ID_ARRAY = 'array of id';
	const TYPE_INTEGER = 'integer';

	protected $host = 'firmbook.ru';
	protected $publicKey;
	protected $privateKey;
	
	/**
	 * Инициализация сервиса
	 * @param array $spec	Ассоциативный массив должен содержать
	 *							publiсKey - Публичный ключ
	 *							privateKey - Приватный ключ
	 *						Которые можно найти в личном кабинете в разделе настройки 
	 *							на сайте Firmbook.ru
	 */
	public function __construct(array $spec) {
		if (array_key_exists('host', $spec))
			$this->host = $spec['host'];
		$this->publicKey = $this->checkRequiredValue($spec, "publicKey");				
		$this->privateKey = $this->checkRequiredValue($spec, "privateKey");
	}

	/**
	 * Создать вебинар. 
	 * @param array $conferenceData	массив с данными для создания вебианара
	 *								обязательные поля:
	 *								title - Название вебинара
	 *								summary - Краткое описание вебинара
	 *								description - Полное описание
	 *								isRegistrationPublic - Флаг того что вебинар публичный
	 *								startDate - Дата начала вебинара (DateTime)
	 *								participantIds - Массив строк с идентификаторами пользователей 
	 *									Фирмбук, приглашенных на вебинар
	 *								coHostIds - Массив строк с идентификаторами пользователей
	 *									Фирмбук, соведущих этого вебинара
	 * 								durationHours
	 * 									длительность вебинара (часы) – от 0 до 59
	 * 								durationMinutes
	 * 									длительность вебинара (минуты) – от 0 до 59
	 * @return Firmbook_Command_Result Результат выполнения команды
	 */
	public function createConference(array $conferenceData) {
		$this->checkRequiredValue($conferenceData, "title", Firmbook_Service::TYPE_STRING, 3, 255);
		$this->checkRequiredValue($conferenceData, "summary", Firmbook_Service::TYPE_STRING, 3, 512);
		$this->checkRequiredValue($conferenceData, "description", Firmbook_Service::TYPE_STRING, 0, 10000);
		$this->checkRequiredValue($conferenceData, "isRegistrationPublic", Firmbook_Service::TYPE_BOOLEAN);
		$this->checkRequiredValue($conferenceData, "startDate", Firmbook_Service::TYPE_DATE);
		$this->checkRequiredValue($conferenceData, "participantIds", Firmbook_Service::TYPE_ID_ARRAY);
		$this->checkRequiredValue($conferenceData, "coHostIds", Firmbook_Service::TYPE_ID_ARRAY);		
		$this->checkRequiredValue($conferenceData, "durationHours", Firmbook_Service::TYPE_INTEGER);
		$this->checkRequiredValue($conferenceData, "durationMinutes", Firmbook_Service::TYPE_INTEGER);
		$command = new Firmbook_Command($this->host,
				'/Exec/CreateConferenceCommand',
				$this->privateKey, $this->publicKey, 
				new Firmbook_Serializer_UrlEncode($conferenceData)
			);
		return $command->getResult();
	}
	
	/**	
	 * Обновить список гостей вебинара.
	 *		Должны быть переданы ВСЕ запросы на билеты, в том числе которые были
	 *		зарегистрированы ранее. Если вы хотите убрать билеты, то надо просто 
	 *		не передавать билет в параметры.
	 * Например, если вы хотите добавить трех пользователей требуется вызвать:
	 *		$fb->updateGuestTickets($id, array(
	 *			Firmbook_Ticket::newTicket('Первый пользователь', 1),
	 *			Firmbook_Ticket::newTicket('Второй пользователь', 2),
	 *			Firmbook_Ticket::newTicket('Третий пользователь', 3),
	 *		));
	 * Например, если вы хотите добавить еще двух пользователей:
	 *		$ticketId1, $ticketId2, $ticketId3 - Id билетов полученных с Фирмбук
	 *			c помощью getTickets
	 *		$fb->updateGuestTickets($id, array(
	 *			Firmbook_Ticket::existingTicket('Первый пользователь', 1, $ticketId1),
	 *			Firmbook_Ticket::existingTicket('Второй пользователь', 2, $ticketId2),
	 *			Firmbook_Ticket::existingTicket('Третий пользователь', 3, $ticketId3),
	 *			Firmbook_Ticket::newTicket('Четвертый пользователь', 4),
	 *			Firmbook_Ticket::newTicket('Пятый пользователь', 5),
	 *		));
	 * @param string $conferenceId	Идентификатор вебинара
	 * @param array $guestList		Массив содержащий объекты Firmbook_Ticket
	 * @return Firmbook_Command_Result Результат выполнения команды 
	 */
	public function updateGuestTickets($conferenceId, array $guestList) {		
		$this->checkId($conferenceId);
		$command = new Firmbook_Command($this->host,
				'/Exec/UpdateGuestEventListCommand',
				$this->privateKey, $this->publicKey,
				new Firmbook_Serializer_UrlEncode(array(
					'conferenceId' => $conferenceId,
					'newTicketInfoList' => $guestList
				))
			);
		return $command->getResult();
	}
	
	/**
	 * Создает тикет для указанного вебинара
	 * 
	 * @param string $conferenceId		Идентифкатор вебианра
	 * @param string $displayName		Имя пользователя для которого требуется создать билет
	 * @param string $tag				Тег билета(сделан для вашего ID в базе)
	 * @return Firmbook_Command_Result	Результат выполнения запроса
	 */	
	public function issueTicket($conferenceId, $displayName, $tag = null) {
		$this->checkId($conferenceId);
		$command = new Firmbook_Command($this->host,
				'/Exec/IssueGuestTicketCommand',
				$this->privateKey, $this->publicKey,
				new Firmbook_Serializer_UrlEncode(array(
					'conferenceId' => $conferenceId,
					'displayName' => $displayName,
					'tag' => $tag
				))
			);
		return $command->getResult();
	}
	
	/**
	 * Получить список гостевых билетов
	 * @param string $conferenceId		Идентификатор вебинара
	 * @param int $fromIndex			Первый тикет для выборки
	 * @param int $pageSize				Максимальное количество выбраных тикетов (не более 50) 
	 * @return Firmbook_Command_Result	Результат выполнения запроса 
	 */
	public function getTickets($conferenceId, $fromIndex, $pageSize) {
		$this->checkId($conferenceId);
		if ($fromIndex < 0)
			throw new Exception ("First ticket index must be positive");
		if ($pageSize > 50)
			throw new Exception ("Page size must be lesser than 50");
		
		$query = new Firmbook_Query($this->host,
				"/data/event/$conferenceId/guestTickets", 
				$this->privateKey, $this->publicKey,
				$pageSize, $fromIndex);
		return $query->getResult();
	}
	
	/**
	 * Обновить описание вебинара
	 * @param string $conferenceId	Идентификатор вебинара
	 * @param array $conferenceData	массив с данными для создания вебианара
	 *								обязательные поля:
	 *								title - Название вебинара
	 *								summary - Краткое описание вебинара
	 *								description - Полное описание
	 *								isRegistrationPublic - Флаг того что вебинар публичный
	 *								startDate - Дата начала вебинара (DateTime)
	 *								participantIds - Массив строк-тдентификаторов пользователей 
	 *									Фирмбук, приглашенных на вебинар
	 *								coHostIds - Массив строк-тдентификаторов пользователей
	 *									Фирмбук, соведущих этого вебинара
	 *								removedParticipantIds - Массив строк-идентификаторов пользователей
	 *									Фирмбук, билет которых должен быть анулирован
	 * 								durationHours
	 * 									длительность вебинара (часы) – от 0 до 59
	 * 								durationMinutes
	 * 									длительность вебинара (минуты) – от 0 до 59
	 * @return Firmbook_Command_Result	Результат выполнения запроса 
	 */
	public function modifyConference($conferenceId, array $conferenceData) {
		$this->checkRequiredValue($conferenceData, "title", Firmbook_Service::TYPE_STRING, 3, 255);
		$this->checkRequiredValue($conferenceData, "summary", Firmbook_Service::TYPE_STRING, 3, 512);
		$this->checkRequiredValue($conferenceData, "description", Firmbook_Service::TYPE_STRING, 0, 10000);
		$this->checkRequiredValue($conferenceData, "isRegistrationPublic", Firmbook_Service::TYPE_BOOLEAN);
		$this->checkRequiredValue($conferenceData, "startDate", Firmbook_Service::TYPE_DATE);
		$this->checkRequiredValue($conferenceData, "participantIds", Firmbook_Service::TYPE_ID_ARRAY);
		$this->checkRequiredValue($conferenceData, "coHostIds", Firmbook_Service::TYPE_ID_ARRAY);				
		$this->checkRequiredValue($conferenceData, "removedParticipantIds", Firmbook_Service::TYPE_ID_ARRAY);
		$this->checkRequiredValue($conferenceData, "durationHours", Firmbook_Service::TYPE_INTEGER);
		$this->checkRequiredValue($conferenceData, "durationMinutes", Firmbook_Service::TYPE_INTEGER);
		$conferenceData['conferenceId'] = $conferenceId;
		$command = new Firmbook_Command($this->host,
				'/Exec/ModifyConferenceInfoCommand',
				$this->privateKey, $this->publicKey, 
				new Firmbook_Serializer_UrlEncode($conferenceData)
			);	
		return $command->getResult();		
	}
	
	/**
	 * Завершить вебинар и поместить его в архив
	 * @param string $conferenceId	Идентификатор вебинара
	 * @return Firmbook_Command_Result	Результат выполнения запроса 
	 */
	public function finishConference($conferenceId) {
		$this->checkId($conferenceId);
		$command = new Firmbook_Command($this->host,
				'/Exec/MarkConferenceAsFinishedCommand',
				$this->privateKey, $this->publicKey, 
				new Firmbook_Serializer_UrlEncode(array(
					'conferenceId' => $conferenceId)
				)
			);	
		return $command->getResult();
	}
	
	protected function checkId($id) {
		if (strlen($id) != 22)
			throw new Exception("Id is not correct");
	}

	protected function checkRequiredValue(array $spec, $name, 
			$requiredType = Firmbook_Service::TYPE_STRING, 
			$minLength = 0, $maxLength = PHP_INT_MAX) {
		if (!array_key_exists($name, $spec))
			throw new Exception("Required parameter $name doesn't exist.");
		$value = $spec[$name];
		if ($requiredType == Firmbook_Service::TYPE_STRING) {
			if (!is_string($value))
				throw new Exception("Parameter $name should be $requiredType."); 
			if (strlen($value, 'UTF-8') < $minLength)
				throw new Exception("Parameter $name should be minimum $minLength length."); 
			if (strlen($value, 'UTF-8') > $maxLength)
				throw new Exception("Parameter $name should be maximum $maxLength length."); 
		}
		if ($requiredType == Firmbook_Service::TYPE_BOOLEAN && !is_bool($value))
			throw new Exception ("Parameter $name should be $requiredType.");
		if ($requiredType == Firmbook_Service::TYPE_DATE && !$value instanceof DateTime)
			throw new Exception("Parameter $name should be instanceof DateTime.");
		if ($requiredType == Firmbook_Service::TYPE_ID_ARRAY && !is_array($value))
			throw new Exception("Parameter $name should be $requiredType format.");
		if ($requiredType == Firmbook_Service::TYPE_INTEGER && !is_numeric($value))
			throw new Exception ("Parameter $name should be $requiredType.");
		return $value;
	}
}
?>