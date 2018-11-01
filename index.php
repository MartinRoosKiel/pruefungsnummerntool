<?php

session_start();
include_once('sessionhelpers.inc.php');


if ( !logged_in() ) {
    echo 'Sie sind nicht eingeloggt    ';
    echo '<a href="login.php">Anmelden</a>';
}

if(logged_in())
{
include_once('header.php'); 
}
else
{?>
 <div align="center">
<img src="images/pic_header_claim.png">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/logo_header_s2.png">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src="images/pic_header_order_default.png">
<br><br> 
<div align="center">
<?php
}
?>

<br><br>
<h1>Pr&uuml;fungsnummernvergabe</h1>
<img src="images/Logo_Wasserrettung_RGB_1.jpg" width="400px">



