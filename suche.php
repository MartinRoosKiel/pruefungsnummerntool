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
?>

<br>
<hr>
Bitte geben sie den Namen m&ouml;glichst vollst&auml;ndig an!


<form  action="suche.php" method="post">
    <fieldset>
        <legend>Suche</legend>

        <p><label for="vorname">Vorname</label> <input type = "text" name = "vorname">
        </p>
        <p>
            <label for="nachname">Nachname</label> <input type = "text" name = "nachname">
        </p>
        <?php
        if ($_SESSION['usrlevel'] >= 1) {
            echo '<p><input  type="submit" name="submit" value="Abfragen"></p>';
        } else {
            echo 'Im Demomodus sind keine Eintragungen m�glich.';
        }
        ?>


    </fieldset>
</form>


<hr>



<?php
$tdetd = "</td><td>";
if (isset($_POST['submit'])) {
    $erg = get_brevet($_POST['nachname'], $_POST['vorname']);



    echo "<table><caption>Ergebnis der Suche der letzten 6 Jahre</caption><tr><th scope=\"col\">Vorname</th><th scope=\"col\">Nachname</th><th scope=\"col\">Pruefungsnummer</th><th scope=\"col\">letzte Pruefung</th><th scope=\"col\">Ausbilder</th></tr>";
    foreach ($erg as $daten) {
        echo "<tr><td>" . $daten[0] . $tdetd . $daten[1] . $tdetd . $daten[2] . $tdetd . $daten[3] . $tdetd . $daten[4] . "</td><tr>";
    }
    echo "</table>";
}
?>




