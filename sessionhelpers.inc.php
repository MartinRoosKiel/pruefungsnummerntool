<?php
include('connect.inc.php');

/**
 * @return void
 */
 
 define("FNAME" ,"Vorname");
 define("NAME","Nachname");
 define("PNUMBER","Pruefungsnummer");
 define("AUSBILDER","Ausbilder");
 define("DATUM","Datum");
 define("UNGAB","Ung&uuml;ltige Abfrage:");
 define("UNGABSQL"," sql:");
 
function connect () {
   
    DBi::$con = new mysqli('localhost', USERNAME, PASSWORD, TABLE);
	if(DBi::$con->connect_errno){ 
		die("Verbindung fehlgeschlagen");
		}
}

class DBi{
	public static $con;
}

 

/**
* @param string $vorname
* @param string $nachname
* @return array
*/
function get_brevet($nachname,$vorname)
{ 
    $wildVorname = "%".$vorname."%";
    $wildNachname ="%".$nachname."%";
    
	$rV = [];
        
	$stm = DBi::$con->prepare("Select Vorname, Nachname,  Nummer, Datum, Ausbilder from ( (Select pr.Vorname as Vorname, pr.Name as Nachname, ks.Ende as Datum, pr.Nummer as Nummer, us.Name as Ausbilder from pruefung pr join kurse ks on pr.kurs = ks.id join users us on us.UserId = pr.AusbilderId) union (Select wh.Vorname as Vorname, wh.Nachname as Nachname, wh.Datum, pr.Nummer, us.Name as Ausbilder from wiederholung wh join pruefung pr on wh.Nachname = pr.Name and pr.Vorname = wh.Vorname join users us on us.userId = wh.AusbilderId)) as daten where Datum = (Select max(datum) from ((Select ks.Ende as Datum from pruefung pr join kurse ks on pr.kurs = ks.id where pr.Vorname LIKE ? and pr.Name LIKE ? ) Union ( Select wh.Datum as Datum from wiederholung wh where wh.Vorname LIKE ? and wh.Nachname LIKE ? )) as datum ) AND daten.Vorname LIKE ? and daten.Nachname LIKE ?  and Datum > (SELECT Date(DATE_SUB(now(), INTERVAL 6 Year)))");
	$stm->bind_param("ssssss",$wildVorname,$wildNachname,$wildVorname,$wildNachname,$wildVorname,$wildNachname);
        $stm->execute();
        $erg = $stm->get_result();
	while($row = mysqli_fetch_array($erg))
	{
			array_push($rV,$row[0],$row[1],$row[2],$row[3],$row[4]);	
	}
        $stm->close();  
	return $rV;
}

/**
* @return array
*/
function get_users()
{ 
	$rV = array();
	$stm = DBi::$con->prepare("SELECT UserName,UserLevel FROM `users` ORDER BY UserName");
        $stm->execute();
	$erg =$stm->get_result();
	while($row = mysqli_fetch_array($erg))
	{
			array_push($rV,$row[0],$row[1]);	
	}
        $stm->close(); 
	return $rV;
}

/**
* @param string $jahr
* @param string $lvov
* @return array
*/
function get_statistik_LVOV($jahr, $lvov)
{ 
    $wildJahr ="%".$jahr."%";

	$stm = DBi::$con->prepare("SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ?  AND `Verband` = ?");
        $stm->bind_param("ss",$wildJahr,$lvov);
	return get_statistik_bySQL($stm);
}

/**
* @param string $jahr
* @return array
*/
function get_statistik($jahr)
{ 
    $wildJahr ="%".$jahr."%";

	$stm = DBi::$con->prepare("SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ?");
        $stm->bind_param("s",$wildJahr);
	return get_statistik_bySQL($stm);

}

function get_statistik_bySQL($stm)
{
        $stm->execute();
	$erg =$stm->get_result();
        
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
		$sqlTemplate = "SELECT id from `pruefung` WHERE `Kurs`= $row[0] and `Nummer` LIKE ";
		
		$sql2 = $sqlTemplate."'%-B%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$bronze = $bronze + mysqli_num_rows($erg2);

		$sql2 = $sqlTemplate."'%-S%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$silber = $silber + mysqli_num_rows($erg2);

		$sql2 = $sqlTemplate."'%-G%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$gold = $gold + mysqli_num_rows($erg2);
		
		$sql2 = $sqlTemplate."'%-J%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$junior = $junior + mysqli_num_rows($erg2);
		
		$sql2 = $sqlTemplate."'%-WR%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$retter = $retter + mysqli_num_rows($erg2);
		
		$sql2 = $sqlTemplate."'%-WL%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$leiter = $leiter + mysqli_num_rows($erg2);
		
		$sql2 = $sqlTemplate."'%-BS%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$bs = $bs + mysqli_num_rows($erg2);
		
		$sql2 = $sqlTemplate."'%-BB%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$bb = $bb + mysqli_num_rows($erg2);
		
		$sql2 = $sqlTemplate."'%-asr%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$asr = $asr + mysqli_num_rows($erg2);
		
		$sql2 = $sqlTemplate."'%-mw%' ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$multiwr = $multiwr + mysqli_num_rows($erg2);
	
	}
