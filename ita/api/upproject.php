<?php
header('Content-Type: application/json');	

session_start();
$arr = array();
if(isset($_SESSION['id'])){
	include 'connect.php';	
	$titolo = mysqli_real_escape_string($connessione, ucfirst($_POST['titolo']));
	$descr = mysqli_real_escape_string($connessione, ucfirst(nl2br($_POST['descr'])));
	$categoria = mysqli_real_escape_string($connessione, $_POST['categoria']);
	$cat = "";
	switch($categoria){
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
	$iduser = $_SESSION['id'];
	
	/*if($_POST['open'] == "open"){
		$open = 1;
	}else{
		$open = 0;
	}*/
	$titolo_check = str_replace(" ", "", $titolo);
	$descr_check = str_replace(" ", "", $descr);
	if($categoria != "" && $titolo_check != "" && $descr_check != ""){
		$dt = date('Y-m-d H:i:s');
		$up = mysqli_query($connessione, "INSERT INTO progetti(iduser,title, descr, categoria, img, date_create) values('$iduser','$titolo','$descr', '$cat', 'icon/default_pj.png', '$dt')");
		$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE iduser = '$iduser' AND title = '$titolo' ORDER BY date DESC LIMIT 1");
		$asc = mysqli_fetch_assoc($sel);
		$idproj = $asc['idproj'];
		$date = $asc['date'];
		$ups = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_proj, date_action) values('create','$iduser','$idproj', '$date')");
		if($up && $ups){
			
			$arr['status'] = "ok";
			$arr['id_proj'] = $idproj;
			$arr['cat'] = $cat;
			
		}else{
			$arr['status'] = "errore insert";
		}
	}else{
		$arr['status'] = "data_null";
		$arr['t'] = $titolo_check;
		if($titolo_check == ""){
			$arr['what'] = "titolo";
		}else if($descr_check == ""){
			$arr['what'] = "descr";
		}else{
			$arr['what'] = "no_data";
		}
	}
	mysqli_close($connessione);
}else{
	$arr['status'] = "no log";
}
$json = json_encode($arr);
echo "$json";
?>