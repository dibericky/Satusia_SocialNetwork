<?php


session_start();
header('Content-Type: application/json');
$array = array();	
	include 'connect.php';
	if(isset($_POST['message']) && isset($_POST['title']) && isset($_POST['id_p'])){
		$msg = mysqli_real_escape_string($connessione, nl2br($_POST['message']));
		$title = mysqli_real_escape_string($connessione, $_POST['title']);
		$id_user = $_SESSION['id'];
		$id_p = mysqli_real_escape_string($connessione, $_POST['id_p']);
		if($msg != ""){
			$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = '$id_p' AND iduser = '$id_user'");
			$n = mysqli_num_rows($sel);
			if($n == 1){
				$role = "founder";
			}else{
				$sel = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_proj = '$id_p' AND id_mit = '$id_user'");
				$n = mysqli_num_rows($sel);	
				if($n == 1){
					$role = "coll";
				}else{
					$role = "none";
				}
			}
			$array['role'] = $role;
			if($role != "none"){
				
				$q = mysqli_query($connessione, "INSERT INTO `post_p`(`id_p`, `id_user`, `message`, `who_is`, title_post) VALUES ('$id_p','$id_user','$msg','$role', '$title')");
				if($q){
					$sl = mysqli_query($connessione, "SELECT * FROM post_p WHERE id_p = '$id_p' AND id_user = '$id_user' AND who_is = '$role' ORDER BY id_post DESC LIMIT 1");
					$n = mysqli_num_rows($sl);
					if($n > 0){
						$as = mysqli_fetch_assoc($sl);
						$id_post = $as['id_post'];
						$date_post = $as['date_post'];
						
						$ins_act = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_post, id_proj, date_action) values('post', '$id_user', '$id_post', '$id_p', '$date_post')");
						if($ins_act){
							$array['status'] = "ok";
						}else{
							$array['status'] = "not_published";
						}
					}else{
						$array['status'] = "not_insert";
					}
				}else{
					$array['status'] = "error_insert";
				}
			
			}else{
				$array['status'] = "not_allowed";
			}
		}else{
			$array['status'] = "no_message";
		}
	
	}/*else if(isset($_POST['delete']) && isset($_POST['id_news']) &&  isset($_POST['idproj']) && isset($_POST['iduser'])){
		$id_news = mysql_real_escape_string($_POST['id_news']);
		$id_news = str_replace("p_","",$id_news);
		$idproj = mysql_real_escape_string($_POST['idproj']);
		$iduser = mysql_real_escape_string($_POST['iduser']);
		$sel = mysql_query("SELECT * FROM progetti WHERE idproj = '$idproj' AND iduser = '$iduser'");
		$n = mysql_num_rows($sel);
		$i_can = false;
		if($n > 0){
			$i_can = true;
		}else{
			$sel = mysql_query("SELECT * FROM want_help WHERE id_proj = '$idproj' AND id_mit = '$iduser'");
			$n = mysql_num_rows($sel);
			if($n > 0){
				$i_can = true;
			}
		}
		if($i_can == true){
			$del = mysql_query("DELETE FROM `post_p` WHERE `id_post` = '$id_news'");
			$del_nf = mysql_query("DELETE FROM news_feed WHERE id_post = '$id_news'");
			
			$sel_nf = mysql_query("SELECT * FROM news_feed WHERE id_post = '$id_news'");
			$n_nf = mysql_num_rows($sel_nf);
			
			$sel = mysql_query("SELECT * FROM post_p WHERE id_post = '$id_news'");
			$n = mysql_num_rows($sel);
			if($n == 0 && $n_nf == 0){
				echo "1";
			}else{
				echo "4";
			}
			
			
		}else{
			echo "2";
		}
		
	}*/else{
		$array['status'] = "no_var";

	}

	$result = json_encode($array);

	mysqli_close($connessione);

	echo "$result";
?>