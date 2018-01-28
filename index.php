<?php
// VERSION: 2

ini_set('session.use_only_cookies', 1);
session_start();

//$_SESSION["log"] = FALSE; //LoginVar True wenn an eingeloggt ist FALSE ansonsten

if (isset($_SESSION["BenutzerEmail"]) == false OR isset($_SESSION["Pw"]) == false) //Wenn Email oder Passwort nicht vorhanden sind werden sie auf null gesetzt
	{
	$_SESSION["BenutzerEmail"] = NULL;
	$_SESSION["Pw"] = NULL;
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
     <h1><img src="logo.jpg" width=200px height=auto>     <font color="#000"><font color="#666"><span style="font-size:1.9em">P</span></font>ro<font color="#666">M</font>anager 2.0</font></h1>
    </header>




		
   </hgroup>
  
   </div> 
   
   </br>

<p>
<h3>
<form method="post" action="login.php">
<fieldset>
<legend><span style="font-size:1.9em">Log In</span></legend>
<p><label>E-Mail: &nbsp;&nbsp;&nbsp;&nbsp; <input name="email" type="text"></label></p>
<p><label>Passwort: <input name="pass" type="password"></label></p>
</br>
</fieldset>
</br>
<input type="submit" value="Log In"/>
<?php
//Error report:
if ($_SESSION["BenutzerEmail"] == "WRONG" OR $_SESSION["Pw"] == "WRONG") // Falls Passwort oder Email falsch war:
	{
	echo "<h5><font color='#FF0000'>Falsche Email oder falsches Passwort</font><br></h5>";
	unset($_SESSION["BenutzerEmail"]);
	unset($_SESSION["Pw"]);
	}
?>
</form>
</h3>


</br></br></br></br></br>
</p>
</br>
<div id="bottom" role="bottom">
&copy; #o8 Programming 2015
</div>
 </body>
 
</html>

