<?php

include('connect.inc.php');

/**
 * @return void
 */
define("FNAME", "Vorname");
define("NAME", "Nachname");
define("PNUMBER", "Pruefungsnummer");
define("AUSBILDER", "Ausbilder");
define("DATUM", "Datum");
define("UNGAB", "Ung&uuml;ltige Abfrage:");
define("UNGABSQL", " sql:");

function connect() {

    DBi::$conn = new mysqli('rdbms.strato.de', USERNAME, PASSWORD, TABLE);
    // Check connection
    if (!DBi::$conn) {
        echo "db-Connection failed!";
        die("Connection failed: " . mysqli_connect_error());
    }
}

class DBi {

    public static $conn;

}

/**
 * @param string $vorname
 * @param string $nachname
 * @return array
 */
function get_brevet($nachname, $vorname) {
    $wildVorname = "%" . $vorname . "%";
    $wildNachname = "%" . $nachname . "%";

    $rV = array();

    $stm = DBi::$conn->prepare("Select Vorname, Nachname, Nummer, Datum, Ausbilder from ( ( Select wh.Vorname as Vorname, wh.Nachname as Nachname, max(wh.Datum) as Datum, pr.Nummer, us.Name as Ausbilder from wiederholung wh join pruefung pr on wh.Nachname = pr.Name and pr.Vorname = wh.Vorname join users us on us.userid = wh.AusbilderId group by wh.vorname, wh.nachname) union ( Select pr.Vorname as Vorname, pr.Name as Nachname, kr.ende as Datum, pr.Nummer as Nummer, us.Name as Ausbilder from pruefung pr join kurse kr on pr.Kurs = kr.id join users us on pr.AusbilderId = us.UserId ) ) as daten where Vorname LIKE ? and Nachname Like ? and Datum > (SELECT Date(DATE_SUB(now(), INTERVAL 6 Year))) group by Vorname, Nachname;");

    $stm->bind_param("ss", $wildVorname, $wildNachname);
    $stm->execute();
    $stm->bind_result($rVorname, $rNachname, $nummer, $datum, $ausbilder);
    while ($stm->fetch()) {

        $rV[] = array(htmlentities($rVorname), htmlentities($rNachname), $nummer, $datum, htmlentities($ausbilder));
    }
    $stm->close();
    return $rV;
}

/**
 * @return array
 */
function get_users() {
    $sql = "SELECT UserName,UserLevel FROM users ORDER BY UserName";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($userName, $userLevel);
    $erg = array();
    while ($stmt->fetch()) {
        $erg[] = array($userName, $userLevel);
    }

    $stmt->close();

    return $erg;
}

/**
 * @param string $jahr
 * @param string $lvov
 * @return array
 */
function get_statistik_LVOV($jahr, $lvov) {
    $wildJahr = "%" . $jahr . "%";
    $sql = "SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ?  AND `Verband` = ?";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->bind_param("ss", $wildJahr, $lvov);
    $stmt->execute();

    $stmt->bind_result($id, $bemerkung);
    $erg = array();
    while ($stmt->fetch()) {
        $erg[$id] = $bemerkung;
    }
    $stmt->close();
    return get_statistik_bySQL($erg);
}

/**
 * @param string $jahr
 * @return array
 */
function get_statistik($jahr) {
    $wildJahr = "%" . $jahr . "%";
    $sql = "SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ?";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->bind_param("s", $wildJahr);
    $stmt->execute();

    $stmt->bind_result($id, $bemerkung);
    $erg = array();
    while ($stmt->fetch()) {
        $erg[$id] = $bemerkung;
    }
    $stmt->close();
    return get_statistik_bySQL($erg);
}

function get_statistik_bySQL($array) {


    $bronze = 0;
    $silber = 0;
    $gold = 0;
    $junior = 0;
    $retter = 0;
    $leiter = 0;
    $bs = 0;
    $bb = 0;
    $asr = 0;
    $multiwr = 0;

    foreach ($array as $id => $bemerkung) {

        $sqlTemplate = "SELECT count(*) from `pruefung` WHERE `Kurs`= $id and `Nummer` LIKE ";

        $sql2 = $sqlTemplate . "'%-B%' ";
        $count = 0;
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $bronze = $bronze + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-S%' ";
        $count = 0;
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $silber = $silber + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-G%' ";
        $count = 0;
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $gold = $gold + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-J%' ";
        $count = 0;
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $junior = $junior + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-WR%' ";
        $count = 0;
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $retter = $retter + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-WL%' ";
        $count = 0;
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $leiter = $leiter + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-BS%' ";
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $bs = $bs + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-BB%' ";
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $bb = $bb + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-asr%' ";
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $asr = $asr + $count;
        }
        $stmt2->close();

        $sql2 = $sqlTemplate . "'%-mw%' ";
        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        while ($stmt2->fetch()) {
            $multiwr = $multiwr + $count;
        }
        $stmt2->close();
    }
    return array("Bronze" => $bronze, "Silber" => $silber, "Gold" => $gold, "Junioretter" => $junior, "Wasserretter" => $retter, "Wachleiter" => $leiter, "Bootsf&uuml;hrer See" => $bs, "Bootsf&uuml;hrer Binnen" => $bb, "Ausbilder Schwimmen und Rettungsschwimmen" => $asr, "Multiplikator Wasserretter" => $multiwr);
}

