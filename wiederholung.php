<?php

session_start();
include_once('sessionhelpers.inc.php');



if ( !logged_in() ) {
    echo 'Sie sind nicht eingeloggt.';
    echo '<p><a href="login.php">Anmelden</a></p>';
}

if ( logged_in() ) {
    	include_once('header.php'); 


if(isset($_POST['nachname'])&isset($_POST['vorname'])&isset($_POST['datum'])&isset($_POST['ausbilderId']))
{

	$nachname = htmlspecialchars($_POST['nachname']);
	$vorname = htmlspecialchars($_POST['vorname']);
	$datum = htmlspecialchars($_POST['datum']);
	$ausbilderId = htmlspecialchars($_POST['ausbilderId']);
 	echo "<br><hr>";
	echo "letzter Eintrag <br> Name: ".$nachname."<br> Vorname: ";
	echo $vorname."<br> Datum: ".$datum."<br>";
	$erg = eintragen_wiederholung($nachname,$vorname,$datum,$ausbilderId);
	echo "<hr>";
}


echo "<br>";
echo "<form  action=\"wiederholung.php\" method=\"post\">";
echo "<fieldset>";
echo "<legend>Wiederholungspr&uuml;fung</legend>";
echo "<p><label for=\"nachname\">Name </label><input name=\"nachname\" type=\"text\" value =\"\"></p>";
echo "<p><label for=\"vorname\">Vorname </label><input name=\"vorname\" type=\"text\" value =\"\"></p>";
echo "<p><label for=\"datum\">Datum </label> <input name = \"datum\" type=\"text\" value =\"\"></p>	";
echo "<p><label for=\"ausbilderId\">Ausbilder</label><select name = \"ausbilderId\">";

				$erg = get_ausbilder();
				echo ausbilderSelector($erg);
			 
echo "</p>";
		 if($_SESSION['usrlevel'] >= 2) {
echo "<p><input  type=\"submit\" name=\"submit\" value=\"Absenden\"></p>";
}
else {
echo "	Im Demomodus sind keine Eintragungen möglich.";
} 

echo "</fieldset>";
echo "</form>";
}


?>







