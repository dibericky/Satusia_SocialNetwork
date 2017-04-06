<?php
session_start();
header('Content-Type: application/json');	
$array = array();
if(isset($_SESSION['id'])){
	include 'connect.php';
	if(isset($_POST['text']) && isset($_POST['id_p'])){
		$user = $_SESSION['id'];
		$text = mysqli_real_escape_string($connessione, nl2br($_POST['text']));
		$id_p = mysqli_real_escape_string($connessione, $_POST['id_p']);
		$q = mysqli_query($connessione, "INSERT INTO commenti_p(id_user, id_proj, text) VALUES('$user','$id_p','$text')");
		if($q){
			$sl = mysqli_query($connessione, "SELECT * FROM commenti_p WHERE id_proj = '$id_p' AND id_user = '$user' ORDER BY id_com DESC LIMIT 1");
			$n = mysqli_num_rows($sl);
			if($n > 0){
				$as = mysqli_fetch_assoc($sl);
				mysqli_free_result($sl);
				$id_com = $as['id_com'];
				$date_com = $as['date_com'];
					
				$ins_act = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_com, id_proj, date_action) values('comment_p', '$user', '$id_com', '$id_p', '$date_com')");
				if($ins_act){
					$array['status'] = "ok";
				}else{
					$array['status'] = "not_insert_in_newsfeed";
				}
			}else{
				$array['status'] = "not_found_comment";
			}
			
		}else{
			$array['status'] = "not_insert";
		}
	
	}else{
		$array['status'] = "not_data";
	}
}else{
	$array['status'] = "not_logged";
}
$json = json_encode($array);
echo "$json";
mysqli_close($connessione);

?>