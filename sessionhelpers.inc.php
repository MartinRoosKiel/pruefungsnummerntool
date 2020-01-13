<?php
include('connect.inc.php');

/**
 * @return void
 */
 
 define("FNAME" ,"Vorname");
 define("NAME","Nachname");
 define("PNUMBER","Pruefungsnummer");
 define("AUSBILDER","Ausbilder");
 defeine("DATUM","Datum");
 
function connect () {
   
    DBi::$con = mysqli_connect('localhost', USERNAME, PASSWORD) || exit(mysqli_connect_error());
mysqli_select_db( DBi::$con,TABLE) || exit(mysqli_connect_error());}

class DBi{
	public static $con;
}

/**
* @param string $vorname
* @param string $nachname
* @return array
*/
function get_suche($nachname,$vorname)
{ 
	$rV = array(FNAME,NAME,PNUMBER,AUSBIDLER);
	$sql = "SELECT pruefung.Vorname,pruefung.Name,pruefung.Nummer ,users.Name from `pruefung` Inner join users ON pruefung.AusbilderId = users.UserId WHERE pruefung.Vorname LIKE '%".$vorname."%' AND pruefung.Name LIKE '%".$nachname."%'";
	
	$erg = mysqli_query(DBi::$con,$sql);
	while($row = mysqli_fetch_array($erg))
	{
			array_push($rV,$row[0],$row[1],$row[2],$row[3]);	
	}
	return $rV;
}

/**
* @param string $vorname
* @param string $nachname
* @return array
*/
function get_brevet($nachname,$vorname)
{ 
	$rV = array(FNAME,NAME,PNUMBER,DATUM, AUSBILDER);
	$sql = "Select Vorname, Nachname,  Nummer, Datum, Ausbilder from ( (Select pr.Vorname as Vorname, pr.Name as Nachname, ks.Ende as Datum, pr.Nummer as Nummer, us.Name as Ausbilder from pruefung pr join kurse ks on pr.kurs = ks.id join users us on us.UserId = pr.AusbilderId) union (Select wh.Vorname as Vorname, wh.Nachname as Nachname, wh.Datum, pr.Nummer, us.Name as Ausbilder from wiederholung wh join pruefung pr on wh.Nachname = pr.Name and pr.Vorname = wh.Vorname join users us on us.userId = wh.AusbilderId)) as daten where Datum = (Select max(datum) from ((Select ks.Ende as Datum from pruefung pr join kurse ks on pr.kurs = ks.id where pr.Vorname LIKE '%".$vorname."%' and pr.Name LIKE '%".$nachname."%' ) Union ( Select wh.Datum as Datum from wiederholung wh where wh.Vorname LIKE '%".$vorname."%' and wh.Nachname LIKE '%".$nachname."%' )) as datum ) AND daten.Vorname LIKE '%".$vorname."%' and daten.Nachname LIKE '%".$nachname."%'  and Datum > (SELECT Date(DATE_SUB(now(), INTERVAL 6 Year)))";
	$erg = mysqli_query(DBi::$con,$sql);
	while($row = mysqli_fetch_array($erg))
	{
			array_push($rV,$row[0],$row[1],$row[2],$row[3],$row[4]);	
	}
	return $rV;
}

/**
* @param string $vorname
* @param string $nachname
* @return array
*/
function get_wiederholung($nachname,$vorname)
{ 
	$rV = array(FNAME,NAME,DATUM, AUSBILDER);
	$sql = "SELECT wiederholung.Vorname,wiederholung.Nachname,MAX(wiederholung.Datum),us.Name from `wiederholung` Inner join users us ON wiederholung.AusbilderId = users.UserId WHERE wiederholung.Vorname LIKE '%".$vorname."%' AND wiederholung.Nachname LIKE '%".$nachname."%' GROUP BY Vorname, Nachname";
	$erg = mysqli_query(DBi::$con,$sql);
	while($row = mysqli_fetch_array($erg))
	{
			array_push($rV,$row[0],$row[1],$row[2],$row[3]);	
	}
	return $rV;
}

