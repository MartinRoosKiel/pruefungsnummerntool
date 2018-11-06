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
Bitte geben sie den Namen m&ouml;glichst vollst&auml;ndig an!


<table>
<form  action="suche.php" method="post">

<tr><td>Vorname </td><td> <input type = "text" name = "vorname"> </input>
	
			
	</td>
</tr>	
<tr>
	<td>Nachname </td><td> <input type = "text" name = "nachname"> </input>
		</td>
</tr>
<tr>
	<td><?php if($_SESSION['usrlevel'] >= 1) {
echo '<input  type="submit" name="submit" value="Abfragen">';
}
else {
	echo 'Im Demomodus sind keine Eintragungen m�glich.';
} ?>
		
	</td>
</tr>
</form>
</table>


<hr>



<?php 

if(isset($_POST['submit']))
{
echo "Ergebnis der Suche ";

echo "Ergebnis der Suche der letzten 6 Jahre";
	$erg = get_brevet($_POST['nachname'],$_POST['vorname']);

	$x = count($erg);
	$i = 0;

echo "<table>";
	while($i<$x)
	{
		echo "<tr><td>".$erg[$i]."</td><td>".$erg[$i+1]."</td><td>".$erg[$i+2]."</td><td>".$erg[$i+3]."</td><td>".$erg[$i+4]."</td><tr>";
$i = $i+5;
	}
echo "</table>";
}


?>




