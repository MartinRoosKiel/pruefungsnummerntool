<?php

session_start();
include_once('sessionhelpers.inc.php');


if ( !logged_in() ) {
    echo 'Sie sind nicht eingeloggt.';
    echo '<p><a href="login.php">Anmelden</a></p>';
}



if ( logged_in() ) {
	include_once('header.php'); 


 if(isset($_POST['change']))
{
	if($_SESSION['usrlevel'] > 2) 
	{
		echo change_userdata($_POST['username'],$_POST['email'],$_SESSION['userid'],$_POST['name'],$_POST['asr'],$_POST['atr']);
		header("Location:config.php");
	}
	else
	{
		echo change_userdata($_POST['username'],$_POST['email'],$_SESSION['userid'],"","","");
		header("Location:config.php");
	}
}

 if(isset($_POST['changePass']))
{
 	echo change_pass($_POST['pass'],$_SESSION['userid']);
	   header("Location:config.php");
}
if($_SESSION['usrlevel'] > 2) {
	if(isset($_POST['insertUser']))
	{
		echo insert_user($_POST['username'],$_POST['name'],$_POST['asr'],$_POST['atr'],$_POST['pass'],$_POST['email'],$_POST['level']);
	}	
		if(isset($_POST['deleteUser']))
	{
		echo delete_user($_POST['username'],$_POST['level']);
	}	
}





$zeile =  user_data($_SESSION['userid']);

echo <<<END
<form method="post" action="config.php">
<table border="0" cellpadding="0" cellspacing="4">
<tr><td>
<label>Benutzername:</label> </td><td> <input name="username" type="text" size="80" value=$zeile[0] >
</td></tr>
<tr><td>
<tr><td>
<label>Name:</label> </td><td> <input name="name" type="text" size="80" value=$zeile[2] >
</td></tr>
<tr><td>
<label>ASR Nummer:</label> </td><td> <input name="asr" type="text" size="80" value=$zeile[3] >
</td></tr>
<tr><td>
<tr><td>
<label>ATR Nummer:</label> </td><td> <input name="atr" type="text" size="80" value=$zeile[4] >
</td></tr>
<tr><td>
<tr><td>
eMail: </td><td> <input name="email" type="text" size="80" id="email" value=$zeile[1]>
</td></tr>
<tr><td>
Passwort: </td><td> <input name="pass" type="text" size="80" id="pass">
</td></tr>
<tr><td>
Level: </td><td> <select name="level"><option value = "0">Demo</option><option value = "1">Ansehen</option><option value = "2">Ausbilder</option><option value = "3">Admin</option>
</td></tr>
<tr><td>
END;
if($_SESSION['usrlevel'] >= 1) {
echo '<input type="submit" name="change" value="&auml;ndere Nutzerdaten"></td><td><input type="submit" name="changePass" value="&auml;ndere Passwort">';

}
if($_SESSION['usrlevel'] > 2) {
echo '<input type="submit" name="insertUser" value="Nutzer eintragen">';
echo '<input type="submit" name="deleteUser" value="Nutzer entfernen">';


}
if($_SESSION['usrlevel'] == 0) {
	echo 'Im Demomodus sind keine Eintragungen möglich.';
}

echo '</td></tr></table></form>';
if($_SESSION['usrlevel'] > 2) {
echo '<br><hr><br>';
echo "<table><tr><th>Nutzername</th><th>Level</th></tr>";
	$erg = get_users();

	$x = count($erg);
	$i = 0;
	while($i<$x)
	{
		echo "<tr><td>".$erg[$i]."</td><td>".$erg[$i+1]."</td><tr>";
		$i = $i+2;
	}
echo "</table> ";
}
}
?>
