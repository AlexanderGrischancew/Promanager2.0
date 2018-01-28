<?php
// VERSION: 2 
ini_set('session.use_only_cookies', 1);
session_start();
	
if (isset($_COOKIE['login'])) 
	{
	$GeheimerSchlÃ¼sssel = "____";
	list($c_username, $cookie_hash) = split(',',$_COOKIE['login']);
	if (md5($c_username.$GeheimerSchlÃ¼sssel) != $cookie_hash)		
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

$BenutzerID = $_SESSION["BenutzerID"];
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
//unset($_SESSION["NotUser"]);

//if(isset($_POST["AdminBenutzerInfoID"]))
//	{
//	$BenutzerID = $_POST["AdminBenutzerInfoID"];
//	$_SESSION["NotUser"] = $BenutzerID;
//	unset($_POST["AdminBenutzerInfoID"]);
//	}
//else
	//{
//	   $BenutzerID = $_SESSION["BenutzerID"];
	//}
	
?>

<!DOCTYPE HTML>
<head>

  <title>Projektmanagementsystem</title>

  <meta name="author" content="Xubuntu" >
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
      <li><a href="profil.php">Profil <img src="manchen.png" width=auto height=20px ></a></li>
	  <li><a href="projects.php">Projekte</a></li>
	  <li><a href="raume.php">Raum</a></li>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='users.php'>Benutzer</a></li>&nbsp;";} ?>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
	  
	  </ul>
    </nav>
	</hgroup>
  
   </div> 
<p>
<div id="search" role="search">

</div>
<table id="spalten" role="spalten" border="0">
<tr>
<td id="transparent" role="transparent">
<h2>
<img src="people.jpg" align="left" width=100% height=auto>
</h2>
</td>

<td id="transparent" role="transparent">
<h2>
...Vernetzen</br></br></br>
...Organisieren</br></br></br>
...Managen</br></br></br></br></br>

<b><font color="#000"><font color="#666"><span style="font-size:1.1em">P</span></font>ro<font color="#666">M</font>anager 2.0 ...</font></b>
</h2>
</td>
</tr>
</table>
<!--
<marquee scrollamount="5" scrolldelay="50">
  <b><img src="seilmann.jpg" width=auto height=50px ><span style="font-size:2.0em">D</span>ave und Noppa Programming 2015</b>
</marquee>

Wenn sie diese Seite gefunden haben, m&#xfc;ssen Sie viel Zeit in das Testen unserer Seite investiert haben.</br> Herzlichen Gl&#xfc;ckwunsch.</br></br>PS: Ton Bitte an!!
<script language="JavaScript" type="text/javascript">

// PLAYER VARIABLES

var mp3snd = "";

document.write('<audio autoplay="autoplay">');
document.write('<source src="'+mp3snd+'" type="audio/mpeg">');
document.write('<!--[if lt IE 9]>');
document.write('<bgsound src="'+mp3snd+'" loop="1">');
document.write('<![endif]'

</script>-->


</p>

<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
