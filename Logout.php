<?php
// VERSION: 2

ini_set('session.use_only_cookies', 1);
session_start();

$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | logged out\r\n";
$logFile = fopen("log.txt", "a");
fwrite($logFile, $logMessage);
fclose($logFile);

session_destroy();
setcookie('login', "",time()-3600);

echo "<head>

  <title>Projektmanagementsystem</title>

	<meta name='author' content='Daveundnoppaprograming'>
	<meta name='description' content='Projektmanagementsystem'>
	<meta name='keywords' content='Managementsoftware für großere Firmen'>
	<meta name='robots' content='all'>
	<meta http-equiv='content-type' content='text/html; charset=windows-1252'>
  
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='tstyle.css'>  
	</head>";
	
echo "<body>";
echo "<div id='main' role='main'>";

echo "<div align='center'>";

echo "<h5>Sie wurden ausgelogt</h5>";
$log = FALSE;

echo "<form method='post' action='index.php'> 
	<input type='submit' value='Zur&uuml;ck zum Login'/>
	</form>";

echo "</div>";
echo "</div>";

echo "</body>";

?>
