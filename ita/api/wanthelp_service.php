<?php
session_start();
header('Content-Type: application/json');	

$array = array();
global $action;
$action  = null;
global $status;
$status = "";
if(isset($_SESSION['id'])){
	include 'connect.php';
	$user = mysqli_real_escape_string($connessione, $_SESSION['id']);
	$status = "logged";
	if(isset($_GET['idproj'])){  //utente ha premuto su bottone Collabora/abbandona
		$idproj = mysqli_real_escape_string($connessione, $_GET['idproj']);
		$selP = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = $idproj");
		$nP = mysqli_num_rows($selP);
		if($nP > 0){
			$ascP = mysqli_fetch_assoc($selP);
			if($ascP['iduser'] != $user){
				$sel = mysqli_query($connessione, "SELECT * FROM want_help, progetti WHERE id_mit = $user AND id_proj = $idproj AND id_proj = idproj ORDER BY want_help.date DESC LIMIT 1");
				$n_sel = mysqli_num_rows($sel);
				if($n_sel > 0){  //richiesta già inviata  -> abbandona o annulla
					$asc = mysqli_fetch_assoc($sel);
					mysqli_free_result($sel);
					$view = $asc['view'];
					if($view == "0"){ //annulla
						$id_wh = $asc['id_wh'];
						$up = mysqli_query($connessione, "UPDATE want_help SET view = 2 WHERE id_mit = $user AND id_wh = $id_wh");
						if($up){
							$status= "ok";
							$action = "annullato";
						}else{
							$status = "error annulla";
						}
					}else if($view == "-1"){
						$status = "rejected";
					}else if($view == "1"){ //abbandona
						$id_wh = $asc['id_wh'];
						$up = mysqli_query($connessione, "UPDATE want_help SET view = 3 WHERE id_mit = $user AND id_wh = $id_wh");
						if($up){
							$status = "ok";
							$action = "abbandonato";
						}else{
							$status = "error abbandona";
						}
					}else if($view == "2" || $view == "3"){ //rinvia richiesta
						inviaRichiesta($ascP, $user, $idproj, $connessione);
					}

				}else{ //prima richiesta
					inviaRichiesta($ascP, $user, $idproj, $connessione);
				}
			}
		}
	}else if(isset($_GET['accept']) && isset($_GET['id_wh'])){
		//control id_wh
		$id_wh = mysqli_real_escape_string($connessione, $_GET['id_wh']);
		$accept = mysqli_real_escape_string($connessione, $_GET['accept']);
		$selWH = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_wh = $id_wh AND view = 0 AND id_ric = $user ORDER BY date DESC");
		$nWH = mysqli_num_rows($selWH);
		mysqli_free_result($selWH);
		if($nWH > 0){
			if($accept == "true"){
				$req = "1";
				$str_ac_rf = "accept_w_h";
			}else{
				$req = "-1";
				$str_ac_rf = "refuse_w_h";
			}
			$up = mysqli_query($connessione, "UPDATE want_help SET view = $req WHERE id_wh = $id_wh AND id_ric = $user");
			if($up){
				$sel = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_wh = $id_wh AND id_ric = $user ORDER BY date DESC LIMIT 1");
				$n = mysqli_num_rows($sel);
				if($n > 0){
					$asc = mysqli_fetch_assoc($sel);
					mysqli_free_result($sel);
					$date = $asc['date'];
					$mit = $asc['id_mit'];
					$id_pj = $asc['id_proj'];
					//$up_nf = mysql_query("UPDATE news_feed SET date_action = '$date' WHERE id_wh = '$id_wh'");
					$date_now = date("Y-m-d H:i:s");
					$ins_nf_ac_rf = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_user_target, id_proj, id_wh, date_action) values ('$str_ac_rf','$user', '$mit', '$id_pj','$id_wh', '$date_now')");
					if($ins_nf_ac_rf){
						$status = "ok";
						//$mit = $asc['id_mit'];
						$selU = mysqli_query($connessione, "SELECT immagine_profilo, Nome, Cognome FROM utenti WHERE id = $mit");
						$ascU = mysqli_fetch_assoc($selU);
						$array['img'] = $ascU['immagine_profilo'];
						$array['Nome'] = $ascU['Nome'];
						$array['Cognome'] = $ascU['Cognome'];
						$array['action'] = $req;
					}else{
						$status = "error_last_update";
					}
				}else{
					$status = "error_last_selection";
				}
			}else{
				$status = "error_update";
			}
		}else{
			$status = "request_not_found";
		}

	}

	//mysqli_close($connessione);
}else{
	$status = "not_logged";
}

$array['status'] = $status;
if($action != null){
	$array['action'] = $action;
}
$json = json_encode($array);
echo "$json";


function inviaRichiesta($ascP, $user, $idproj, $connessione){
	

		$ric = $ascP['iduser'];
		$insWantHelp = mysqli_query($connessione, "INSERT INTO want_help(id_mit, id_ric, id_proj, view) VALUES ($user, $ric, $idproj, 0)");
		$sel_w = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = $user AND id_proj = $idproj AND view = 0 ORDER BY date DESC LIMIT 1");
		$nw = mysqli_num_rows($sel_w);
		if($nw > 0){
			$asc_w = mysqli_fetch_assoc($sel_w);
			mysqli_free_result($sel_w);
			$datew = $asc_w['date'];
			$idw = $asc_w['id_wh'];
			$insNF = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_proj, id_wh, date_action) values ('want_help','$user','$idproj','$idw', '$datew')");
			if($insNF){
				$GLOBALS['status'] = "ok";
				$GLOBALS['action'] = "send request";
			}else{
				$GLOBALS['status'] = "error send request";
			}
		
		}else{
			$GLOBALS['status'] = "error insert";
		}
	
}
?>