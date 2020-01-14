<?php
ob_start();
session_start();
include_once('sessionhelpers.inc.php');

if ( !logged_in() ) {
    echo 'Sie sind nicht eingeloggt.';
    echo '<p><a href="login.php">Anmelden</a></p>';
}




if ( logged_in() ) 
{include_once('header.php'); 
}

if(isset($_POST['eintragen']))
{
	if(logged_in()){
		$erg =eintragen_kurs($_POST['nummer'],$_POST['begin'],$_POST['ende'],$_POST['kommentar'],$_POST['verband']);
		header("location:kurse.php");
	}
	else{
		echo "Sie sind nicht eingeloggt!";
	}
}
ob_end_flush();
?>


<form method="post" action="kurse.php">
<fieldset>
<p>
	<label>	Kursnummer</label>
	<input name="nummer" type="text" size="20">
	</p>
<p>
		<label for="begin">Startdatum
	
		<input name="begin" type="text" size="20">
		</label>
	</p>
<p>
		<label for="ende">Enddatum
	
		<input name="ende" type="text" size="20">
		</label>
	</p>
	<p>
		<label for="verband">LV/OV Nummer
	
		<input name="verband" type="text" size="20">
		</label>
	</p>
	<p>
		<label for="kommentar">Kursbeschreibung
	
		<input name="kommentar" type="text" size="20">
		</label>
	</p>
	<?php if($_SESSION['usrlevel'] >= 2) {
		
echo '<p><input  type="submit" name="eintragen" value="Speichern"></p>'; 
}
else {
	echo 'Im Demomodus sind keine Eintragungen m�glich.';
} ?>
		
</fieldset>
</form>
<hr>




			<table>
			<tr><th>Nummer</th><th>Beginn</th><th>Ende</th><th>LV / OV</th><th>Kursbeschreibung</th></tr>
			<?php
				$erg = kurs_daten();
				$kursListe = listeKurse($erg);
				echo htmlspecialchars_decode($kursListe);
				
			 ?>
			</table>
		

