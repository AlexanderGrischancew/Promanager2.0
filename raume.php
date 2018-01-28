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

//$_SESSION["ErstellError"] = "";
$host = $_SESSION["host"]; 
$user = $_SESSION["user"];
$pw = $_SESSION["pw"];
$db = $_SESSION["db"];

$verbindung = mysql_connect("$host",$user,$pw)
	OR die("Error: $abfrage <br>".mysql_error());
	
$datenbank = mysql_select_db("$db")
	OR die("Error: $abfrage <br>".mysql_error());
	
$RauemeArray = array("");
$abfrage = "SELECT RaumID FROM Raeume;";
$BID = mysql_query($abfrage)
	OR die("Error: $abfrage <br>".mysql_error());
	
while($row = mysql_fetch_assoc($BID))
	{
	$Buffer = "$row[RaumID]";
	array_push($RauemeArray,$Buffer);
	}
	
$RaumRows = count($RauemeArray) -1;


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
    <link rel="stylesheet" href="raumstyle.css">  
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
	  <?php if($_SESSION["Admin"] == 1){echo "<li><a href='users.php'>Benutzer</a></li>&nbsp;";} ?>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>


		
   </hgroup>
  
   </div> 
 <div id="table" role="table">
</br></br>


<?php
echo "<table  id='raumtable' role='raumtable' border cellpadding='100'>";
 
echo "<div id='inscroll' role='inscroll'>
	<thead>
	<tr>
		<td id='zellenfarbe' role='zellenfarbe'><b>Name:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Beschreibung:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Status:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Belegung:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Bearbeiten:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>L&ouml;schen:</b></td>
		<td id='zellenfarbe' role='zellenfarbe'><b>Buchen:</b></td>
	</tr>
	</thead>";
	
echo "<tbody>";

for ($D = 0; $D < $RaumRows; $D++ )
	{

	$ID = $D +1;
 	
	$abfrage = "SELECT Name,Beschreibung,Projekt FROM Raeume WHERE RaumID = '$RauemeArray[$ID]';";
	$BenutzerInfo = mysql_query($abfrage)
		OR die("Error: $abfrage <br>".mysql_error());
	
	while($row = mysql_fetch_assoc($BenutzerInfo))
		{
		$Name = "$row[Name]";
		$Beschreibung = "$row[Beschreibung]";
		$Projekt = "$row[Projekt]";
		}

	$abfrage = "SELECT Name FROM Projekt WHERE ProjektID = '$Projekt';";
	$BenutzerInfo = mysql_query($abfrage)
		OR die("Error: $abfrage <br>".mysql_error());
	
	while($row = mysql_fetch_assoc($BenutzerInfo))
		{
		$ProjektName = "$row[Name]";
		}


	$ZeitplanString = "<h5><font color='#37A32B'>Frei</font></h5>";
	$Status = "Frei";

	$ProjektLeiterID = -1;
	if($Projekt != NULL)
		{
		$ZeitplanString = "<h5><font color='#FF0000'>Besetzt</font></h5>";
		$Status = "Besetzt";
		$abfrage = "SELECT BenutzerID FROM ProjektLeiter WHERE ProjektID = '$Projekt';";
		$BenutzerInfo = mysql_query($abfrage)
			OR die("Error: $abfrage <br>".mysql_error());
	
		while($row = mysql_fetch_assoc($BenutzerInfo))
			{
			$ProjektLeiterID = "$row[BenutzerID]";
			}
	}
	else
		{
		$ProjektName = "Keins";
		}

	echo "
		<tr>
			 <td><?php echo '<h5>$Name</h5></td>
			 <td><?php echo '<h5>$Beschreibung</h5></td>
			 <td><?php echo '<h5>$ZeitplanString</h5></td>
			<td><?php echo '<h5>Projekt:<font color='#503DDC'>$ProjektName<font/></h5></td>";
			 if($_SESSION["Admin"] == TRUE)
				{
					echo "
						<td><form method='post' action='raumbearbeiten.php'>
							<input type='submit' value='Raum bearbeiten'/>
							<input name='bearbeitenID' type='hidden' value='$RauemeArray[$ID]'/>
						</td>
							</form>";
				}
			 if($Status == "Frei" AND $_SESSION["Admin"] == 1)
				{
				 echo "<td><form method='post' action='raumloeschen.php'>
							<input type='submit' value='l&ouml;schen'/>
							<input type='hidden' name='RaumIDloeschen' value='$RauemeArray[$ID]'/>
					  </td>
							</form>";
				}
			 else
				{
				echo "<td><input type='hidden' value='l&ouml;schen'/></td>";
				}
				
			 if($Status == "Frei")
				{
				 echo"<td><form method='post' action='raumbuchen.php'>
							<input type='submit' style='BACKGROUND-COLOR: #37A32B' value='Buchen'/>
							<input type='hidden' name='RaumIDbuchen' value='$RauemeArray[$ID]'/>
							<input type='hidden' name='Status' value='$Status'/>
					 </td>
							</form>";
				}
			 if($ProjektLeiterID == $_SESSION["BenutzerID"] AND $Status != "Frei" OR $_SESSION["Admin"] == TRUE AND $Status != "Frei")
				{
				 echo "<td><form method='post' action='raumentbuchen.php'>
				<input type='submit' style='BACKGROUND-COLOR: #ff0000' value='Entbuchen'/>
				<input type='hidden' name='RaumIDbuchen' value='$RauemeArray[$ID]'/>
				<input type='hidden' name='Status' value='$Status'/>
				</td>
				</form>";
				}
	echo "</tr>";
}
echo "</tbody>";


echo "</table>";
echo "</div>";
echo "</div>";
echo "</br></br>";
echo "<div id='ersteller' role='ersteller'>";

if($_SESSION["Admin"] == TRUE)
	{
	echo "<form method='post' action='raumerstellen.php'>
	<input type='submit' value='Raum erstellen'/>
	</form>";
	}
echo "</div>";
mysql_close($verbindung);
?>
   </br>
<p>

</p>

<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
