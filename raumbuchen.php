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
$Admin = $_SESSION["Admin"];

if(isset($_SESSION["RaumIDbuchen"]))
	{
	$RaumIDbuchen = $_SESSION["RaumIDbuchen"];
	}
else
	{
	$RaumIDbuchen = $_POST["RaumIDbuchen"];
	$_SESSION["RaumIDbuchen"] = $RaumIDbuchen; 
	}
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
	
$ProjektIDLeiter = array("");
$abfrage = "SELECT ProjektID FROM ProjektLeiter WHERE BenutzerID ='$BenutzerID';";
$BID = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($BID))
	{
	$Buffer = "$row[ProjektID]";
	array_push($ProjektIDLeiter,$Buffer);
	}
	
$abfrage = "SELECT ProjektID FROM ProjektMitarbeiter WHERE BenutzerID ='$BenutzerID';";
$BID = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
global $ProjektID;
$ProjektID = array("");

while($row = mysql_fetch_assoc($BID))
	{ 
	$Buffer = "$row[ProjektID]";
if (in_array($Buffer,$ProjektIDLeiter) == false)
	{
	array_push($ProjektID,$Buffer);
	}
	}
	
if ($Admin == TRUE)
	{
	$abfrage = "SELECT ProjektID FROM Projekt;";
	$BID = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());

while($row = mysql_fetch_assoc($BID))
	{ 
	$Buffer = "$row[ProjektID]";
if (in_array($Buffer,$ProjektIDLeiter) == false)
	{
	array_push($ProjektIDLeiter,$Buffer);
	}
	}
	}

	$_SESSION["ProjektID"] = $ProjektID;
	
	

$LeiterRows = count($ProjektIDLeiter);
$LeiterRows = $LeiterRows - 1;


$MitarbieterRows = count($ProjektID);
$MitarbieterRows = $MitarbieterRows - 1;
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
	  <li><a href="projects.php">Projekte</a></li>
	  <li><a href="raume.php"><font color='#666'>Raum</font></a></li>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='users.php'>Benutzer</a></li>&nbsp;";} ?>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>


		
   </hgroup>
  
   </div> 
   
   
   
<form method="post" action="raumeinbuchen.php">
<br/><br/>
</br></br></br></br>

<div id="table" role="table">   
<table border cellpadding=100">

	<tr>
		<td><b>Name:</b></td>
		<td><b>Beschreibung:</b></td>
		<td><b>Buchen:</b></td>
	</tr>

</table>
</div>
<br/>
<div  id="scroll" role="scroll">
<?php 
echo "<div id='inscroll' role='inscroll'>";
echo "<table border cellpadding='100'>";
/*for ($D = 0; $D < $MitarbieterRows; $D++ )
{	
	$ID  = $D + 1;
	$abfrage = "SELECT Name,Beschreibung,ErstelltAm FROM Projekt WHERE ProjektID ='$ProjektID[$ID]';";
	$ProjektInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
	while($row = mysql_fetch_assoc($ProjektInfo))
	{
	$Name = "$row[Name]";
	$Beschreibung = "$row[Beschreibung]";
	$ErstelltAm = "$row[ErstelltAm]";
	}
	

echo "<form method='post' action='ProjektInfo.php'>";   
  


	echo "<tr>
			 <td><?php echo '<h5><b><font color='#6666FF'>$Name</font></b></h5></td>
			 <td><?php echo '<h5>$Beschreibung</h5></td>
			 <td><label><input type='checkbox' name=Projekt[] value='$ProjektID[$ID]'/></label></td>
			 </tr>";
		


}*/


for ($D = 0; $D < $LeiterRows; $D++ )
{	
	$ID  = $D + 1;
	$abfrage = "SELECT Name,Beschreibung,ErstelltAm FROM Projekt WHERE ProjektID ='$ProjektIDLeiter[$ID]';";
	$ProjektInfo = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
	while($row = mysql_fetch_assoc($ProjektInfo))
	{
	$Name = "$row[Name]";
	$Beschreibung = "$row[Beschreibung]";
	$ErstelltAm = "$row[ErstelltAm]";
	}
	

echo "<form method='post' action='ProjektInfo.php'>";   
  


	echo "<tr>
			 <td><?php echo '<h5><b><font color='#0000BF'>$Name</font></b></h5></td>
			 <td><?php echo '<h5>$Beschreibung</h5></td>
			 <td><label><input type='checkbox' name=Projekt[] value='$ProjektIDLeiter[$ID]'/></label></td></tr>";


}

echo "</table>";
echo "</div>";
	mysql_close($verbindung);
?>
</div>

<div id="ersteller" role="ersteller">
</br></br>
<input type="submit" value="Buchen"/>
</br>
<?php
if (isset($_SESSION["ErstellError"]))
	{
	$ErstellError = $_SESSION["ErstellError"];
	echo "<h5><font color='#FF0000'>$ErstellError</font><br></h5>";
	unset($_SESSION["ErstellError"]);
	}
?>
</div>
</div>

<p>
</p>

</br>
<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
