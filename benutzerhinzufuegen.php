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
$BenutzerID = $_SESSION["BenutzerID"];

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
##Hauptskript:
$NeuBenutzerName		= "";
$NeuBenutzerVorname		= "";
$NeuBenutzerTelefonNr	= "";
$NeuBenutzerAusbildung	= "";
$NeuBenutzerErfahrung	= "";
$NeuBenutzerEmailAdr	= "";

	
if (isset($_SESSION["ErstellError"]))//Falls man durch einene error zurückkommt
	{
	#Aus dem speicher auslesen
	$NeuBenutzerName 		= $_SESSION["NeuBenutzerName"];
	$NeuBenutzerVorname		= $_SESSION["NeuBenutzerVorname"];
	$NeuBenutzerEmailAdr	= $_SESSION["NeuBenutzerEmailAdr"];
	$NeuBenutzerTelefonNr	= $_SESSION["NeuBenutzerTelefonNr"];
	$NeuBenutzerAusbildung	= $_SESSION["NeuBenutzerAusbildung"];
	$NeuBenutzerErfahrung	= $_SESSION["NeuBenutzerErfahrung"];
	}	
?>

<!DOCTYPE HTML>
<head>

  <title>Projektmanagementsystem</title>

  <meta name="author" content="Daveundnoppaprograming">
  <meta name="description" content="Projektmanagementsystem">
  <meta name="keywords" content="Managementsoftware für großere Firmen">
  <meta name="robots" content="all">
  <meta http-equiv="content-type" content="text/html; charset=windows-1252">
  
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="tstyle.css">  
<style>
#top {
	text-align: left;
}
  </style>
  
 </head>
 <body style="zoom: 95%;">
   <div id="main" role="main">
     <hgroup>

    <header>
     <h1><img src="logo.jpg" width=200px height=auto>     <a style="text-decoration: none;" href="Projektsystem.php"><font color="#000"><font color="#666"><span style="font-size:1.9em">P</span></font>ro<font color="#666">M</font>anager 2.0</font></a></h1>
    </header>

    <nav>
     <ul>
      <li><a href="profil.php">Profil <img src="manchen.png" width=auto height=20px ></a></li>
	  <li><a href="projects.php">Projekte</a></li>
	  <li><a href="raume.php">Raum</a></li>
	  <li><a href="users.php"><font color="#666">Benutzer</font></a></li>&nbsp;
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>


		
   </hgroup>
  
   </div>
   
<div id="ersteller" role="ersteller">
<form method="post" action="benutzereinfuegen.php">   
</br></br>
Name: </br><label><input name="NeuBenutzerName" type="text" value = <?php echo $NeuBenutzerName; ?>></label>
</br></br>
Vorname: </br><label><input name="NeuBenutzerVorname" type="text" value = <?php echo $NeuBenutzerVorname; ?>></label>
</br></br>
EmailAdr: </br><label><input name="NeuBenutzerEmailAdr" type="text" value = <?php echo $NeuBenutzerEmailAdr; ?>></label>
</br></br>
TelefonNr: </br><label><input name="NeuBenutzerTelefonNr" type="text" value = <?php echo $NeuBenutzerTelefonNr; ?>></label>
</br></br>
Ausbildung: </br><label><input name="NeuBenutzerAusbildung" type="text" value = <?php echo $NeuBenutzerAusbildung; ?>></label>
</br></br>
<label>Erfahrung:</br><textarea name="NeuBenutzerErfahrung" cols="30" rows="5"><?php echo $NeuBenutzerErfahrung;?></textarea></label>
</br></br>
GebDatum: </br><label><input name="NeuBenutzerGebDatum" type="date" ></label><!-- Funktioniert nur in HTML5 unterstützenden Browers eg. Chrome,Opera,Safari, in Firefox wird es NICHT richtig interpretiert (in Windows Explorer funktioniert eh nichts da klärt sich dass ja von selber, vlt. aber in SPARTA?) -->
<br/></br>
Passwort: </br><label><input name="NeuBenutzerPasswort" type="password"></label>
</br></br>
Passwort wiederholen: </br><label><input name="NeuBenutzerPasswortCheck" type="password"></label>
</br></br>
Admin:<label><input type='checkbox' name=Admin[] value="1"/></label></td>
<br/></br><br/>
<input type="submit" value="Hinzuf&uuml;gen"/>
</form>

<?php
#Error report:
if (isset($_SESSION["ErstellError"]))
	{
	$ErstellError = $_SESSION["ErstellError"];
	echo "<h5><font color='#FF0000'>$ErstellError</font><br></h5>";
	unset($_SESSION["ErstellError"]);
	}
?>
</div>
<p>
</br></br></br></br></br></br></br></br>


</p>
</br>
<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
