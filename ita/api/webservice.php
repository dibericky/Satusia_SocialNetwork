<?php
//if($_SERVER['HTTP_REFERER'] == "http://satusia.com/beta/new/notif.php"){
session_start();

error_reporting(E_ALL);
ini_set(‘display_errors’, ‘1’);
include 'connect.php';
header('Content-Type: application/json');	

include 'time_ago.php';

if(isset($_SESSION['id'])){
	$act = mysqli_real_escape_string($connessione, $_GET['action']);
	if($act == "follower"){  //?action=follower&id=ID
			//Chi ID segue
			$id = mysqli_real_escape_string($connessione, $_GET['id']);
			$sel = mysqli_query($connessione, "SELECT followed FROM follow WHERE follower = '$id'");
			$n = mysqli_num_rows($sel);
			$arr_ass = array("num" => $n, "id" => "");
			$arr = array();
			$a = 0;
			while($asc = mysqli_fetch_assoc($sel)){
				$arr[$a] = $asc['followed'];
				$a = $a +1;
			}
			$arr_ass["id"] = $arr;
			$result = json_encode($arr_ass);
			echo "$result";
	}
	
	else if($act =="followed"){ //?action=followed&id=ID
		//chi segue ID
		$id = mysqli_real_escape_string($connessione, $_GET['id']);
		if(isset($_GET['by'])){
			$by = mysqli_real_escape_string($connessione, $_GET['by']);
			$by = " ".$by.", ";
		}else{
			$by = "";
		}
		$limit = $by." 9";
		$arr = array();
		$sel = mysqli_query($connessione, "SELECT follower, Nome, Cognome, professione, immagine_profilo FROM follow, utenti WHERE followed = '$id' AND follower = utenti.id LIMIT".$limit);
		$n = mysqli_num_rows($sel);
		$arr_ass = array("num" => $n, "user" => "");
		
		$a = 0;
		while($asc = mysqli_fetch_assoc($sel)){
			$arr[$a] = $asc;
			$a = $a +1;
		}
		$arr_ass["user"] = $arr;
		$result = json_encode($arr_ass);
		echo "$result";
	}
	
	else if($act == "info_id" ){   //action=info_id&id=ID&nome&cognome ...
		$id = mysqli_real_escape_string($connessione, $_GET['id']);
		$array = array();
		if(isset($_GET['nome'])){
			$array[0] = "Nome";
		}
		if(isset($_GET['cognome'])){
			$array[1] = "Cognome";
		}
		if(isset($_GET['nascita'])){
			$array[2] = "Data_Nascita";
		}
		if(isset($_GET['email'])){
			$array[3] = "email";
		}
		if(isset($_GET['ip_reg'])){
			$array[4] = "ip_reg";
		}
		if(isset($_GET['data_reg'])){
			$array[5] = "data_reg";
		}
		if(isset($_GET['ipll'])){
			$array[6] = "ip_last_login";
		}
		if(isset($_GET['img'])){
			$array[7] = "immagine_profilo";
		}
		if(isset($_GET['citta'])){
			$array[8] = "citta";
		}
		if(isset($_GET['sesso'])){
			$array[9] = "sesso";
		}
		if(isset($_GET['professione'])){
			$array[10] = "professione";
		}
		if(isset($_GET['telefono'])){
			$array[11] = "telefono";
		}
		if(isset($_GET['biografia'])){
			$array[12] = "biografia";
		}
		if(isset($_GET['scuola_superiore'])){
			$array[13] = "scuola_superiore";
		}
		if(isset($_GET['universita'])){
			$array[14] = "universita";
		}
		if(isset($_GET['lingue'])){
			$array[15] = "lingue";
		}
		if(isset($_GET['frase_personale'])){
			$array[16] = "frase_personale";
		}
		$str= "";
		
		$named_array = array("Nome"=>"","Cognome"=>"","Data_Nascita"=>"","email"=>"","ip_reg"=>"","data_reg"=>"","ip_last_login"=>"","immagine_profilo"=>"","citta"=>"","sesso"=>"","professione"=>"","telefono"=>"","biografia"=>"", "scuola_superiore"=>"","universita"=>"","lingue"=>"", "frase_personale"=>"");
		$num_element = 17;
		for($k=0; $k<$num_element; $k++){
			if($array[$k] != null){
					$str= $str.$array[$k].", ";
				}
			}
		$str=substr_replace($str, "", -2);
			
			$q = "SELECT ".$str." FROM utenti WHERE id = '$id'";
			//echo "$q";
			$sel = mysqli_query($connessione, $q);
			
			$asc = mysqli_fetch_row($sel);
			$c = 0;
			for($w = 0; $w < $num_element; $w++){
				if($array[$w] != null){
					$field = $array[$w];
					if($field == "universita" || $field == "lingue"){
						$arr_multi = Array();
						if($asc[$c] != ""){
							$str_uni = $asc[$c];
							$str_uni = str_replace("[", "", $str_uni);
							$str_uni = str_replace("]", "", $str_uni);
							$arr_multi = explode(",", $str_uni);
						}
						$asc[$c] = $arr_multi;
					}else if($field == "ip_reg" || $field == "ip_last_login"){
						$asc[$c] = "not_allowed";
					}else if($field == "data_reg"){
						$asc[$c] = timeAgo($asc[$c]);
					}else if($field == "Data_Nascita"){
						$dateN = $asc[$c];
						if($dateN != "0000-00-00"){
							//$dateN = date_format(date_create($dateN), "d/m/Y");
							$asc[$c] = timeAgo($dateN);
						}else{
							$asc[$c] = "Non inserita";
						}
					}
					$named_array[$field] = $asc[$c];
					
					$c++;
				}
			}
			
			$js_on= json_encode($named_array);
			echo "$js_on";
			
		
	}else if($act == "info_progetto"){  //?action=info_progetto&id_p=ID  
		$id_p = mysqli_real_escape_string($connessione, $_GET['id_p']);
		$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = '$id_p'");
		$n = mysqli_num_rows($sel);
		$arr = array();

		if($n > 0){
			$asc = mysqli_fetch_assoc($sel);
			$n_like = $asc['likes'];
			$sel_l = mysqli_query($connessione, "SELECT * FROM likestab WHERE idproj = '$id_p'");
			
			$k = 0;
			while($asc_l = mysqli_fetch_assoc($sel_l)){
				$arr[$k] = $asc_l['idutente'];
				$k++;
			}
			$arr_ass = array("num" => $n_like, "idutente" => $arr);
			$asc['likes'] = $arr_ass;
			$asc['status'] = true;
			$js_on = json_encode($asc);
		}else{
			$arr['status'] = false;
			$js_on = json_encode($arr);
		}
		
		
		echo "$js_on";
	
	}else if($act == "similar_p"){ //?action=similar_p&id_p=ID  
		
		$id_p = mysqli_real_escape_string($connessione, $_GET['id_p']);
		$sel = mysqli_query($connessione, "SELECT * FROM progetti, utenti WHERE idproj = '$id_p' AND progetti.iduser = utenti.id");
		$asc = mysqli_fetch_assoc($sel);
		$cat = $asc['categoria'];
		$tag_first = json_decode($asc['tag']);
		$citta_first = $asc['citta'];
		
		$arr = array();
		$k = 0;
		//$sel_smlr = mysql_query("SELECT * FROM progetti, utenti WHERE categoria = '$cat' AND utenti.id = progetti.iduser AND finito != '1'");
		$sel_smlr = mysqli_query($connessione, "SELECT * FROM progetti, utenti WHERE idproj != '$id_p' AND datediff(now(), date) < 100 AND categoria = '$cat' AND utenti.id = progetti.iduser AND finito != 1");
		$sim_item = array();
		while($asc_smlr = mysqli_fetch_assoc($sel_smlr)){
			$user = array("id" => $asc_smlr['id'], "citta"=>$asc_smlr['citta']);
			$date = date("d-m-Y", strtotime($asc_smlr['date']));
			$punt = 0.5;
			
			$s_i = 0;
			$tag_o = json_decode($asc_smlr['tag']);
			if($citta_first == $asc_smlr['citta']){
				$punt = $punt + 0.1;
				$sim_item[$s_i] = $asc_smlr['citta'];
				$s_i++;
			}
			//if($cat == $asc_smlr['categoria']) $punt = $punt + 0.5;
			// aggiungere controlli tag     0.3 x tag
			for($j = 0; $j < count($tag_first); $j++){
				for($w = 0; $w < count($tag_o); $w++){
					if($tag_first[$j] == $tag_o[$w]){
						$punt = $punt + 0.3;
						$sim_item[$s_i] = $tag_o[$w];
						$s_i++;
					}
				}
			}
			$short_descr = $asc_smlr['descr'];
			if(strlen($short_descr) > 153){
				$short_descr = substr($asc_smlr['descr'],0,150);
				$short_descr = $short_descr."...";
			}
			$info_pro = array("punteggio"=>$punt,"idproj" => $asc_smlr['idproj'], "categoria"=>$asc_smlr['categoria'],"user"=>$user, "title"=> $asc_smlr['title'], "descr"=> $short_descr, "date"=> $date, "img" => $asc_smlr['img'], "tag"=>$tag_o, "similar_item"=> $sim_item);
			$arr[$k] = $info_pro;
			$k++;
			$sim_item = "";
		
		}
		
		for($x = 0; $x < $k; $x++){
			for($y = 0; $y<$k; $y++){
				$a = (float)$arr[$x][punteggio]; 
				$b = (float) $arr[$y][punteggio];
				if($a > $b){
					$c = $arr[$x];
					$arr[$x] = $arr[$y];
					$arr[$y] = $c;
				}
			}
			
		}
		$info_first = array("tag_first"=> $tag_first, "citta_first" => $citta_first, "cate_first"=> $cat);
		$array = array();
		$array["info_first_project"] = $info_first;
		$array['similar_proj'] = $arr;
		$js_on = json_encode($array);
		echo "$js_on";
		
	
	}else if($act == "proj_same_cat"){ //?action=proj_same_cat&id=IDUSER
		$id_user = mysqli_real_escape_string($connessione, $_GET['id']);
		$sel_p = mysqli_query($connessione, "SELECT * FROM progetti WHERE iduser = '$id_user'");
		$n = mysqli_num_rows($sel_p);
		if($n > 0){
			$ar_id = array();
			$ar_cate = array();
			$ar_title = array();
			
			$x = 0;
			while($as= mysqli_fetch_assoc($sel_p)){
				$ar_id[$x] = $as['idproj'];
				$ar_cate[$x] = $as['categoria'];
				$ar_title[$x] = $as['title'];
				$x++;
			}
			$max = count($ar_id) - 1;
			$random = rand(0, $max);
			$cate = $ar_cate[$random];
			$title = $ar_title[$random];
			$id = $ar_id[$random];
			$sel_other_p = mysqli_query($connessione, "SELECT * FROM progetti WHERE categoria = '$cate' AND iduser != '$id_user'");
			$n_o = mysqli_num_rows($sel_other_p);
			$result = array();
			$start_p = array("id_p"=>$id, "cate_p"=>$cate, "title_p"=>$title);
			$result['start_p'] = $start_p;
			if($n_o > 0){
				
				$arr_other_p = array();
				$x = 0;
				while($asc = mysqli_fetch_assoc($sel_other_p)){
					$arr_other_p[$x] = $asc;
					$x++;
				}
				$max = count($arr_other_p) - 1;
				$random = rand(0, $max);
				$result['proj_random'] = $arr_other_p[$random];
				//$result['random'] = $random;
				//$result['n_o'] = $n_o;
				
			}else{
				$result['proj_random'] = null;
			}
			
			
		}else{
			$result = null;
		}
		
		$js_on = json_encode($result);
		echo "$js_on";
	
	}else if($act == "coll_proj"){ //?action=coll_proj&id=ID
		$proj = $_GET['id'];
		$sel_coll = mysqli_query($connessione, "SELECT utenti.id, immagine_profilo, Nome, Cognome FROM want_help, utenti WHERE id_proj = '$proj' AND view = 1 AND id_mit = utenti.id");
		
		$arr = array();
		$k = 0;
		while($asc = mysqli_fetch_assoc($sel_coll)){
			$arr[$k] = $asc;
			$k++;
		}
		$js_on = json_encode($arr);
		echo "$js_on";
	
	}else if($act == "follow_p"){  //?action=follow_p&proj=proj
		$proj = mysqli_real_escape_string($connessione, $_GET['proj']);
		if(isset($_GET['by'])){
			$by = mysqli_real_escape_string($connessione, $_GET['by']);
			$by = " ".$by.", ";
		}else{
			$by = "";
		}
		$limit = $by." 9";
		$arr = array();
		$sel = mysqli_query($connessione, "SELECT user, Nome, immagine_profilo FROM follow_p, utenti WHERE proj = '$proj' AND user = utenti.id LIMIT".$limit);
		$n = mysqli_num_rows($sel);
		$arr_ass = array("num" => $n, "user" => "");
		
		$a = 0;
		while($asc = mysqli_fetch_assoc($sel)){
			$arr[$a] = $asc;
			$a = $a +1;
		}
		$arr_ass["user"] = $arr;
		$result = json_encode($arr_ass);
		echo "$result";
	
	}else if($act == "post_p"){  //?action=post_p&proj=ID
		$proj = mysqli_real_escape_string($connessione, $_GET['proj']);
		if(isset($_GET['by'])){
			$by = mysqli_real_escape_string($connessione, $_GET['by']);
			$by = " ".$by.", ";
		}else{
			$by = "";
		}
		$limit = $by." 5";
		$arr = array();
		$sel = mysqli_query($connessione, "SELECT id, id_post, Nome, Cognome, message, title_post, date_post, who_is  FROM post_p, utenti WHERE id_p = '$proj' AND id_user = id ORDER BY date_post DESC LIMIT".$limit);
		$a = 0;
		while($asc = mysqli_fetch_assoc($sel)){
			$asc['date_post'] = timeAgo($asc['date_post']);
			$arr[$a] = $asc;
			$a = $a +1;
		}
		$result = json_encode($arr);
		echo "$result";
	
	}else if($act == "proj_user"){  //?action=proj_user&user=ID OR ?action=proj_user&user=ID&finito=N 
		$user = mysqli_real_escape_string($connessione, $_GET['user']);
		$arr = array();
		$selUser = mysqli_query($connessione, "SELECT Nome, Cognome, citta FROM utenti WHERE id = '$user'");
		$ascUser = mysqli_fetch_assoc($selUser);
		$nome = $ascUser['Nome'];
		$cognome = $ascUser['Cognome'];
		$citta = $ascUser['citta'];
		if(isset($_GET['finito']) && $_GET['finito'] != null){
			$finito = mysqli_real_escape_string($connessione, $_GET['finito']);
			$sel = mysqli_query($connessione, "SELECT *  FROM progetti WHERE iduser = '$user' AND finito = '$finito' ORDER BY date DESC");
		}else{
			$sel = mysqli_query($connessione, "SELECT *  FROM progetti WHERE iduser = '$user' ORDER BY date DESC");
		}
		$a = 0;
		while($asc = mysqli_fetch_assoc($sel)){
			$asc['nome'] = $nome;
			$asc['citta'] = $citta;
			$asc['cognome'] = $cognome;
			$idp = $asc['idproj'];
			$selNFll = mysqli_query($connessione, "SELECT * FROM follow_p WHERE proj = '$idp'");
			$nfll = mysqli_num_rows($selNFll);
			$asc['follower'] = $nfll;
			
			$selNCll = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_proj = '$idp'");
			$ncll = mysqli_num_rows($selNCll);
			$asc['collab'] = $ncll;
			
			$arr[$a] = $asc;
			$a = $a +1;
		}
		$result = json_encode($arr);
		echo "$result";		
	
	}else if($act == "change_finito"){ //?action=change_finito&proj=ID_PROJ
		$proj = mysqli_real_escape_string($connessione, $_GET['proj']);
		$arr = array();
		$sel = mysqli_query($connessione, "SELECT finito FROM progetti WHERE idproj = '$proj'");
		$asc = mysqli_fetch_assoc($sel);
		if($asc['finito'] == 0){
			$finito = 1;
		}else if($asc['finito'] == 1){
			$finito = 0;
		}
		$arr['finito'] = $finito;
		$up = mysqli_query($connessione, "UPDATE progetti SET finito = '$finito' WHERE idproj = '$proj'");
		if($up){
			if($finito == 1){
				$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = '$proj'");
				$asc = mysqli_fetch_assoc($sel);
				$dt = $asc['date'];
				$user = $asc['iduser'];
				$ins_act = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, id_proj, date_action) values('complete', '$user', '$proj', '$dt')");
				if($ins_act){
					//tutto ok
				}else{
					$arr['finito'] = "Errore nell'update";
				}
			}
		}else{
			$arr['finito'] = "Errore nell'update";
		}
		$result = json_encode($arr);
					
		echo "$result";		
				
	
	
	}else if($act == "coll_user"){ //?action=coll_user&user=ID
		$id = mysqli_real_escape_string($connessione, $_GET['user']);
		$sel_coll = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_ric = '$id' AND view = 1");
		//$rw_coll = mysql_num_rows($sel_coll);
		$coll_id_array = array();
		$coll_limit = 0;
		$arr_img = array();
		while($assc_coll = mysqli_fetch_assoc($sel_coll)){
			$arr_img['id'] = $assc_coll['id_mit'];
			$arr_img['immagine_profilo'] = $assc_coll['immagine_profilo'];
			$coll_id_array[$coll_limit] = $arr_img;
			$coll_limit++;
		}
		/*$coll_id_array = array_unique($coll_id_array);
		$coll_id_array = array_values($coll_id_array);
		*/
		$result = json_encode($coll_id_array);
		echo "$result";
		
	}else if($act == "com_user"){ //?action=com_user&user_a=ID&user_b=ID
		$id_a = mysqli_real_escape_string($connessione, $_GET['user_a']);
		$id_b = mysqli_real_escape_string($connessione, $_GET['user_b']);
		$sel_a = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = '$id_a'");
		$sel_b = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = '$id_b'");
		$ass_a = mysqli_fetch_assoc($sel_a);
		$ass_b = mysqli_fetch_assoc($sel_b);
		$array = array();
		if($ass_a['citta'] == $ass_b['citta']){
			$array['citta'] = $ass_a['citta'];
		}
		if($ass_a['sesso'] == $ass_b['sesso']){
			$array['sesso'] = $ass_a['sesso'];
		}
		if($ass_a['professione'] == $ass_b['professione']){
			$array['professione'] = $ass_a['professione'];
		}
		$sel_a = mysqli_query($connessione, "SELECT * FROM interessi WHERE id_user = '$id_a'");
		$sel_b = mysqli_query($connessione, "SELECT * FROM interessi WHERE id_user = '$id_b'");
		
		$n_a = mysqli_num_rows($sel_a);
		$n_b = mysqli_num_rows($sel_b);
		if($n_a > 0 && $n_b > 0){
			$asc_a = mysqli_fetch_assoc($sel_a);
			$int_a = $asc_a['interessi'];
			$int_a = json_decode($int_a);
			$n_a = count($int_a);
			$asc_b = mysqli_fetch_assoc($sel_b);
			$int_b = $asc_b['interessi'];
			$int_b = json_decode($int_b);
			$n_b = count($int_b);
		
			if($n_a > 0 && $n_b > 0){
				$arr_i = array_intersect($int_a, $int_b);
				$arr_i = array_values($arr_i);
				$array['interessi'] = $arr_i;
				
			}else{
				$array['interessi'] = null;
			}
		}else{
			$array['interessi'] = null;
		}
		$result = json_encode($array);
		echo "$result";
		
	}else if($act == "interest_user"){ //?action=interest_user&user=ID or ?action=interest_user&user=ID&do=ADD&int=INTERESSE   do=ADD or do=DEL
		$id = mysqli_real_escape_string($connessione, $_GET['user']);
		
		if(isset($_GET['do'])){
			$do = mysqli_real_escape_string($connessione, $_GET['do']);
			$int = mysqli_real_escape_string($connessione, $_GET['int']);
			$sel = mysqli_query($connessione, "SELECT * FROM interessi WHERE id_user = '$id'");
			if($do == "ADD"){
				$n = mysqli_num_rows($sel);
				if($n == 0){
					$arr = array();
					if(strpos($int, ",")){
						$arr = explode(",",$int);
					}else{
						$arr[0] = $int;
					}
					$arr = array_unique($arr);
					$arr = array_values($arr);
					$j = json_encode($arr);
					$i = mysqli_query($connessione, "INSERT into interessi(id_user, interessi) VALUES('$id','$j')");
				}else{
					$asc = mysqli_fetch_assoc($sel);
					$interest = json_decode($asc['interessi']);
					$n_i = count($interest);
					if(strpos($int, ",")){
						$int = explode(",",$int);
						$n_ex = count($int);
						for($z = 0; $z < $n_ex; $z++){
							$interest[$n_i] = $int[$z];
							$n_i++;
						}
					}else{
						$interest[$n_i] = $int;
					}
					$interest = array_unique($interest);
					$interest = array_values($interest);
					$j = json_encode($interest);
					$i = mysqli_query($connessione, "UPDATE interessi set interessi = '$j' WHERE id_user = '$id'");
				}
			}else if($do == "DEL"){
					$asc = mysqli_fetch_assoc($sel);
					$interest = json_decode($asc['interessi']);
					$n_i = count($interest);
					$ary = array();
					$y = 0;
					for($x=0; $x < $n_i; $x++){
						if($interest[$x] != $int){
							$ary[$y] = $interest[$x];
							$y++;
						}
					}
					
					$j = json_encode($ary);
					$i = mysqli_query($connessione, "UPDATE interessi set interessi = '$j' WHERE id_user = '$id'");
			}
		}
		$sel = mysqli_query($connessione, "SELECT * FROM interessi WHERE id_user = '$id'");
		$ass = mysqli_fetch_assoc($sel);
		$interest = json_decode($ass['interessi']);
		
		$ass['interessi'] = $interest;
		
		$result = json_encode($ass);
		echo "$result";
	}else if($act == "news_feed"){ //?action=news_feed
		$array = Array();
		
		$id = mysqli_real_escape_string($connessione, $_SESSION['id']);
		$sel_proj_i_help = mysqli_query($connessione, "SELECT id_proj FROM want_help WHERE id_mit = '$id' AND view = 1");
		$n = mysqli_num_rows($sel_proj_i_help);
		if($n > 0){
			$arr_i_help = array();
			$x = 0;
			while($as_i_h = mysqli_fetch_assoc($sel_proj_i_help)){  //Progetti a cui collaboro
				$arr_i_help[$x] = $as_i_h['id_proj'];
				$x++;
			}
			
			
			
		}else{
			$arr_i_help = false;
		}
		
		$sel_proj_fll = mysqli_query($connessione, "SELECT proj FROM follow_p WHERE user = '$id'");
		$n = mysqli_num_rows($sel_proj_fll);
		if($n > 0){
			$arr_proj_fll = array();
			$x = 0;
			while($as_proj_fll = mysqli_fetch_assoc($sel_proj_fll)){  //Progetti che seguo
				$arr_proj_fll[$x] = $as_proj_fll['proj'];
				$x++;
			}
			
						
		}else{
			$arr_proj_fll = false;
		}
		
		$x = 0;
		$arr_proj = Array();
		if($arr_i_help != false){
			for($y=0; $y < count($arr_i_help); $y++){
				$arr_proj[$x] = $arr_i_help[$y];
				$x++;
			
			}
		}
		if($arr_proj_fll != false){
			for($y=0; $y < count($arr_proj_fll); $y++){
				$arr_proj[$x] = $arr_proj_fll[$y];
				$x++;
			
			}
		}
		$arr_proj = array_unique($arr_proj); //elimino doppioni
		$arr_proj = array_values($arr_proj); //riordino indici
		
		
		$sel_fll = mysqli_query($connessione, "SELECT followed FROM follow WHERE follower = '$id'"); //persone che seguo
		//$array['sql_fl'] = "SELECT followed FROM follow WHERE follower = '$id'";
		$n_f = mysqli_num_rows($sel_fll);
		$arr_fll = array();
		if($n_f > 0){
			
			$x = 0;
			while($as_fll = mysqli_fetch_assoc($sel_fll)){
				$arr_fll[$x] = $as_fll['followed'];
				$x++;
			}
		}else{
			
		}
		//SELECT * FROM news_feed WHERE ((id_user_actor != '11') OR (id_user_actor = '11' AND action = 'accept_w_h')) AND (id_user_actor = '' OR id_user_target = '' )
		$query_start = "SELECT * FROM news_feed WHERE ((id_user_actor != '$id') OR (id_user_actor = '$id' AND action = 'accept_w_h')) AND (";
		$query = "";
		if(count($arr_proj) > 0){ 
			$q_proj = "id_proj = '$arr_proj[0]' ";
			
			for($x = 1; $x < count($arr_proj); $x++){
				$q_proj = $q_proj."OR id_proj = '$arr_proj[$x]' ";
			}
			$query = $q_proj;
		}
		$array['count_arr_proj'] = count($arr_proj);
		$array['count_arr_fll'] = count($arr_fll);
		
		if(count($arr_fll) > 0){
			if(count($arr_proj) > 0){
				$q_fll_u = "OR id_user_actor = '$arr_fll[0]' ";
				$q_fll_2 = "OR id_user_target = '$arr_fll[0]'";
			}else{
				$q_fll_u = "id_user_actor = '$arr_fll[0]' ";
				$q_fll_2 = "OR id_user_target = '$arr_fll[0]' ";
			}
			for($x = 1; $x < count($arr_fll); $x++){
				$q_fll_u = $q_fll_u."OR id_user_actor = '$arr_fll[$x]' ";
				$q_fll_2 = $q_fll_2."OR id_user_target = '$arr_fll[$x]' ";
			}

			$query = $query.$q_fll_u.$q_fll_2;
		}
		if($query == ""){
			$query = "1 = 2";
		}
		$query_complete = $query_start.$query.")";
		$query_init = $query_complete;
		$sel_n_f = mysqli_query($connessione, $query_complete);
		$n = mysqli_num_rows($sel_n_f);
		if($n > 0){
			$arr_n_f = array();
			$x = 0;
			while($asc_n_f = mysqli_fetch_assoc($sel_n_f)){
				$arr_n_f[$x] = $asc_n_f['id_action'];
				$x++;
			}
		}else{
			$arr_n_f = null;
		}
		
		if($arr_n_f != null){
			$arr_n_f = array_unique($arr_n_f);
			$arr_n_f = array_values($arr_n_f);
			$query = "SELECT id_action, action, id_user_actor, id_proj, id_user_target, id_post, id_com,id_wh, date_action, Nome, Cognome, immagine_profilo FROM news_feed, utenti WHERE id_user_actor = utenti.id AND action != 'message_p' AND action != 'refuse_w_h' AND action != 'want_help' AND action != 'complete' AND (";   
			$q = "id_action = '$arr_n_f[0]' ";
			for($x = 1; $x < count($arr_n_f); $x++){
				$q = $q."OR id_action = '$arr_n_f[$x]' ";
			}

			$limit = 50;
			if(isset($_GET['by'])){
				$by = mysqli_real_escape_string($connessione, $_GET['by']).", ";
			}else{
				$by = "";
			}

			$query = $query.$q.") ORDER BY date_action DESC LIMIT ".$by.$limit;
			
			$sel_n = mysqli_query($connessione, $query);
			$n_nf = mysqli_num_rows($sel_n);
			$array_newsfeed = Array();
			if($n_nf > 0){
				$x = 0;
				//$array_feed = array();
				while($as = mysqli_fetch_assoc($sel_n)){
					//$array_feed['detail'] = $as;
					$as['date_action'] = timeAgo($as['date_action']);
					if($as['action'] == "follow_p" || $as['action'] == "post" || $as['action'] == "create" || $as['action'] == "complete" || $as['action'] == "share_p" || $as['action'] == "want_help" || $as['action'] == "accept_w_h"){
						$id_proj = $as['id_proj'];
						$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = '$id_proj'");
						$sc = mysqli_fetch_assoc($sel);
						$as['title'] = $sc['title'];
						$descr_p = $sc['descr'];
						if(strlen($descr_p) > 503){
							$descr_p = substr($descr_p, 0, 500);
							$descr_p = $descr_p."...";
						}
						$as['descr'] = $descr_p;
						$as['img_proj'] = $sc['img'];
						$as['cate'] = $sc['categoria'];
					}
					if($as['action'] == "follow_u" || $as['action'] == "share_u"){
						$id_target = $as['id_user_target'];
						$sel = mysqli_query($connessione, "SELECT Nome, Cognome, immagine_profilo, professione, citta, frase_personale, Data_Nascita FROM utenti WHERE id = '$id_target'");
						$sc = mysqli_fetch_assoc($sel);
						$as['Nome_followed'] = $sc['Nome'];
						$as['Cognome_followed'] = $sc['Cognome'];
						$as['immagine_profilo_followed'] = $sc['immagine_profilo'];
						$as['professione'] = $sc['professione'];
						$as['data_nascita'] = $sc['Data_Nascita'];
						$as['citta'] = $sc['citta'];
						$as['frase_personale'] = $sc['frase_personale'];
							
					}
					if($as['action'] == "complete"){
						$selection = mysqli_query($connessione, "SELECT date_post, id_p, id_post, title_post, id_user, message, who_is, immagine_profilo, Nome, Cognome FROM post_p, utenti WHERE id_p = '$id_proj' AND id_user = utenti.id ORDER BY date_post desc LIMIT 3");
						$ar_selection = array();
						$k_l_p = 0;
						while($sel_l_p = mysqli_fetch_assoc($selection)){
							$sel_l_p['date_post'] = timeAgo($sel_l_p['date_post']);
							$ar_selection[$k_l_p] = $sel_l_p;
							$k_l_p++;
						}
						$as['last_post'] = $ar_selection;
					}
					if($as['action'] == "post"){
						$idpost = $as['id_post'];
						$sel = mysqli_query($connessione, "SELECT * FROM post_p WHERE id_post = '$idpost'");
						$n_post = mysqli_num_rows($sel);
						if($n_post > 0){
							$sc = mysqli_fetch_assoc($sel);
							$as['post'] = $sc['message'];
							$as['title_post'] = $sc['title_post'];
							$as['who_is'] = $sc['who_is'];
							//$selection = mysql_query("SELECT date_post, id_p, id_post, id_user, message, title_post, who_is, immagine_profilo, Nome, Cognome FROM post_p, utenti WHERE id_p = '$proj_selected' AND id_post = '$idpost'  AND id_user = utenti.id ");
							//$ar_selection = array();
							//$jk = 0;
							//while($asc_selection = mysql_fetch_assoc($selection)){
								/*$asc_selection['date_post'] = timeAgo($asc_selection['date_post']);
								$ar_selection[$jk] = $asc_selection;
								$jk++;*/
							//}
							//$as['message'] = $sc['message'];
							//$as['message'] = $ar_selection;
						}
					}
					if($as['action'] == "comment_p"){
						$idcom = $as['id_com'];
						$sel = mysqli_query($connessione, "SELECT * FROM commenti_p, progetti WHERE id_com = '$idcom' AND id_proj = idproj");
						$n_s_com = mysqli_num_rows($sel);
						if($n_s_com > 0){
							$as_comment = mysqli_fetch_assoc($sel);
							$as['comment'] = $as_comment['text'];
							$as['title_proj'] = $as_comment['title'];
							$as['img_proj'] = $as_comment['img'];
						}else{
							$as['comment'] = "Error_Comment_Not_Found";
							
						}
					}
					if($as['action'] == "accept_w_h"){
						$id_target = $as['id_user_actor'];
						$id_target = $as['id_user_target'];
						$sel = mysqli_query($connessione, "SELECT Nome, Cognome, immagine_profilo FROM utenti WHERE id = '$id_target'");
						$sc = mysqli_fetch_assoc($sel);
						$as['Nome_accepted'] =  $sc['Nome'];
						$as['Cognome_accepted'] =  $sc['Cognome'];
						$as['immagine_profilo_accepted'] =  $sc['immagine_profilo'];
								
					}
					/*
					if($as['action'] == "want_help"){
						$wh = $as['id_wh'];
						
						$sel = mysql_query("SELECT * FROM want_help WHERE id_wh = '$wh'");
						$n_wh = mysql_num_rows($sel);
						if($n_wh > 0){
							$as_wh = mysql_fetch_assoc($sel);
							$as['view'] = $as_wh['view'];
							if($as_wh['view'] != 1){
								//$as['view'] = "Non accettabile";
								$as = null;
							}	
						}else{
							$as['view'] = "Error_View_Not_Found";
							
						}
					}*/
					
					
					
					$array_newsfeed[$x] = $as;
					$x++;
				}
			}
			//$arr_n_f['query'] = $query;
		}else{
			$arr_n_f = array();
		}
		
		/*$arr_n_f['query'] = $query;	
		$arr_n_f['arr_i_help'] = $arr_i_help;
		$arr_n_f['arr_proj_fll'] = $arr_proj_fll;
		$arr_n_f['arr_proj'] = $arr_proj[0];
		$arr_n_f['arr_fll'] = $arr_fll;*/
		$array['idact'] = $arr_n_f;
		//$array['q_act'] = $query_init;
		$array['newsfeed'] = $array_newsfeed;
		$array['n_notizie'] = $n_nf;
		$result = json_encode($array);
		echo "$result";
		
		
	
	}else if($act == "u_same_city"){ //?action=u_same_city&id=ID_USER OR ?action=u_same_city&id=ID_USER&limit=true 
			$id = mysqli_real_escape_string($connessione, $_GET['id']);
			$sel = mysqli_query($connessione, "SELECT citta FROM utenti WHERE id = '$id'");
			
			$n = mysqli_num_rows($sel);
			if($n > 0){
				$as = mysqli_fetch_assoc($sel);
				$citta = $as['citta'];
				if($citta != "" && $citta != " "){
					if(isset($_GET['limit'])){
						$sel_o = mysqli_query($connessione, "SELECT id, Nome, Cognome,immagine_profilo FROM utenti WHERE citta = '$citta' AND id != '$id' LIMIT 2");
					}else{
						$sel_o = mysqli_query($connessione, "SELECT id, Nome, Cognome,immagine_profilo, professione FROM utenti WHERE citta = '$citta' AND id != '$id'");
					}
					$n = mysqli_num_rows($sel_o);
					if($n > 0){
						$ar = array();
						$x = 0;
						while($asc = mysqli_fetch_assoc($sel_o)){
							$ar[$x] = $asc;
							$x++;
						
						}
						$arra =  array("citta"=>$citta, "users"=>$ar);
					}else{
					
						$arra = null;
					}
					
				}else{
					$arra = null;
				}
				
			}else{
				$arra = null;
			}
			
			$result = json_encode($arra);
			echo "$result";
	
	}else if($act == "u_same_interest"){ //?action=u_same_interest&id=IDUSER
		$id = mysqli_real_escape_string($connessione, $_GET['id']);
		$sel = mysqli_query($connessione, "SELECT * FROM interessi WHERE id_user = '$id'");
		$ass = mysqli_fetch_assoc($sel);
		$interest = json_decode($ass['interessi']);
		$n_interest = count($interest);
		
		if($n_interest > 0){
			$ok = false;
			for($k = 0; $k < $n_interest; $k++){
				$random = rand(0, $n_interest - 1);
				$int_rand = $interest[$random];
				$int_rand_like = "\"".$int_rand."\"";
				$sel_o = mysqli_query($connessione, "SELECT id_user, Nome, Cognome, immagine_profilo FROM interessi, utenti WHERE id = id_user AND id_user != $id AND interessi LIKE '%$int_rand_like%' LIMIT 4");
				$n = mysqli_num_rows($sel_o);
				if($n > 0){
					$ok = true;
					break;
				}
			}
			if($ok == true){
				$ar = array();
				$x = 0;
				while($as = mysqli_fetch_assoc($sel_o)){
					$ar[$x] = $as;
					$x++;
				}
				$array = array("int_rand" => $int_rand, "users"=>$ar);
			}else{
				$array = null;
			}
			
		}else{
			$array = null;
		}
		$result = json_encode($array);
		echo "$result";
		
	
	
	
	}else if($act == "comment_proj"){ //?action=comment_proj&id_proj=ID 
		$proj = mysqli_real_escape_string($connessione, $_GET['id_proj']);
		$limit = "";
		$by = "";
		if(isset($_GET['limit'])){
			$limit = mysqli_real_escape_string($connessione, $_GET['limit']);
		}
		if(isset($_GET['by'])){
			$by = mysqli_real_escape_string($connessione, $_GET['by']);
		}
		if($by == "" && $limit == ""){
			$limit = "LIMIT 8";
		}else{
			if($by ==""){
				$by = 0;
			}
			$limit = "LIMIT ".$by.", ".$limit;
		}
		$sel = mysqli_query($connessione, "SELECT Nome, Cognome, immagine_profilo, date_com, id_com, id_user, id_proj, text FROM commenti_p, utenti WHERE id_proj = '$proj' AND utenti.id = id_user ORDER BY date_com DESC ".$limit);
		$n = mysqli_num_rows($sel);
		
		$array = Array();
		$arr = Array();
		if($n > 0){
			$x = 0;
			$sel_f = mysqli_query($connessione, "SELECT iduser FROM progetti WHERE idproj = '$proj'");
			$n_f = mysqli_num_rows($sel_f);
			if($n_f > 0){
				$asc_f = mysqli_fetch_assoc($sel_f);
				$id_f = $asc_f['iduser'];   //ID FONDATORE
				$sel_col = mysqli_query($connessione, "SELECT id_mit FROM want_help WHERE id_proj = '$proj' AND view = 1");
				$n_col = mysqli_num_rows($sel_col);
				$array_col = Array();  //ID COLLABORATORI
				if($n_col > 0){
					$z = 0;
					while($asc_col = mysqli_fetch_assoc($sel_col)){
						$array_col[$z] = $asc_col['id_mit'];
						$z++;
					}
				}else{
					$array_col = null;
				}
				while($asc = mysqli_fetch_assoc($sel)){   //assoc sui commenti
					$id_commentator = $asc['id_user'];
					$array['result'] = "ok";
					if($id_commentator == $id_f){
						$role = "founder";
					}else{
						if($array_col != null ){
							$in_arr = in_array($id_commentator, $array_col);
							if($in_arr){
								$role = "coll";
							}else{
								$role = "normal";
							}
						}else{
							$role = "normal";
						}
					}
					$asc['role'] = $role;
					$arr[$x] = $asc;
					$x++;
				}
			}else{
				$array['result'] = "No project found";
			}
		}else{
			$array['result'] = "No comment found";
		}
		$array['comment'] = $arr;

		$result = json_encode($array);
		echo "$result";
	}else if($act == "share_on_nf"){ //?action=share_on_nf&idp=ID
		$id_u_con = $_SESSION['id'];
		$ok_get = false;
		if(isset($_GET['idp'])){
			$ok_get = true;
			$nf_act = "share_p";
			$target = "id_proj";
			$id_share = mysqli_real_escape_string($connessione, $_GET['idp']);
			$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = $id_share ORDER BY date DESC LIMIT 1");
			$img_str = "img";
		}else if(isset($_GET['idu'])){
			$ok_get = true;
			$nf_act = "share_u";
			$target = "id_user_target";
			$id_share = mysqli_real_escape_string($connessione, $_GET['idu']);
			$img_str = "immagine_profilo";
			$sel = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = $id_share LIMIT 1");
		}
		$array = Array();
		if($ok_get){
			$n = mysqli_num_rows($sel);
			
			if($n > 0){
				$asc = mysqli_fetch_assoc($sel);
				
				$date = date('Y-m-d H:i:s');

				$q = mysqli_query($connessione, "INSERT INTO news_feed(action, id_user_actor, ".$target.", date_action) VALUES('$nf_act', '$id_u_con', '$id_share', '$date')");
				if($q){
					$array['status'] = "ok";
					
					$array['image'] = $asc[$img_str];
				}else{
					$array['status'] = "error share";
				}
			}else{
				$array['status'] = "proj not found";
			}
		}
		$result = json_encode($array);
		echo "$result";
	
	}else if($act == "all_proj_user"){ //?action=all_proj_user&id=ID
		$id = mysqli_real_escape_string($connessione, $_GET['id']);
		$sel_wh = mysqli_query($connessione, "SELECT title, idproj FROM want_help, progetti WHERE id_mit = '$id' AND view = 1 AND id_proj = idproj");
		$n_wh = mysqli_num_rows($sel_wh);
		$ar_all = Array();
		$x = 0;
		if($n_wh > 0){
			while($as_wh = mysqli_fetch_assoc($sel_wh)){
				$sel_view = mysqli_query($connessione, "SELECT date_view FROM view_notif WHERE id_user = '$id'");
				$n_view_ntf = mysqli_num_rows($sel_view);
				$str_v_ntf = "";
				if($n_view_ntf > 0){
					$asc_view_ntf = mysqli_fetch_assoc($sel_view);
					$date_view_ntf = $asc_view_ntf['date_view'];
					$str_v_ntf = "AND date_action > '$date_view_ntf' ";
				
				}
				$idproj = $as_wh['idproj'];
				$qstr = "SELECT * FROM news_feed WHERE id_user_actor != '$id' AND id_proj = '$idproj' ".$str_v_ntf."ORDER BY date_action DESC";
				$sel = mysqli_query($connessione, $qstr);
				$n = mysqli_num_rows($sel);
				$as_wh['n_notif'] = $n;
				$ar_all[$x] = $as_wh;
				$x++;
			}
		}
		$sel_pj = mysqli_query($connessione, "SELECT title, idproj FROM progetti WHERE iduser = '$id'");
		$n_pj = mysqli_num_rows($sel_pj);
		if($n_pj > 0){
			while($as_pj = mysqli_fetch_assoc($sel_pj)){
				$sel_view = mysqli_query($connessione, "SELECT date_view FROM view_notif WHERE id_user = '$id'");
				$n_view_ntf = mysqli_num_rows($sel_view);
				$str_v_ntf = "";
				if($n_view_ntf > 0){
					$asc_view_ntf = mysqli_fetch_assoc($sel_view);
					$date_view_ntf = $asc_view_ntf['date_view'];
					$str_v_ntf = "AND date_action > '$date_view_ntf' ";
				
				}
				$idproj = $as_pj['idproj'];
				$qstr = "SELECT * FROM news_feed WHERE id_user_actor != '$id' AND id_proj = '$idproj' ".$str_v_ntf."ORDER BY date_action DESC";
				$sel = mysqli_query($connessione, $qstr);
				$n = mysqli_num_rows($sel);
				$as_pj['n_notif'] = $n;
				$ar_all[$x] = $as_pj;
				
				$x++;
			}
		}
		$result = json_encode($ar_all);
		echo "$result";
		
	
	}else if($act == "tag_p"){ //action=tag_p&idproj='+id_proj   OR   action=tag_p&do=ADD&tag=TAG&idproj='+id_proj action=tag_p&do=DEL&tag=TAG&idproj='+id_proj
		$idproj = mysqli_real_escape_string($connessione, $_GET['idproj']);
		
		if(isset($_GET['do'])){
			$do = mysqli_real_escape_string($connessione, $_GET['do']);
			$get_tag = mysqli_real_escape_string($connessione, $_GET['tag']);
			$sel = mysqli_query($connessione, "SELECT tag FROM progetti WHERE idproj = '$idproj'");
			if($do == "ADD"){
				$n = mysqli_num_rows($sel);
				
					$asc = mysqli_fetch_assoc($sel);
					$as_tag = json_decode($asc['tag']);
					$n_i = count($as_tag);
					if(strpos($get_tag, ",")){
						$get_tag = explode(",",$get_tag);
						$n_ex = count($get_tag);
						for($z = 0; $z < $n_ex; $z++){
							$as_tag[$n_i] = $get_tag[$z];
							$n_i++;
						}
					}else{
						$as_tag[$n_i] = $get_tag;
					}
					$as_tag = array_unique($as_tag);
					$as_tag = array_values($as_tag);
					$j = json_encode($as_tag);
					
					$i = mysqli_query($connessione, "UPDATE progetti set tag = '$j' WHERE idproj = '$idproj'");
				
			}else if($do == "DEL"){
					$asc = mysqli_fetch_assoc($sel);
					$as_tag = json_decode($asc['tag']);
					$n_i = count($as_tag);
					$ary = array();
					$y = 0;
					for($x=0; $x < $n_i; $x++){
						if($as_tag[$x] != $get_tag){
							$ary[$y] = $as_tag[$x];
							$y++;
						}
					}
					
					$j = json_encode($ary);
					$i = mysqli_query($connessione, "UPDATE progetti set tag = '$j' WHERE idproj = '$idproj'");
			}
		}
		$sel = mysqli_query($connessione, "SELECT tag FROM progetti WHERE idproj = '$idproj'");
		$ass = mysqli_fetch_assoc($sel);
		$as_tag = json_decode($ass['tag']);
		$ass['tag'] = $as_tag;
		
		$result = json_encode($ass);
		echo "$result";
	
	}else if($act == "connection_u"){ //$action=connection_u&user=ID        tutte le mie connessioni, miei collab e collab a proj a cui collaboro
		$us = mysqli_real_escape_string($connessione, $_GET['user']);
		$ar = Array();
		$id_arr = Array();
		//seleziona collaboratori progetti a cui collaboro
		$sel_proj_i_help = mysqli_query($connessione, "SELECT id_proj FROM want_help WHERE id_mit = '$us' AND view = 1");
		$n = mysqli_num_rows($sel_proj_i_help);
		$x = 0;
		if($n > 0){
			$ok = true;
			
			while($as_i_h = mysqli_fetch_assoc($sel_proj_i_help)){  //Progetti a cui collaboro
				$pj = $as_i_h['id_proj'];
				
				$sel_p = mysqli_query($connessione, "SELECT id_mit FROM want_help WHERE id_proj = '$pj' AND id_mit != '$us' AND view = 1");
				while($as = mysqli_fetch_assoc($sel_p)){
					$id_arr[$x] = $as['id_mit'];
					$x++;
				}
			}
		}else{
			$ok = false;
		}
		$sel_my_pj = mysqli_query($connessione, "SELECT id_mit FROM want_help WHERE id_ric = '$us' AND view = 1");
		$n = mysqli_num_rows($sel_my_pj);
		if($n > 0){
			$ok2 = true;
			while($as_my_p = mysqli_fetch_assoc($sel_my_pj)){  //miei progetti
				$id_arr[$x] = $as_my_p['id_mit'];
				$x++;
			}
		}else{
			$ok2 = false;
		}
		if($ok == false && $ok2 == false){
			$ar = [];
		}else{
			$id_arr = array_unique($id_arr);
			$id_arr = array_values($id_arr);
			$str = "id = ".$id_arr[0];
			for($y = 1; $y < count($id_arr); $y++){
				$str = $str." OR id = ".$id_arr[$y];
			}
			$query = "SELECT Nome, Cognome, id, immagine_profilo FROM utenti WHERE ".$str;
			$sel_info = mysqli_query($connessione, $query);
			$x = 0;
			while($as_info = mysqli_fetch_assoc($sel_info)){
				$ar[$x] = $as_info;
				$x++;
			}
		}
		$result = json_encode($ar);
		echo "$result";
			
			
			
			
		
	}else if($act == "timeline_profile"){ //?action=timeline_profile&user=ID
		if(isset($_GET['user'])){

			$id = mysqli_real_escape_string($connessione, $_GET['user']);
			//SEL want_help
			$sel_w = mysqli_query($connessione, "SELECT id_wh FROM want_help WHERE id_mit = '$id' AND view = 1");
			$n_wh = mysqli_num_rows($sel_w);
			$str = "";
			if($n_wh > 0){
				while($asc = mysqli_fetch_assoc($sel_w)){
					if($str == ""){
						$str = "id_wh = ".$asc['id_wh'];
					}else{
						$str = $str." OR id_wh = ".$asc['id_wh'];
					}
				}
			}
			if($str != ""){
				$strq = "OR (id_user_actor = '$id' AND (".$str."))";
			}else{
				$strq = "";
			}
			$q = "SELECT * FROM news_feed WHERE (id_user_actor = '$id' AND action != 'want_help' AND action != 'message_p') ".$strq." ORDER BY date_action DESC";
			$sel_n = mysqli_query($connessione, $q);
			$n = mysqli_num_rows($sel_n);
			$arr_n_f = array();
		//	$arr_n_f['query'] = $q;
			if($n > 0){
				$x = 0;
				
				while($as = mysqli_fetch_assoc($sel_n)){
					//$array_feed['detail'] = $as;
					if($as['action'] == "follow_p" || $as['action'] == "post" || $as['action'] == "create" || $as['action'] == "complete" || $as['action'] == "share_p" || $as['action'] == "want_help"){
						$id_proj = $as['id_proj'];
						$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = '$id_proj'");
						$sc = mysqli_fetch_assoc($sel);
						$as['title'] = $sc['title'];
						$descr_p = $sc['descr'];
						if(strlen($descr_p) > 503){
							$descr_p = substr($descr_p, 0, 500);
							$descr_p = $descr_p."...";
						}
						$as['descr'] = $descr_p;
						$as['img_proj'] = $sc['img'];
						$as['cate'] = $sc['categoria'];
					}
					if($as['action'] == "follow_u" || $as['action'] == "share_u"){
						$id_target = $as['id_user_target'];
						$sel = mysqli_query($connessione, "SELECT Nome, Cognome, immagine_profilo, professione, citta, frase_personale, Data_Nascita FROM utenti WHERE id = '$id_target'");
						$sc = mysqli_fetch_assoc($sel);
						$as['Nome_followed'] = $sc['Nome'];
						$as['Cognome_followed'] = $sc['Cognome'];
						$as['immagine_profilo_followed'] = $sc['immagine_profilo'];
						$as['professione'] = $sc['professione'];
						$as['data_nascita'] = $sc['Data_Nascita'];
						$as['citta'] = $sc['citta'];
						$as['frase_personale'] = $sc['frase_personale'];
							
					}
					if($as['action'] == "complete"){
						$selection = mysqli_query($connessione, "SELECT date_post, id_p, id_post, title_post, id_user, message, who_is, immagine_profilo, Nome, Cognome FROM post_p, utenti WHERE id_p = '$id_proj' AND id_user = utenti.id ORDER BY date_post desc LIMIT 3");
						$ar_selection = array();
						$k_l_p = 0;
						while($sel_l_p = mysqli_fetch_assoc($selection)){
							$sel_l_p['date_post'] = timeAgo($sel_l_p['date_post']);
							$ar_selection[$k_l_p] = $sel_l_p;
							$k_l_p++;
						}
						$as['last_post'] = $ar_selection;
					}
					if($as['action'] == "post"){
						$idpost = $as['id_post'];
						$sel = mysqli_query($connessione, "SELECT * FROM post_p WHERE id_post = '$idpost'");
						$sc = mysqli_fetch_assoc($sel);
						$proj_selected = $sc['id_p'];
						$selection = mysqli_query($connessione, "SELECT date_post, id_p, id_post, id_user, message, title_post, who_is, immagine_profilo, Nome, Cognome FROM post_p, utenti WHERE id_p = '$proj_selected' AND id_post <= '$idpost'  AND id_user = utenti.id ORDER BY date_post desc LIMIT 10");
						$ar_selection = array();
						$jk = 0;
						while($asc_selection = mysqli_fetch_assoc($selection)){
							$asc_selection['date_post'] = timeAgo($asc_selection['date_post']);
							$ar_selection[$jk] = $asc_selection;
							$jk++;
						}
						//$as['message'] = $sc['message'];
						$as['message'] = $ar_selection;
					}
					if($as['action'] == "comment_p"){
						$idcom = $as['id_com'];
						$sel = mysqli_query($connessione, "SELECT * FROM commenti_p, progetti WHERE id_com = '$idcom' AND id_proj = idproj");
						$n_s_com = mysqli_num_rows($sel);
						if($n_s_com > 0){
							$as_comment = mysqli_fetch_assoc($sel);
							$as['comment'] = $as_comment['text'];
							$as['title_proj'] = $as_comment['title'];
							$as['img_proj'] = $as_comment['img'];
						}else{
							$as['comment'] = "Error_Comment_Not_Found";
							
						}
					}
					if($as['action'] == "want_help"){
						$wh = $as['id_wh'];
						
						$sel = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_wh = '$wh'");
						$n_wh = mysqli_num_rows($sel);
						if($n_wh > 0){
							$as_wh = mysqli_fetch_assoc($sel);
							$as['view'] = $as_wh['view'];
							if($as_wh['view'] != 1){
								//$as['view'] = "Non accettabile";
								$as = null;
							}	
						}else{
							$as['view'] = "Error_View_Not_Found";
							
						}
					}
					
					$arr_n_f[$x] = $as;
					$x++;
				
			}
			
		}
		
		
		
		$result = json_encode($arr_n_f);
		echo "$result";
		
		
		}
	}else if($act == "get_last_pj_for_home"){//?action=get_last_pj_for_home
		$idcon = $_SESSION['id'];
		$sel_w = mysqli_query($connessione, "SELECT id_proj FROM want_help WHERE id_mit = '$idcon' AND view = 1");
		$n_wh = mysqli_num_rows($sel_w);
		$str = "";
		$str_anti = "";
		if($n_wh > 0){
			while($asc = mysqli_fetch_assoc($sel_w)){
				
				$str = $str." OR idproj = ".$asc['id_proj'];
				$str_anti = $str_anti." AND idproj != ".$asc['id_proj'];
				
			}
		}
		$q = "SELECT * FROM progetti WHERE iduser = ".$idcon." ".$str." ORDER BY date DESC LIMIT 4";
		$sel = mysqli_query($connessione, $q);
		$ns = mysqli_num_rows($sel);
		$array = array();
		$ar_my_pj = array();
		if($ns > 0){
			$x = 0;
			while($asc = mysqli_fetch_assoc($sel)){
				$ar_my_pj[$x] = $asc;
				$x++;
			}
		}
		$array['my_project'] = $ar_my_pj;


		$q_general = "SELECT * FROM progetti WHERE iduser != ".$idcon." ".$str_anti." ORDER BY date DESC LIMIT 4";
		$sel_general = mysqli_query($connessione, $q_general);
		$n_general = mysqli_num_rows($sel_general);
		$ar_general = array();
		if($n_general > 0){
			$x = 0;
			while($asc_g = mysqli_fetch_assoc($sel_general)){
				$ar_general[$x] = $asc_g;
				$x++;
			}
		}
		$array['general_project'] = $ar_general;
		//$array['q1'] = $q;
		//$array['q2'] = $q_general;
		$json = json_encode($array);
		echo "$json";
		
	}else if($act == "msg_proj"){ //?action=msg_proj&proj=ID_PROJ
		$array = array();
		if(isset($_GET['proj'])){
			$proj = mysqli_real_escape_string($connessione, $_GET['proj']);
			$user_con = mysqli_real_escape_string($connessione, $_SESSION['id']);
			//controllo autorizzazione
			//isFounder?
			$isFounder = false;
			$isColl = false;
			$selCheck = mysqli_query($connessione, "SELECT iduser FROM progetti WHERE idproj = $proj");
			$n = mysqli_num_rows($selCheck);
			if($n > 0){
				$ascCheck = mysqli_fetch_assoc($selCheck);
				if($ascCheck['iduser'] == $user_con){
					$isFounder = true;
				}else{  //isColl
					$selC = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = $user_con AND id_proj = $proj ORDER BY date DESC LIMIT 1");
					$nC = mysqli_num_rows($selC);
					if($nC > 0){
						$ascC = mysqli_fetch_assoc($selC);
						if($ascC['view'] == 1){
							$isColl = true;
						}
					}
				}

				if($isFounder == true || $isColl == true){
					$selMsg = mysqli_query($connessione, "SELECT id_message, id_proj, id_user, message, date_message, immagine_profilo, Nome, Cognome FROM message_proj, utenti WHERE id_proj = $proj AND id_user = utenti.id ORDER BY date_message DESC LIMIT 10");
					$nMsg = mysqli_num_rows($selMsg);
					$arrayMsg = array();
					if($nMsg > 0){
						$x = 0;
						while($ascMsg = mysqli_fetch_assoc($selMsg)){
							$arrayMsg[$x] = $ascMsg;
							$x++;
						}
					}
					$array['message'] = $arrayMsg;
					$array['status'] = "ok";
				}else{
					$array['status'] = "not_allowed";
				}
			}else{	
				$array['status'] = "proj_not_found";
			}
		}else{
			$array['status'] = "not_get";
		}
		$json = json_encode($array);
		echo "$json";

	}
}else{
	$array = Array("status"=> "not_logged", "session" => $_SESSION['id']);
	$result = json_encode($array);
	echo "$result";
}	
	mysqli_close($connessione);
/*}else{
	echo "Accesso Negato";
}*/
?>