<?php
if (isset($_GET['newcal'])) unset($_SESSION['calid']);

if (isset($_GET['calid'])) { $_SESSION['calid']=$_GET['calid']; $calendarId=$_GET['calid']; }

if (!isset($_SESSION['calid'])) {
	echo('<h3>Choose a Calendar first:</h3>');
	$optParams = array(
	  'maxResults' => 30,
	  'showDeleted' => false,
	);
	$calendarList = $service->calendarList->listCalendarList($optParams);

 	foreach ($calendarList->getItems() as $calendarListEntry) {
 	 		if ($calendarListEntry->getAccessRole() == "owner")  // show only calendars where user is owner
  	  		echo ("<br><a href='?calid=".$calendarListEntry->getID()."'>".$calendarListEntry->getSummary()."</a>");
  	}
} else {
	echo ("<br><a href='?newcal'>choose different calendar</a>");	
	$calendarId = $_SESSION['calid'];
}
	
	