<?php /*
error_reporting(E_ALL);
ini_set(‘display_errors’, ‘1’);*/

header('Content-Type: application/json');
$arr = Array();
include 'connect.php';
if(isset($_POST['reg_nome']) && isset($_POST['reg_cogn']) && isset($_POST['email']) && isset($_POST['reg_pass']) ){
	$nome = mysqli_real_escape_string($connessione, ucfirst($_POST['reg_nome']));
	$email_ok = true;
	$cognome = mysqli_real_escape_string($connessione, ucfirst($_POST['reg_cogn']));
	$email = mysqli_real_escape_string($connessione, $_POST['email']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  $email_ok = false; 
	}
	if($email_ok){
		$exist = mysqli_query($connessione, "SELECT * FROM utenti WHERE email = '$email'");
		$n_e = mysqli_num_rows($exist);
		if($n_e > 0){
			$arr['status'] = "email_presente";
		}else{
			$request_exist = mysqli_query($connessione, "SELECT * FROM temp_utenti WHERE email = '$email'");
			$n_r = mysqli_num_rows($request_exist);
			if($n_r > 0){
				$arr['status'] = "request_presente";
			}else{
				$psw = mysqli_real_escape_string($connessione, $_POST['reg_pass']);
				$cntrl = str_replace(" ", "", $psw);
				if($cntrl == ""){
					$arr['status'] = "passord_empty";
				}else{
					$reg_pass = md5($psw);
					$ip = $_SERVER['REMOTE_ADDR'];
					$rand = rand()*rand();
					$kytmp = $email."aa".$ip."bb".$nome."cc".$cognome.$rand;
					$kytmp = md5($kytmp);
					$q = mysqli_query($connessione, "INSERT INTO temp_utenti(key_temp, Nome,Cognome,email,password, ip_reg) values('$kytmp','$nome','$cognome','$email','$reg_pass', '$ip')");
					if($q){
						$from = "no-reply@satusia.com";
						$subject = "Registrazione a Satusia";
						$link = "https://satusia.com/index.php?key=".$kytmp;
						$message = "<html><head></head><body>Premere sul link per confermare la registrazione <a href='".$link."'>".$link."</a>";
						$message = $message." <br /> Se non hai richiesto la registrazione ignora questa email.  In caso di problemi contatta: help@satusia.com";
						$message = $message." </body></html>";
						$mail_in_html  = "MIME-Version: 1.0\r\n";
						$mail_in_html .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$mail_in_html .= "From: <$from>";	
				   		if(mail($email,$subject,$message,$mail_in_html)){
							$arr['status'] = "ok";
						}else{
							$arr['status'] = "error_email";
				 		}
					}else{
						$arr['status'] = "error_query";
					}
				}
			}
		}
	}else{
		$arr['status'] = "invalid_email";
	}

}else if(isset($_GET['key_temp'])){
	$key = mysqli_real_escape_string($connessione, $_GET['key_temp']);
	$control = mysqli_query($connessione, "SELECT * FROM temp_utenti WHERE key_temp = '$key'");
	$n = mysqli_num_rows($control);
	if($n > 0){
		$asc = mysqli_fetch_assoc($control);
		$email = $asc['email'];
		$exist = mysqli_query($connessione, "SELECT * FROM utenti WHERE email = '$email'");
		$n_e = mysqli_num_rows($exist);
		if($n_e > 0){
			$arr['status'] = "email_presente";
		}else{
			$nome = $asc['Nome'];
			$cognome = $asc['Cognome'];
			$psw = $asc['password'];
			$ip_reg = $asc['ip_reg'];
			$date_reg = $asc['date'];
			$ins = mysqli_query($connessione, "INSERT INTO utenti(Nome, Cognome, password, ip_reg, data_reg, email) VALUES('$nome', '$cognome', '$psw', '$ip_reg', '$date_reg', '$email')");
			if($ins){
				$arr['status'] = "ok";
				$del = mysqli_query($connessione, "DELETE FROM temp_utenti WHERE email = '$email'");
				if($del){
					$arr['del'] = "ok";
				}else{
					$arr['del'] = "error_del";
				}
			}else{
				$arr['status'] = "error_ins";
			}
		}
	}else{
		$arr['status'] = "key_not_found";
	}
}else{
	$arr['status'] = "no_data_sent";
}
mysqli_close($connessione);
$res = json_encode($arr);
echo $res;