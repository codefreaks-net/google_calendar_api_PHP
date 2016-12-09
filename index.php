<?php

/*

	Using Google Calendar API with PHP
	long term, offline Access
	
	(C) http://www.codefreaks.net

*/
session_start();

require_once("authenticate.php");


// Start Calendar Service with authenticated client !
$service = new Google_Service_Calendar($client);

require_once("choose_calendar.php");

if (isset($calendarId)) {
	include_once("write_event.php");
	include_once("show_events.php");
}

?>

<h3>all done</h3>