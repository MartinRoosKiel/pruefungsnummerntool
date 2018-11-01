<?php

session_start();
include_once('sessionhelpers.inc.php');

echo'Bitte die Seite Aktualisieren!';
if ( logged_in() ) {
logout();
header("Location:index.php");
    
}else {
    header("Location:index.php");
}
if(!logged_in())
{
header("Location:index.php");
}

?>