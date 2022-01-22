<?php

session_start();
include_once('sessionhelpers.inc.php');



if (!logged_in()) {
    echo 'Sie sind nicht eingeloggt.';
    echo '<p><a href="login.php">Anmelden</a></p>';
}

if (logged_in()) {
    include_once('header.php');
}

echo '<br>';
echo '<hr>';
echo 'Bitte w&auml;hlen sie f&uuml;r eine Jahresstatistik das entsprechende Jahr und dr&uuml;cken sie "Abfragen"!';

echo '<form  action="statistik.php" method="post">';
echo '<fieldset>';
echo '<legend>Jahresstatistik gesamt</legend>';
echo '<p><label for="jahr">Kursjahr </label> <select name = "jahr">';

$erg = jahreszahlen();
echo kursjahrSelector($erg);

echo '	<select>';
echo '</p>	';



if ($_SESSION['usrlevel'] >= 1) {
    echo '<p><input  type="submit" name="submit" value="Abfragen"></p>';
} else {
    echo 'Im Demomodus sind keine Abfragen m�glich.';
}

echo '</fieldset>';
echo '</form>';

echo '<hr>';
echo 'Bitte w&auml;hlen sie f&uuml;r eine Jahresstatistik das entsprechende Jahr und den OV / LV  und dr&uuml;cken sie "Abfragen"!';



echo '<form  action="statistik.php" method="post">';
echo '<fieldset>';
echo '<legend>Jahresstatistik f&uuml;r einen LV/OV</legend>';
echo '<p><label for="jahr">Kursjahr </label> <select name = "jahr">';

$erg = jahreszahlen();
echo kursjahrSelector($erg);

echo '	</select>';
echo '</p>	';
echo '<p><label for="lvov">LV / OV </label> <select name = "lvov">';


$erg = lvov();
echo lvOvSelector($erg);

echo '	</select>';
echo '</p>	';


if ($_SESSION['usrlevel'] >= 1) {
    echo '<p><input  type="submit" name="go" value="Abfragen"></p>';
} else {
    echo 'Im Demomodus sind keine Abfragen m&ouml;glich.';
}

echo '	</fieldset>';
echo '</form>';

echo '<hr>';



$trtd = "<tr><td>";
$tdetd = "</td><td>";
$tdetre = "</td></tr>";
$tab = "<table>";
$tabe = "</table>";

if (isset($_POST['submit'])) {

    $jahr = htmlspecialchars($_POST['jahr']);


    echo "<table><caption>Statistik f&uuml;r das Jahr " . $jahr . " </caption><tr><th scope=\"col\">Abnahmen pro Kurs</th><td width=30></td><th scope =\"col\">gesamt Abnahmen</th></tr><tr><td valign=\"top\">";
    $erg = get_pruefungen($jahr);

    echo $tab;
    foreach ($erg as $kurs => $anzahl) {
        echo $trtd . $kurs . $tdetd . $anzahl . $tdetre;
    }
    echo $tabe . $tdetd . "</td><td valign=\"top\">";

    $erg = get_statistik($jahr);

    echo $tab;
    foreach ($erg as $level => $anzahl) {
        echo $trtd . $level . $tdetd . $anzahl . $tdetre;
    }
    echo $tabe . $tdetre . $tabe;
}
if (isset($_POST['go'])) {
    $jahr = htmlspecialchars($_POST['jahr']);
    $lvov = htmlspecialchars($_POST['lvov']);

    echo "<table><caption>Statistik f&uuml;r das Jahr " . $jahr . " und den LV / OV " . $lvov . "</caption><tr><th scope=\"col\">Abnahmen pro Kurs</th><td width=30></td><th scope=\"col\">gesamt Abnahmen</th></tr><tr><td valign=\"top\">";
    $erg = get_pruefungen_lvov($jahr, htmlspecialchars($lvov));

    echo $tab;
    foreach ($erg as $kurs => $anzahl) {
        echo $trtd . $kurs . $tdetd . $anzahl . $tdetre;
    }
    echo $tabe . " </td><td></td><td valign=\"top\">";


    $erg = get_statistik_LVOV($jahr, $lvov);

    echo $tab;
    foreach ($erg as $level => $anzahl) {
        echo $trtd . $level . $tdetd . $anzahl . $tdetre;
    }
    echo $tabe . $tdetre . $tabe;
}
?>




