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
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$ProjektID = $_SESSION["InfoID"];
	
$ProjektUpdateName = $_POST["ProjektName"];
$ProjektUpdateBeschreibung = $_POST["projektbeschreibung"];

$_SESSION["ProjektUpdateName"] = $ProjektUpdateName;
$_SESSION["ProjektUpdateBeschreibung"] = $ProjektUpdateBeschreibung;


if (isset($_POST["Leiter"][0])== false)
	{
	$ErstellError = "Kein Leiter ausgewählt";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:projektbearbeiten.php");
	exit;	
	}
else
	{
	$LeiterIDarray = $_POST["Leiter"];
	$LeiterCount = count ($LeiterIDarray);
	if($LeiterCount >1)
		{
		$ErstellError = "Mehr als 1 Leiter ausgewählt";
		$_SESSION["ErstellError"] = $ErstellError;
		header("location:projektbearbeiten.php");
		exit;		
		}
	}	

if (isset($_POST["Arbeiter"][0])== false)
	{
	$ErstellError = "Kein Arbeiter ausgewählt";
	$_SESSION["ErstellError"] = $ErstellError;
	header("location:projektbearbeiten.php");
	exit;		
	}
else
	{
	$ArbeiterIDarray = $_POST["Arbeiter"];
	$ArbeiterCount = count ($ArbeiterIDarray);
	}
	
$abfrage = "SELECT Name,Beschreibung,ErstelltAm FROM Projekt WHERE ProjektID ='$ProjektID';";
$ProjektInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($ProjektInfo))
	{
	$ProjektName = "$row[Name]";
	$ProjektBeschreibung= "$row[Beschreibung]";
	}
	
if($ProjektName != $ProjektUpdateName)
	{
	if(	$ProjektUpdateName == " " OR $ProjektUpdateName == "")
		{
		$_SESSION["ErstellError"] = "Keinen Namen eingegeben";
		header("location:projektbearbeiten.php");
		exit;		
		}
	else
		{
		$abfrage = "SELECT Name FROM Projekt";
		$ProjektNamen = mysql_query($abfrage);
		while($row = mysql_fetch_assoc($ProjektNamen))
			{
			$ProjektNameArray = array("");		
			$Buffer = "$row[Name]";
			array_push($ProjektNameArray,$Buffer);
			}
		if(in_array($ProjektUpdateName,$ProjektNameArray))
			{
			$_SESSION["ErstellError"] = "Name bereits vergeben";
			header("location:projektbearbeiten.php");
			exit;	
			}
		}
	}
	
if($ProjektUpdateBeschreibung != $ProjektBeschreibung)
	{
	if ($ProjektUpdateBeschreibung == "" OR $ProjektUpdateBeschreibung == " ")
		{
		$_SESSION["ErstellError"] = "Keine Beschreibung eingegeben";
		header("location:projektbearbeiten.php");
		exit;		
		}
	}
	
$abfrage = "UPDATE `Projekt` SET `Name`='$ProjektUpdateName',`Beschreibung`='$ProjektUpdateBeschreibung' WHERE ProjektID ='$ProjektID';";
$ProjektNamen = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());

$abfrage = "DELETE FROM `ProjektLeiter` WHERE ProjektID = $ProjektID;";
$ProjektNamen = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());

$abfrage = "INSERT INTO `ProjektLeiter`(`BenutzerID`, `ProjektID`) VALUES ('$LeiterIDarray[0]','$ProjektID')";
$ProjektNamen = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());

for ($D = 0; $D < $ArbeiterCount; $D++ )
	{	
	$abfrage = "DELETE FROM `ProjektMitarbeiter` WHERE ProjektID = '$ProjektID';";
	mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	}

for ($D = 0; $D < $ArbeiterCount; $D++ )
	{	
	$abfrage = "INSERT INTO `ProjektMitarbeiter` (`BenutzerID`,`ProjektID`) VALUES('$ArbeiterIDarray[$D]','$ProjektID');";
	mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	}

mysql_close($verbindung);

$logFile = fopen("log.txt", "a");
if($ProjektName != $ProjektUpdateName){$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | edited project | \"".$ProjektUpdateName."\" | OLD NAME: \"".$ProjektName."\" \r\n";}
else{$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | edited project | \"".$ProjektUpdateName."\" \r\n";}
fwrite($logFile, $logMessage);
fclose($logFile);

unset($_SESSION["ProjektUpdateID"]);
header("location:projects.php");
exit;

?>
