<?php
// Event löschen
if (isset($_GET['delEvent'])) {
	$service->events->delete($calendarId, $_GET['delEvent']);
}

$today = new DateTime("today");
$optParams = array(
  'maxResults' => 300,
  'timeMin' => $today->format("c"), 
);

   try {
	  $results = $service->events->listEvents($calendarId, $optParams);
   } catch (\Google_Service_Exception $e) { 
      handle_exception($e);
   }

if (count($results->getItems()) > 0) {	
  echo('<table><tr><th>Datum</th><th>Zeit</th><th>Dauer(Minuten)</th><th>Eintrag</th><th>Löschen</th></tr>');	
  foreach ($results->getItems() as $event) {
 	
  	$start= new DateTime($event->start->dateTime);
  	$end= new DateTime($event->end->dateTime);
  	$dauer = date_diff($end, $start);
    $minuten=$dauer->h*60+$dauer->i;
	$summary = $event->getSummary();
   	
    echo('<tr><td>'.$start->format("j.n.").'</td><td>'.$start->format("G:i").'</td><td>'.$minuten.'</td><td>'.$event->getSummary().'</td><td><a href="?delEvent='.$event->getId().'">Löschen</a></td></tr>');

  }
  echo("</table>");
}
