<?php
// VERSION: 2

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
	
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

$BenutzerID = $_POST["AdminRechteID"];

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
#Hauptskript:
$abfrage = "SELECT `Admin`,Name,Vorname FROM `Benutzer` WHERE BenutzerID = '$BenutzerID';"; //Admin?
$AdminStatus = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
while($row = mysql_fetch_assoc($AdminStatus))
	{
	$Admin = "$row[Admin]";
	$Name = "$row[Name]";
	$Vorname = "$row[Vorname]";
	}
	
if ($Admin == 1){$Admin = TRUE;}else{$Admin = FALSE;}
	
if($Admin == TRUE){$AdminChange = 0;}else{$AdminChange = 1;} //Festlegen ob Admin entfernt oder hinzugefügt werden soll

	
$update = "UPDATE `Benutzer` SET `Admin`= '$AdminChange' WHERE BenutzerID = '$BenutzerID';";//Admin ändern anhand obriger Abfrage
mysql_query($update)
	OR die("Error: $abfrage <br>".mysql_error());
	
	
if ($AdminChange == 1)
	{
	$Admin = "TRUE";
	}
else
	{
	$Admin = "FALSE";
	}
$logFile = fopen("log.txt", "a");
$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | changed admin rights | ".$Name.$Vorname." | ".$Admin." \r\n";
fwrite($logFile, $logMessage);
fclose($logFile);
	
header("location:users.php");
exit;
	
?>
