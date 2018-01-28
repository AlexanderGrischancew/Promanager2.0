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

$AdminBenutzerInfoID = $_POST['AdminBenutzerInfoID'];
$BenutzerID = $AdminBenutzerInfoID;

	
	
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
	
$abfrage = "SELECT Name,Vorname,TelefonNr,Ausbildung,Erfahrung,GebDatum,Admin FROM Benutzer WHERE BenutzerID ='$BenutzerID';";//User parameter aus SQL
$BenutzerInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
	
while($row = mysql_fetch_assoc($BenutzerInfo))
	{
	$Name = "$row[Name]";
	$Vorname = "$row[Vorname]";
	$TelefonNr = "$row[TelefonNr]";
	$Ausbildung = "$row[Ausbildung]";
	$Erfahrung = "$row[Erfahrung]";
	$GebDatum = "$row[GebDatum]";
	$Admin = "$row[Admin]";
	}

mysql_close($verbindung);
	
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
      <li><a href="profil.php">Profil<img src="manchen.png" width=auto height=20px ></a></li>
	  <li><a href="projects.php">Projekte</a></li>
	  <li><a href="raume.php">Raum</a></li>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='users.php'><font color='#666'>Benutzer</font></a></li>&nbsp;";} ?>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>

	
   </hgroup>
  
   </div> 
<div id="table" role="table">
</br></br></br></br>
   
<table border cellpadding="100">

	<tr>
		<td>Name:</td>
		<td><?php echo "<h5>$Name</h5>"; ?></td>
	</tr>

	<tr>
		<td>Vorname:</td>
		<td><?php echo "<h5>$Vorname</h5>"; ?></td>
	</tr>

	<tr>
		<td>TelefonNr:</td>
		<td><?php echo "<h5>$TelefonNr</h5>"; ?></td>
	</tr>

	<tr>
		<td>Ausbildung:&emsp;&emsp;&emsp;</td>
		<td><?php echo "<h5>$Ausbildung</h5>"; ?></td>
	</tr>

	<tr>
		<td>Erfahrung:</td>
		<td><?php echo "<h5>$Erfahrung</h5>"; ?></td>
	</tr>

	<tr>
		<td>GebDatum:</td>
		<td><?php echo "<h5>$GebDatum</h5>"; ?> &emsp;&emsp;&emsp; </td>
	</tr>
<?php

	
if ($Admin == 1)
	{
	$AdminString = "<h5><font color='#37A32B'>Admin</font></h5>";
	}
else
	{
	$AdminString = "<h5><font color='#FF0000'>Kein Admin</font></h5>";
	}
echo "<tr>
		<td>Admin:</td>
		<td><?php echo '$AdminString&emsp;&emsp;&emsp; </td>
	</tr>";
	


echo"</table>

<form method='post' action='profilbearbeitenAdmin.php'>
</br>
<input type='submit' value='Bearbeiten'/>
 <input type='hidden' name='AdminBenutzerInfoID' value='$BenutzerID'></input>
</form> 

<form method='post' action='users.php'>
</br>
<input type='submit' value='Zur&uuml;ck'/>
</form> ";?>
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
