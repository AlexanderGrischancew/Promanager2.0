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
	
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];
$Admin = $_SESSION["Admin"];
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
//$ProjektID = $_POST['BenutzerProjektID'];
$BenutzerID = $_POST['BenutzerIDProjekt'];
$_SESSION['BenutzerIDProjekt'] = $BenutzerID;


//if ($ProjektID == -1)
	//{
$DELETE = "DELETE FROM projektmitarbeiter WHERE BenutzerID ='$BenutzerID'";	
	//}
/*else
	{
	$DELETE = "DELETE FROM projektmitarbeiter WHERE BenutzerID ='$BenutzerID' AND ProjektID = '$ProjektID'";	
	}*/
	
mysql_query($DELETE);

header("location:loeschenbestaetigen.php");
exit;

?>
