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
	
//$ErstellError = "";
	
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

$ProjektErstellName = $_POST["ProjektName"]; 
$ProjektErstellBeschreibung = $_POST["projektbeschreibung"];

$_SESSION["ProjektErstellName"] = $ProjektErstellName;
$_SESSION["ProjektErstellBeschreibung"] = $ProjektErstellBeschreibung;

$BenutzerRows = $_SESSION["BenutzerRows"];
	
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());


$Von = explode("-",$_POST["ProjektVon"]);
$Bis = explode("-",$_POST["ProjektBis"]);

if($Von[0] == "")
	{
	$ErstellError = "Kein Anfangs-Datum eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:ProjektErstellen.php");
	exit;		
	}
else
	{
	$VonString = (string)$Von[0]."-".(string)$Von[1]."-".(string)$Von[2];
	}
	
if($Bis[0] == "")
	{
	$ErstellError = "Kein End-Datum eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:ProjektErstellen.php");
	exit;		
	}
else
	{
	$BisString = (string)$Bis[0]."-".(string)$Bis[1]."-".(string)$Bis[2];		
	}
	
if($ProjektErstellName == "" OR $ProjektErstellName == " ")
	{
	$ErstellError = "Kein Name eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:ProjektErstellen.php");
	exit;	
	}

if($ProjektErstellBeschreibung == "" OR $ProjektErstellBeschreibung == " ")
	{
	$ErstellError = "Keine Beschreibung eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:ProjektErstellen.php");
	exit;	
	}

if (isset($_POST["Leiter"][0])== false)
	{
	$ErstellError = "Kein Leiter ausgew&auml;hlt";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:ProjektErstellen.php");
	exit;	
	}
else
	{
	$LeiterIDarray = $_POST["Leiter"];
	$LeiterCount = count ($LeiterIDarray);
	if($LeiterCount >1)
		{
		$ErstellError = "Mehr als 1 Leiter ausgew&auml;hlt";
		$_SESSION["ErstellError"] = $ErstellError;
		header("location:ProjektErstellen.php");
		exit;		
		}
	}	

if (isset($_POST["Arbeiter"][0])== false)
	{
	$ErstellError = "Kein Arbeiter ausgew&auml;hlt";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:ProjektErstellen.php");
	exit;		
	}
else
	{
	$ArbeiterIDarray = $_POST["Arbeiter"];
	$ArbeiterCount = count ($ArbeiterIDarray);
	}

$ProjektNameArray = array("");

$abfrage = "SELECT Name FROM Projekt";
$ProjektAnzahl = mysql_query($abfrage);
while($row = mysql_fetch_assoc($ProjektAnzahl))
	{ 
	$Buffer = "$row[Name]";
	array_push($ProjektNameArray,$Buffer);
	}

if (in_array($ProjektErstellName,$ProjektNameArray))
	{
	$ErstellError = "Name bereits vergeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:ProjektErstellen.php");
	exit;		
	}

$abfrage = "INSERT INTO `Projekt` (`Name`,`Beschreibung`) VALUES('$ProjektErstellName','$ProjektErstellBeschreibung');";
mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());


$abfrage = "SELECT ProjektID FROM Projekt WHERE Name = '$ProjektErstellName'";
$ProjektNameID = mysql_query($abfrage);
while($row = mysql_fetch_assoc($ProjektNameID))
	{ 
	$ProjektErstelltID = "$row[ProjektID]";
	}
	
$abfrage = "INSERT INTO `ProjektLeiter` (`BenutzerID`,`ProjektID`) VALUES('$LeiterIDarray[0]','$ProjektErstelltID');";
mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());


for ($D = 0; $D < $ArbeiterCount; $D++ )
	{	
	
	$abfrage = "INSERT INTO `ProjektMitarbeiter` (`BenutzerID`,`ProjektID`) VALUES('$ArbeiterIDarray[$D]','$ProjektErstelltID');";
	mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	}

$abfrage = "INSERT INTO `Zeitplan` (`Anfang`,`Ende`,`projekt`) VALUES('$VonString','$BisString','$ProjektErstelltID');";
mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());

$logFile = fopen("log.txt", "a");
$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | added project | \"".$ProjektErstellName."\" \r\n";
fwrite($logFile, $logMessage);
fclose($logFile);

header("location:projects.php");
exit;


?>
