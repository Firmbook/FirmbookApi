<?php
require_once 'Common.php';
require_once 'PEAR.php';
require_once 'Firmbook.php';

function getAllTickets(Firmbook_Service $firmbook, $conferenceId, $ticketInfo) {
	$data = $ticketInfo->getData();
	
	$resultData = array();
	$pageSize = 50;
	for ($i = 0; $i < $data['TotalCount'] / $pageSize + 1; $i ++) {
		$tickets = $firmbook->getTickets($conferenceId, $pageSize*$i, $pageSize);
		$resultData = array_merge($resultData, $tickets->getContent());
	}
	return array(
		'Content' => $resultData, 
		'TotalCount' => $data['TotalCount']
	);
}

function getTickets($publicKey, $privateKey, $conferenceId) {
	$firmbook = new Firmbook_Service(array(
		'publicKey' => $publicKey, 
		'privateKey' => $privateKey
	));
	$ticketInfo = $firmbook->getTickets($conferenceId, 0, 1);
	if ($ticketInfo->isOk()) {
		return json_encode(getAllTickets($firmbook, $conferenceId, $ticketInfo));	
	} else {	
		return json_encode($ticketInfo->getData());
	}
}

function issueTickets($publicKey, $privateKey, $conferenceId, $displayName, $tag) {
	$firmbook = new Firmbook_Service(array(
		'publicKey' => $publicKey, 
		'privateKey' => $privateKey
	));
	$issueTicketInfo = $firmbook->issueTicket($conferenceId, $displayName, $tag);
	if ($issueTicketInfo->isOk()) {
		return json_encode($issueTicketInfo->getId());	
	} else {	
		return json_encode($issueTicketInfo->getData());
	}
}

$func = $_GET['func'];

if ($func == 'getTickets')
	echo getTickets($_GET['publicKey'], $_GET['privateKey'], $_GET['conferenceId']);

if ($func == 'issueTicket')
	echo issueTickets($_GET['publicKey'], $_GET['privateKey'], 
			$_GET['conferenceId'], $_GET['displayName'], $_GET['tag']);
?>