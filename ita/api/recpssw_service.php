<?php
header('Content-Type: application/json');
include 'connect.php';
$arr = Array();

if(isset($_POST['email'])){
	$email = mysqli_real_escape_string($connessione, $_POST['email']);
	$sel = mysqli_query($connessione, "SELECT Nome FROM utenti WHERE email = '$email'");
	$n = mysqli_num_rows($sel);
	if($n > 0){

		$random = rand()*rand()*rand();
		$str_rnd = "aaaaa".$email.$random;
		$str_rnd = md5($str_rnd);
		$sel_c = mysqli_query($connessione, "SELECT * FROM miss_password WHERE email = '$email'");
		$n_c = mysqli_num_rows($sel_c);
		$up_ins = false;
		if($n_c > 0){
			$qstr = "UPDATE miss_password SET key_msspswd = '".$str_rnd."' WHERE email = '".$email."'";
			$up = mysqli_query($connessione, $qstr);
			if($up){
				$up_ins = true;
				$arr['act'] = "up";
			}
		}else{
			$qstr = "INSERT INTO miss_password(email, key_msspswd) VALUES('".$email."', '".$str_rnd."')";
			$ins = mysqli_query($connessione, $qstr);
			if($ins){
				$up_ins = true;
				$arr['act'] = "ins";
			}
		}
			
		if($up_ins){
			$from = "no-reply@satusia.com";
			$subject = "Satusia - Richiesta di reset password";
			$link = "https://satusia.com/recupera_password.php?key=".$str_rnd;
			$message = "<html><head></head><body>Premere sul link per impostare una nuova password <a href='".$link."'>".$link."</a>";
			$message = $message." <br /> Se non hai richiesto il reset della password ignora questa email e continua ad usare la password che hai sempre usato.";
			$message = $message." </body></html>";
			$mail_in_html  = "MIME-Version: 1.0\r\n";
			$mail_in_html .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$mail_in_html .= "From: <$from>";						
   			if(mail($email,$subject,$message,$mail_in_html)){
				$arr['status'] = "ok";
			}else{
				$arr['status'] = "error_mail";
			}
		}else{
			$arr['status'] = "error_ins_up";
		}

	}else{
		$arr['status'] = "email_not_found";
	}
}else if(isset($_POST['key']) && isset($_POST['psw'])){
	$key = mysqli_real_escape_string($connessione, $_POST['key']);
	$psw = mysqli_real_escape_string($connessione, $_POST['psw']);
	$cntrl_psw = str_replace(" ", "", $psw);
	if($cntrl_psw != ""){
		$sel = mysqli_query($connessione, "SELECT * FROM miss_password WHERE key_msspswd = '$key'");
		$n = mysqli_num_rows($sel);
		if($n > 0){
			$asc = mysqli_fetch_assoc($sel);
			$email = $asc['email'];
			$del = mysqli_query($connessione, "DELETE FROM miss_password WHERE email = '$email' AND key_msspswd = '$key'");
			if($del){
				$password = md5($psw);
				$up = mysqli_query($connessione, "UPDATE utenti SET password = '$password' WHERE email = '$email'");
				if($up){
					$arr['status'] = "ok";
				}else{
					$arr['status'] = "error_up";
				}
			}else{
				$arr['status'] = "error_del";
			}
		}else{
			$arr['status'] = "key_not_found";
		}
	}else{
		$arr['status'] = "password_not_valid";
	}
}
mysqli_close($connessione);

$res = json_encode($arr);
echo "$res";
?>