/**
 * @param string $jahr
 * @param string $lvov
 * @return array
 */
function get_pruefungen_lvov($jahr, $lvov) {
    $wildJahr = "%" . $jahr . "%";

    $stm = DBi::$conn->prepare("SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ? AND `Verband` = ?");
    $stm->bind_param("ss", $wildJahr, $lvov);

    return get_pruefungen_body($stm);
}

/**
 * @param string $jahr
 * @return array
 */
function get_pruefungen($jahr) {
    $wildJahr = "%" . $jahr . "%";

    $stm = DBi::$conn->prepare("SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ?");
    $stm->bind_param("s", $wildJahr);

    return get_pruefungen_body($stm);
}

function get_pruefungen_body($stmt) {
    $stmt->execute();
    $stmt->bind_result($id, $bemerkung);

    $kurse = array();
    while ($stmt->fetch()) {

        $kurse[$id] = $bemerkung;
    }

    $stmt->close();
    foreach ($kurse as $id => $bemerkung) {

        $sql2 = "SELECT count(*) from `pruefung` WHERE `Kurs`= $id ";

        $stmt2 = DBi::$conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->bind_result($count);
        if ($count == '') {
            $count = 0;
        }
        while ($stmt2->fetch()) {
            $rV[$bemerkung] = $count;
        }

        $stmt2->close();
    }
    return $rV;
}

/**
 * @param string $vorname
 * @param string $name
 * @param int $kurs
 * @param string $level
 * @return string prï¿½fungsnummer
 */
function eintragen_pruefung($name, $vorname, $kurs, $level, $ausbilderId) {

    $lfd = next_number($kurs);
    $stmSelect = DBi::$conn->prepare("SELECT `laufende_nummer`,`start`,`Verband` FROM `kurse` WHERE id= ?");
    $stmSelect->bind_param("i", $kurs);
    $stmSelect->execute();
    $stmSelect->bind_result($laufende_nummer, $start, $verband);
    while ($stmSelect->fetch()) {
        $kNummer = $laufende_nummer;
        $lvov = $verband;
        $jahr = substr($start, 2, 2);
    }

    $stmSelect->close();

    if ($kNummer == 0) {
        $kursNummer = "AN";
    } else {
        $kursNummer = str_pad($kNummer, 2, '0', STR_PAD_LEFT);
    }
    $rv = $lvov . "/" . $kursNummer . "/" . str_pad($lfd, 2, '0', STR_PAD_LEFT) . "/" . $jahr . "-" . $level;

    $stmInsert = DBi::$conn->prepare("INSERT INTO `pruefung` (`id`,`Vorname`,`Name`,`Kurs`,`Nummer`,`AusbilderId`) VALUES (?,?,?,?,?,?)");
    $stmInsert->bind_param("issisi", $lfd, $vorname, $name, $kurs, $rv, $ausbilderId);
    $stmInsert->execute();
    $stmInsert->close();

    return $rv;
}

/**
 * @param string $vorname
 * @param string $name
 * @param string $datum
 */
function eintragen_wiederholung($name, $vorname, $datum, $ausbilderId) {
    $sql = "INSERT INTO wiederholung (Vorname,Nachname,Datum,AusbilderId) VALUES (?,?,?,?)";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->bind_param("sssi", $vorname, $name, $datum, $ausbilderId);
    $stmt->execute();
    $stmt->close();
    return "Wiederholung eingetragen!";
}

/**
 * @param kurs
 * @return int
 */
function next_number($kurs) {
    $sql = "SELECT MAX(id) FROM pruefung WHERE Kurs= ?";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->bind_param("i", $kurs);
    $stmt->execute();
    $stmt->bind_result($maxID);

    while ($stmt->fetch()) {
        $rValue = $maxID;
    }
    $stmt->close();
    return $rValue + 1;
}

/**
 * @param
 * @return string
 */
