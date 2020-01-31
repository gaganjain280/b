<?php
/** 
 * Template Name: google calendar
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
get_header(); 

?>
<div>
	<iframe src="https://calendar.google.com/calendar/embed?height=300&amp;wkst=1&amp;bgcolor=%23C0CA33&amp;ctz=Asia%2FKolkata&amp;src=Z2FnYW4uY29kaW5na2FydEBnbWFpbC5jb20&amp;src=Y2luazdxMWFpcHZkZjg4Nzg5dnE5bGIzY29AZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;src=ZW4uaW5kaWFuI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&amp;color=%2322AA99&amp;color=%238A2D38&amp;color=%231F753C&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;showNav=1&amp;showTitle=0&amp;mode=MONTH" style="border:solid 1px #777" width="500" height="300" frameborder="0" scrolling="no"></iframe>
</div>
<div>
  <iframe src="https://calendar.google.com/calendar/b/1/embed?height=300&amp;wkst=1&amp;bgcolor=%23ffffff&amp;ctz=Asia%2FKolkata&amp;src=YWRkcmVzc2Jvb2sjY29udGFjdHNAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&amp;src=ZG5yY2ltOGgwaGpoZ2dmMW9jMm1scmoycTRAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;color=%23329262&amp;color=%23A87070&amp;showPrint=0" style="border:solid 1px #777" width="500" height="300" frameborder="0" scrolling="no"></iframe>
</div>
<html>
  <head>
    <title>Google Calendar API Quickstart</title>
    <meta charset="utf-8" />
  </head>
  <body>


    <pre id="content" style="white-space: pre-wrap;"></pre>

<!--  -->
<div class="row">
        <form name="guest_form" id="Bookform">
              <h5>Select Date:</h5>
               <input type="text" id="daterange" value="" />
               <br><br>
    </form>  

 <!-- insert event -->
<script src="https://apis.google.com/js/api.js"></script>
<script>
  /**
   * Sample JavaScript code for calendar.events.insert
   * See instructions for running APIs Explorer code samples locally:
   * https://developers.google.com/explorer-help/guides/code_samples#javascript
   */
  function authenticate() {
    return gapi.auth2.getAuthInstance()
        .signIn({scope: "https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.events"})
        .then(function() { alert("Sign-in successful")},
              function(err) { alert("Error signing in", err); });
  }
  function loadClient() {
    gapi.client.setApiKey("AIzaSyCgn3AhryzKA4Vgp5M_ucyZi6lzuaEPSho");
    return gapi.client.load("https://content.googleapis.com/discovery/v1/apis/calendar/v3/rest")
        .then(function() { console.log("GAPI client loaded for API"); },
              function(err) { console.error("Error loading GAPI client for API", err); });
  }
  // Make sure the client is loaded and sign-in is complete before calling this method.
  function execute() {
   let date_range    = $('#daterange').val();
   var res = date_range.split(" - ");
   var start_date=res[0];
   var end_date=res[1];
    var start_date = new Date(start_date); 
    var end_date = new Date(end_date); 
    alert(start_date);
    alert(end_date);
    return gapi.client.calendar.events.insert({
    "calendarId": "cink7q1aipvdf88789vq9lb3co@group.calendar.google.com",
     "resource": {
        "end": {
          // '2019-12-27T17:00:00-07:00'
          'dateTime': end_date,
            'timeZone': 'Asia/Kolkata'
        },
        "start": {
                  'dateTime': start_date,
                  'timeZone': 'Asia/Kolkata'  
                 }
      }
    })
        .then(function(response) {
                // Handle the results here (response.result has the parsed body).
                alert("Response", response);
              },
              function(err) { console.error("Execute error", err); });
  }
  gapi.load("client:auth2", function() {
    gapi.auth2.init({client_id: "358393949584-7f9h84lp6blm6rf4bvp8glgee970ioee.apps.googleusercontent.com"});
  });
</script>
<button onclick="authenticate().then(loadClient)">authorize and load</button>
<button onclick="execute()">Save date</button>

</body>
</html>




<?php get_footer(); ?>


<?php 
require_once 'google-api-php-client-2.4.0/vendor/autoload.php';
$client = new Google_Client(); //AUTHORIZE OBJECTS
$client_id = '358393949584-7f9h84lp6blm6rf4bvp8glgee970ioee.apps.googleusercontent.com'; //Client ID
$service_account_name = 'rental-60@quickstart-1576752502200.iam.gserviceaccount.com'; //Email Address

$key_file_location =$_SERVER['DOCUMENT_ROOT'] .'/wp-content/themes/twentysixteen/include/s.p12';
$client->setClientId($client_id);
$client->setClientSecret('lF-h89hFADXcVft2uBx1NNPX');

$client_secrets =$_SERVER['DOCUMENT_ROOT'] .'/wp-content/themes/twentysixteen/include/client_secret.json';

$client->setAuthConfig($client_secrets);

$client->setDeveloperKey($client_secrets);

$key = file_get_contents($key_file_location);

// $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);

// $client->setRedirectUri('http://gagan.codingkloud.com/test-cl');
$client->setRedirectUri('http://gagan.codingkloud.com/gcalender');
$client->setAccessType("offline");
// $client->setApprovalPrompt("consent");
$client->setIncludeGrantedScopes(true);   // incremental auth
// $client->setState($sample_passthrough_value);
$client->setLoginHint('gagan.codingkart@gmail.com');
// $client->setApprovalPrompt('consent');
$auth_url = $client->createAuthUrl();

// $client->authenticate($_GET['code']);
$code=$_GET['code'];
// $client->authenticate($code);
// print_r($code);
// $access_token = $client->getAccessToken();
$accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);

// Exchange authorization code for access token
// $accessToken = $client->authenticate($code);
 // print_r($accessToken);

$client->setAccessToken($accessToken);
$event = new Google_Service_Calendar_Event(array(
            'summary' => 'checking',
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => array(
                'dateTime' => '2020-01-13T09:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
                'dateTime' => '2020-01-23T17:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ),
            'recurrence' => array(
                'RRULE:FREQ=DAILY;COUNT=2'
            ),
       
        ));
         $service = new Google_Service_Calendar($client);
// print_r($service);

     $new_event = $service->events->insert('dnrcim8h0hjhggf1oc2mlrj2q4@group.calendar.google.com', $event);

echo $new_event->getId();
print_r($new_event);


// $access_token = $client->getAccessToken();
//
?>