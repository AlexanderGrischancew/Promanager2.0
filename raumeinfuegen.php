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
	
$ErstellError = "";
	
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

$ErstellNameRaum = $_POST["ProjektName"];
$ErstellBeschreibungRaum = $_POST["projektbeschreibung"];
$_SESSION["NameSave"] = $ErstellNameRaum;
$_SESSION["BeschreibungSave"] = $ErstellBeschreibungRaum;

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
if($ErstellNameRaum == "" OR $ErstellNameRaum == " ")
	{
	$ErstellError = "Kein Name eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:raumerstellen.php");
	exit;		
	}
	
if($ErstellBeschreibungRaum == "" OR $ErstellBeschreibungRaum == " ")
	{
	$ErstellError = "Keine Beschreibung eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:raumerstellen.php");
	exit;		
	}

$RaumNameArray = array("");

$abfrage = "SELECT Name FROM Raeume";
$mysqlquery = mysql_query($abfrage);
while($row = mysql_fetch_assoc($mysqlquery))
	{ 
	$Buffer = "$row[Name]";
	array_push($RaumNameArray,$Buffer);
	}
	

if (in_array($ErstellNameRaum,$RaumNameArray))
	{
	$ErstellError = "Name bereits vergeben!";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:raumerstellen.php");
	exit;		
	}
	
else
	{
	$abfrage = "INSERT INTO `Raeume`(`Name`, `Beschreibung`) VALUES ('$ErstellNameRaum','$ErstellBeschreibungRaum');";
	mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());	
	}
	
	$logFile = fopen("log.txt", "a");
	$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | added room | ".$ErstellNameRaum." \r\n";
	fwrite($logFile, $logMessage);
	fclose($logFile);
	
header("location:raume.php");
exit;

?>
