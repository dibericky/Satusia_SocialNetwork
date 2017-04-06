<?php
session_start();
include 'connect.php';	
header('Content-Type: application/json');	
$arr = Array();
if(isset($_SESSION['id'])){
	$user = mysqli_real_escape_string($connessione, $_SESSION['id']);
	if(isset($_GET['type'])){
		$type = mysqli_real_escape_string($connessione, $_GET['type']);
		$arr['type'] = $type;
		if($type == "info_pers"){
			//$arr['aa'] = "";
			$str = "";
			if(isset($_POST['nome'])){
				$nome = mysqli_real_escape_string($connessione, $_POST['nome']);
				$str = " Nome = '".$nome."'";
			}
			if(isset($_POST['cognome'])){
				$cognome = mysqli_real_escape_string($connessione, $_POST['cognome']);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." Cognome = '".$cognome."'";
			}
			if(isset($_POST['professione'])){
				$job = mysqli_real_escape_string($connessione, $_POST['professione']);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." professione = '".$job."'";
			}
			if(isset($_POST['citta'])){
				$citta = mysqli_real_escape_string($connessione, $_POST['citta']);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." citta = '".$citta."'";
			}
			if(isset($_POST['dateNasc'])){
				$dateNasc = mysqli_real_escape_string($connessione, $_POST['dateNasc']);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." Data_Nascita = '".$dateNasc."'";
			}
			if(isset($_POST['sex'])){
				$sex = mysqli_real_escape_string($connessione, $_POST['sex']);
				if($sex == "Uomo" || $sex == "Donna"){
					if($str != ""){
						$str = $str.",";
					}
					$str = $str." sesso = '".$sex."'";
				}
			}
			if(isset($_POST['scuola_sup'])){
				$scuola_sup = mysqli_real_escape_string($connessione, $_POST['scuola_sup']);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." scuola_superiore = '".$scuola_sup."'";
			}
			if(isset($_POST['frase_pers'])){
				$scuola_sup = mysqli_real_escape_string($connessione, $_POST['frase_pers']);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." frase_personale = '".$scuola_sup."'";
			}
			
			if(isset($_POST['biografia'])){
				$biografia = mysqli_real_escape_string($connessione, nl2br($_POST['biografia']));
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." biografia = '".$biografia."'";
			}
			if(isset($_POST['lingue'])){
				$str_lingue = "[";
				$arr_lingue = $_POST['lingue'];
				for($x = 0; $x < count($arr_lingue); $x++){
					if(str_replace(" ", "", $arr_lingue[$x]) != ""){
						if($str_lingue != "["){
							$str_lingue = $str_lingue.",";
						}
						$str_lingue = $str_lingue.$arr_lingue[$x];
					}
				}
				$str_lingue = $str_lingue."]";
				$lingue = mysqli_real_escape_string($connessione, $str_lingue);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." lingue = '".$lingue."'";
			}
			if(isset($_POST['universita'])){
				$str_uni = "[";
				$arr_uni = $_POST['universita'];
				for($x = 0; $x < count($arr_uni); $x++){
					if(str_replace(" ", "", $arr_uni[$x]) != ""){
						if($str_uni != "["){
							$str_uni = $str_uni.",";
						}
						$str_uni = $str_uni.$arr_uni[$x];
					}
				}
				$str_uni = $str_uni."]";
				$universita = mysqli_real_escape_string($connessione, $str_uni);
				if($str != ""){
					$str = $str.",";
				}
				$str = $str." universita = '".$universita."'";
			}

			$str_soc = "";
			
			$facebook = "";
			$twitter ="";
			$sito_personale = "";
			if(isset($_POST['facebook'])){
				$facebook = mysqli_real_escape_string($connessione, $_POST['facebook']);
				
				$str_soc = $str_soc." facebook = '".$facebook."'";
				
			}
			if(isset($_POST['twitter'])){
				$twitter = mysqli_real_escape_string($connessione, $_POST['twitter']);
				if($str_soc != ""){
					$str_soc = $str_soc.",";
				}
				$str_soc = $str_soc." twitter = '".$twitter."'";
			}
			if(isset($_POST['site_web'])){
				$sito_personale = mysqli_real_escape_string($connessione, $_POST['site_web']);
				if($str_soc != ""){
					$str_soc = $str_soc.",";
				}
				$str_soc = $str_soc." personal = '".$sito_personale."'";
			}



			
			if($str != "" && $str_soc != ""){
				$q_str = "UPDATE utenti SET".$str." WHERE id = $user";
				$sel_soc = mysqli_query($connessione, "SELECT * FROM social_user WHERE id_user = $user");
				$n_soc = mysqli_num_rows($sel_soc);
				if($n_soc > 0){
					$q_str_soc = "UPDATE social_user SET ".$str_soc." WHERE id_user = $user";
				}else{
					$q_str_soc = "INSERT INTO social_user(id_user, facebook, twitter, personal) VALUES($user, '$facebook', '$twitter', '$sito_personale')";
				}
				//$arr['q_soc'] = $q_str_soc;
				//$arr['q'] = $q_str;
				$q = mysqli_query($connessione, $q_str);
				$q_soc = mysqli_query($connessione, $q_str_soc);
				if($q && $q_soc){
					$arr['status'] = "ok";
					
				}else{
					$arr['status'] = "error!";
				}
			}else{
				$arr['status'] = "null_value";
			}
			
			
			
				
		}else if($type == "info_account"){
			$str = "";
			$arr['bb'] = "";
			if(isset($_POST['email'])){
				$email = mysqli_real_escape_string($connessione, $_POST['email']);
				$str = " email = '".$email."'";
				$arr['up_email'] = true;
			}
			if(isset($_POST['new_pssw'])){
				$sel = mysqli_query($connessione, "SELECT password, gotPsw FROM utenti WHERE id = $user");
				$asc = mysqli_fetch_assoc($sel);
				$hasPsw = $asc['gotPsw'];
				$okPsw = false;
				if($hasPsw == 1 && isset($_POST['now_pssw'])){
					$now_pssw = mysqli_real_escape_string($connessione, $_POST['now_pssw']);
					$md5_now = md5($now_pssw);
					if($asc['password'] == $md5_now){
						$okPsw = true;
					}
					
				}else if($hasPsw == 0){
					$okPsw = true;
				}else{
					$okPsw = false;
				}
				$new_pssw = mysqli_real_escape_string($connessione, $_POST['new_pssw']);
				$new_pssw = str_replace(" ", "", $new_pssw);
				if($new_pssw != ""){
					$arr['up_psw'] = true;
					$new_pssw = md5($new_pssw);
					if($str != ""){
						$str = $str.",";
					}
					if($okPsw == true){
						$str = $str." password = '".$new_pssw."'";
						if($hasPsw == 0){
							$str = $str.", gotPsw = '1'";
						}
						$arr['psw'] = "psw";
					}else{
						$arr['error'] = "error_psw";
						$str = ""; 
					}
				}
			}
			
			if($str != ""){
				$q_str = "UPDATE utenti SET".$str." WHERE id = $user";
				$q = mysqli_query($connessione, $q_str);
				if($q){
					$arr['status'] = "ok";
					
				}else{
					$arr['status'] = "error";
				}
			}else{
				$arr['status'] = "null_value";
			}
			
		}else if($type == "proj"){
			$arr['cc'] = "";
			if(isset($_GET['proj'])){
				$proj = mysqli_real_escape_string($connessione, $_GET['proj']);
				$iCan = false;
				//isMyPj?
				$selMy = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = $proj");
				$nMy = mysqli_num_rows($selMy);
				if($nMy > 0){
					$ascPj = mysqli_fetch_assoc($selMy);
					mysqli_free_result($selMy);
					if($ascPj['iduser'] == $user){
						$iCan = true;  //i am founder
					}else{
						$selColl = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = $user AND id_proj = $proj ORDER BY date DESC LIMIT 1");
						$nColl = mysqli_num_rows($selColl);
						if($nColl > 0){
							$ascColl = mysqli_fetch_assoc($selColl);
							mysqli_free_result($selColl);
							$view = $ascColl['view'];
							if($view == 1){
								$iCan = true;
							}
						}
					}
				
					if($iCan){
						$str = "";
						if(isset($_POST['title'])){
							$title = mysqli_real_escape_string($connessione, $_POST['title']);
							$str = "title = '".$title."'";
						}
						if(isset($_POST['categ'])){
							$categ = mysqli_real_escape_string($connessione, $_POST['categ']);
							$cat = "";
							switch($categ){
								case "informatica" : $cat = "Informatica & Tecnologia"; break;
								case "scienze" : $cat = "Scienze"; break;
						        case "arte" : $cat = "Arte"; break;
						        case "musica" : $cat = "Musica"; break;
						        case "moda" : $cat = "Moda"; break;
						        case "giornalismo" : $cat = "Giornalismo"; break;
						        case "culinaria" : $cat = "Culinaria"; break;
						        case "noprofit" : $cat = "No-Profit"; break;
						        default: $cat = "Altro";
							}
							if($str != ""){
								$str = $str.", ";
							}
							$str = $str."categoria = '".$cat."'";
							$arr['cat'] = $cat;
						}
						if(isset($_POST['descr'])){
							$descr = mysqli_real_escape_string($connessione, nl2br($_POST['descr']));
							if($str != ""){
								$str = $str.", ";
							}
							$str = $str."descr = '".$descr."'";
						}
						if($str != ""){
							$q_str = "UPDATE progetti SET ".$str." WHERE idproj = $proj";
							$q = mysqli_query($connessione, $q_str);
							
							if($q){
								$arr['status'] = "ok";
								
							}else{
								$arr['status'] = "error";
							}
						}else{
							$arr['status'] = "not_data_passed";
						}
						
					}else{
						$arr['status'] = "not_allowed";
					}
				}else{
					$arr['status'] = "project_not_found";
				}

			}else{	
				$arr['status'] = "proj_not_set";
			}
		}
	}else{
		$arr['status'] = "not_type";
	}

}else{
	$arr['status'] = "not_logged";
}
$j = json_encode($arr);
mysqli_close($connessione);
echo $j;
?>