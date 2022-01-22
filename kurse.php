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


echo '<form method="post" action="kurse.php">';
echo '<fieldset>';
echo '<legend>neuer Kurs</legend>';
echo '<p>';
echo '	<label for="nummer">	Kursnummer</label>';
echo '	<input name="nummer" type="text" size="20">';
echo '	</p>';
echo '<p>';
echo '		<label for="begin">Startdatum';
	
echo '		<input name="begin" type="text" size="20">';
echo '		</label>';
echo '	</p>';
echo '<p>';
echo '		<label for="ende">Enddatum';
	
echo '		<input name="ende" type="text" size="20">';
echo '		</label>';
echo '	</p>';
echo '	<p>';
echo '		<label for="verband">LV/OV Nummer';
	
echo '		<input name="verband" type="text" size="20">';
echo '		</label>';
echo '	</p>';
echo '	<p>';
echo '		<label for="kommentar">Kursbeschreibung';
	
echo '		<input name="kommentar" type="text" size="20">';
echo '		</label>';
echo '	</p>';
	 if($_SESSION['usrlevel'] >= 2) {
		
echo '<p><input  type="submit" name="eintragen" value="Speichern"></p>'; 
}
else {
	echo 'Im Demomodus sind keine Eintragungen möglich.';
} 
		
echo '</fieldset>';
echo '</form>';
echo '<hr>';
echo '<table>';
echo 	'<caption>vorhandene Kurse</caption>';
echo '<tr><th scope="col">Nummer</th><th scope="col">Beginn</th><th scope="col">Ende</th><th scope="col">LV / OV</th><th scope="col">Kursbeschreibung</th></tr>';

				$erg = kurs_daten();
				$kursListe = listeKurse($erg);
				echo htmlspecialchars_decode($kursListe);
				
			 
echo'</table>';
		
}

?>
