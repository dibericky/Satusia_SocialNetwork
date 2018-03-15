<?php
header('Content-Type: application/json');
$status = false;
$arr = Array();
if(isset($_POST['logmail']) && isset($_POST['logpsswd'])){
	include 'connect.php';
	$mail = mysqli_real_escape_string($connessione, $_POST['logmail']);
	$psswd = md5($_POST['logpsswd']);
	
	//$query = mysqli_query($connessione, "SELECT * FROM utenti WHERE email = '$mail' AND password = '$psswd' AND gotPsw = '1'");
	$query = mysqli_query($connessione, "SELECT * FROM utenti WHERE email = '$mail' AND password = '$psswd'");
	
		$row = mysqli_num_rows($query);
		
		if ($row == 1){

			session_start();
			$assoc = mysqli_fetch_assoc($query);
			mysqli_free_result($query);
			if(isset($_GET['remember'])){
				$random = rand()*rand();
				$random = "qqqqqq".$random."aaaaa".$random;
				$str_remember = md5($random);
				$id_user = $assoc['id'];
				$sel_rm = mysqli_query($connessione, "SELECT * FROM remember WHERE id = '$id_user'");
				$n = mysqli_num_rows($sel_rm);
				$ok = false;
				
				if($n > 0){
					$up = mysqli_query($connessione, "UPDATE remember SET string_rem = '$str_remember' WHERE id = '$id_user'");
					if($up){
						$ok = true;
					}
					//$arr['app'] = 0;
				}else{
					$ins = mysqli_query($connessione, "INSERT INTO remember(id, string_rem) VALUES('$id_user', '$str_remember')");
					if($ins){
						$ok = true;
					}
					//$arr['ins'] = 0;
				}
				if($ok){
					setcookie("usr", $assoc['id'], time()+(3600 * 48));
				
					setcookie("tkn", $str_remember, time()+(3600 * 48));
				}
				$arr['ck'] = $ok;
				$arr['n'] = $n;
			}

			$_SESSION['id'] = $assoc['id'];	
			$last = $assoc['ip_last_login'];
			if($last == ""){
				$arr['isFirst'] = "ok";
			}else{
				$arr['isFirst'] = "no";
			}
			$id = $assoc['id'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$q = mysqli_query($connessione, "UPDATE utenti SET ip_last_login = '$ip' WHERE id = '$id'");	
			$status = true;
			//$_SESSION['nome'] = $assoc['Nome'];
			//$_SESSION['cognome'] = $assoc['Cognome'];
			
		/*	$_SESSION = array();
	

			session_destroy();
			*/
		}
		//echo $row;
		mysqli_close($connessione);
}

$arr["status"] = $status;
$res = json_encode($arr);
echo "$res";

?>
