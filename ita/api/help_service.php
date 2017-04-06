<?php
header('Content-Type: application/json');	
$arr = Array();
if(isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email']) && isset($_POST['txt'])){
	$nome = htmlentities($_POST['nome']);
	$cognome = htmlentities($_POST['cognome']);
	$email_u = htmlentities($_POST['email']);
	$txt = htmlentities($_POST['txt']);

	$email = 'help@satusia.com';
	$from = "no-reply@satusia.com";
	$subject = "Assistenza automatica";
	$message = "<html><head></head><body>Nome: ".$nome."  Cognome: ".$cognome;
	$message = $message." <br /> Email: ".$email_u." <br/ > <p>".$txt."</p>";
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
	$arr['status'] = "no_post";
}
$res = json_encode($arr);
echo "$res";
?>