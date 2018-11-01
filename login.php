<?php

session_start();
include_once('sessionhelpers.inc.php');

if ( isset($_POST['login']) ) {
    $userid = check_user($_POST['username'], $_POST['userpass']);
    if ( $userid ) {
		$userlevel = get_user_level($userid);
	$_SESSION['name'] = $_POST['username'];
	$_SESSION['userid'] = $userid;
	$_SESSION['usrlevel'] = $userlevel;
        login($userid);
    } else {
        echo '<p>Ihre Anmeldedaten waren nicht korrekt!</p>';
    }
}

if ( !logged_in() ) {
    echo <<<END
	 <div align="center">
<img src="images/pic_header_claim.png">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/logo_header_s2.png">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src="images/pic_header_order_default.png">
<br><br>
<h1>Pr&uuml;fungsnummernvergabe</h1> </div>
<form method="post" action="login.php">
<table border="0" cellpadding="0" cellspacing="4">
<tr><td>
<label>Benutzername:</label> </td><td> <input name="username" type="text">
</td></tr><tr>
<td><label>Passwort:</label> </td><td> <input name="userpass" type="password" id="userpass">
</td></tr><tr><td>
<input name="login" type="submit" id="login" value="Einloggen">
</td></tr>
</table>
</form>
END;
} else {
    header("Location:index.php");

}

?>