/**
* @return array
*/
function get_users()
{ 
	$rV = array();
	$sql = "SELECT UserName,UserLevel FROM `users` ORDER BY UserName";
	$erg = mysqli_query(DBi::$con,$sql);
	while($row = mysqli_fetch_array($erg))
	{
			array_push($rV,$row[0],$row[1]);	
	}
	return $rV;
}

/**
* @param string $jahr
* @param string $lvov
* @return array
*/
function get_statistik_LVOV($jahr, $lvov)
{ 

	$sql = "SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE '%".$jahr."%' AND `Verband` = ".$lvov;
	return get_statistik_bySQL($sql);
}

/**
* @param string $jahr
* @return array
*/
function get_statistik($jahr)
{ 

	$sql = "SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE '%".$jahr."%';";
	return get_statistik_bySQL($sql);

}

function get_statistik_bySQL($sql)
{
		$erg = mysqli_query(DBi::$con,$sql);
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

	while($row = mysqli_fetch_array($erg))
	{
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-B%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$bronze = $bronze + mysqli_num_rows($erg2);

		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-S%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$silber = $silber + mysqli_num_rows($erg2);

		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-G%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$gold = $gold + mysqli_num_rows($erg2);
		
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-J%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$junior = $junior + mysqli_num_rows($erg2);
		
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-WR%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$retter = $retter + mysqli_num_rows($erg2);
		
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-WL%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$leiter = $leiter + mysqli_num_rows($erg2);
		
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-BS%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$leiter = $bs + mysqli_num_rows($erg2);
		
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-BB%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$leiter = $bb + mysqli_num_rows($erg2);
		
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-asr%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$leiter = $asr + mysqli_num_rows($erg2);
		
		$sql2 = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE '%-mw%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$leiter = $multiwr + mysqli_num_rows($erg2);
		
		
		
	}

	$rV = array("Bronze",$bronze,"Silber",$silber,"Gold",$gold,"Junioretter",$junior,"Wasserretter",$retter,"Wachleiter",$leiter,"Bootsf&uuml;hrer See", $bs,"Bootsf&uuml;rer Binnen",$bb, "Ausbilder Schwimmen und Rettungsschwimmen",$asr, "Multiplikator Wasserretter", $multiwr);
	return $rV;
}
	
/**
* @param string $jahr
* @param string $lvov
* @return array
*/
function get_pruefungen_lvov($jahr,$lvov)
{ 

	$sql = "SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE '%".$jahr."%' AND `Verband` = ".$lvov;
	$erg = mysqli_query(DBi::$con,$sql);
	$rV = array("Kurs-Bezeichnung","Abnahmen");

	while($row = mysqli_fetch_array($erg))
	{
		$sql2 = "SELECT MAX(id) from `pruefung` WHERE `Kurs`= $row[0] ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$erg2 = mysqli_fetch_row($erg2);
		if($erg2[0]=='')
		{
			$erg2[0] = 0;
		}
		array_push($rV,$row[1],$erg2[0]);	
	}
	return $rV;
}
/**
* @param string $jahr
* @return array
*/
function get_pruefungen($jahr)
{ 

	$sql = "SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE '%".$jahr."%';";
	$erg = mysqli_query(DBi::$con,$sql);
	$rV = array("Kurs-Bezeichnung","Abnahmen");

	while($row = mysqli_fetch_array($erg))
	{
		$sql2 = "SELECT MAX(id) from `pruefung` WHERE `Kurs`= $row[0] ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$erg2 = mysqli_fetch_row($erg2);
		if($erg2[0]=='')
		{
			$erg2[0] = 0;
		}
		array_push($rV,$row[1],$erg2[0]);	
	}
	return $rV;
}

