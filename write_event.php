<?php

if (isset($_GET['writeEvent'])) {
	$event = new Google_Service_Calendar_Event();

	$event->setSummary("Event created by Codefreaks.");
	$event->setDescription("This Event was added by Codefreaks. Just delete!");

	$dt = new DateTime();
	$dt->add(new DateInterval("PT2H"));
  	$start = new Google_Service_Calendar_EventDateTime();
  	$start-> setDateTime($dt->format("c"));
  	$start-> setTimeZone("Europe/Berlin");

	$dt->add(new DateInterval("PT2H"));  	
   	$end = new Google_Service_Calendar_EventDateTime();
  	$end-> setDateTime($dt->format("c"));
  	$end-> setTimeZone("Europe/Berlin");
  	
  	$event->setStart($start);
  	$event->setEnd($end);
	$service->events->insert($calendarId, $event);
}

?>


<hr>
<a href="?writeEvent">Event in 2 Stunden einfuegen</a>

