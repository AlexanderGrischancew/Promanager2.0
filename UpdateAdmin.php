<?php
// VERSION: 2.2 [HASHING]

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
	
$AdminBenutzerInfoID = $_POST['AdminBenutzerInfoID'];
$BenutzerID = $AdminBenutzerInfoID;
$_SESSION["AdminBenutzerInfoID"] = $BenutzerID;


$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

unset($_SESSION["ErstellError"]);

	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());

$abfrage = "SELECT Admin FROM Benutzer WHERE BenutzerID ='$BenutzerID';";//User parameter aus SQL
$mysqlquery = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
	
while($row = mysql_fetch_assoc($mysqlquery))
	{
	$OldAdminStatus = "$row[Admin]";
	}
	
$Ausbildung = $_POST["ausbildung"];
$UPDATE = "UPDATE Benutzer SET Ausbildung='$Ausbildung' WHERE BenutzerID ='$BenutzerID'";
mysql_query($UPDATE);

$Erfahrung = $_POST["erfahrung"];
$UPDATE = "UPDATE Benutzer SET Erfahrung='$Erfahrung' WHERE BenutzerID ='$BenutzerID'";
mysql_query($UPDATE);

$TelefonNr = $_POST["tele"];
$UPDATE = "UPDATE Benutzer SET TelefonNr='$TelefonNr' WHERE BenutzerID ='$BenutzerID'";
mysql_query($UPDATE);

if(isset($_POST["Admin"][0])){$Admin = $_POST["Admin"][0];} else{$Admin = 0;} //Admin?
$UPDATE = "UPDATE Benutzer SET Admin='$Admin' WHERE BenutzerID ='$BenutzerID'";
mysql_query($UPDATE);

	
if(isset($_POST["AltPasswort"]) OR isset($_POST["NeuPasswort"]) OR isset($_POST["NeuPasswortCheck"]) AND $_POST["AltPasswort"] != "")
	{
	$CHECK = "SELECT Passwort FROM Benutzer WHERE BenutzerID ='$BenutzerID'";
	$CHECKPw = mysql_query($CHECK); 
	
	while($row = mysql_fetch_assoc($CHECKPw))
		{
		$ORIGINALPasswort = "$row[Passwort]";
		}
	if(password_verify($_POST["AltPasswort"],$ORIGINALPasswort))
		{
		if($_POST["NeuPasswort"] == "" OR $_POST["NeuPasswort"] == " ")
			{
			$ErstellError = "Neues Passwort nicht gültig oder nicht eingegeben";
			$_SESSION["ErstellError"] = $ErstellError;
			header("location:profilbearbeitenAdmin.php");
			exit;	
			}
		elseif($_POST["NeuPasswort"] == $_POST["NeuPasswortCheck"])
			{
			$NeuPasswort = $_POST["NeuPasswort"];
			$NeuPasswortHash = password_hash($NeuPasswort, PASSWORD_DEFAULT);
			$UPDATE = "UPDATE Benutzer SET Passwort='$NeuPasswortHash' WHERE BenutzerID ='$BenutzerID'";
			mysql_query($UPDATE);
			}
		elseif($_POST["NeuPasswort"] != $_POST["NeuPasswortCheck"])
			{
			$ErstellError = "Passwörter stimmen nicht überein";
			$_SESSION["ErstellError"] = $ErstellError;
			header("location:profilbearbeitenAdmin.php");
			exit;	
			}
		}
	elseif(password_verify($_POST["AltPasswort"],$ORIGINALPasswort) == FALSE AND $_POST["AltPasswort"] != "")
		{
		$ErstellError = "Falsches Passwort eingegeben";

		$_SESSION["ErstellError"] = $ErstellError;
		header("location:profilbearbeitenAdmin.php");
		exit;	
		}
	}

$abfrage = "SELECT Name FROM Benutzer WHERE BenutzerID ='$BenutzerID';"; //"SOLL" aus SQL auslesen
$mysqlquery = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($mysqlquery))
	{
	$BenutzerName  = "$row[Name]";
	}	


mysql_close($verbindung);

if($Admin == 1) {$AdminString = "TRUE";}else{$AdminString = "FALSE";}

$logFile = fopen("log.txt", "a");
if($Admin != $OldAdminStatus){$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | edited profile | \"".$BenutzerName."\" | set Admin Staus | ".$AdminString." \r\n";}
else{$logMessage = date('Y-m-d H:i:s')." | ".$_SESSION["BenutzerEmail"]." | edited profile | ".$BenutzerName." \r\n";}
fwrite($logFile, $logMessage);
fclose($logFile);

unset($_SESSION["AdminBenutzerInfoID"]);

header("location: users.php");
exit;
?>
