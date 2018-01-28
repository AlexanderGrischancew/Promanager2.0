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
	
$Name = "";
$Beschreibung = "";

if (isset($_SESSION["ErstellError"]))
	{
	$Name = $_SESSION["NameSave"];
	$Beschreibung = $_SESSION["BeschreibungSave"];
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
      <li><a href="profil.php">Profil</a></li>
	  <li><a href="projects.php">Projekte</a></li>
	  <li><a href="raume.php"><font color="#666">Raum</font></a></li>
	  <li><a href="users.php">Benutzer</a></li>&nbsp;
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'>LOG</a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>


		
   </hgroup>
  
   </div> 
   
   </br>
   
<div id="ersteller" role="ersteller">
<form method='post' action='raumeinfuegen.php'>
</br></br>
Name: </br><label><input name="ProjektName" type="text" value = <?php echo $Name ?>></label>
</br></br>
<label>Beschreibung:</br><textarea name="projektbeschreibung" cols="30" rows="5"><?php echo $Beschreibung ?></textarea></label>
<br/><br/>
<input type='submit' value='Best&auml;tigen'/>

<?php 
if (isset($_SESSION["ErstellError"]))
	{
	$ErstellError = $_SESSION["ErstellError"];
	echo "<h5><font color='#FF0000'>$ErstellError</font><br></h5>";
	unset($_SESSION["ErstellError"]);
	}
?>
</form>
</div>
<p>
</br></br></br></br></br></br></br></br>



</p>
</br>
<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
