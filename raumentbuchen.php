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

//$_SESSION["ErstellError"] = "";
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$RaumID = $_POST["RaumIDbuchen"];
	
$abfrage = "SELECT Name FROM Raeume WHERE RaumID = '$RaumID';"; //"SOLL" aus SQL auslesen
$mysqlquery = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($mysqlquery))
	{
	$RaumName  = "$row[Name]";
	}	
		
$abfrage = "UPDATE `Raeume` SET `Projekt`= NULL WHERE RaumID = '$RaumID'";
mysql_query($abfrage);

$logFile = fopen("log.txt", "a");
$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | unoccupied room | ".$RaumName." \r\n";
fwrite($logFile, $logMessage);
fclose($logFile);

header("location:raume.php");
exit;

?>
