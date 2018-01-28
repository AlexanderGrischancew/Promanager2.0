<?php
// VERSION: 2.1

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

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
##Hauptskript:


/*if (isset($_SESSION['BenutzerIDProjekt']))
	{
	$BenutzerID = $_SESSION['BenutzerIDProjekt'];
	unset($_SESSION['BenutzerIDProjekt']);
	}
else
	{*/
	$BenutzerID = $_POST["BenutzerloeschenID"];
	//}
	
//print($BenutzerID);
//exit;

$abfrage = "SELECT Name,Vorname FROM Benutzer WHERE BenutzerID = '$BenutzerID'";
$BenutzerProjekte = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());

while($row = mysql_fetch_assoc($BenutzerProjekte))
	{
	$Name  = "$row[Name]";
	$Vorname = "$row[Vorname]";
	}


	
$delete = " DELETE FROM Benutzer WHERE BenutzerID = '$BenutzerID';";//Benutzer löschen
mysql_query($delete)
	OR die("Error: $abfrage <br>".mysql_error());
	
$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | deleted user | ".$Name.$Vorname."\r\n";
$logFile = fopen("log.txt", "a");
fwrite($logFile, $logMessage);
fclose($logFile);
	
header("location:users.php");
exit;
?>
