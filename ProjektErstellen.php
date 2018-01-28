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
	
$abfrage = "SELECT BenutzerID FROM Benutzer;";
$BenutzerIDs = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
global $BenutzerIDsArray;
$BenutzerIDsArray = array("");

while($row = mysql_fetch_assoc($BenutzerIDs))
	{ 
	$Buffer = "$row[BenutzerID]";
	array_push($BenutzerIDsArray,$Buffer);
	}

$_SESSION["BenutzerIDsArray"] = $BenutzerIDsArray;
	
$abfrage = "SELECT BenutzerID FROM Benutzer;";
$ProjektAnzahl = mysql_query($abfrage);
$BenutzerRows  = mysql_num_rows($ProjektAnzahl);

$_SESSION["BenutzerRows"] = $BenutzerRows;
	
	
	
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
    <link rel="stylesheet" href="sstyle.css">  
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
<div id="table" role="table">
</br></br></br></br>
<div id="rechts" role="rechts" <!--style="overflow: scroll;-->">


</br>
<table border cellpadding="100">

	<tr>
		<th>Name:</th>
		<td>MA:</td>
		<td>PL:</td>
	</tr>

</table>


<form method="post" action="projekteinfuegen.php">
<?php
for ($D = 0; $D < $BenutzerRows; $D++ )
	{	
	$ID  = $D + 1;
	$abfrage = "SELECT Name,Vorname FROM Benutzer WHERE BenutzerID ='$BenutzerIDsArray[$ID]';";
	$Mitarbeiter = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
	while($row = mysql_fetch_assoc($Mitarbeiter))
		{
		$Name = "$row[Name]";
		$Vorname = "$row[Vorname]";
		} 
	
	echo "</br>
	<table border cellpadding='100'>
	
		<tr>
			<th>$Name $Vorname</th>
			<td><label><input type='checkbox' name=Arbeiter[] value='$BenutzerIDsArray[$ID]'/></label></td>
			<td><label><input type='checkbox' name=Leiter[] value='$BenutzerIDsArray[$ID]'/></label></td>
		</tr>

	</table>";
	}


?>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</div>
Name: </br><label><input name="ProjektName" type="text" value = <?php echo $_SESSION['ProjektErstellName']; ?>></label>
</br></br>
<label>Beschreibung:</br><textarea name="projektbeschreibung" cols="30" rows="5"><?php echo $_SESSION['ProjektErstellBeschreibung']; ?></textarea></label>
<br/><br/>
Von: </br><label><input name="ProjektVon" type="date"></label> <!-- Funktioniert nur in HTML5 unterstützenden Browers eg. Chrome,Opera,Safari, in Firefox wird es NICHT richtig interpretiert (in Windows Explorer funktioniert eh nichts da klärt sich dass ja von selber, vlt. aber in SPARTA?) -->
<br/>
Bis: </br><label><input name="ProjektBis" type="date"></label><!-- Funktioniert nur in HTML5 unterstützenden Browers eg. Chrome,Opera,Safari, in Firefox wird es NICHT richtig interpretiert (in Windows Explorer funktioniert eh nichts da klärt sich dass ja von selber, vlt. aber in SPARTA?) -->



</br></br></br></br></br></br></br>
<input type="submit" value="Erstellen"/>

<?php
if (isset($_SESSION["ErstellError"]))
	{
	$ErstellError = $_SESSION["ErstellError"];
	echo "<h5><font color='#FF0000'>$ErstellError</font><br></h5>";
	unset($_SESSION["ErstellError"]);
	}
?>
</form>


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
	
	
	
	
	
