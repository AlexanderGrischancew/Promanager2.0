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

$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

$Name = $_POST["RaumNameUpdate"];
$Beschreibung = $_POST["RaumBeschreibungUpdate"];

$RaumID = $_SESSION["RaumUpdateID"];

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$RaumNameArray = array("");

$abfrage = "SELECT Name FROM Raeume";
$mysqlquery = mysql_query($abfrage);
while($row = mysql_fetch_assoc($mysqlquery))
	{ 
	$Buffer = "$row[Name]";
	array_push($RaumNameArray,$Buffer);
	}
	
$abfrage = "SELECT Name FROM Raeume WHERE RaumID = '$RaumID';";
$mysqlquery = mysql_query($abfrage);
while($row = mysql_fetch_assoc($mysqlquery))
	{ 
	$RaumNameAlt = "$row[Name]";
	}
	

if (in_array($Name,$RaumNameArray) AND $Name != $RaumNameAlt)
	{
	$ErstellError = "Name bereits vergeben!";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:raumerstellen.php");
	exit;		
	}
	
$abfrage = "UPDATE `Raeume` SET `Name`='$Name',`Beschreibung`='$Beschreibung' WHERE RaumID = '$RaumID';";
$BenutzerInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
$logFile = fopen("log.txt", "a");
if($Name != $RaumNameAlt){$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | edited room | \"".$Name."\" | OLD NAME: \"".$RaumNameAlt."]\" \r\n";}
else{$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | edited room | \"".$Name."\" \r\n";}
fwrite($logFile, $logMessage);
fclose($logFile);
	
header("location:raume.php");
exit;
	
?>
