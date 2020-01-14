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



<form  action="pruefung.php" method="post">
<fieldset>
<legend>neue Pr&uuml;fung</legend>
<p>
	<label>	Kursnummer</label>
	<input name="nummer" type="text" size="20">
</p>
<p>
	<label for="nachname">Name </label>
	<input name="nachname" type="text" value ="">
</p>
<p>
	<label for="vorname">Vorname </label>
	<input name="vorname" type="text" value ="">
</p>
<p>
	<label for="kurs">Kurs </label> 
	<select name = "kurs">
	
				<?php
				$erg = kurs_daten();
				echo kursSelector($erg);
				?>
    </select>
</p>
<p>
	<label for="level">Level</label>
	
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
		</select>
	<p>
<p>
	<label for="ausbilderId">Ausbilder</label>
	<select name = "ausbilderId">
		<?php
				$erg = get_ausbilder();
				echo ausbilderSelector($erg);
			 ?>
</p>

	<?php if($_SESSION['usrlevel'] >= 2) {
echo '<p><input  type="submit" name="submit" value="Absenden"></p>';
}
else {
	echo 'Im Demomodus sind keine Eintragungen m�glich.';
} ?>
		
	</fieldset>
</form>

<hr>


			<table>
			<caption>vorhandene Kurse</caption>
			<tr><th scope="col">Nummer</th><th scope="col">Beginn</th><th scope="col">Ende</th><th scope="col">LV / OV</th><th scope="col">Kursbeschreibung</th></tr>
			<?php
				$erg = kurs_daten();
				$kursListe = listeKurse($erg);
				echo htmlspecialchars_decode($kursListe);
			 ?>
			</table>
		
