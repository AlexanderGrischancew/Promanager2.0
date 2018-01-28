<?php
// VERSION: 2.2 [HASH]

ini_set('session.use_only_cookies', 1);
session_start();

$configs_path = "confs.ini";
if(file_exists($configs_path)) //Schaut ob die conf.ini exestiert
	{
	$configs_array = parse_ini_file($configs_path);
	$host = $configs_array["SqlhostAdress"]; 
	$user = $configs_array["SqlUserroot"];
	$pw   = $configs_array["SqlUserPasswort"];
	$db   = $configs_array["SqlDB"];
	
	$_SESSION["host"] = $host; 
	$_SESSION["user"] = $user;
	$_SESSION["pw"]   = $pw;
	$_SESSION["db"]   = $db;
	
	$verbindung = mysql_connect("$host",$user,$pw)//stellt verbindung zur db her
	OR die("Error: $abfrage <br>".mysql_error());
	
	$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
	$_SESSION["Verbindung"] = $verbindung;//""
	
	}
else
	{
	die("<h3><font color='#FF0000'> Error: '.ini' file not found </h3><h4><br><font color='#000000'> ErrorCode: 1</h4>");//Wenn die datei nicht gefunden wurde
	}

	
//Passwort und Email einlesen
$email 		= $_POST["email"];
$passwort 	= $_POST["pass"];
$_SESSION["BenutzerEmail"] = $email;


if ($email == "") //Email eingegeben ?
	{
	$_SESSION["BenutzerEmail"] = "WRONG";
	mysql_close($verbindung);
	header("location: index.php");
	exit;
	}
$email = mysql_real_escape_string($email);
	
	
$abfrage = "SELECT Admin,Passwort,BenutzerID FROM Benutzer WHERE EmailAdr ='$email';"; //"SOLL" aus SQL auslesen
$BenutzerPasswort = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($BenutzerPasswort))
	{
	$BenutzerID  = "$row[BenutzerID]";
	$Passwort = "$row[Passwort]";
	$Admin = "$row[Admin]";
	}	
	
if ($passwort == "" OR password_verify($passwort,$Passwort) == FALSE) //Passwort eingegeben oder falsch ?
	{
	$_SESSION["Pw"] = "WRONG";
	mysql_close($verbindung);
	header("location: index.php");
	exit;
	}
	
if (password_verify($passwort,$Passwort))//wenn alles stimmt
	{
	session_regenerate_id();
	$logMessage = date('Y-m-d H:i:s')." | ".$email." | logged in\r\n";
	$logFile = fopen("log.txt", "a");
	fwrite($logFile, $logMessage);
	fclose($logFile);
	//file_put_contents("log.txt",$logMessage, FILE_APPEND | LOCK_EX);
	if ($Admin == 1)// Wandelt 0 und 1 in false und true
		{
		$_SESSION["Admin"] = TRUE;
		}
	else
		{
		$_SESSION["Admin"] = FALSE;
		}

	$GeheimerSchlüsssel = "____";
	setcookie('login', $email.','.md5($email.$GeheimerSchlüsssel));
	
	//$log = TRUE;
	//$_SESSION["log"] = $log;
	
	
	$_SESSION["BenutzerID"] = $BenutzerID;
	mysql_close($verbindung);
	header("location: profil.php");
	exit;
	}
?>
