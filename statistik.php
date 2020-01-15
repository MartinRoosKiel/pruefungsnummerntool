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


?>

<br>
<hr>
Bitte w&auml;hlen sie f&uuml;r eine Jahresstatistik das entsprechende Jahr und dr&uuml;cken sie "Abfragen"!



<form  action="statistik.php" method="post">
<fieldset>
<legend>Jahresstatistik gesamt</legend>
<p><label for="jahr">Kursjahr </label> <select name = "jahr">
	
				<?php
				$erg = jahreszahlen();
				echo kursjahrSelector($erg);
			 ?>
	<select>
</p>	

		

		<?php if($_SESSION['usrlevel'] >= 1) {
echo '<p><input  type="submit" name="submit" value="Abfragen"></p>';
}
else {
	echo 'Im Demomodus sind keine Abfragen möglich.';
} ?>
	
</fieldset>
</form>

<hr>
Bitte w&auml;hlen sie f&uuml;r eine Jahresstatistik das entsprechende Jahr und den OV / LV  und dr&uuml;cken sie "Abfragen"!



<form  action="statistik.php" method="post">
<fieldset>
<legend>Jahresstatistik f&uuml;r einen LV/OV</legend>
<p><label for="jahr">Kursjahr </label> <select name = "jahr">
	
				<?php
				$erg = jahreszahlen();
				echo kursjahrSelector($erg);
			 ?>
	</select>
</p>	
<p><label for="lvov">LV / OV </label> <select name = "lvov">
	
				<?php
				$erg = lvov();
				echo lvOvSelector($erg);
			 ?>
	</select>
</p>	

		
<?php if($_SESSION['usrlevel'] >= 1) {
echo '<p><input  type="submit" name="go" value="Abfragen"></p>';
}
else {
	echo 'Im Demomodus sind keine Abfragen m&ouml;glich.';
} ?>
		
	</fieldset>
</form>

<hr>



<?php 
    $trtd = "<tr><td>";
	$tdetd = "</td><td>";
	$tdetre = "</td></tr>";
	$tabe ="</table>";

if(isset($_POST['submit']))
{
	
	$jahr = htmlspecialchars($_POST['jahr']);
	
	
echo "<table><caption>Statistik f&uuml;r das Jahr ".$jahr." </caption><tr><th scope=\"col\">Abnahmen pro</th><td width=30></td><th scope =\"col\">gesamt Abnahmen</th></tr><tr><td valign=\"top\">";
	$erg = get_pruefungen($jahr);

	$x = count($erg);
	$i = 0;

echo "<table><caption>Kurs</caption>";
	while($i<$x)
	{
		echo $trtd.$erg[$i].$tdetd.$erg[$i+1].$tdetre;
		$i = $i+2;

	}
echo $tabe.$tdetd."</td><td valign=\"top\">";


	$erg = get_statistik($jahr);

	$x = count($erg);
	$i = 0;
echo "<table><caption>Ausbildungslevel</caption>";
	while($i<$x)
	{
		echo $trtd.$erg[$i].$tdetd.$erg[$i+1].$tdetre;
		$i = $i+2;

	}
echo $tabe.$tdetre.$tabe;

}
if(isset($_POST['go']))
{
$jahr = htmlspecialchars($_POST['jahr']);
$lvov = htmlspecialchars($_POST['lvov']);

echo "<table><caption>Statistik f&uuml;r das Jahr ".$jahr." und den LV / OV ".$lvov."</caption><tr><th scope=\"col\">Abnahmen pro</th><td width=30></td><th scope=\"col\">gesamt Abnahmen</th></tr><tr><td valign=\"top\">";
	$erg = get_pruefungen_lvov($jahr,htmlspecialchars($lvov));

	$x = count($erg);
	$i = 0;

echo "<table> <caption>Kurs</caption>";
	while($i<$x)
	{
		echo $trtd.$erg[$i].$tdetd.$erg[$i+1].$tdetre;
		$i = $i+2;

	}
echo $tabe." </td><td></td><td valign=\"top\">";


	$erg = get_statistik_LVOV($jahr,$lvov);

	$x = count($erg);
	$i = 0;
echo "<table><caption>Ausbildungslevel</caption>";
	while($i<$x)
	{
		echo $trtd.$erg[$i].$tdetd.$erg[$i+1].$tdetre;
		$i = $i+2;

	}
echo $tabe.$tdetre.$tabe;

}

?>




