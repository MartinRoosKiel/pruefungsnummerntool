<?php

session_start();
include_once('sessionhelpers.inc.php');


if ( !logged_in() ) {
    echo 'Sie sind nicht eingeloggt.';
    echo '<p><a href="login.php">Anmelden</a></p>';
}



if ( logged_in() ) {
	include_once('header.php'); 

$usrl = $_SESSION['usrlevel'];
$userId = $_SESSION['userid'];
if(isset($_POST['email'])){
	$eMail = $_POST['email'];
}
if(isset($_POST['username'])) {
	$user = $_POST['username'];
}

 if(isset($_POST['change']))
{
	if($usrl > 2) 
	{
		echo change_userdata($user,$eMail,$userId,$_POST['name'],$_POST['asr'],$_POST['atr']);
		
	}
	else
	{
		echo change_userdata($user,$eMail,$userId,"","","");
	}
}

 if(isset($_POST['changePass']))
{
 	echo change_pass($_POST['pass'],$userId);
}
if($usrl > 2) {
	if(isset($_POST['insertUser']))
	{
		echo insert_user($user,$_POST['name'],$_POST['asr'],$_POST['atr'],$_POST['pass'],$eMail,$_POST['level']);
	}	
		if(isset($_POST['deleteUser']))
	{
		echo delete_user($user,$_POST['level']);
	}	
}





$zeile =  user_data($userId);

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
if($usrl >= 1) {
echo '<input type="submit" name="change" value="&auml;ndere Nutzerdaten"></td><td><input type="submit" name="changePass" value="&auml;ndere Passwort">';

}
if($usrl > 2) {
echo '<input type="submit" name="insertUser" value="Nutzer eintragen">';
echo '<input type="submit" name="deleteUser" value="Nutzer entfernen">';


}
if($usrl == 0) {
	echo 'Im Demomodus sind keine Eintragungen m�glich.';
}

echo '</td></tr></table></form>';
if($usrl > 2) {
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
