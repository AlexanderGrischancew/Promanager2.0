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
	
//unset($_SESSION["ProjektUpdateID"]);
//$_SESSION["ErstellError"] = "";
$_SESSION["ProjektErstellName"] = "";
$_SESSION["ProjektErstellBeschreibung"] = "";
	
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];
$BenutzerID = $_SESSION["BenutzerID"];
$Admin = $_SESSION["Admin"];
	
$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
	
$ProjektIDLeiter = array("");
$abfrage = "SELECT ProjektID FROM ProjektLeiter WHERE BenutzerID ='$BenutzerID';";
$ProjektLeiterAbfrage = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($ProjektLeiterAbfrage))
	{
	$Buffer = "$row[ProjektID]";
	array_push($ProjektIDLeiter,$Buffer);
	}
	
$abfrage = "SELECT ProjektID FROM ProjektMitarbeiter WHERE BenutzerID ='$BenutzerID';";
$ProjektMitarbeiterAbfrage = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
//global $ProjektID;
$ProjektID = array("");

while($row = mysql_fetch_assoc($ProjektMitarbeiterAbfrage))
	{ 
	$Buffer = "$row[ProjektID]";
	if (in_array($Buffer,$ProjektIDLeiter) == false)
		{
		array_push($ProjektID,$Buffer);
		}
	}
$ProjektIDAdmin = array("");
if ($Admin == TRUE)
	{
	$abfrage = "SELECT ProjektID FROM Projekt;";
	$BID = mysql_query($abfrage)
		OR die("Error: $abfrage <br>".mysql_error());
		
	while($row = mysql_fetch_assoc($BID))
		{ 
		$Buffer = "$row[ProjektID]";
		if (in_array($Buffer,$ProjektID) == false AND in_array($Buffer,$ProjektIDLeiter) == false)
			{
			array_push($ProjektIDAdmin,$Buffer);
			}
		}
	}

$_SESSION["ProjektID"] = $ProjektID;
	
	

$LeiterRows = count($ProjektIDLeiter);
$LeiterRows = $LeiterRows - 1;


$MitarbieterRows = count($ProjektID);
$MitarbieterRows = $MitarbieterRows - 1;

if ($Admin == TRUE)
	{
	$AdminRows = count($ProjektIDAdmin);
	$AdminRows = $AdminRows - 1;
	}




	
	
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
</br></br>
   
<?php 
echo "<table id='projecttable' role='projecttable' border cellpadding='100'>
	<thead>
	<tr>
		<td id='zellenfarbe' role='zellenfarbe'><b>Name:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Beschreibung:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Erstellt am:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Informationen:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Bearbeiten:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>L&ouml;schen:</b></td>
	</tr>
	</thead>";
	
echo "<tbody>";

for ($D = 0; $D < $MitarbieterRows; $D++ )
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
			 <td><?php echo '<h5><b><font color='#FF8000'>$Name</font></b></h5></td>
			 <td style='width:20%; height:60px; vertical-align:top;'><div style='width:100%; height:60px; overflow:scroll;'><?php echo '<h5>$Beschreibung</h5></div></td>
			 <td><?php echo '<h5>$ErstelltAm</h5></td>
			 <td><input type='submit' name='info' value='Informationen'></input></td>
			 <input type='hidden' name='InfoID' value='$ProjektID[$ID]'></input>
			 </form>";
			 if ($Admin == TRUE)
				{
				echo"<form method='post' action='projektbearbeiten.php'>
				<th><input type='submit' name='Bearbeiten' value='Bearbeiten'></input></th>
				<input type='hidden' name='InfoID' value='$ProjektID[$ID]'></input>
				</form>
				<form method='post' action='projektloeschen.php'>
				<th><input type='submit' name='Projektloeschen' value='L&ouml;schen'></input></th>
				<input type='hidden' name='InfoID' value='$ProjektID[$ID]'></input>
				</form>";
				}
	echo"</tr>";
		


}


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
			 <td><?php echo '<h5><b><font color='#0B610B'>$Name</font></b></h5></td>
			 <td style='width:20%; height:60px; vertical-align:top;'><div style='width:100%; height:60px; overflow:scroll;'><?php echo '<h5>$Beschreibung</h5></div></td>
			 <td><?php echo '<h5>$ErstelltAm</h5></td>
			 <td><input type='submit' name='info' value='Informationen'></input></td>
			 <input type='hidden' name='InfoID' value='$ProjektIDLeiter[$ID]'></input>
			 </form>
			 <form method='post' action='projektbearbeiten.php'>
			 <th><input type='submit' name='Bearbeiten' value='Bearbeiten'></input></th>
			 <input type='hidden' name='InfoID' value='$ProjektIDLeiter[$ID]'></input>
			 </form>
			 <form method='post' action='projektloeschen.php'>
			 <th><input type='submit' name='Projektloeschen' value='L&ouml;schen'></input></th>
			 <input type='hidden' name='InfoID' value='$ProjektIDLeiter[$ID]'></input>
			 </form>
		</tr>";
		


	}
	
if ($Admin == TRUE)
	{
	for ($D = 0; $D < $AdminRows; $D++ )
	{	
	$ID  = $D + 1;
	$abfrage = "SELECT Name,Beschreibung,ErstelltAm FROM Projekt WHERE ProjektID ='$ProjektIDAdmin[$ID]';";
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
			 <td><?php echo '<h5><b><font color='#DF013A'>$Name</font></b></h5></td>
			 <td style='width:20%; height:60px; vertical-align:top;'><div style='width:100%; height:60px; overflow:scroll;'><?php echo '<h5>$Beschreibung</h5></div></td>
			 <td><?php echo '<h5>$ErstelltAm</h5></td>
			 <td><input type='submit' name='info' value='Informationen'></input></td>
			 <input type='hidden' name='InfoID' value='$ProjektIDAdmin[$ID]'></input>
			 </form>
			 <form method='post' action='projektbearbeiten.php'>
			 <th><input type='submit' name='Bearbeiten' value='Bearbeiten'></input></th>
			 <input type='hidden' name='InfoID' value='$ProjektIDAdmin[$ID]'></input>
			 </form>
			 <form method='post' action='projektloeschen.php'>
			 <th><input type='submit' name='Projektloeschen' value='L&ouml;schen'></input></th>
			 <input type='hidden' name='InfoID' value='$ProjektIDAdmin[$ID]'></input>
			 </form>
		</tr>";
		


	}	
	}

echo "</tbody>";
echo "</table>";
echo "</div>";
mysql_close($verbindung);
?>

 
</div>
</br></br>
<div id="ersteller" role="ersteller">
<form method='post' action='ProjektErstellen.php'><input type='submit' name='erstellen' value='Projekt erstellen'></input></form>
</div>
</p>
<p>

</p>
</br>
<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
