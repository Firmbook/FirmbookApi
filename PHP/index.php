<?php
require_once 'Common.php';
require_once 'PEAR.php';
require_once 'Firmbook.php';

$firmbook = new Firmbook_Service(array(
	'publicKey' => '', 
	'privateKey' => ''
));

/*
// Пример создания вебинара на Firmbook.ru который начнется через час
$result = $firmbook->createConference(array(
	'title' => 'Тестовое мероприятие',
	'summary' => 'Краткое описание',
	'description' => 'Описание тестового мероприятия',
	'isRegistrationPublic' => false,
	'startDate' => new DateTime('next hour'),
	'participantIds' => array(),
	'coHostIds' => array()
));
dump($result);
*/

/*
// Пример обновления списка гостей в вебинаре
$guestResult = $firmbook->updateGuestTickets('PAcB5ygUGEuM2xH5zsxVCA', array(
	Firmbook_Ticket::newTicket('Первый человек'),
	Firmbook_Ticket::newTicket('Второй человек'),
	Firmbook_Ticket::newTicket('Третий человек', 1),
	Firmbook_Ticket::newTicket('Четвертый человек', 2),
));
dump($guestResult);
*/

// Пример получения списка билетов в вебинаре
$getTicketsResult = $firmbook->getTickets('6kYfk1EzxE_6vhkg_OAeTQ', 0, 50);
if ($getTicketsResult->isOk()) {
	// Результат нормальный
} else {	
	// обработка ошибки
}
dump($getTicketsResult);
?>