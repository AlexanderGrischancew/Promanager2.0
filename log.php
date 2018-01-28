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
      <li><a href="profil.php">Profil <img src="manchen.png" width=auto height=20px ></a></li>
	  <li><a href="projects.php">Projekte</a></li>
	  <li><a href="raume.php">Raum</a></li>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='users.php'>Benutzer</a></li>&nbsp;";} ?>
	  <?php if($_SESSION["Admin"] == TRUE){echo "<li><a href='log.php'><font color='#666'>LOG</font></a></li>&nbsp;";} ?>
	  <li><a href="Logout.php"><img src="tuer.png" width=auto height=20px ></a></li>&nbsp;
     </ul>
    </nav>
	
		
   </hgroup>
   
  
 </div>

</br></br></br>
<div id="table" role="table"> 
<table  id="logtable" role="logtable" border cellpadding=100">
<td id="zellenlogfarbe" role="zellenlogfarbe"><b>Timestamp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
<td id="zellenlogfarbe" role="zellenlogfarbe"><b>User&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
<td id="zellenlogfarbe" role="zellenlogfarbe"><b>Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
<td id="zellenlogfarbe" role="zellenlogfarbe"><b>Object&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
<td id="zellenlogfarbe" role="zellenlogfarbe"><b>State&nbsp;&nbsp;</b></td>
</table>
</div>
<div  id="scroll" role="scroll">
<?php
$logFile = fopen("log.txt","r");
while(!feof($logFile))
	{
	$logZeile = fgets($logFile, 4096);
	echo "<h5> $logZeile </h5>";
	}
?>

</div>
</br>
<form method='post' action='clearLog.php'><input type='submit' name='clearLog' value='Log leeren'></input></form>
<p>
</br></br></br></br></br></br></br></br>



</p>
</br>
<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>
