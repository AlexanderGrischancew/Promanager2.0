<?php

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

if (isset($_SESSION['BenutzerIDProjekt']))
	{
	$BenutzerIDLoeschen = $_SESSION['BenutzerIDProjekt'];
	unset($_SESSION['BenutzerIDProjekt']);
	}
else
	{
	$BenutzerIDLoeschen = $_POST["BenutzerloeschenID"];
	}

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$abfrage = "SELECT ProjektID FROM ProjektMitarbeiter WHERE BenutzerID = '$BenutzerIDLoeschen'";//Überprüfen ob der benutzer sich in einem Projekt befindet
$BenutzerProjekte = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());

while($row = mysql_fetch_assoc($BenutzerProjekte))
	{
	$ProjektID  = "$row[ProjektID]";
	}

if (isset($ProjektID))
	{
	$_SESSION["ErstellError"] = "Der Benutzer befindet sich in einem Projekt!";
	//$_SESSION["BenutzerProjekt"] = $ProjektID;
	$_SESSION["BenutzerIDProjekt"] = $BenutzerIDLoeschen;
	header("location:users.php");
	exit;
	}//""
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
    <link rel="stylesheet" href="pstyle.css">  
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
      <li><a href="profil.php"><font color="#666">Profil </font><img src="manchen.png" width=auto height=20px ></a></li>
	  <li><a href="projects.php">Projekte</a></li>
	  <li><a href="raume.php">Raum</a></li>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='users.php'>Benutzer</a></li>&nbsp;";} ?>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>

	
   </hgroup>
  
   </div> 

<form method="post" action="benutzerloeschen.php">
</br>
<?php 
	
echo "<input type='hidden' name='BenutzerloeschenID' value='$BenutzerIDLoeschen'></input>";
?>
<h2>Sind Sie sicher dass Sie den Benutzer l&ouml;schen wollen?</h2>
<input type="submit" style='BACKGROUND-COLOR: #ff0000' value="L&ouml;schen Best&auml;tigen"/>
</form>

<form method="post" action="users.php">
</br>
<input type="submit" style='BACKGROUND-COLOR: #37A32B' value="Abbrechen"/>
</form>
</div>
   
   </br>

<p>
</br></br></br></br></br></br></br></br>



</p>
</br>
<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
