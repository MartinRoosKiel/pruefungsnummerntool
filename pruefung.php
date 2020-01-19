<?php

session_start();
include_once('sessionhelpers.inc.php');

if ( !logged_in() ) {
    echo 'Sie sind nicht eingeloggt.';
    echo '<p><a href="login.php">Anmelden</a></p>';
}
if ( logged_in() ) {
    	include_once('header.php'); 
if(isset($_POST['nachname'])&isset($_POST['vorname'])&isset($_POST['kurs'])&isset($_POST['level'])&isset($_POST['ausbilderId']))
{
	if(logged_in()){
		$nachname = htmlspecialchars($_POST['nachname']);
		$vorname =  htmlspecialchars($_POST['vorname']);
		$kurs =  htmlspecialchars($_POST['kurs']);
		$level =  htmlspecialchars($_POST['level']);
		$ausbilderId =  htmlspecialchars($_POST['ausbilderId']);
		echo "<br><hr>";
		echo "letzter Eintrag <br> Name: ".$nachname."<br> Vorname: ";
		echo $vorname."<br> Kurs: ".$kurs."<br> Level: ".$level."<br>";
		$erg = eintragen_pruefung($nachname,$vorname,$kurs,$level,$ausbilderId);
		echo "Pruefungsnummer: ".$erg;
		echo "<hr>";
	}
	else
	{
		echo "Sie sind nicht eingeloggt!";
	}
}
echo"<br>";
echo"<form  action=\"pruefung.php\" method=\"post\">";
echo"<fieldset>";
echo"<legend>neue Pr&uuml;fung</legend>";
echo"<p>";
echo"	<label for=\"nachname\">Name </label>";
echo"	<input name=\"nachname\" type=\"text\" value =\"\">";
echo"</p>";
echo"<p>";
echo"	<label for=\"vorname\">Vorname </label>";
echo"	<input name=\"vorname\" type=\"text\" value =\"\">";
echo"</p>";
echo"<p>";
echo"	<label for=\"kurs\">Kurs </label> ";
echo"	<select name = \"kurs\">";			
			$erg = kurs_daten();
echo kursSelector($erg);				
echo"    </select>";
echo"</p>";
echo"<p>";
echo"	<label for=\"level\">Level</label>";	
echo"		<select name=\"level\">";
echo"		<option value=\"B\">Bronze</option>";
echo"		<option value=\"S\">Silber</option>";
echo"		<option value=\"G\">Gold</option>";
echo"		<option value=\"J\">Juniorretter</option>";
echo"		<option value=\"WR\">Wasserretter</option>";
echo"		<option value=\"WL\">Wachleiter</option>";
echo"		<option value=\"BS\">Bootsf&uuml;hrer See</option>";
echo"		<option value=\"BB\">Bootsf&uuml;hrer Binnen</option>";
echo"		<option value=\"MW\">Multiplikator Wasseretter</option>";
echo"		</select>";
echo "<p>";
echo" <p>";
echo"	<label for=\"ausbilderId\">Ausbilder</label>";
echo"	<select name = \"ausbilderId\">";	
			$erg = get_ausbilder();
echo ausbilderSelector($erg);			 
echo"</p>";
	if($_SESSION['usrlevel'] >= 2) {
echo '<p><input  type="submit" name="submit" value="Absenden"></p>';
}
else {
	echo 'Im Demomodus sind keine Eintragungen möglich.';
} 		
echo"	</fieldset>";
echo"</form>";
echo"<hr>";
echo"<table>";
echo"<caption>vorhandene Kurse</caption>";
echo"<tr><th scope=\"col\">Nummer</th><th scope=\"col\">Beginn</th><th scope=\"col\">Ende</th><th scope=\"col\">LV / OV</th><th scope=\"col\">Kursbeschreibung</th></tr>";
$erg = kurs_daten();
$kursListe = listeKurse($erg);
echo $kursListe;		
echo"</table>";		
}