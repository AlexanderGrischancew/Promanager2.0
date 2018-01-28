<?php
// VERSION: 2 [HASH]

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
	
##Hauptskript
#Eingaben Auslesen:	
$NeuBenutzerName 			= $_POST["NeuBenutzerName"];
$NeuBenutzerVorname 		= $_POST["NeuBenutzerVorname"];
$NeuBenutzerEmailAdr		= $_POST["NeuBenutzerEmailAdr"];	
$NeuBenutzerTelefonNr 		= $_POST["NeuBenutzerTelefonNr"];	
$NeuBenutzerAusbildung 		= $_POST["NeuBenutzerAusbildung"];
$NeuBenutzerErfahrung 		= $_POST["NeuBenutzerErfahrung"];
$NeuBenutzerGebDatum 		= explode("-",$_POST["NeuBenutzerGebDatum"]);//Datum auseinander nehmen
$NeuBenutzerPasswort 		= $_POST["NeuBenutzerPasswort"];
$NeuBenutzerPasswortCheck 	= $_POST["NeuBenutzerPasswortCheck"];
//$NeuBenutzerAdmin:
if(isset($_POST["Admin"][0])){$NeuBenutzerAdmin = $_POST["Admin"][0];} else{$NeuBenutzerAdmin = 0;} //Admin?

#Eingaben speichern:
$_SESSION["NeuBenutzerName"] 		= $NeuBenutzerName; 			
$_SESSION["NeuBenutzerVorname"] 	= $NeuBenutzerVorname;
$_SESSION["NeuBenutzerEmailAdr"]	= $NeuBenutzerEmailAdr;		
$_SESSION["NeuBenutzerTelefonNr"] 	= $NeuBenutzerTelefonNr; 			
$_SESSION["NeuBenutzerAusbildung"] 	= $NeuBenutzerAusbildung; 		
$_SESSION["NeuBenutzerErfahrung"] 	= $NeuBenutzerErfahrung;

#Überprüfen ob alles eingegebn wurde
if($NeuBenutzerName == "" OR $NeuBenutzerName == " ")
	{
	$ErstellError = "Kein Name eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;	
	}
	
if($NeuBenutzerVorname == "" OR $NeuBenutzerVorname == " ")
	{
	$ErstellError = "Kein Vorname eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;	
	}
	
if($NeuBenutzerEmailAdr == "" OR $NeuBenutzerEmailAdr == " ")
	{
	$ErstellError = "Keine EmailAdr eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;	
	}
	
if($NeuBenutzerTelefonNr == "" OR $NeuBenutzerTelefonNr == " ")
	{
	$ErstellError = "Keine TelefonNr eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;	
	}

if($NeuBenutzerAusbildung == "" OR $NeuBenutzerAusbildung == " ")
	{
	$ErstellError = "Keine Ausbildung eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;	
	}
	
if($NeuBenutzerPasswort == "" OR $NeuBenutzerPasswort == " " OR $NeuBenutzerPasswortCheck == "" OR $NeuBenutzerPasswortCheck == " ")
	{
	$ErstellError = "Kein Passwort eingegeben oder nicht bestätigt";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;	
	}
elseif($NeuBenutzerPasswort != $NeuBenutzerPasswortCheck) //Passwörter gleich ?
	{
	$ErstellError = "Passwörter stimmt nicht überein";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;		
	}

if($NeuBenutzerGebDatum[0] == "")
	{
	$ErstellError = "Kein GebDatum eingegeben";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:benutzerhinzufuegen.php");
	exit;		
	}
else
	{
	$NeuBenutzerGebDatum = (string)$NeuBenutzerGebDatum[0]."-".(string)$NeuBenutzerGebDatum[1]."-".(string)$NeuBenutzerGebDatum[2]; //Datum SQL konform farmatieren
	}
	
//Passwort Hashen
$NeuBenutzerPasswort = password_hash($NeuBenutzerPasswort, PASSWORD_DEFAULT);
	
$insert = "INSERT INTO `Benutzer`(`Name`, `Vorname`, `EmailAdr`, `TelefonNr`, `Ausbildung`, `Erfahrung`,`Admin`,`GebDatum`,`Passwort`) 
	VALUES ('$NeuBenutzerName','$NeuBenutzerVorname','$NeuBenutzerEmailAdr','$NeuBenutzerTelefonNr','$NeuBenutzerAusbildung','$NeuBenutzerErfahrung'
	,'$NeuBenutzerAdmin','$NeuBenutzerGebDatum','$NeuBenutzerPasswort');";//Benutzer Inserten
mysql_query($insert)
	OR die("Error: $abfrage <br>".mysql_error());
	
$logFile = fopen("log.txt", "a");
$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | added user | ".$NeuBenutzerName.$NeuBenutzerVorname ." \r\n";
fwrite($logFile, $logMessage);
fclose($logFile);
		
header("location:users.php");
exit;
	
?>
