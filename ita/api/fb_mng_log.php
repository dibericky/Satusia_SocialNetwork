<?php
header('Content-Type: application/json');
$arr = Array();
include 'connect.php';
$fbid = mysqli_real_escape_string($connessione, $_POST['fbid']);
$nome = mysqli_real_escape_string($connessione, $_POST['nome']);
$cognome = mysqli_real_escape_string($connessione, $_POST['cognome']);
$email = mysqli_real_escape_string($connessione, $_POST['email']);
$data = mysqli_real_escape_string($connessione, $_POST['data']);
$time = strtotime($data);
$data = date('Y-m-d',$time);
$arr['data'] = $data;
if(isset($_GET['crpt'])){
	$str = $fbid."&&".$nome."__"."0134679".$cognome."@".$cognome.$email.$data."A";
	$k = mysqli_real_escape_string($connessione, $_POST['k']);
	$k = md5($k);
	$int = 1000;
	setcookie("a_t_s",$k,time()+$int);
	$str = md5(md5($str));
	$strk = md5($str.$k."aa");
	setcookie("s_t_s",$strk,time()+$int);
	$arr['str'] = $str;
	$arr['status'] = "ok";
}else if(isset($_POST['fbid']) && isset($_POST['crpt'])){
	
	//$str = $fbid."&&".$nome."__"."0134679".$cognome."@".$cognome.$email.$data."A";
	//$str = md5(md5($str));
	$str_2 = mysqli_real_escape_string($connessione, $_POST['crpt']);
	// STR == STR_2    E    STRK ==  STR_2.K.AA
	$strk = $_COOKIE['s_t_s'];
	$k = $_COOKIE['a_t_s'];
	//$arr['ck1'] = $strk;
	//$arr['ck2'] = $k;
	unset($_COOKIE['a_t_s']);
	unset($_COOKIE['s_t_s']);
	$strk_2 = md5($str_2.$k."aa");
	
	$ok_ok = false;
	//$arr['k2'] = $strk_2;
	//$arr['k1'] = $strk;
	if($strk_2 == $strk){
		$ok_ok = true;
	}
	if($ok_ok == true && $fbid != "" && $nome != "" && $cognome != "" && $email != "" && $data != "" ){
		$sel = mysqli_query($connessione, "SELECT * FROM fb_access WHERE fb_id = '$fbid'");
		$n = mysqli_num_rows($sel);
		$arr['n'] = $n;
		if($n == 0){
			//utente mai usato fb per conn o non registrato a satusia
			//check email
			$selEmail = mysqli_query($connessione, "SELECT * FROM utenti WHERE email = '$email'");
			$nEmail = mysqli_num_rows($selEmail);
			if($nEmail > 0){ //collega account
				$arr['action'] = "connect";
				$ascEmail = mysqli_fetch_assoc($selEmail);
				$iduser = $ascEmail['id'];
			}else{//registra utente
				$ip = $_SERVER['REMOTE_ADDR'];
				$insUser = mysqli_query($connessione, "INSERT INTO utenti(Nome, Cognome, email, Data_Nascita, gotPsw, ip_reg) VALUES('$nome', '$cognome', '$email', '$data', 0, '$ip')");
				$iduser = mysqli_insert_id($connessione);
				$arr['last_id'] = $iduser;
				$arr['action'] = "registra";
			} 
			
			$ins = mysqli_query($connessione, "INSERT INTO fb_access(id_user, fb_id) VALUES('$iduser', '$fbid')");
			if($ins){
				$arr['status'] = "ok_reg";
				$arr['nome'] = $nome;
				$arr['cognome'] = $cognome;
				$arr['email'] = $email;
				$arr['data'] = $data;
				
			}else{
				$arr['status'] = "error_reg";
			}

		}else{
			//logga
			$arr['action'] = "logga";
			$assoc = mysqli_fetch_assoc($sel);
			$iduser = $assoc['id_user'];
			$arr['id_user'] = $iduser;
		}
		$sel_u = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = '$iduser'");
		$n_u = mysqli_num_rows($sel_u);
		if($n_u > 0){
			session_start();
			$_SESSION['id'] = $iduser;
			$ip = $_SERVER['REMOTE_ADDR'];
			$q = mysqli_query($connessione, "UPDATE utenti SET ip_last_login = '$ip' WHERE id = '$iduser'");	
			$arr['status'] = "ok_log";
		}else{
			$arr['status'] = "user_not_found";
		}
	
	}else{
		$arr['status'] = "empty_data";
	}
}else{
	$arr['status'] = "no_data_sent";
}
$res = json_encode($arr);
echo "$res";
?>