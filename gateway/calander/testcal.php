<?php
/** 
 * Template Name: testcalendar
 *
 */
?>
<?php
// echo "gdfggggggggggggggggggggg";
require_once 'google-api-php-client-2.4.0/vendor/autoload.php';

$client_id = '358393949584-7f9h84lp6blm6rf4bvp8glgee970ioee.apps.googleusercontent.com'; //Client ID
$service_account_name = 'rental-60@quickstart-1576752502200.iam.gserviceaccount.com'; //Email Address

$key_file_location =$_SERVER['DOCUMENT_ROOT'] .'/wp-content/themes/twentysixteen/include/s.p12';
$client = new Google_Client(); //AUTHORIZE OBJECTS

$client->setClientId($client_id);
$client->setClientSecret('lF-h89hFADXcVft2uBx1NNPX');
$client->setApplicationName("Rentalbooking");

//INSTATIATE NEEDED OBJECTS (In this case, for freeBusy query, and Create New Event)
$service = new Google_Service_Calendar($client);
$id = new Google_Service_Calendar_FreeBusyRequestItem($client);
$item = new Google_Service_Calendar_FreeBusyRequest($client);
$event = new Google_Service_Calendar_Event($client);
$startT = new Google_Service_Calendar_EventDateTime($client);
$endT = new Google_Service_Calendar_EventDateTime($client);

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
 header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
// print_r($client);

     // print_r($new_event);
// $cred = new Google_Auth_AssertionCredentials();
    // $service_account_name,
    // array('https://www.googleapis.com/auth/calendar'),
    // $key

?>

