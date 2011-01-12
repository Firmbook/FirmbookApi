<?php
require_once 'Common.php';
require_once 'PEAR.php';
require_once 'Firmbook.php';

$firmbook = new Firmbook_Service(array(
	'publicKey' => 'caa8ff25a9a54922a4af51f1656d4bcc', 
	'privateKey' => '1b7cd85b97174babb8984d03192afa92'
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
$guestResult = $firmbook->updateGuestEventList('MwS117sKD0GjcDmUWXbztg', array(
	Firmbook_Ticket::newTicket('Testing guest', '1'),
	Firmbook_Ticket::newTicket('Testing guest2', '222'),
	Firmbook_Ticket::newTicket('Testing guest3', '234'),
	Firmbook_Ticket::newTicket('Testing guest4', '235'),
));
*/
$guestResult = $firmbook->getTickets('MwS117sKD0GjcDmUWXbztg', 0, 50);
dump($guestResult);
?>