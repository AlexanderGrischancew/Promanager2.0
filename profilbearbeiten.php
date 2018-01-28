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

/*if(isset($_POST["AdminBenutzerBearbeitenID"]))
	{
	$BenutzerID = $_POST["AdminBenutzerBearbeitenID"];
	$_SESSION["IDbearbeiten"] = $BenutzerID;
	}
if(isset($_SESSION["NotUser"]))
	{
	$BenutzerID = $_SESSION["NotUser"];
	$_SESSION["IDbearbeiten"] = $BenutzerID;
	}*/

	
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$abfrage = "SELECT Name,Vorname,TelefonNr,Ausbildung,Erfahrung,GebDatum,Passwort FROM Benutzer WHERE BenutzerID ='$BenutzerID';";
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
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>

	
   </hgroup>
  
   </div> 
<div id="table" role="table">
</br></br></br></br>


<form method="post" action="Update.php">   
<table border cellpadding="100">

	<tr>
		<td>Name:</td>
		<!-- <td><?php //if(isset($_SESSION["NotUser"]) OR isset($_POST["AdminBenutzerBearbeitenID"])){echo "<h6><label></br><input name='Name' type='text' value='$Name'></br></br></label></h6>";}else{echo "<h5>$Name</h5>";} ?></td> -->
		<td><?php echo "<h5>$Name</h5>"; ?></td>
	</tr>

	<tr>
		<td>Vorname:</td>
		<td><?php echo "<h5>$Vorname</h5>"; ?></td>
	</tr>

	<tr>
		<td>TelefonNr:</td>
		<td><h6><label></br><input name="tele" type="text" value=<?php echo "$TelefonNr"; ?>></br></br></label></h6></td>
	</tr>

	<tr>
		<td>Ausbildung:&emsp;&emsp;&emsp;</td>
		<td><h6><label></br><input name="ausbildung" type="text" value = <?php echo "$Ausbildung"; ?>></br></br></label></h6></td>
	</tr>

	<tr>
		<td>Erfahrung:</td>
		<td><h6><label></br><input name="erfahrung" type="text" value=<?php echo "$Erfahrung"; ?>></br></br></label></h6></td>
	</tr>

	<tr>
		<td>GebDatum:</td>
		<td><?php echo "<h5>$GebDatum</h5>"; ?> &emsp;&emsp;&emsp; </td>
	</tr>
	
	<tr>
		<td>Passwort &auml;ndern:</td>
		<tr><td><h6>Altes Passwort:</h6><h6><label></br><input name="AltPasswort" type="password"></br></br></label></h6></td></tr>
		<tr><td><h6>Neues Passwort:</h6><h6><label></br><input name="NeuPasswort" type="password"></br></br></label></h6></th></tr>
		<tr><td><h6>Neues Passwort best&auml;tigen:</h6><h6><label></br><input name="NeuPasswortCheck" type="password"></br></br></label></h6></td></tr>
	</tr>

</table>
</br>
<input type="submit" value="&Auml;ndern"/>
</form>

<?php
if (isset($_SESSION["ErstellError"]))
	{
	$ErstellError = $_SESSION["ErstellError"];
	echo "<h5><font color='#FF0000'>$ErstellError</font><br></h5>";
	unset($_SESSION["ErstellError"]);
	}
?>


</br>
</br>

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

<?php
//unset($_SESSION["NotUser"]);
//unset($_POST["AdminBenutzerBearbeitenID"]);
?>