/**
 * @param string $vorname
 * @param string $name
 * @param int $kurs
 * @param string $level
 * @return string pr�fungsnummer
 */
function eintragen_pruefung($name,$vorname,$kurs,$level,$ausbilderId)
{

	$lfd = next_number($kurs);
 	$sql = "SELECT `laufende_nummer`,`start`,`Verband` FROM `kurse` WHERE id= $kurs";
	$erg = mysqli_query(DBi::$con,$sql);
	$zeile = mysqli_fetch_row($erg);
	$kNummer = $zeile[0];
	if($kNummer==0)
	{
		$kursNummer = "AN";
	}
	else
	{
		$kursNummer = str_pad($kNummer, 2 ,'0', STR_PAD_LEFT);
	}
	$rv = $zeile[2]."/".$kursNummer."/".str_pad($lfd, 2 ,'0', STR_PAD_LEFT)."/".substr($zeile[1],2,2)."-".$level;


$sql = "INSERT INTO `pruefung` (`id`,`Vorname`,`Name`,`Kurs`,`Nummer`,`AusbilderId`) VALUES ('".$lfd."','".$vorname."','".$name."','".$kurs."', '".$rv."', '".$ausbilderId."');";
$db_erg = mysqli_query(DBi::$con,$sql);

	return $rv;
	
}

/**
 * @param string $vorname
 * @param string $name
 * @param string $datum
 */
function eintragen_wiederholung($name,$vorname,$datum,$ausbilderId)
{

$sql = "INSERT INTO `wiederholung` (`Vorname`,`Nachname`,`Datum`,`AusbilderId`) VALUES ('".$vorname."','".$name."', '".$datum."', '".$ausbilderId."');";
$db_erg = mysqli_query(DBi::$con,$sql);
	
}



/**
 * @param kurs 
 * @return int
 */
function next_number($kurs)
{
	$sql = "SELECT MAX(id) FROM `pruefung` WHERE `Kurs`= $kurs;";
	$db_erg = mysqli_query(DBi::$con,$sql);
	if( !$db_erg)
	{
		die('Ung�ltige Abfrage:'.mysqli_error(DBi::$con).' sql:'.$sql);	
	}
	$row = mysqli_fetch_row($db_erg);
	$rV = $row[0]+1;
	return $rV;
}

/**
 * @param 
 * @return string
 */
function kurs_daten()
{
	$sql = "SELECT * FROM `kurse` ORDER BY `start` DESC, `laufende_nummer` DESC;";

	$db_erg = mysqli_query(DBi::$con,$sql);
	if( !$db_erg)
	{
		die('Ung�ltige Abfrage:'.mysqli_error(DBi::$con).' sql:'.$sql);	
	}
	return $db_erg;
}
/**
* @param
* @return string
*/
function get_ausbilder()
{

 $sql = "SELECT UserId,Name FROM `users` WHERE ASR not LIKE '' or ATR not LIKE '' ORDER BY `Name` ASC;"; 

	$db_erg = mysqli_query(DBi::$con,$sql);
	if( !$db_erg)
	{
		die('Ung�ltige Abfrage:'.mysql_error(DBi::$con).' sql:'.$sql);	
	}
	return $db_erg;
}

/**
 * @param 
 * @return string
 */
function jahreszahlen()
{
	$sql = "SELECT DISTINCT SUBSTR(`ende`,1,4)  FROM `kurse` ORDER by `id` DESC;";

	$db_erg = mysqli_query(DBi::$con,$sql);
	if( !$db_erg)
	{
		die('Ung�ltige Abfrage:'.mysqli_error(DBi::$con).' sql:'.$sql);	
	}
	return $db_erg;
}

/**
 * @param 
 * @return string
 */
function lvov()
{
	$sql = "SELECT DISTINCT `Verband` FROM `kurse` ORDER by `id` ASC;";

	$db_erg = mysqli_query(DBi::$con,$sql);
	if( !$db_erg)
	{
		die('Ung�ltige Abfrage:'.mysqli_error(DBi::$con).' sql:'.$sql);	
	}
	return $db_erg;
}




