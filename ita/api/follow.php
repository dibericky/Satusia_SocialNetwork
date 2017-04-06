<?php
session_start();
header('Content-Type: application/json');
$array = array();	
if(isset($_GET['followed'])){
	include 'connect.php';
	$user = $_SESSION['id'];
	$followed = mysqli_real_escape_string($connessione, $_GET['followed']);
	$sel = mysqli_query($connessione, "SELECT * FROM follow WHERE follower = '$user' AND followed = '$followed'");
	$rw = mysqli_num_rows($sel);
	mysqli_free_result($sel);
	if($rw > 0){
		$del = mysqli_query($connessione, "DELETE FROM follow WHERE follower = '$user' AND followed = '$followed'");
		if($del){
			$array['status'] = "unfollow";
		}else{
			$array['status'] = "error unfollow";
		}
	}else{
		$ins = mysqli_query($connessione, "INSERT INTO follow(follower,followed) values('$user', '$followed')");
		if($ins){
			$sel_date = mysqli_query($connessione, "SELECT date FROM follow WHERE follower = '$user' AND followed = '$followed'");
			$n = mysqli_num_rows($sel_date);
			if($n > 0){
				$as = mysqli_fetch_assoc($sel_date);
				$dt = $as['date'];
				$ins_act = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_user_target, date_action) values('follow_u', '$user', '$followed', '$dt')");
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
	mysql_close($connessione);
	
}else{
	$array['status'] = "error_u";

}
$result = json_encode($array);
echo "$result";
?>