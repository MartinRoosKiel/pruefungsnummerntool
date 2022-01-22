<?php

session_start();
include_once('sessionhelpers.inc.php');

if ( logged_in() ) {
	
while(session_status() == PHP_SESSION_NONE){sleep(1);} 	
	
	echo <<<END
	Die Seite wird aktualisiert!
	<script language="javascript" type="text/javascript">
<!--
window.setTimeout('window.location = "index.php"',1000);
// –>
</script>
END;
	logout();
	session_destroy();
	session_unset();   // Remove the $_SESSION variable information.
	session_id(null); 
	exit;
	
	
}
?>