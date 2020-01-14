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
 <div style="text-align:center; margin-left: auto; margin-right: auto;">>
<img src="images/pic_header_claim.png" alt="header links Wir helfen hier und jetzt">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/logo_header_s2.png" alt="125 Jahre ASB">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src="images/pic_header_order_default.png" alt="banner rechts">
<br><br> 
<div style="text-align:center; margin-left: auto; margin-right: auto;">>
<?php
}
?>

<br><br>
<h1>Pr&uuml;fungsnummernvergabe</h1>
<img src="images/Logo_Wasserrettung_RGB_1.jpg" width="400px" alt="ASB WRD Logo Rettungsring">