$stm->close(); 
	return  array("Bronze",$bronze,"Silber",$silber,"Gold",$gold,"Junioretter",$junior,"Wasserretter",$retter,"Wachleiter",$leiter,"Bootsf&uuml;hrer See", $bs,"Bootsf&uuml;rer Binnen",$bb, "Ausbilder Schwimmen und Rettungsschwimmen",$asr, "Multiplikator Wasserretter", $multiwr);
}
	
/**
* @param string $jahr
* @param string $lvov
* @return array
*/
function get_pruefungen_lvov($jahr,$lvov)
{ 
    $wildJahr = "%".$jahr."%";

	$stm = DBi::$con->prepare("SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ? AND `Verband` = ?");
        $stm->bind_param("ss",$wildJahr,$lvov);
        
	return get_pruefungen_body($stm);
}
/**
* @param string $jahr
* @return array
*/
function get_pruefungen($jahr)
{ 
    $wildJahr = "%".$jahr."%";

	$stm = DBi::$con->prepare("SELECT id,bemerkungen FROM `kurse` WHERE `ende` LIKE ?");
	$stm->bind_param("s",$wildJahr);
        
        return get_pruefungen_body($stm);
}

function get_pruefungen_body($stm)
{
        $stm->execute();
        $erg =$stm->get_result();
	$rV = array("Kurs-Bezeichnung","Abnahmen");

	while($row = mysqli_fetch_array($erg))
	{
		$sql2 = "SELECT count(*) from `pruefung` WHERE `Kurs`= $row[0] ";
		$erg2 = mysqli_query(DBi::$con,$sql2);
		$erg2 = mysqli_fetch_row($erg2);
		if($erg2[0]=='')
		{
			$erg2[0] = 0;
		}
		array_push($rV,$row[1],$erg2[0]);	
	}
        $stm->close(); 
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
 	$stmSelect = DBi::$con->prepare("SELECT `laufende_nummer`,`start`,`Verband` FROM `kurse` WHERE id= ?");
        $stmSelect->bind_param("i",$kurs);
        $stmSelect->execute();
        
	$erg = $stmSelect->get_result();
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
        $stmSelect->close(); 

        $stmInsert = DBi::$con->prepare("INSERT INTO `pruefung` (`id`,`Vorname`,`Name`,`Kurs`,`Nummer`,`AusbilderId`) VALUES (?,?,?,?,?,?)");
        $stmInsert->bind_param("issisi",$lfd,$vorname,$name,$kurs,$rv,$ausbilderId);
        $stmInsert->execute();
        $stmInsert->close();
        
	return $rv;
	
}

/**
 * @param string $vorname
 * @param string $name
 * @param string $datum
 */
function eintragen_wiederholung($name,$vorname,$datum,$ausbilderId)
{
$sql= "INSERT INTO `wiederholung` (`Vorname`,`Nachname`,`Datum`,`AusbilderId`) VALUES (?,?,?,?)";
$stm = Dbi::$con-prepare($sql);
        $stm->bind_param("sssi",$vorname,$name,$datum,$ausbilderId);
        $stm->execute();
        $erg = $setm->get_result();
        if( !$erg)
	{
		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);	
	}
        $stm->close();
	
}



/**
 * @param kurs 
 * @return int
 */
function next_number($kurs)
{$sql = "SELECT MAX(id) FROM `pruefung` WHERE `Kurs`= ?";
	$stm = DBi::$con->prepare($sql);
        $stm->bind_param("i", $kurs);
        $stm->execute();
        $erg = $stm->get_result();
        $stm->close();
	if( !$erg)
	{
		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);	
	}
	$row = mysqli_fetch_row($erg);
	return $row[0]+1;
}

/**
 * @param 
 * @return string
 */
