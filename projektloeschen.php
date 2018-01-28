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
$ProjektID = $_POST["InfoID"];
$_SESSION["ProjektUpdateID"] = $ProjektID;
	
	
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$abfrage = "SELECT Name FROM Projekt WHERE ProjektID ='$ProjektID';";
$ProjektAnzahl = mysql_query($abfrage);

while($row = mysql_fetch_assoc($ProjektAnzahl))
	{ 
	$ProjektName = "$row[Name]";
	}

$abfrage = "DELETE FROM `Projekt` WHERE ProjektID ='$ProjektID';";
$ProjektInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	

mysql_close($verbindung);

$logFile = fopen("log.txt", "a");
$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | deleted project | ".$ProjektName." \r\n";
fwrite($logFile, $logMessage);
fclose($logFile);

header("location:projects.php");
exit;
	
?>
