<?php
/**
 * Template Name: G-cal-ics
 * 
 */
// echo "ok";
?>
<?php
// Fetch vars
$event = array(
	'id' => 'dnrcim8h0hjhggf1oc2mlrj2q4@group.calendar.google.com',
	'title' =>'Bookings done',
	'datestart' =>'2020-01-13', 
	'dateend' => '2020-01-23',
	'address' => 'indore'
);
function dateToCal($time) {
	return date('Ymd\This', $time) . 'Z';
}
// Build the ics file
$ical = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTEND:"2020-01-23T17:00:00-07:00"
UID:' . md5($event['title']) . '
DTSTAMP:' . time() . '
URL;VALUE=' . $event['id'] . '
SUMMARY:' . addslashes($event['title']) . '
DTSTART:"2020-01-23T17:00:00-07:00"
END:VEVENT
END:VCALENDAR';
//set correct content-type-header
if($event['id']){
	// header('Content-type: text/calendar; charset=utf-8');
	// header('Content-Disposition: attachment; filename=mohawk-event.ics');
	echo $ical;
} else {
	// If $id isn't set, then kick the user back to home. Do not pass go, and do not collect $200.
	header('Location: /');
}
?>