function kurs_daten()
{$sql = "SELECT * FROM `kurse` ORDER BY `start` DESC, `laufende_nummer` DESC";
	$stm = DBi::$con->prepare($sql);
        $stm->execute();
	$erg = $stm->get_result();
        
        $stm->close();
	if( !$erg)
	{
		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	return $erg;
}
/**
* @param
* @return string
*/
function get_ausbilder()
{ $sql = "SELECT UserId,Name FROM `users` WHERE ASR not LIKE '' or ATR not LIKE '' ORDER BY `Name` ASC;"; 

	$db_erg = mysqli_query(DBi::$con,$sql);
	if( !$db_erg)
	{
		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	return $db_erg;
}

/**
 * @param 
 * @return string
 */
function jahreszahlen()
{$sql = "SELECT DISTINCT SUBSTR(`ende`,1,4)  FROM `kurse` ORDER by `id` DESC";
	$stm = Dbi::$con->prepare($sql);
        $stm->execute();
	$erg = $stm->get_result();
        $stm->close();
	if( !$erg)
	{
		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	return $erg;
}

/**
 * @param 
 * @return string
 */
function lvov()
{
	$sql = "SELECT DISTINCT `Verband` FROM `kurse` ORDER by `id` ASC;";
$stm= DBi::$con->prepare($sql);
$stm->execute();
$erg = $stm->get_result();
$stm->close();
	if( !$erg)
	{
		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	return $erg;
}




/**
 * @param string $userID
 * @return string
 */
function get_user_level($userID)
{
	$sql = "SELECT `UserLevel` FROM `users` Where UserID =".$userID.";";
        $stm= DBi::$con->prepare($sql);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	$erg = mysqli_query(DBi::$con, $sql );
	if ( ! $erg )
	{
  		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	$zeile = mysqli_fetch_row($erg);
  	return  $zeile[0];
}

/**
 * @param string $userID
 * @return string
 */
function user_data($userID)
{
	$sql = "SELECT `UserName`, `UserMail`, `Name`,`ASR`,`ATR` FROM `users` Where UserID =".$userID.";";
	        $stm= DBi::$con->prepare($sql);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	if ( ! $erg )
	{
  		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	$zeile = mysqli_fetch_row($erg);
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
	        $stm= DBi::$con->prepare($sql);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	$zeile = mysqli_fetch_row($erg);
	
	return $zeile[0];
	
}

function listeKurse($liste)
{
	$trtd ="<tr><td>";
	$tdetd ="</td><td>";
	$tdetre ="</td></tr>";

	$rString ="";
	while($row = mysqli_fetch_array($liste))
				{
					$rString .=  $trtd.$row[1].$tdetd.$row[2].$tdetd.$row[3].$tdetd.$row[5].$tdetd.$row[4].$tdetre;
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
	$sql = "UPDATE users SET UserName = ?,UserMail = ? Where UserID = ?";
                $stm= DBi::$con->prepare($sql);
                $stm->bind_param("ssi",$name,$email,$userID);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	if ( ! $erg )
	{
  		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	
}

/**
 * @param string $userID
 * @param string $pass
 * @return void
 */
function change_pass($pass,$userID)
{
    $md5Pass = md5($pass);
	$sql = "UPDATE users SET UserPass = ? Where UserID = ?";
                $stm= DBi::$con->prepare($sql);
                $stm->bind_param("si",$md5Pass,$userID);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	if ( ! $erg )
	{
  		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
	
	
}

/**
 * @param string $name
 * @param string $level
 * @return boolean
 */
function delete_user ( $name,$level ) {
        
        $sql = "DELETE from `users` WHERE `UserName` = ? AND `UserLevel` = ?";
                $stm= DBi::$con->prepare($sql);
                $stm->bind_param("si",$name,$level);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	if ( ! $erg )
	{
  		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
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
    $md5Pass = md5($pass);
	$sql = "INSERT INTO `users`(`UserName`,`UserPass`,`UserMail`,`UserLevel`)VALUES (?,?,?,?)";
	
   $stm= DBi::$con->prepare($sql);
                $stm->bind_param("sssi",$name,$md5Pass,$email,$level);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	if ( ! $erg )
	{
  		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
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

        $md5Pass = md5($pass);
	 $sql = "SELECT UserId FROM users WHERE UserName = ? AND UserPass=?";
   
   $stm= DBi::$con->prepare($sql);
                $stm->bind_param("ss",$name,$md5Pass);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
	if ( ! $erg )
	{
  		die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
	}
    if ( mysqli_num_rows($erg) == 1 ) {
        $user = mysqli_fetch_assoc($erg);
        return ( $user["UserId"] );
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
    $session = session_id();
    	$sql = "UPDATE users SET UserSession = ? WHERE UserId = ?";
        
   
   $stm= DBi::$con->prepare($sql);
                $stm->bind_param("si",$session,$userid);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
}


/**
 * @return boolean
 */
function logged_in () {
    $session = session_id();
    $sql = "SELECT UserId FROM users WHERE UserSession = ?";
    $stm = DBi::$con->prepare($sql);
    
                $stm->bind_param("s",$session);
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
    return (mysqli_num_rows($erg) == 1);
}


/**
 * @return void
 */
function logout () {
    $sql = "UPDATE users SET UserSession = '' WHERE UserSession = ?";
    
     $stm= DBi::$con->prepare($sql);
                $stm->bind_param("s",session_id());
        $stm->execute();
$erg = $stm->get_result();
$stm->close();
    if (!$erg ) {
        die(UNGAB.mysqli_error(DBi::$con).UNGABSQL.$sql);
    }
}

connect();

?>
