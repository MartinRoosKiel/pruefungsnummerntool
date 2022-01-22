 <style type="text/css">
 a.menu {  margin-bottom:5px; text-align:center; width:16ex;
         border-style:ridge; border-width:3px; padding:2px; text-decoration:none; }

a.menu:link    { color:#fff; background-color:#E60000; }
a.menu:visited { color:#fff; background-color:#E60000; }
a.menu:hover   { color:#E60000; background-color:#FFD633; }
a.menu:active  { color:#E60000; background-color:#FFD633; }
 </style>
 <div style="text-align:center; margin-left: auto; margin-right: auto;">
<img src="images/pic_header_claim.png"  alt="header links Wir helfen hier und jetzt">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/logo_header_s2.png"  alt="125 Jahre ASB">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src="images/pic_header_order_default.png"  alt="banner rechts">
<br><br>
<div style="text-align:center; margin-left: auto; margin-right: auto;">
<form><?php
if(isset($_SESSION['name']))
{
	echo '<a class="menu" href="index.php">Willkommen '.$_SESSION['name'].'</a>';
}
echo '<a class="menu" href="index.php">Startseite</a>';
echo '<a class="menu" href="config.php">Administration</a>'; 
echo '<a class="menu" href="statistik.php">Statistik</a>';
if(isset($_SESSION['usrlevel']) && $_SESSION['usrlevel'] != 1) {	
	echo '<a class="menu" href="kurse.php">Kurse</a>';
	echo '<a class="menu" href="pruefung.php">Pr&uuml;fung</a>';
	echo '<a class="menu" href="wiederholung.php">Wiederholungen</a>';
}
echo '<a class="menu" href="suche.php">Suche</a>';
?>

  
<a class="menu" href="logout.php">Ausloggen</a>

</form>
</div>