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


<table>
<form  action="statistik.php" method="post">

<tr><td>Kursjahr </td><td> <select name = "jahr">
	
				<?php
				$erg = jahreszahlen();
				echo kursjahrSelector($erg);
			 ?>
	</td>
</tr>	

		
<tr>
	<td>
		<?php if($_SESSION['usrlevel'] >= 1) {
echo '<input  type="submit" name="submit" value="Abfragen">';
}
else {
	echo 'Im Demomodus sind keine Abfragen möglich.';
} ?>
	</td>
</tr>
</form>
</table>
<hr>
Bitte w&auml;hlen sie f&uuml;r eine Jahresstatistik das entsprechende Jahr und den OV / LV  und dr&uuml;cken sie "Abfragen"!


<table>
<form  action="statistik.php" method="post">

<tr><td>Kursjahr </td><td> <select name = "jahr">
	
				<?php
				$erg = jahreszahlen();
				echo kursjahrSelector($erg);
			 ?>
	</td>
</tr>	
<tr><td>LV / OV </td><td> <select name = "lvov">
	
				<?php
				$erg = lvov();
				echo lvOvSelector($erg);
			 ?>
	</td>
</tr>	

		
<tr>
	<td><?php if($_SESSION['usrlevel'] >= 1) {
echo '<input  type="submit" name="go" value="Abfragen">';
}
else {
	echo 'Im Demomodus sind keine Abfragen m&ouml;glich.';
} ?>
		
	</td>
</tr>
</form>
</table>

<hr>



<?php 

if(isset($_POST['submit']))
{
echo "Statistik f&uumlr das Jahr ".$_POST['jahr'];
echo "<table><tr><td>Abnahmen pro Kurs</td><td width=30></td><td>Abnahmen nach Level</td></tr><tr><td valign=\"top\">";
	$erg = get_pruefungen($_POST['jahr']);

	$x = count($erg);
	$i = 0;

echo "<table>";
	while($i<$x)
	{
		echo "<tr><td>".$erg[$i]."</td><td>".$erg[$i+1]."</td><tr>";
		$i = $i+2;

	}
echo "</table> </td><td></td><td valign=\"top\">";


	$erg = get_statistik($_POST['jahr']);

	$x = count($erg);
	$i = 0;
echo "<table>";
	while($i<$x)
	{
		echo "<tr><td>".$erg[$i]."</td><td>".$erg[$i+1]."</td><tr>";
		$i = $i+2;

	}
echo "</table></td></tr></table>";

}
if(isset($_POST['go']))
{

echo "Statistik f&uuml;r das Jahr ".$_POST['jahr']." und den LV / OV ".$_POST['lvov'];

echo "<table><tr><td>Abnahmen pro Kurs</td><td width=30></td><td>Abnahmen nach Level</td></tr><tr><td valign=\"top\">";
	$erg = get_pruefungen_lvov($_POST['jahr'],$_POST['lvov']);

	$x = count($erg);
	$i = 0;

echo "<table>";
	while($i<$x)
	{
		echo "<tr><td>".$erg[$i]."</td><td>".$erg[$i+1]."</td><tr>";
		$i = $i+2;

	}
echo "</table> </td><td></td><td valign=\"top\">";


	$erg = get_statistik_LVOV($_POST['jahr'],$_POST['lvov']);

	$x = count($erg);
	$i = 0;
echo "<table>";
	while($i<$x)
	{
		echo "<tr><td>".$erg[$i]."</td><td>".$erg[$i+1]."</td><tr>";
		$i = $i+2;

	}
echo "</table></td></tr></table>";

}

?>