function kurs_daten() {
    $sql = "SELECT id, laufende_nummer,start,ende,bemerkungen,Verband FROM kurse ORDER BY start DESC, laufende_nummer DESC";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($id, $laufende_nummer, $start, $ende, $bemerkungen, $Verband);
    while ($stmt->fetch()) {
        $erg[$id] = array($laufende_nummer, $start, $ende, $bemerkungen, $Verband);
    }
    return $erg;
}

/**
 * @param
 * @return string
 */
function get_ausbilder() {
    $sql = "SELECT UserId,Name FROM users WHERE ASR not LIKE '' or ATR not LIKE '' ORDER BY Name ASC;";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($userId, $name);
    $erg = array();
    while ($stmt->fetch()) {
        $erg[$userId] = $name;
    }
    return $erg;
}

/**
 * @param
 * @return string
 */
function jahreszahlen() {
    $sql = "SELECT DISTINCT SUBSTR(ende,1,4)  FROM kurse ORDER by id DESC";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($jahr);
    $erg = array();
    while ($stmt->fetch()) {
        $erg[] = $jahr;
    }
    $stmt->close();
    return $erg;
}

/**
 * @param
 * @return string
 */
function lvov() {
    $sql = "SELECT DISTINCT Verband FROM kurse ORDER by id ASC;";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($lvov);
    $erg = array();
    while ($stmt->fetch()) {
        $erg[] = $lvov;
    }
    $stmt->close();
    return $erg;
}

/**
 * @param string $userID
 * @return string
 */
function get_user_level($userID) {

    $rValue = false;
    $stmt = mysqli_prepare(DBi::$conn, "SELECT UserLevel FROM users Where UserID = ?;");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->bind_result($userLevel);

    while (mysqli_stmt_fetch($stmt)) {
        $rValue = $userLevel;
    }
    $stmt->close();
    return $rValue;
}

/**
 * @param string $userID
 * @return string
 */
function user_data($userID) {
    $sql = "SELECT UserName, UserMail, Name,ASR, ATR FROM users Where UserID = ?";

    $stmt = DBi::$conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->bind_result($userName, $userMail, $name, $aSR, $aTR);

    $rV = array();
    while ($stmt->fetch()) {
        $rv[0] = $userName;
        $rv[1] = $userMail;
        $rv[2] = $name;
        $rv[3] = $aSR;
        $rv[4] = $aTR;
    }

    return $rV;
}

/**
 * @param string $nummer
 * @param string $begin
 * @param string $ende
 * @param string $kommentar
 * @return void
 */
function eintragen_kurs($nummer, $begin, $ende, $kommentar, $verband) {
    $sql = "INSERT INTO kurse(laufende_nummer,start,ende,bemerkungen,Verband)VALUES (?,?,?,?,?)";
    $stmt = DBi::$conn->prepare($sql);
    $stmt->bind_param("issss", $nummer, $begin, $ende, $kommentar, $verband);
    $stmt->execute();
    $stmt->bind_result($rueckgabewert);
    while ($stmt->fetch()) {
        $zeile = $rueckgabewert;
    }
    $stmt->close();


    return $zeile;
}

function listeKurse($array) {
    $trtd = "<tr><td>";
    $tdetd = "</td><td>";
    $tdetre = "</td></tr>";

    $rString = "";
    foreach ($array as $id => $kursInfo) {
        $rString .= $trtd . $kursInfo[0] . $tdetd . $kursInfo[1] . $tdetd . $kursInfo[2] . $tdetd . htmlentities($kursInfo[3]) . $tdetd . $kursInfo[4] . $tdetre;
    }
    return $rString;
}

function kursSelector($array) {
    $rString = "";
    foreach ($array as $nummer => $kursDaten) {
        $rString .= "<option value=$nummer";
        if (isset($kurs) && $kurs == $nummer) {
            $rString .= " selected =\"selected\"";
        }

        $rString .= "> $kursDaten[0] /" . substr($kursDaten[1], 0, 4) . "</option>";
    }
    return $rString;
}

function lvOvSelector($array) {
    $rString = "";
    foreach ($array as $verband) {
        $rString .= "<option value=$verband> $verband</option>";
    }
    return $rString;
}

function kursjahrSelector($array) {
    $rString = "";

    foreach ($array as $datum) {
        $kursjahr = substr($datum, 0, 4);
        $rString .= "<option value=$kursjahr> $kursjahr</option>";
    }

    return $rString;
}

function ausbilderSelector($array) {
    $rString = "";

    foreach ($array as $id => $name) {
        $rString .= "<option value=$id>" . htmlspecialchars($name) . "</option>";
    }
    return $rString;
}

/**
 * @param string $name
 * @param string $email
 * @param string $userID
 * @return void
 */
