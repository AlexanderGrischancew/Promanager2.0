<?php

ini_set('session.use_only_cookies', 1);
session_start();
	
if (isset($_COOKIE['login'])) 
	{
	$GeheimerSchlüsssel = "____";
	list($c_username, $cookie_hash) = split(',',$_COOKIE['login']);
	if (md5($c_username.$GeheimerSchlüsssel) != $cookie_hash)		
		{
		header("location: index.php");
		exit;	
		}
	}
else
	{
	header("location: index.php");
	exit;	
	}
	
if ($_SESSION["Admin"] == FALSE)
	{
	header("location: profil.php");
	exit;
	}
	
$logFile = fopen("log.txt", "w");
$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | cleared Log | log.txt \r\n";
fwrite($logFile, $logMessage);
fclose($logFile);

header("location: log.php");
exit;
?>
