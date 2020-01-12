<?php

session_start();
include_once('sessionhelpers.inc.php');



if ( !logged_in() ) {
    echo 'Sie sind nicht eingeloggt.';
    echo '<p><a href="login.php">Anmelden</a></p>';
}

if ( logged_in() ) {
    	include_once('header.php'); 
}

if(isset($_POST['nachname'])&isset($_POST['vorname'])&isset($_POST['kurs'])&isset($_POST['level'])&isset($_POST['ausbilderId']))
{
	if(logged_in()){
		$nachname = $_POST['nachname'];
		$vorname = $_POST['vorname'];
		$kurs = $_POST['kurs'];
		$level = $_POST['level'];
		$ausbilderId = $_POST['ausbilderId'];
		echo "<br><hr>";
		echo "letzter Eintrag <br> Name: ".htmlspecialchars($nachname)."<br> Vorname: ";
		echo htmlspecialchars($vorname)."<br> Kurs: ".htmlspecialchars($kurs)."<br> Level: ".htmlspecialchars($level)."<br>";
		$erg = eintragen_pruefung($nachname,$vorname,$kurs,$level,$ausbilderId);
		echo "Pruefungsnummer: ".$erg;
		echo "<hr>";
	}
	else
	{
		echo "Sie sind nicht eingeloggt!";
	}
}


?>

<br>



<table>
<form  action="pruefung.php" method="post">

<tr><td>Name </td><td><input name="nachname" type="text" value =""></td></tr>
<tr><td>Vorname </td><td><input name="vorname" type="text" value =""></td></tr>
<tr><td>Kurs </td><td> <select name = "kurs">
	
				<?php
				$erg = kurs_daten();
				echo kursSelector($erg);
				?>
	</td>
</tr>	
<tr><td>Level</td>
	<td>
		<select name="level">
		<option value="B">Bronze</option>
		<option value="S">Silber</option>
		<option value="G">Gold</option>
		<option value="J">Juniorretter</option>
		<option value="WR">Wasserretter</option>
		<option value="WL">Wachleiter</option>
		<option value="BS">Bootsf&uuml;hrer See</option>
		<option value="BB">Bootsf&uuml;hrer Binnen</option>
		<option value="MW">Multiplikator Wasseretter</option>
	</td>
</tr>
<tr><td>Ausbilder</td>
<td> <select name = "ausbilderId">
<?php
				$erg = get_ausbilder();
				echo ausbilderSelector($erg);
			 ?>
</td>

</tr>
		
<tr>
	<td>	<?php if($_SESSION['usrlevel'] >= 2) {
echo '<input  type="submit" name="submit" value="Absenden">';
}
else {
	echo 'Im Demomodus sind keine Eintragungen möglich.';
} ?>
		
	</td>
</tr>
</form>
</table>

<hr>

<table>
	<tr>
		<td>
			<table>
			<tr><td>Nummer</td><td>Beginn</td><td>Ende</td><td>LV / OV</td><td>Kursbeschreibung</td></tr>
			<?php
				$erg = kurs_daten();
				$kursListe = listeKurse($erg);
				echo htmlspecialchars_decode($kursListe);
			 ?>
			</table>
		</td>
	</tr>
</table>

