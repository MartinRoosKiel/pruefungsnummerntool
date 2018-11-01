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
	header("location:kurse.php");}
else
echo "Sie sind nicht eingeloggt!";
}
ob_end_flush();
?>


<form method="post" action="kurse.php">
<table border="0" cellpadding="0" cellspacing="4">
<tr>
	<td>
		Kursnummer
	</td>
	<td>
		<input name="nummer" type="text" size="20">
	</td>
</tr>
<tr>
	<td>
		Startdatum
	</td>
	<td>
		<input name="begin" type="text" size="20">
	</td>
</tr>
<tr>
	<td>
		Enddatum
	</td>
	<td>
		<input name="ende" type="text" size="20">
	</td>
</tr>
<tr>
	<td>
		LV/OV Nummer
	</td>
	<td>
		<input name="verband" type="text" size="20">
	</td>
</tr>
<tr>
	<td>
		Kursbeschreibung
	</td>
	<td>
		<input name="kommentar" type="text" size="20">
	</td>
</tr>
<tr>
	<td>
	<?php if($_SESSION['usrlevel'] >= 2) {
echo '<input  type="submit" name="eintragen" value="Speichern">';
}
else {
	echo 'Im Demomodus sind keine Eintragungen möglich.';
} ?>
		
	</td>
</tr>
</table>
</form>
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

