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
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$ProjektID = $_POST["InfoID"];

$abfrage = "SELECT Name,Beschreibung,ErstelltAm FROM Projekt WHERE ProjektID ='$ProjektID';";
$ProjektInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($ProjektInfo))
	{
	$ProjektName = "$row[Name]";
	$Beschreibung = "$row[Beschreibung]";
	$ErstelltAm = "$row[ErstelltAm]";
	}
	
$abfrage = "SELECT Anfang,Ende FROM Zeitplan WHERE Projekt ='$ProjektID';";
$ProjektInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($ProjektInfo))
	{
	$Von = "$row[Anfang]";
	$Bis = "$row[Ende]";
	}
	
$abfrage = "SELECT BenutzerID FROM ProjektLeiter WHERE ProjektID ='$ProjektID';";
$ProjektLeiterAbfarge = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($ProjektLeiterAbfarge))
	{
	$ProjektLeiterID = "$row[BenutzerID]";
	}
	
$abfrage = "SELECT Name,Vorname FROM Benutzer WHERE BenutzerID ='$ProjektLeiterID';";
$ProjektLeiterInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($ProjektLeiterInfo))
	{
	$ProjektLeiterName = "$row[Name]";
	$ProjektLeiterVorname = "$row[Vorname]";
	}
	
$abfrage = "SELECT BenutzerID FROM ProjektMitarbeiter WHERE ProjektID = '$ProjektID';";
$MitarbeiterAnzahl = mysql_query($abfrage);
$Anzahl  = mysql_num_rows($MitarbeiterAnzahl);


$abfrage = "SELECT BenutzerID FROM ProjektMitarbeiter WHERE ProjektID ='$ProjektID';";
$MitarbeiterID = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
global $MitarbeiterIDs;
$MitarbeiterIDs = array("");

while($row = mysql_fetch_assoc($MitarbeiterID))
	{ 
	$Buffer = "$row[BenutzerID]";
	array_push($MitarbeiterIDs,$Buffer);
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
    <link rel="stylesheet" href="rstyle.css">  
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
	  <li><a href="projects.php"><font color="#666">Projekte</font></a></li>
	  <li><a href="raume.php">Raum</a></li>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='users.php'>Benutzer</a></li>&nbsp;";} ?>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>


		
   </hgroup>
  
   </div>
   
 </br></br></br>
<?php 
echo"<h7><b><font color='#0000BF'>$ProjektName</font></b></br></br></h7>";

echo "<div id='proinftable' role='proinftable'>";

echo "<table border cellpadding='100'>";

	echo "<tr>
			 <td><?php echo '<h6><u>Projektleiter</u></br></br></h6>
			 <h6>$ProjektLeiterName</br></h6>
			 <h6>$ProjektLeiterVorname</br></h6></td>
		</tr>";
		
echo "</table>";
echo "</div>";

echo "<div id='proinftable' role='proinftable'>";   
echo "</br>";
   
echo "<table border cellpadding='100'>";

for ($D = 0; $D < $Anzahl; $D++ )
	{	
	$ID  = $D + 1;
	$abfrage = "SELECT Name,Vorname FROM Benutzer WHERE BenutzerID ='$MitarbeiterIDs[$ID]';";
	$Mitarbeiter = mysql_query($abfrage)
		OR die("Error: $abfrage <br>".mysql_error());
	
	while($row = mysql_fetch_assoc($Mitarbeiter))
		{
		$MitarbeiterName = "$row[Name]";
		$MitarbeiterVorname = "$row[Vorname]";
		}



	echo "<tr>
			 <td><?php echo '<h5>$MitarbeiterName</br>$MitarbeiterVorname</h5></td>
		</tr>";
		
	}

echo "</table>";

echo "</div>";


echo "<div id='proinftable' role='proinftable'>";
echo "<table border cellpadding='100'>";

echo "<tr>
			 <td><?php echo '<h5><b>Anfang:</b></h5><h5>$Von</h5></td>
		</tr>";
echo "<tr>
			 <td><?php echo '<h5><b>Ende:</b></h5><h5>$Bis</h5></td>
		</tr>";
		
echo "</table>";

echo "</div>";

mysql_close($verbindung);
?>  
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
