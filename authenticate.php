<?php
require_once __DIR__.'/google-api-php-client-2.1.0_PHP54/vendor/autoload.php';

$authConfigFile='client.secrets.json';
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';

$client = new Google_Client();
$client->setAuthConfigFile($authConfigFile);
$client->addScope("https://www.googleapis.com/auth/calendar");
$client->setAccessType("offline");
$client->setRedirectUri($redirect_uri);

$token = loadToken();

if (is_array($token) and isset($token["access_token"])) {
	$client->setAccessToken($token);

	
// revoke Token bedeutet, dass der Token bei Google ganz abgemeldet wird. 
// Damit ist auch der Refresh Token ungültig.
if(isset($_GET['revoke'])) {
	$client->revokeToken();
	saveToken("");
	echo('<script>location.reload();</script>');
}



// token abgelaufen --> Wir versuchen über den refresh Token neu zu authentifizieren.
if($client->isAccessTokenExpired()) { 
	$refreshToken=loadRefreshToken();
	
  try {
	$newToken=$client->fetchAccessTokenWithRefreshToken($refreshToken);
  } 
  catch (\Google_Service_Exception $e) 
  { 
      handle_exception($e);
  }	
	saveToken($newToken);
}

	// Alles fertig ! Authentifiziert !
	
	

} else { // kein Token abgespeichert

// authentifizierung durchführen -----------------------------

if (!isset($_GET['code'])) { 
	
	// 1. Stufe. Authentifizierungsbildschirm von Google wird geladen
  
	$client->setRedirectUri($redirect_uri);
	$auth_url = $client->createAuthUrl();
	echo('<script>window.location.replace("'.filter_var($auth_url, FILTER_SANITIZE_URL).'");</script>');


} else {

  // 2. Stufe. Es wurde $_GET[code] in url mitgegeben.	
  try {
  	$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
	saveToken($token);  
	$client->setRedirectUri($redirect_uri);
	echo('<script>window.location.replace("'.filter_var($redirect_uri, FILTER_SANITIZE_URL).'");</script>');

   
  } 
  catch (\Google_Service_Exception $e) 
  { 
      handle_exception($e);
  }	
	
}
}


// handle google service exception 
function handle_exception($e) {
		global $client;
		$client->revokeToken();
		saveToken("");
		echo('<script>location.reload();</script>');
	
}

// in dieser Funktion muss das Token des Users zurückgegeben werden. (Falls vorhanden
// von einer früheren Authentifizierung
// das Token kann als Cookie, in einer Datei oder auch in der Datenbank abgelegt werden. 
// Die Beispielimplementierung hier legt eine einfache Textdatei an in der das Token gespeichert wird
// ACHTUNG ! Es wird unabhängig davon welcher User die Website verwendet in die selbe Datei geschrieben
// Dies ist nur eine Implementierung für Testzwecke !!
function loadToken() {
	return unserialize(file_get_contents("token.txt"));
}	
	
function loadRefreshToken() {
	return unserialize(file_get_contents("token_refresh.txt"));
}	
	
function saveToken($token) {
	file_put_contents("token.txt",serialize($token));
	if (isset($token['refresh_token'])) file_put_contents("token_refresh.txt", serialize($token));
}	
	
