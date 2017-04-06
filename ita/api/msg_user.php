<?php

session_start();
include 'connect.php';
header('Content-Type: application/json');	
$arr = Array();
if(isset($_SESSION['id'])){
	$user = mysqli_real_escape_string($connessione, $_SESSION['id']);
		
	if(isset($_POST['dest']) && isset($_POST['message'])){  //send message to user
		
		$dest = mysqli_real_escape_string($connessione, $_POST['dest']);
		$msg = mysqli_real_escape_string($connessione, nl2br($_POST['message']));
		$sel_exist = mysqli_query($connessione, "SELECT Nome FROM utenti WHERE id = $dest");
		$n_exist = mysqli_num_rows($sel_exist);
		if($n_exist > 0){
			mysqli_free_result($sel_exist);
			$exist = true;
			$ins = mysqli_query($connessione, "INSERT INTO message_user (id_mit, id_ric, text) VALUES('$user', '$dest', '$msg')");
			if($ins){
				$sel = mysqli_query($connessione, "SELECT * FROM message_user WHERE id_mit = $user AND id_ric = $dest ORDER BY date_message DESC LIMIT 1");
				$n_s = mysqli_num_rows($sel);
				if($n_s > 0){
					$asc_s = mysqli_fetch_assoc($sel);
					mysqli_free_result($sel);
					$date = $asc_s['date_message'];
					$id_mess = $asc_s['id_mess'];
					$sel_chat = mysqli_query($connessione, "SELECT * FROM chat_user WHERE (user_1 = $user AND user_2 = $dest) OR (user_1 = $dest AND user_2 = $user)");
					$n_chat = mysqli_num_rows($sel_chat);
					if($n_chat > 0){
						$asc_chat = mysqli_fetch_assoc($sel_chat);
						mysqli_free_result($sel_chat);
						$user_1 = $asc_chat['user_1'];
						$user_2 = $asc_chat['user_2'];
						$ups = mysqli_query($connessione, "UPDATE chat_user SET date = '$date', id_message = '$id_mess' WHERE user_1 = '$user_1' AND user_2 = '$user_2'");
						if($ups){
							$arr['status'] = "ok";
							$arr['date'] = $date;
						}else{
							$arr['status'] = "error_up_chat";
							$arr['c1'] = $user_1;
							$arr['c2'] = $user_2;
								
						}
					}else{
						$ins_chat = mysqli_query($connessione, "INSERT INTO chat_user (user_1, user_2, id_message) VALUES($user, $dest, $id_mess)");
						if($ins_chat){
							$arr['status'] = "ok";

						}else{
							$arr['status'] = "ins_chat_failed";
						}
					}
				}else{
					$arr['status'] = "err_not_found";
				}
			}else{
				$arr['status'] = "err_ins";
			}
		}else{
			$exist = false;
			$arr['status'] = "user_not_found";
		}
		$arr['exist'] = $exist;
	}else if(isset($_GET['get_list_chat'])){
		$get = mysqli_real_escape_string($connessione, $_GET['get_list_chat']);
		$selViewNotif = mysqli_query($connessione, "SELECT * FROM view_notif WHERE id_user = $user");
		$nVN = mysqli_num_rows($selViewNotif);
		$dateNow = date('Y-m-d H:i:s');
		$view_date = null;
		if($nVN > 0){
			$ascVD = mysqli_fetch_assoc($selViewNotif);
			mysqli_free_result($selViewNotif);
			if($get == "user"){
				$view_date = $ascVD['date_view_message_user'];
			}else if($get == "proj"){
				$view_date = $ascVD['date_view_message_proj'];
			}
		}
		if($get == "user"){
			$sel = mysqli_query($connessione, "SELECT user_1, user_2, utenti.id, chat_user.date, id_mit, id_ric, Nome, Cognome, immagine_profilo, text FROM chat_user, utenti, message_user WHERE ((user_1 = '$user' AND utenti.id = user_2) OR (user_2 = '$user' AND utenti.id = user_1)) AND id_mess = id_message ORDER BY chat_user.date DESC LIMIT 20");
			$n = mysqli_num_rows($sel);
			$ar_chat = Array();
			if($n > 0){
				$x = 0;
				
				while($asc = mysqli_fetch_assoc($sel)){
					$asc['new'] = false;
					if($asc['id_mit'] == $user){
						$asc['who_wrote'] = "tu";
					}else{
						$asc['who_wrote'] = "other";
					}
					if($asc['date'] > $view_date){
						$asc['new'] = true;
					}
					$ar_chat[$x] =  $asc;

					$x++;
				}
			}
			mysqli_free_result($sel);
			$arr['status'] = "ok";
			$arr['chat'] = $ar_chat;
		}else if($get == "proj"){
			$sel_p = mysqli_query($connessione, "SELECT * FROM progetti WHERE iduser = '$user'");
			$n_p = mysqli_num_rows($sel_p);
			//$ar_p = Array();
			$str = "";
			if($n_p > 0){  //prog
				//$y = 0;
				
				while($asp = mysqli_fetch_assoc($sel_p)){
					$id_p = $asp['idproj'];
					if($str == ""){
						$str = "chat_proj.idproj = '$id_p' ";
					}else{
						$str = $str."OR chat_proj.idproj = '$id_p' ";
					}
				}
				
			}
			$selp = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = '$user' AND view = 1");
			$np = mysqli_num_rows($selp);
			if($np > 0){
				while($as_p = mysqli_fetch_assoc($selp)){
					$id_p = $as_p['id_proj'];
					if($str == ""){
						$str = "chat_proj.idproj = '$id_p' ";
					}else{
						$str = $str."OR chat_proj.idproj = '$id_p' ";
					}
				}
				
			}
			$ar_chat = Array();
			mysqli_free_result($selp);
			if($str != ""){
				$str_q = "SELECT chat_proj.idproj, img, date_message, message_proj.id_user, title, message FROM chat_proj, message_proj, progetti WHERE (".$str.") AND progetti.idproj = chat_proj.idproj AND message_proj.id_message = id_mess ORDER BY date_message DESC LIMIT 20";
				$sel = mysqli_query($connessione, $str_q);
				//$arr['q'] = $str_q;
				$n = mysqli_num_rows($sel);
				
				if($n > 0){
					$x = 0;
					
					while($asc = mysqli_fetch_assoc($sel)){
						$asc['new'] = false;
						if($asc['id_user'] == $user){
							$asc['who_wrote'] = "tu";
						}else{
							$asc['who_wrote'] = "other";
						}
						if($asc['date_message'] > $view_date){
							$asc['new'] = true;
						}
						$ar_chat[$x] =  $asc;

						$x++;
					}
				}
			}
			$arr['status'] = "ok";
			$arr['chat'] = $ar_chat;
			mysqli_free_result($sel);
		}
		
	}else if(isset($_GET['get_chat_with_user'])){ 
		$sel_me = mysqli_query($connessione, "SELECT immagine_profilo FROM utenti WHERE id = $user");
		$asc_me = mysqli_fetch_assoc($sel_me);
		$img_me = $asc_me['immagine_profilo'];

		$user_chat = mysqli_real_escape_string($connessione, $_GET['get_chat_with_user']);
		$sel_exist = mysqli_query($connessione, "SELECT Nome, immagine_profilo, Cognome, id FROM utenti WHERE id = $user_chat");
		$n_exist = mysqli_num_rows($sel_exist);
		if($n_exist > 0){
			$asc_o = mysqli_fetch_assoc($sel_exist);
			mysqli_free_result($sel_exist);
			$arr['other_img'] = $asc_o['immagine_profilo'];
			$arr['Nome'] = $asc_o['Nome'];
			$arr['id_o'] = $asc_o['id'];
			$arr['Cognome'] = $asc_o['Cognome'];
			$exist = true;
			$sel = mysqli_query($connessione, "SELECT date_message, id_mit, id_ric, id_mess, text, immagine_profilo FROM message_user, utenti WHERE ((id_mit = $user AND id_ric = $user_chat) OR (id_mit = $user_chat AND id_ric = $user)) AND utenti.id = $user_chat ORDER BY date_message DESC");
			$n = mysqli_num_rows($sel);
			$ar_chat = Array();
			$arr['me_img'] = $img_me;
			if($n > 0){
				$x = 0;
				while($asc = mysqli_fetch_assoc($sel)){
					if($asc['id_mit'] == $user){
						$asc['who_wrote'] = "tu";
					}else{
						$asc['who_wrote'] = "other";
					}
					$ar_chat[$x] =  $asc;
					$x++;
				}
			}
			mysqli_free_result($sel);
			$arr['status'] = "ok";
			$arr['chat'] = $ar_chat;
		}else{
			$exist = false;
			$arr['status'] = "no_user_found";
		}
		$arr['exist'] = $exist;
	}else if(isset($_GET['msg_proj'])){  //get messages proj
		$proj = mysqli_real_escape_string($connessione, $_GET['msg_proj']);
		$iCan = false;
		//isMyPj?
		$selMy = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = $proj");
		$nMy = mysqli_num_rows($selMy);
		if($nMy > 0){
			$ascPj = mysqli_fetch_assoc($selMy);
			mysqli_free_result($selMy);
			$imgProj = $ascPj['img'];
			$title = $ascPj['title'];
			if($ascPj['iduser'] == $user){
				$iCan = true;  //i am founder
			}else{
				$selColl = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = $user AND id_proj = $proj ORDER BY date DESC LIMIT 1");
				$nColl = mysqli_num_rows($selColl);
				if($nColl > 0){
					$ascColl = mysqli_fetch_assoc($selColl);
					$view = $ascColl['view'];
					if($view == 1){
						$iCan = true;
					}
				}
				mysqli_free_result($selColl);
			}
		
			if($iCan){
				$selMsg = mysqli_query($connessione, "SELECT date_message, id_message, id_proj, id_user, message, immagine_profilo FROM message_proj, utenti WHERE id_proj = $proj AND utenti.id = id_user ORDER BY date_message DESC LIMIT 50");
				$arr_msg = Array();
				$nMsg = mysqli_num_rows($selMsg);
				$x = 0;
				while($ascMsg = mysqli_fetch_assoc($selMsg)){
					if($ascMsg['id_user'] == $user){
						$ascMsg['who_wrote'] = "tu";
					}else{
						$ascMsg['who_wrote'] = "other";
					}
					$arr_msg[$x] = $ascMsg;
					$x++;
				}
				mysqli_free_result($selMsg);
				$arr['chat'] = $arr_msg;
				$arr['status'] = "ok";
				$arr['title'] = $title;
				$arr['img'] = $imgProj;
				$arr['id_p'] = $proj;
			}else{
				$arr['status'] = "not_allowed";
			}
		}else{
			$arr['status'] = "project_not_found";
		}
	}else if(isset($_POST['proj']) && isset($_POST['message'])){ //send message to proj
		
		$proj = mysqli_real_escape_string($connessione, $_POST['proj']);
		$msg = mysqli_real_escape_string($connessione, nl2br($_POST['message']));
		$sel_exist = mysqli_query($connessione, "SELECT title FROM progetti WHERE idproj = $proj");
		$n_exist = mysqli_num_rows($sel_exist);
		if($n_exist > 0){
			$exist = true;
			$ins = mysqli_query($connessione, "INSERT INTO message_proj (id_proj, id_user, message) VALUES('$proj', '$user', '$msg')");
			if($ins){
				$sel = mysqli_query($connessione, "SELECT * FROM message_proj WHERE id_user = $user AND id_proj = $proj ORDER BY date_message DESC LIMIT 1");
				$n_s = mysqli_num_rows($sel);
				if($n_s > 0){
					$asc_s = mysqli_fetch_assoc($sel);
					$id_mess = $asc_s['id_message'];
					$date_mess = $asc_s['date_message'];
					$sel_chat = mysqli_query($connessione, "SELECT * FROM chat_proj WHERE idproj = $proj");
					$n_chat = mysqli_num_rows($sel_chat);
					if($n_chat > 0){
						$asc_chat = mysqli_fetch_assoc($sel_chat);
						mysqli_free_result($sel_chat);
						$pj = $asc_chat['idproj'];
						$ups = mysqli_query($connessione, "UPDATE chat_proj SET id_mess = '$id_mess' WHERE idproj = $proj");
						if($ups){
							$ins = mysqli_query($connessione, "INSERT INTO news_feed (action, id_user_actor, id_proj, id_com) VALUES('message_p', '$user', '$proj', '$id_mess')");
							if($ins){
								$arr['status'] = "ok";
								$arr['date'] = $date_mess;
							}else{
								$arr['status'] = "no_nf";
							}
						}else{
							$arr['status'] = "error_up_chat";
						}
					}else{
						$ins_chat = mysqli_query($connessione, "INSERT INTO chat_proj (idproj, id_mess) VALUES($proj, $id_mess)");
						if($ins_chat){
							
							$ins = mysqli_query($connessione, "INSERT INTO news_feed (action, id_user_actor, id_proj, id_com, date_action) VALUES('message_p', '$user', '$proj', '$id_mess', '$date_mess')");
							if($ins){
								$arr['status'] = "ok";
							}else{
								$arr['status'] = "no_nf";
							}

						}else{
							$arr['status'] = "ins_chat_failed";
						}
					}
				}else{
					$arr['status'] = "err_not_found";
				}
				mysqli_free_result($sel);
			}else{
				$arr['status'] = "err_ins";
			}
		}else{
			$arr['status'] = "project_not_found";
			$exist = false;
		}
		$arr['exist'] = $exist;
	}else{
		$arr['status'] = "data_not_sent";
	}

}else{
	$arr['status'] = "not_logged";
}
$j = json_encode($arr);
mysqli_close($connessione);
echo $j;
?>