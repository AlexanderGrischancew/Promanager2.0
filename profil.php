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
	
$abfrage = "SELECT Name,Vorname,TelefonNr,Ausbildung,Erfahrung,GebDatum FROM Benutzer WHERE BenutzerID ='$BenutzerID';";//User parameter aus SQL
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
	}

mysql_close($verbindung);
	
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
   
<table id="spalten" role="spalten" border="0">
<tr>
<td id="transparent" role="transparent">
<div id="spalte1" role="spalte1">
</br></br></br></br>
<table id="profiltable" role="profiltable" border cellpadding="100">

	<tr>
		<td id="zellenfarbe" role="zellenfarbe">Name:</td>
		<td id="zellenfarbe2" role="zellenfarbe2"><?php echo "<h5>$Name</h5>"; ?></td>
	</tr>

	<tr>
		<td id="zellenfarbe" role="zellenfarbe">Vorname:</td>
		<td id="zellenfarbe2" role="zellenfarbe2"><?php echo "<h5>$Vorname</h5>"; ?></td>
	</tr>

	<tr>
		<td id="zellenfarbe" role="zellenfarbe">TelefonNr:</td>
		<td id="zellenfarbe2" role="zellenfarbe2"><?php echo "<h5>$TelefonNr</h5>"; ?></td>
	</tr>

	<tr>
		<td id="zellenfarbe" role="zellenfarbe">Ausbildung:</td>
		<td id="zellenfarbe2" role="zellenfarbe2"><?php echo "<h5>$Ausbildung</h5>"; ?></td>
	</tr>

	<tr>
		<td id="zellenfarbe" role="zellenfarbe">Erfahrung:</td>
		<td id="zellenfarbe2" role="zellenfarbe2"><?php echo "<h5>$Erfahrung</h5>"; ?></td>
	</tr>

	<tr>
		<td id="zellenfarbe" role="zellenfarbe">GebDatum:</td>
		<td id="zellenfarbe2" role="zellenfarbe2"><?php echo "<h5>$GebDatum</h5>"; ?> &emsp;&emsp;&emsp; </td>
	</tr>
<?php
/*if(isset($_SESSION["NotUser"]))//Falls man als Admin bearbeitet
	{
	if ($_SESSION["Admin"] == TRUE)
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
	}
*/
?>

</table>

<form method="post" action="profilbearbeiten.php">
</br>
<!--<input type="submit" value="Bearbeiten"/>-->
<input type="image" src="bearbeiten.png" width=170px height=auto>
</form>
</div>
</td>
<td id="transparent" role="transparent">
<div id="spalte2" role="spalte2">
<img src="user.png" width=55% height=auto>
</div> 
</td>
</tr>
</table>
   </br>

<p>

</p>

<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
