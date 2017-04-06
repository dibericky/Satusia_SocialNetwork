<?php
session_start();
header('Content-Type: application/json');
$array = array();	
if(isset($_GET['proj'])){
	include 'connect.php';
	$user = $_SESSION['id'];
	$proj = mysqli_real_escape_string($connessione, $_GET['proj']);
	$sel = mysqli_query($connessione, "SELECT * FROM follow_p WHERE user = '$user' AND proj = '$proj'");
	$rw = mysqli_num_rows($sel);
	mysqli_free_result($sel);
	if($rw > 0){
		$del = mysqli_query($connessione, "DELETE FROM follow_p WHERE user = '$user' AND proj = '$proj'");
		if($del){
			$array['status'] = "unfollow";
		}else{
			$array['status'] = "error unfollow";
		}
	}else{
		$ins = mysqli_query($connessione, "INSERT INTO follow_p(user, proj) values('$user', '$proj')");
		if($ins){
			$sel_date = mysqli_query($connessione, "SELECT date FROM follow_p WHERE user = '$user' AND proj = '$proj'");
			$n = mysqli_num_rows($sel_date);
			if($n > 0){
				$as = mysqli_fetch_assoc($sel_date);
				$dt = $as['date'];
				$ins_act = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_proj, date_action) values('follow_p', '$user', '$proj', '$dt')");
				if($ins_act){
					$array['status'] = "follow";
				}else{
					$array['status'] = "error_nf";
				}
			}else{
				$array['status'] = "error_follow";
			}
		}else{
			$array['status'] = "error";
		}
	
	}
	
	mysqli_close($connessione);
}else{
	$array['status'] = "error_proj";

}
$result = json_encode($array);
echo "$result";
?>