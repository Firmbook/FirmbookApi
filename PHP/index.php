<?php
require_once 'Common.php';
require_once 'PEAR.php';
require_once 'Firmbook.php';

$firmbook = new Firmbook_Service(array(
	'publicKey' => '', 
	'privateKey' => ''
));

/*
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
$guestResult = $firmbook->updateGuestTickets('', array(
	Firmbook_Ticket::newTicket('Первый человек'),
	Firmbook_Ticket::newTicket('Второй человек'),
	Firmbook_Ticket::newTicket('Третий человек', 1),
	Firmbook_Ticket::newTicket('Четвертый человек', 2),
));
dump($guestResult);
*/
$getTicketsResult = $firmbook->getTickets('', 0, 50);
if ($getTicketsResult->isOk()) {
	// Результат нормальный
	dump($getTicketsResult->getContent());
} else {
	// произошла ошибка
}
?>