function change_userdata($name, $email, $userID) {
    $sql = "UPDATE users SET UserName = ?,UserMail = ? Where UserID = ?";
    $stmt = mysqli_prepare(DBi::$conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $erg);
    mysqli_stmt_close($stmt);
    if (!$erg) {
        die(UNGAB . mysqli_error(DBi::$conn) . UNGABSQL . $sql);
    }
    return "Änderungen übernommen!";
}

/**
 * @param string $name
 * @param string $email
 * @param string $userID
 * @return void
 */
function change_userdata2($userName, $email, $userID, $level, $name, $asr, $atr) {
    $sql = "UPDATE users SET UserName = ?,UserMail = ?, UserLevel = ?, Name = ?, ASR = ?, ATR = ? Where UserID = ?";
    $stmt = mysqli_prepare(DBi::$conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $userName, $email, $level, $name, $asr, $atr, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $erg);
    mysqli_stmt_close($stmt);
    if (!$erg) {
        die(UNGAB . mysqli_error(DBi::$conn) . UNGABSQL . $sql);
    }
    return "Änderungen übernommen!";
}

/**
 * @param string $pass
 * @param string $userID
 * @return void
 */
function change_pass($pass, $userID) {
    $md5Pass = md5($pass);
    $sql = "UPDATE users SET UserPass = ? Where UserID = ?";
    $stmt = mysqli_prepare(DBi::$conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $md5Pass, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $erg);
    mysqli_stmt_close($stmt);
    if (!$erg) {
        die(UNGAB . mysqli_error(DBi::$conn) . UNGABSQL . $sql);
    }
    return "Passwortänderungen übernommen!";
}

/**
 * @param string $name
 * @param string $level
 * @return boolean
 */
function delete_user($name, $level) {

    $sql = "DELETE from `users` WHERE `UserName` = ? AND `UserLevel` = ?";
    $stmt = mysqli_prepare(DBi::$conn, $sql);
    $stmt->bind_param("si", $name, $level);
    $stmt->execute();
    $erg = $stmt->affected_rows;
    if (!$erg) {
        echo "Ergebnis: $erg ";
        echo UNGAB . mysqli_error(DBi::$conn) . UNGABSQL . $sql;
        die(UNGAB . mysqli_error(DBi::$conn) . UNGABSQL . $sql);
    } else {
        return 'Nutzer entfernt!';
    }
}

/**
 * @param string $name
 * @param string $pass
 * @param string $email
 * @param string $level


 * @return boolean
 */
function insert_user($username, $name, $pass, $email, $level, $atr, $asr) {
    $md5Pass = md5($pass);
    $sql = "INSERT INTO `users`(`UserName`, `Name`, `UserPass`, `UserMail`, `UserLevel`, `ATR`, `ASR`)VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare(DBi::$conn, $sql);
    $stmt->bind_param("ssssiss", $username, $name, $md5Pass, $email, $level, $atr, $asr);
    $stmt->execute();
    $stmt->bind_result($erg);
    if (!$erg) {
        echo UNGAB . mysqli_error(DBi::$conn) . UNGABSQL . $sql;
        die(UNGAB . mysqli_error(DBi::$conn) . UNGABSQL . $sql);
    } else {
        return 'Neuer Nutzer eingetragen!';
    }
}

/**
 * @param string $name
 * @param string $pass


 * @return boolean
 */
function check_user($name, $pass) {
    $md5Pass = md5($pass);
    $stmt = mysqli_prepare(DBi::$conn, "SELECT UserId FROM users WHERE UserName = ? AND UserPass = ?");
    mysqli_stmt_bind_param($stmt, "ss", $name, $md5Pass);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);
    while (mysqli_stmt_fetch($stmt)) {
        $rValue = $id;
    }

    return $rValue;
}

/**
 * @param int $userid


 * @return void
 */
function login($userid) {
    $session = session_id();
    $stmt = mysqli_prepare(DBi::$conn, "UPDATE users SET UserSession = ? WHERE UserId = ?");
    mysqli_stmt_bind_param($stmt, "si", $session, $userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt
    );
}

/**
 *
 * @return boolean
 */
function logged_in() {
    $session = session_id();
    $stmt = mysqli_prepare(DBi::$conn, "SELECT UserId FROM users WHERE UserSession = ?");

    mysqli_stmt_bind_param($stmt, "s", $session);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $erg = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return ($erg == 1);
}

/**
 * Logout-Funktion, setzt den Wert fÃ¼r die session_id auf leer
 * @return void
 */
function logout() {



    $session = session_id();
    $stmt = mysqli_prepare(DBi::$conn, "UPDATE users SET UserSession = '' WHERE UserSession = ?");
    mysqli_stmt_bind_param($stmt, "s", $session);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

connect();
?>