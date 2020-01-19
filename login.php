<?php

session_start();
include_once('sessionhelpers.inc.php');

while(session_status() == PHP_SESSION_NONE){
	sleep(1);
}

if ( isset($_POST['login']) ) {
    $userid = check_user($_POST['username'], $_POST['userpass']);
    if ( $userid ) {
		$userlevel = get_user_level($userid);
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['userid'] = $userid;
		$_SESSION['usrlevel'] = $userlevel;
        login($userid);   
    } 
	else {
        $errorMessage = '<p>Ihre Anmeldedaten waren nicht korrekt!</p>';
    } 
}

if ( !logged_in() ) {
	
if(isset($errorMessage)) {
    echo $errorMessage;
}
    echo <<<END
	 <div align="center">
<img src="images/pic_header_claim.png">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/logo_header_s2.png">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src="images/pic_header_order_default.png">
<br><br>
<form method="post" action="login.php">
<fieldset>
<legend>Pr&uuml;fungsnummernvergabe</legend> 
<p>
<label for=\"username\">Benutzername:</label> <input name="username" type="text">
</p><p><label for=\"userpass\">Passwort:</label> <input name="userpass" type="password" id="userpass">
</p>
<p>
<input name="login" type="submit" id="login" value="Einloggen">
<p>
</fieldset>
</form>
END;
}
else
{ 	
echo <<<END
Die Seite wird aktualisiert!
<script language="javascript" type="text/javascript">
<!--
window.setTimeout('window.location = "index.php"',1000);
// –>
</script>
END;
}

?>