/**
 * @param string $userID
 * @return string
 */
function get_user_level($userID)
{
	$sql = "SELECT `UserLevel` FROM `users` Where UserID =".$userID.";";
	$db_erg = mysqli_query(DBi::$con, $sql );
	if ( ! $db_erg )
	{
  		die('Ung�ltige Abfrage: ' . mysqli_error(DBi::$con));
	}
	$zeile = mysqli_fetch_row($db_erg);
	$rv = $zeile[0];
  	return $rv;
}

/**
 * @param string $userID
 * @return string
 */
function user_data($userID)
{
	$sql = "SELECT `UserName`, `UserMail`, `Name`,`ASR`,`ATR` FROM `users` Where UserID =".$userID.";";
	$db_erg = mysqli_query(DBi::$con, $sql );
	if ( ! $db_erg )
	{
  		die('Ung�ltige Abfrage: ' . mysqli_error(DBi::$con));
	}
	$zeile = mysqli_fetch_row($db_erg);
	$rv[0] = $zeile[0];
	$rv[1] = $zeile[1];
	$rv[2] = $zeile[2];
	$rv[3] = $zeile[3];
	$rv[4] = $zeile[4];
  	return $rv;
}

/**
 * @param string $nummer
 * @param string $begin
 * @param string $ende
 * @param string $kommentar
 * @return void
 */


function eintragen_kurs($nummer, $begin, $ende, $kommentar, $verband)
{
	$sql = $sql = "INSERT INTO `kurse`(`laufende_nummer`,`start`,`ende`,`bemerkungen`,`Verband`)VALUES ('$nummer','$begin','$ende','$kommentar','$verband')";
	$db_erg = mysqli_query(DBi::$con, $sql );
	$zeile = mysqli_fetch_row($db_erg);
	
	return $zeile[0];
	
}

function listeKurse($liste)
{
	$rString ="";
	while($row = mysqli_fetch_array($liste))
				{
					$rString .=  "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[5]."</td><td>".$row[4]."</td></tr>";
				}
	return $rString;
}


function kursSelector($liste)
{
	$rString ="";
	while($row = mysqli_fetch_array($liste))
				{
					$kursjahr = substr($row[2],0,4);
					$rString .=  "<option value=$row[0]";
					if(isset($kurs)&&$kurs == $row[0]){ $rString.= " selected =\"selected\"";}
					
					$rString .= "> $row[1] / $kursjahr</option>";
				}
	return $rString;
}

function lvOvSelector($liste)
{
	$rString ="";
	while($row = mysqli_fetch_array($liste))
				{
					$verband = $row[0];
					$rString .= "<option value=$verband> $verband</option>";
			}
			return $rString;
}

function kursjahrSelector($liste)
{
	$rString ="";
	
	while($row = mysqli_fetch_array($liste))
				{
					$kursjahr = substr($row[0],0,4);
					$rString .=  "<option value=$kursjahr> $kursjahr</option>";
				}
	
	return $rString;
	
}

function ausbilderSelector($liste)
{
	$rString ="";
	
	while($row = mysqli_fetch_array($liste))
				{
					$rString .=  "<option value=$row[0]> $row[1]</option>";
				}
	
	return $rString;
	
}

/**
 * @param string $name
 * @param string $email
 * @param string $userID
 * @return void
 */
function change_userdata($name,$email,$userID)
{
	$sql = 'UPDATE users SET UserName = \''.$name.'\',UserMail =\''.$email.'\' Where UserID = \''.$userID.'\'';
	$db_erg = mysqli_query(DBi::$con, $sql );
	
	
}

/**
 * @param string $userID
 * @param string $pass
 * @return void
 */
