<?php
session_start();
header('Content-Type: application/json');	
include 'time_ago.php';/*
error_reporting(E_ALL);
ini_set(‘display_errors’, ‘1’);*/
$array = Array();

if(isset($_SESSION['id'])){
	$array['status'] = "ok";
	
	
	include 'connect.php';
	


	$id = $_SESSION['id'];
	$array['user'] = $id;
	if(isset($_GET['general'])){
		$sel_p = mysqli_query($connessione, "SELECT * FROM progetti WHERE iduser = '$id'");
		$n_p = mysqli_num_rows($sel_p);
		//$ar_p = Array();
		$str = "";
		if($n_p > 0){  //prog
			//$y = 0;
			
			while($asp = mysqli_fetch_assoc($sel_p)){
				$id_p = $asp['idproj'];
				$str = $str."OR id_proj = '$id_p' ";
				
			}
			
		}
		$selp = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = '$id' AND view = 1");
		$np = mysqli_num_rows($selp);
		if($np > 0){
			while($as_p = mysqli_fetch_assoc($selp)){
				$id_p = $as_p['id_proj'];
				$str = $str."OR id_proj = '$id_p' ";
				
			}
		
		}
		mysqli_free_result($selp);
		if(isset($_GET['only_n'])){
			/*
			$sel_view = mysql_query("SELECT * FROM view_notif WHERE id_user = '$id' AND type = 'general'");
			$n_view_ntf = mysql_num_rows($sel_view);
			$str_v_ntf = "";
			$array['n_view_ntf'] = $n_view_ntf;
			if($n_view_ntf > 0){
				$asc_view_ntf = mysql_fetch_assoc($sel_view);
				$date_view_ntf = $asc_view_ntf['date_view'];
				$str_v_ntf = "AND date_action > '$date_view_ntf' ";
				
			}
			//aggiungere notifiche messaggi coll su proj
			$qstr = "SELECT * FROM news_feed WHERE id_user_actor != '$id' AND action != 'want_help' AND action != 'message_p'  AND action != 'create' AND (id_user_target = '$id' ".$str.") ".$str_v_ntf."ORDER BY date_action DESC";
			$sel = mysql_query($qstr);
			$n = mysql_num_rows($sel);
			$array['n_notif'] = $n;*/
		}else{




			//
			$by = "";
			if(isset($_GET['by'])){
				$by = mysqli_real_escape_string($connessione, $_GET['by']).", ";
			}
			$qstr = "SELECT * FROM news_feed WHERE id_user_actor != '$id' AND action != 'want_help' AND action != 'refuse_w_h' AND action != 'message_p'  AND action != 'create' AND (id_user_target = '$id' ".$str.") ORDER BY date_action DESC LIMIT ".$by."10";
			$sel = mysqli_query($connessione, $qstr);
			$n = mysqli_num_rows($sel);
			if($n > 0){ 
				$x = 0;
				$notif = Array();

				//check for date_view
				$selViewNotif = mysqli_query($connessione, "SELECT * FROM view_notif WHERE id_user = $id");
				$nVN = mysqli_num_rows($selViewNotif);
				$dateNow = date('Y-m-d H:i:s');
				if($nVN > 0){
					$asc_view_ntf = mysqli_fetch_assoc($selViewNotif);
					$view_date = $asc_view_ntf['date_view_general'];
					$upVN = mysqli_query($connessione, "UPDATE view_notif SET date_view_general = '$dateNow' WHERE id_user = $id");
					
				}else{
					$insVN = mysqli_query($connessione, "INSERT INTO view_notif(id_user, date_view_general) VALUES($id, '$dateNow')");
					$view_date = null;
				}
				mysqli_free_result($selViewNotif);
					


				$str_v_ntf = "";
				$array['n_view_ntf'] = $nVN;
				
				$array['d_v'] = $asc_view_ntf['date_view_general'];

				while($as = mysqli_fetch_assoc($sel)){
					if($view_date != null){
						if($as['date_action'] > $view_date){
							$as['new_notif'] = true;
							
						}else{
							$as['new_notif'] = false;
						}
					}else{
						$as['new_notif'] = true;
					}

					$what = $as['id_user_actor'];
					$sel_ut = mysqli_query($connessione, "SELECT Nome, Cognome, immagine_profilo FROM utenti WHERE id = '$what'");
					$n_ut = mysqli_num_rows($sel_ut);
					if($n_ut > 0){
						$as_ut = mysqli_fetch_assoc($sel_ut);
						$as['immagine_profilo'] = $as_ut['immagine_profilo'];
						$as['Nome'] = $as_ut['Nome'];
						$as['Cognome'] = $as_ut['Cognome'];
							
						
					}
					if($as['action'] != "follow_u" && $as['action'] != "share_u"){
						$what = $as['id_proj'];
						$sel_pj = mysqli_query($connessione, "SELECT title, img FROM progetti WHERE idproj = '$what'");
						$n_pj = mysqli_num_rows($sel_pj);
						if($n_pj > 0){
							$as_pj = mysqli_fetch_assoc($sel_pj);
							$as['title'] = $as_pj['title'];
							$as['img'] = $as_pj['img'];
						}
					}
					/*if($as['action'] == "want_help"){
						$wh = $as['id_wh'];
						$sel_wh = mysql_query("SELECT view FROM want_help WHERE id_wh = '$wh'");
						$n_wh = mysql_num_rows($sel_wh);
						if($n_wh > 0){
							$as_wh = mysql_fetch_assoc($sel_wh);
							$as['view'] = $as_wh['view'];
						}
					}*/

					if($as['action'] == "comment_p"){
						$what = $as['id_com'];
						$sel_txt = mysqli_query($connessione, "SELECT commenti_p.text FROM commenti_p WHERE id_com = '$what'");
						$n_txt = mysqli_num_rows($sel_txt);
						if($n_txt > 0){
							$as_txt = mysqli_fetch_assoc($sel_txt);
							$as['text'] = $as_txt['text'];
						}
					}else if($as['action'] == "post"){
						$what = $as['id_post'];
						$sel_txt = mysqli_query($connessione, "SELECT message FROM post_p WHERE id_post = '$what'");
						$n_txt = mysqli_num_rows($sel_txt);
						if($n_txt > 0){
							$as_txt = mysqli_fetch_assoc($sel_txt);
							$as['text'] = $as_txt['message'];
						}
					}
					$as['d'] = $as['date_action'];
					$as['date_action'] = timeAgo($as['date_action']);
					$notif[$x] = $as;
					$x++;
				}
				
				mysqli_free_result($sel);
					
			}else{
				
			}
			$it_is = mysqli_query($connessione, "SELECT * FROM view_notif WHERE id_user = '$id' AND type = 'general'");
			$n_itis = mysqli_num_rows($it_is);
			if($n_itis > 0){
				$q_insrt = mysqli_query($connessione, "UPDATE view_notif SET date_view = now() WHERE id_user = '$id' AND type = 'general'");
				
			}else{
				$q_insrt = mysqli_query($connessione, "INSERT INTO view_notif(id_user, type) VALUES('$id', 'general')");
				
			}
			mysqli_free_result($it_is);
					
			
			
			$tot_notif = "";
			//$array['q'] = $qstr;
			$array['notif'] = $notif;
		}
	}else if(isset($_GET['msg_personal'])){
		$selViewNotif = mysqli_query($connessione, "SELECT * FROM view_notif WHERE id_user = $id");
		$nVN = mysqli_num_rows($selViewNotif);
		$dateNow = date('Y-m-d H:i:s');
		$view_date = null;
		if($nVN > 0){
			$ascVD = mysqli_fetch_assoc($selViewNotif);
			$view_date = $ascVD['date_view_message_user'];
			$upVN = mysqli_query($connessione, "UPDATE view_notif SET date_view_message_user = '$dateNow' WHERE id_user = $id");
				
		}else{
			$insVN = mysqli_query($connessione, "INSERT INTO view_notif(id_user, date_view_message_user) VALUES($id, '$dateNow')");
			
		}
		mysqli_free_result($selViewNotif);
						

		$sel = mysqli_query($connessione, "SELECT immagine_profilo, id, Nome, Cognome, immagine_profilo, text, date_message FROM message_user, utenti WHERE id_ric = '$id' AND utenti.id = id_mit ORDER BY date_message DESC LIMIT 10");
		$n = mysqli_num_rows($sel);
		$array['n'] = $n;
		if($n > 0){
			$ar = array();
			$x = 0;
			while($asc = mysqli_fetch_assoc($sel)){
				if($view_date != null){
					if($asc['date_message'] > $view_date){
						$asc['new_notif'] = true;
									
					}else{
						$asc['new_notif'] = false;
					}
				}else{
					$asc['new_notif'] = true;
				}
				$ar[$x] = $asc;
				$x++;
			}
			$array['messaggi'] = $ar;
		}
		mysqli_free_result($sel);
					
	}else if(isset($_GET['msg_project'])){
		$selViewNotif = mysqli_query($connessione, "SELECT * FROM view_notif WHERE id_user = $id");
		$view_date = null;
		$nVN = mysqli_num_rows($selViewNotif);
		$dateNow = date('Y-m-d H:i:s');
		if($nVN > 0){
			$ascVD = mysqli_fetch_assoc($selViewNotif);
			$view_date = $ascVD['date_view_message_proj'];
			$upVN = mysqli_query($connessione, "UPDATE view_notif SET date_view_message_proj = '$dateNow' WHERE id_user = $id");
				
		}else{
			$insVN = mysqli_query($connessione, "INSERT INTO view_notif(id_user, date_view_message_proj) VALUES($id, '$dateNow')");
					
		}
		mysqli_free_result($selViewNotif);
					
		$sel_p = mysqli_query($connessione, "SELECT * FROM progetti WHERE iduser = '$id'");
		$n_p = mysqli_num_rows($sel_p);
		//$ar_p = Array();
		$str = "";
		if($n_p > 0){  //prog
			//$y = 0;
			
			while($asp = mysqli_fetch_assoc($sel_p)){
				$id_p = $asp['idproj'];
				if($str == ""){
					$str = "id_proj = '$id_p' ";
				}else{
					$str = $str."OR id_proj = '$id_p' ";
				}
			}
			
		}
		$selp = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = '$id' AND view = 1");
		$np = mysqli_num_rows($selp);
		if($np > 0){
			while($as_p = mysqli_fetch_assoc($selp)){
				$id_p = $as_p['id_proj'];
				if($str == ""){
					$str = "id_proj = '$id_p' ";
				}else{
					$str = $str."OR id_proj = '$id_p' ";
				}
			}
			
		}
		mysqli_free_result($selp);
		if($str != ""){
			$q = "SELECT Nome, Cognome, immagine_profilo, title, id_proj, message_proj.id_user, message, date_message FROM message_proj, utenti, progetti WHERE id_user != $id AND utenti.id = message_proj.id_user AND message_proj.id_proj = idproj AND (".$str.") ORDER BY date_message DESC LIMIT 10";
			//$array['q'] = $q;
			$sel = mysqli_query($connessione, $q);
			$n = mysqli_num_rows($sel);
			if($n > 0){
				$ar = array();
				$x = 0;
				while($as = mysqli_fetch_assoc($sel)){
					if($view_date != null){
						if($as['date_message'] > $view_date){
							$as['new_notif'] = true;
										
						}else{
							$as['new_notif'] = false;
						}
					}else{
						$as['new_notif'] = true;
					}
					$ar[$x] = $as;
					$x++;
				}
				$array['message_proj'] = $ar;
			}
			mysqli_free_result($sel);
		}

					
	}else if(isset($_GET['req_collab'])){
		$selViewNotif = mysqli_query($connessione, "SELECT * FROM view_notif WHERE id_user = $id");
		$nVN = mysqli_num_rows($selViewNotif);
		$dateNow = date('Y-m-d H:i:s');
		if($nVN > 0){
			$upVN = mysqli_query($connessione, "UPDATE view_notif SET date_view_request = '$dateNow' WHERE id_user = $id");
				
		}else{
			$insVN = mysqli_query($connessione, "INSERT INTO view_notif(id_user, date_view_request) VALUES($id, '$dateNow')");
				
		}
		mysqli_free_result($selViewNotif);

		$sel_p = mysqli_query($connessione, "SELECT idproj, id_wh, title, immagine_profilo, id_mit, Nome, Cognome, want_help.date FROM want_help, progetti, utenti WHERE id_ric = '$id' AND id_proj = idproj AND id_mit = utenti.id AND view = 0 ORDER BY date DESC");
		$n_p = mysqli_num_rows($sel_p);
		//$ar_p = Array();
		$array['n'] = $n_p;
		if($n_p > 0){  //prog
			//$y = 0;
			$ar = array();
			$x = 0;
			while($asp = mysqli_fetch_assoc($sel_p)){
				$ar[$x] = $asp;
				$x++;
			}
			$array['request'] = $ar;	
		}
		mysqli_free_result($sel_p);

	}else if(isset($_GET['n'])){
		$sel_view = mysqli_query($connessione, "SELECT * FROM view_notif WHERE id_user = '$id'");
		$n_view_ntf = mysqli_num_rows($sel_view);
		$str_v_general = "";
		$str_v_msg_u = "";
		$str_v_msg_p = "";
		$str_v_req = "";
				

		if($n_view_ntf > 0){
			$asc_view_ntf = mysqli_fetch_assoc($sel_view);
			$date_view_general = $asc_view_ntf['date_view_general'];
			$str_v_general = "AND date_action > '$date_view_general' ";

			$date_view_msg_u = $asc_view_ntf['date_view_message_user'];
			$str_v_msg_u = "AND date_message > '$date_view_msg_u' ";
			
			$date_view_msg_p = $asc_view_ntf['date_view_message_proj'];
			$str_v_msg_p = "AND date_message > '$date_view_msg_p' ";
			
			$date_view_req = $asc_view_ntf['date_view_request'];
			$str_v_req = "AND want_help.date > '$date_view_req' ";
			
				
		}
		mysqli_free_result($sel_view);
		/*$array['s_g'] = $str_v_general;
		$array['s_m_u'] = $str_v_msg_u;
		$array['s_m_p'] = $str_v_msg_p;
		$array['s_r'] = $str_v_req;*/
		
		
		//general
		$sel_p = mysqli_query($connessione, "SELECT * FROM progetti WHERE iduser = '$id'");
		$n_p = mysqli_num_rows($sel_p);
		//$ar_p = Array();
		$str = "";
		if($n_p > 0){  //prog
			//$y = 0;
			
			while($asp = mysqli_fetch_assoc($sel_p)){
				$id_p = $asp['idproj'];
				$str = $str."OR id_proj = '$id_p' ";
				
			}
			
		}
		$selp = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = '$id' AND view = 1");
		$np = mysqli_num_rows($selp);
		if($np > 0){
			while($as_p = mysqli_fetch_assoc($selp)){
				$id_p = $as_p['id_proj'];
				$str = $str."OR id_proj = '$id_p' ";
				
			}
		
		}
		mysqli_free_result($selp);
		$qstr = "SELECT * FROM news_feed WHERE id_user_actor != '$id' AND action != 'want_help' AND action != 'message_p'  AND action != 'create' AND (id_user_target = '$id' ".$str.") ".$str_v_general."ORDER BY date_action DESC";
		$sel = mysqli_query($connessione, $qstr);
		$n = mysqli_num_rows($sel);
		$array['general'] = $n;
		mysqli_free_result($sel);
		//msg_user
		$sel = mysqli_query($connessione, "SELECT immagine_profilo, id, Nome, Cognome, immagine_profilo, text, date_message FROM message_user, utenti WHERE id_ric = '$id' AND utenti.id = id_mit ".$str_v_msg_u." ORDER BY date_message DESC");
		$n = mysqli_num_rows($sel);
		$array['msg_user'] = $n;
		mysqli_free_result($sel);
		//msg_proj
		$sel_p = mysqli_query($connessione, "SELECT * FROM progetti WHERE iduser = '$id'");
		$n_p = mysqli_num_rows($sel_p);
		//$ar_p = Array();
		$str = "";
		if($n_p > 0){  //prog
			//$y = 0;
			
			while($asp = mysqli_fetch_assoc($sel_p)){
				$id_p = $asp['idproj'];
				if($str == ""){
					$str = "id_proj = '$id_p' ";
				}else{
					$str = $str."OR id_proj = '$id_p' ";
				}
			}
			
		}
		mysqli_free_result($sel_p);
		$selp = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = '$id' AND view = 1");
		$np = mysqli_num_rows($selp);
		if($np > 0){
			while($as_p = mysqli_fetch_assoc($selp)){
				$id_p = $as_p['id_proj'];
				if($str == ""){
					$str = "id_proj = '$id_p' ";
				}else{
					$str = $str."OR id_proj = '$id_p' ";
				}
			}
			
		}
		mysqli_free_result($selp);
		if($str != ""){
			$q = "SELECT Nome, Cognome, immagine_profilo, title, id_proj, message_proj.id_user, message, date_message FROM message_proj, utenti, progetti WHERE id_user != $id AND utenti.id = message_proj.id_user ".$str_v_msg_p."AND message_proj.id_proj = idproj AND (".$str.") ORDER BY date_message DESC";
			$sel = mysqli_query($connessione, $q);
			$n = mysqli_num_rows($sel);
		}else{
			$n = 0;
		}
		$array['msg_proj'] = $n;

		//req
		$sel_p = mysqli_query($connessione, "SELECT idproj, id_wh, title, immagine_profilo, id_mit, Nome, Cognome, want_help.date FROM want_help, progetti, utenti WHERE id_ric = '$id' AND id_proj = idproj AND id_mit = utenti.id AND view = 0 ".$str_v_req." ORDER BY date DESC");
		$n_p = mysqli_num_rows($sel_p);
		$array['req'] = $n_p;




	}
		
	

	mysqli_close($connessione);
}else{
	$array['status'] = "not_logged";
}

$result = json_encode($array);
echo "$result";

?>