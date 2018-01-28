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


$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

unset($_SESSION["ErstellError"]);

/*if(isset($_SESSION["IDbearbeiten"]))
	{
	$BenutzerID = $_SESSION["IDbearbeiten"];
	$header = "users.php";
	}
else
{*/
	//$header = "profil.php";
$BenutzerID = $_SESSION["BenutzerID"];
//	}
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$Ausbildung = $_POST["ausbildung"];
$UPDATE = "UPDATE Benutzer SET Ausbildung='$Ausbildung' WHERE BenutzerID ='$BenutzerID'";
mysql_query($UPDATE);

$Erfahrung = $_POST["erfahrung"];
$UPDATE = "UPDATE Benutzer SET Erfahrung='$Erfahrung' WHERE BenutzerID ='$BenutzerID'";
mysql_query($UPDATE);

$TelefonNr = $_POST["tele"];
$UPDATE = "UPDATE Benutzer SET TelefonNr='$TelefonNr' WHERE BenutzerID ='$BenutzerID'";
mysql_query($UPDATE);

/*if(isset($_SESSION["IDbearbeiten"]))
	{
	$Name = $_POST["Name"];
	$UPDATE = "UPDATE Benutzer SET Name='$Name' WHERE BenutzerID ='$BenutzerID'";
	mysql_query($UPDATE);
	}*/
	
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
			//$_SESSION["NotUser"] = $_SESSION["IDbearbeiten"];
			$_SESSION["ErstellError"] = $ErstellError;
			header("location:profilbearbeiten.php");
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
			//$_SESSION["NotUser"] = $_SESSION["IDbearbeiten"];
			$_SESSION["ErstellError"] = $ErstellError;
			header("location:profilbearbeiten.php");
			exit;	
			}
		}
	elseif(password_verify($_POST["AltPasswort"],$ORIGINALPasswort) == FALSE AND $_POST["AltPasswort"] != "")
		{
		$ErstellError = "Falsches Passwort eingegeben";
		//$_SESSION["NotUser"] = $_SESSION["IDbearbeiten"];
		$_SESSION["ErstellError"] = $ErstellError;
		header("location:profilbearbeiten.php");
		exit;	
		}
	}


mysql_close($verbindung);


//unset($_SESSION["IDbearbeiten"]);
header("location: profil.php");
exit;
?>
