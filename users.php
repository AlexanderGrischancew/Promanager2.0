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
	
	
$Benutzer = array("");
$abfrage = "SELECT BenutzerID FROM Benutzer;";
$BID = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($BID))
	{
	$Buffer = "$row[BenutzerID]";
	array_push($Benutzer,$Buffer);
	}

$rows = (count($Benutzer)-1);
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
    <link rel="stylesheet" href="userstyle.css">  
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
   
   </br>
  <div id="table" role="table">
</br></br>
   

<?php
echo "<table  id='usertable' role='usertable'  border cellpadding='100'>
	<thead>
	<tr>
		<td id='zellenfarbe' role='zellenfarbe'><b>Name:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Vorname:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>TelefonNr:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Admin:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>L&ouml;schen:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Bearbeiten:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Information:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Administratorstatus:</b></td>
	</tr>
	</thead>";
	
echo "<tbody>";

for ($D = 0; $D < $rows; $D++ )
	{	
	$ID  = $D + 1;

	$abfrage = "SELECT Name,Vorname,TelefonNr,Admin FROM Benutzer WHERE BenutzerID = '$Benutzer[$ID]';";
	$BID = mysql_query($abfrage)
		OR die("Error: $abfrage <br>".mysql_error());
	
	while($row = mysql_fetch_assoc($BID))
		{
		$Name = "$row[Name]";
		$Vorname = "$row[Vorname]";
		$TelefonNr = "$row[TelefonNr]";
		$Admin = "$row[Admin]";
		}
	
	if ($Admin == 1)
		{
		$AdminString = "<h5><font color='#37A32B'>Admin</font></h5>";
		}
	else
		{
		$AdminString = "<h5><font color='#FF0000'>Kein Admin</font></h5>";
		}

	echo "<tr>
			 <td><?php echo '<h5><b>$Name</font></b></h5></td>
			 <td><?php echo '<h5>$Vorname</h5></td>
			 <td><?php echo '<h5>$TelefonNr</h5></td>
			 <td><?php echo $AdminString</td>";
	if($Benutzer[$ID] != $BenutzerID)
		{
		echo"<form method='post' action='loeschenbestaetigen.php'>
			<td><input type='submit' name='Benutzerloeschen' value='L&ouml;schen'></input></td>
				<input type='hidden' name='BenutzerloeschenID' value='$Benutzer[$ID]'></input>
			</form>";
		}
	else
		{
		echo "<td><input type='hidden' name='Benutzerloeschen' value='L&ouml;schen'></input></td>";
		}
	echo "<form method='post' action='profilbearbeitenAdmin.php'>
		<td><input type='submit' name='Benutzerbearbeiten' value='Bearbeiten'></input></td>
		<input type='hidden' name='AdminBenutzerInfoID' value='$Benutzer[$ID]'></input>
		</form>
		<form method='post' action='profilAdmin.php'>
		<td><input type='submit' name='BenutzerInfo' value='Informationen'></input></td>
		<input type='hidden' name='AdminBenutzerInfoID' value='$Benutzer[$ID]'></input>
		</form>";
	if($Admin == 1 AND $Benutzer[$ID] != $BenutzerID)
		{
		echo"
		<form method='post' action='adminrechte.php'>
		<td><input type='submit' style='BACKGROUND-COLOR: #ff0000' name='adminentziehen' value='Admin entfernen &nbsp'></input></td>
		<input type='hidden' name='AdminRechteID' value='$Benutzer[$ID]'></input>
		</form>";
		}
	elseif($Admin == 0 AND $Benutzer[$ID] != $BenutzerID)
		{
		echo"
		<form method='post' action='adminrechte.php'>
		<td><input type='submit' style='BACKGROUND-COLOR: #37A32B' name='adminhinzufuegen' value='Admin hinzuf&uuml;gen'></input></td>
		<input type='hidden' name='AdminRechteID' value='$Benutzer[$ID]'></input>
		</form>";
		}
	else
		{
		echo "<td><input type='hidden' name='Benutzerloeschen' value='L&ouml;schen'></input></td>";
		}
				
	echo"</tr>";
	}
echo "</tbody>";
echo "</table></div>";
?>
<br/>

</div>
<?php
if (isset($_SESSION["ErstellError"]))
	{
	$ErstellError = $_SESSION["ErstellError"];
	unset($_SESSION["ErstellError"]);
	echo "<h5><font color='#FF0000'>$ErstellError</font><br></h5>";
	/*echo "<form method='post' action='benutzerAusProjektEntfernen.php'>
		<input type='submit' value='Benutzer aus Projekt entfernen und löschen'/>";
	$BenutzerProjekt = $_SESSION['BenutzerProjekt'];*/
	$BenutzerIDProjekt = $_SESSION['BenutzerIDProjekt'];
	//unset($_SESSION["BenutzerProjekt"]);
	
	unset($_SESSION['BenutzerIDProjekt']);
	
	/*echo "<input type='hidden' name='BenutzerProjektID' value='$BenutzerProjekt'></input>
		<input type='hidden' name='BenutzerIDProjekt' value='$BenutzerIDProjekt'></input>
		</form>";*/
		
	echo "<input type='hidden' name='BenutzerIDProjekt' value='$BenutzerIDProjekt'></input>
		</form>";
	$AlleProjekte = -1;
	echo "<form method='post' action='benutzerAusProjektEntfernen.php'>
		<input type='submit' value='Benutzer aus allen Projekt entfernen und löschen'/>
		<input type='hidden' name='BenutzerProjektID' value='$AlleProjekte'></input>
		<input type='hidden' name='BenutzerIDProjekt' value='$BenutzerIDProjekt'></input>
		</form>";
	}?>
</br>
<div id='ersteller' role='ersteller'>
<form method='post' action='benutzerhinzufuegen.php'>
<input type='submit' name='BenutzerInfo' value='Benutzer Hinzuf&uuml;gen'></input>
</form>
</div>
</div>
</br>

	<div id="bottom" role="bottom">
	&copy; #o8 Programming 2015
	</div>
 </body>
 
</html>
