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

if(isset($_POST['nachname'])&isset($_POST['vorname'])&isset($_POST['datum'])&isset($_POST['ausbilderId']))
{
if(logged_in()){
	$nachname = $_POST['nachname'];
	$vorname = $_POST['vorname'];
	$datum = $_POST['datum'];
	$ausbilderId = $_POST['ausbilderId'];
 	echo "<br><hr>";
	echo "letzter Eintrag <br> Name: ".$nachname."<br> Vorname: ";
	echo $vorname."<br> Datum: ".$datum."<br>";
	$erg = eintragen_wiederholung($nachname,$vorname,$datum,$ausbilderId);
	echo "<hr>";
}
else
echo "Sie sind nicht eingeloggt!";
}


?>

<br>



<table>
<form  action="wiederholung.php" method="post">

<tr><td>Name </td><td><input name="nachname" type="text" value =""></td></tr>
<tr><td>Vorname </td><td><input name="vorname" type="text" value =""></td></tr>
<tr><td>Datum </td><td> <input name = "datum" type="text" value =""></td></tr>	
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