function change_pass($pass,$userID)
{
	$sql = 'UPDATE users SET UserPass = \''.md5($pass).'\' Where UserID = \''.$userID.'\'';
	$db_erg = mysqli_query(DBi::$con, $sql );
	
	
}

/**
 * @param string $name
 * @param string $pass
 * @param string $email
 * @param string $level
 * @return boolean
 */
function delete_user ( $name,$level ) {
    // magic quotes anpassen
    if ( get_magic_quotes_gpc() ) {
        $name = stripslashes($name);
		$level = stripslashes($level);
    }
	$sql = $sql = "DELETE from `users` WHERE `UserName` = '$name' AND `UserLevel` = '$level'";
	$db_erg = mysqli_query(DBi::$con, $sql );
	if (!$db_erg) {
   die('Invalid query: ' . mysqli_error(DBi::$con));
}
else
{return 'Nutzer entfernt!';}
   
}

/**
 * @param string $name
 * @param string $pass
 * @param string $email
 * @param string $level
 * @return boolean
 */
function insert_user ( $name,$pass,$email,$level ) {
    // magic quotes anpassen
    if ( get_magic_quotes_gpc() ) {
        $name = stripslashes($name);
        $pass = stripslashes($pass);
		$email = stripslashes($email);
		$level = stripslashes($level);
		$name = stripslashes($name);
		$asr = stripslashes($asr);
		$atr = stripslashes($atr);
    }
	$sql = $sql = "INSERT INTO `users`(`UserName`,`UserPass`,`UserMail`,`UserLevel`)VALUES ('$name','".md5($pass)."','$email','$level')";
	$db_erg = mysqli_query(DBi::$con, $sql );
	if (!$db_erg) {
   die('Invalid query: ' . mysqli_error(DBi::$con));
}
else
{return 'Neuer Nutzer eingetragen!';}
   
}

/**
 * @param string $name
 * @param string $pass
 * @return boolean
 */
function check_user ( $name, $pass ) {
    // magic quotes anpassen
    if ( get_magic_quotes_gpc() ) {
        $name = stripslashes($name);
        $pass = stripslashes($pass);
    }
    // escapen von \\, \x00, \n, \r, \, ', " und \x1a
    $name = mysqli_real_escape_string(DBi::$con,$name);
    // escapen von Backticks (`)
    $name = preg_replace("/ \x60/", "/ \\\x60(", $name);
    // escapen von % und _
    $name = str_replace('%', '\%', $name);
    $name = str_replace('_', '\_', $name);

    $sql = 'SELECT UserId FROM users WHERE UserName = \'' . $name . '\' AND UserPass=\'' . md5($pass) . '\'';
    if ( !$result = mysqli_query(DBi::$con,$sql) ) {
        exit(mysqli_error(DBi::$con));
    }
    if ( mysqli_num_rows($result) == 1 ) {
        $user = mysqli_fetch_assoc($result);
        return ( $user['UserId'] );
    } else {
        return ( false );
    }
}


/**
 * @param int $userid
 * @return void
 */
function login ( $userid ) 
{
    	$sql = 'UPDATE users SET UserSession = \'' . session_id() . '\' WHERE UserId = ' . ((int)$userid);
    	if ( !mysqli_query(DBi::$con,$sql) ) 
	{
        	exit(mysqli_error(DBi::$con));
  	}
}


/**
 * @return boolean
 */
function logged_in () {
    $sql = 'SELECT UserId FROM users WHERE UserSession = \'' . session_id() . '\'';
    if ( !$result = mysqli_query(DBi::$con,$sql) ) {
        exit(mysqli_error(DBi::$con));
    }
    return (mysqli_num_rows($result) == 1);
}


/**
 * @return void
 */
function logout () {
    $sql = 'UPDATE users SET UserSession = \'\' WHERE UserSession = \'' . session_id() . '\'';
    if ( mysqli_query(DBi::$con,$sql) ) {
        exit(mysqli_error(DBi::$con));
    }
}

connect();

?>
