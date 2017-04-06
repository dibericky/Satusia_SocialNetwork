<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');	
$arr = Array();
if(isset($_SESSION['id'])){
	$arr['status'] = "logged";
	$src = mysqli_real_escape_string($connessione, $_GET['src']);
	//LIMIT = ELEMENTI MASSIMI    BY = DAL NUMERO X IN POI
	if(isset($_GET['fast'])){
		$limit = 3;
		$by = 0;
	}else{
		if(isset($_GET['limit'])){
			$limit = mysqli_real_escape_string($connessione, $_GET['limit']);
			
		}else{
			$limit = 50;
		}
		if(isset($_GET['by'])){
			$by = mysqli_real_escape_string($connessione, $_GET['by']);
		}else{
			$by = 0;
		}
	}
		
		if(isset($_GET['type'])){
			
			$type = mysqli_real_escape_string($connessione, $_GET['type']);
			$str_filter = "";
			if(isset($_GET['city'])){
				$filt_city = mysqli_real_escape_string($connessione, $_GET['city']);
				$str_filter = "citta = '".$filt_city."'";
			}
			if(isset($_GET['professione'])){
				if($str_filter != ""){
					$str_filter = $str_filter." AND ";
				}
				$filt_categ = mysqli_real_escape_string($connessione, $_GET['professione']);
				$str_filter = $str_filter."professione = '".$filt_categ."'";
			}
			if(isset($_GET['categ'])){
				if($str_filter != ""){
					$str_filter = $str_filter." AND ";
				}
				$filt_categ = mysqli_real_escape_string($connessione, $_GET['categ']);
				$cat = "";
				switch($filt_categ){
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
				$str_filter = $str_filter."categoria = '".$cat."'";
			}		
					
			
			if($type == "user"){
				
					if(strlen($src) < 4){
						if($str_filter != ""){
							$str_filter = "AND ".$str_filter;
						}
						$qry = "SELECT * FROM (SELECT *, (IF(Nome LIKE '$src', 5, IF(Nome LIKE '$src%',3, IF(Nome LIKE '%$src',2, IF(Nome LIKE '% $src %',1,IF(Nome LIKE '%$src%',0.5,0))))) + IF(Cognome LIKE '$src', 5, IF(Cognome LIKE '$src%',3, IF(Cognome LIKE '%$src',2, IF(Cognome LIKE '% $src %',1,IF(Cognome LIKE '%$src%',0.5,0)))))) AS w FROM utenti) as q WHERE w > 0 ".$str_filter." ORDER BY w DESC LIMIT ".$by.",".$limit;
						$sel_us = mysqli_query($connessione, $qry);
						
					}else{
						if($str_filter != ""){
							$str_filter = "WHERE ".$str_filter;
						}
						//$sel_u = mysql_query("SELECT Nome, Cognome, immagine_profilo, id FROM utenti WHERE Nome LIKE '$src%' OR Cognome LIKE '$src%'");
						$qry = "SELECT *, MATCH(Nome) AGAINST('$src*' in BOOLEAN MODE) AS pert, IF(Nome LIKE '$src',5,0) AS p2,  MATCH(Cognome) AGAINST('$src*' in BOOLEAN MODE) AS t1, IF(Cognome LIKE '$src',5,0) AS t2 FROM utenti ".$str_filter."HAVING (pert + p2 + t1 + t2) > 0.001 ORDER BY pert + p2 + t1 + t2 DESC LIMIT ".$by.",".$limit;
						$sel_us = mysqli_query($connessione, $qry);
					}
				
				$n_us = mysqli_num_rows($sel_us);
				if($n_us > 0){
					$x = 0;
					$arr_us = Array();
					$arr_user = Array();
					
					while($as_us = mysqli_fetch_assoc($sel_us)){
						$idu = $as_us['id'];
						$sel_n_coll = mysqli_query($connessione, "SELECT id_mit FROM want_help WHERE id_ric = '$idu' AND view = 1");
						$n_coll = mysqli_num_rows($sel_n_coll);
						$xk = 0;
						if($n_coll > 1){
							$arr_coll = Array();
							
							while($asc_n_coll = mysqli_fetch_assoc($sel_n_coll)){
								$arr_coll[$xk] = $asc_n_coll['id_mit'];
								$xk++;
							}

							
						}



						$sel_proj_i_help = mysqli_query($connessione, "SELECT id_proj FROM want_help WHERE id_mit = '$idu' AND view = 1");
						$n = mysqli_num_rows($sel_proj_i_help);
						
						if($n > 0){
							
							while($as_i_h = mysqli_fetch_assoc($sel_proj_i_help)){  //Progetti a cui collaboro
								$pj = $as_i_h['id_proj'];
								
								$sel_p = mysqli_query($connessione, "SELECT id_mit FROM want_help WHERE id_proj = '$pj' AND id_mit != '$idu' AND view = 1");
								while($as = mysqli_fetch_assoc($sel_p)){
									$arr_coll[$xk] = $as['id_mit'];
									$xk++;
								}
							}
						}

						$arr_coll = array_unique($arr_coll);
						$arr_coll = array_values($arr_coll);
						$arr_us['collabs'] = $arr_coll;
						$n_coll = count($arr_coll);
						$arr_us['n_coll'] = $n_coll;
						$sel_n_foll = mysqli_query($connessione, "SELECT follower FROM follow WHERE followed = '$idu'");
						$n_foll = mysqli_num_rows($sel_n_foll);
						$arr_us['n_foll'] = $n_foll;
						if(strlen($src) < 4){
							$arr_us['pertinence'] = $as_us['w'];
						}else{
							$arr_us['pertinence'] = $as_us['pert'] + $as_us['p2'] + $as_us['t1'] + $as_us['t2'];
						}
						$arr_us['Nome'] = $as_us['Nome'];
						$arr_us['Cognome'] = $as_us['Cognome'];
						$arr_us['id'] = $idu;
						$arr_us['professione'] = $as_us['professione'];
						$arr_us['citta'] = $as_us['citta'];
						$arr_us['immagine_profilo'] = $as_us['immagine_profilo'];
						$arr_user[$x] = $arr_us;
						$x++;
					}
					$arr['user'] = $arr_user;
					
				}else{
					$arr['user'] = null;
				}
			
			}else if($type == "proj"){
			
				if(strlen($src) < 4){
					if($str_filter != ""){
						$str_filter = "AND ".$str_filter;
					}
					//$qry = "SELECT * FROM (SELECT *, (IF(title LIKE '$src', 5, IF(title LIKE '$src%',3, IF(title LIKE '%$src',2, IF(title LIKE '% $src %',1,IF(title LIKE '%$src%',0.5,0))))) + IF(tag LIKE '%\"$src\\\"%', 3, IF(tag LIKE '%\"$src%',2,IF(tag LIKE '%$src%',0.5,0)))) AS w FROM progetti) as q WHERE w > 0 ORDER BY w DESC LIMIT ".$by.",".$limit;
					$qry = "SELECT * FROM (SELECT *, (IF(title LIKE '$src', 5, IF(title LIKE '$src%',3, IF(title LIKE '%$src',2, IF(title LIKE '% $src %',1,IF(title LIKE '%$src%',0.5,0))))) + IF(categoria LIKE '%\"$src\\\"%', 3, IF(categoria LIKE '%\"$src%',2,IF(categoria LIKE '%$src%',0.5,0)))) AS w FROM progetti) as q WHERE w > 0  ".$str_filter." ORDER BY w DESC LIMIT ".$by.",".$limit;
					
					$sel_pj = mysqli_query($connessione, $qry);
					
				}else{
					if($str_filter != ""){
						$str_filter = "WHERE ".$str_filter;
					}
					//$sel_u = mysql_query("SELECT Nome, Cognome, immagine_profilo, id FROM utenti WHERE Nome LIKE '$src%' OR Cognome LIKE '$src%'");
					//$qry = "SELECT *, MATCH(title) AGAINST('$src*' in BOOLEAN MODE) AS pert, IF(title LIKE '$src%',2,0) AS p2, IF(categoria LIKE '$src',1.5,IF(categoria LIKE '$src%',0.5,0)) AS cat, MATCH(tag) AGAINST('$src*' in BOOLEAN MODE) AS t1, MATCH(tag) AGAINST('$src' in BOOLEAN MODE) AS t2 FROM progetti HAVING (pert + p2 + t1 + t2 + cat) > 0.001 ORDER BY pert + p2 + t1 + t2 + cat DESC LIMIT ".$by.",".$limit;
					$qry = "SELECT *, MATCH(title) AGAINST('$src*' in BOOLEAN MODE) AS pert, IF(title LIKE '$src%',2,0) AS p2, IF(categoria LIKE '$src',1.5,IF(categoria LIKE '$src%',0.5,0)) AS cat FROM progetti ".$str_filter."HAVING (pert + p2 + cat) > 0.001 ORDER BY pert + p2 + cat DESC LIMIT ".$by.",".$limit;
					$sel_pj = mysqli_query($connessione, $qry);
				}
				$n_pj = mysqli_num_rows($sel_pj);
				if($n_pj > 0){
					$x = 0;
					$arr_pj = Array();
					$arr_proj = Array();
					
					while($as_pj = mysqli_fetch_assoc($sel_pj)){
						$idpj = $as_pj['idproj'];
						$sel_n_coll = mysqli_query($connessione, "SELECT id_proj FROM want_help WHERE id_proj = '$idpj' AND view = 1");
						$n_coll = mysqli_num_rows($sel_n_coll);
						$arr_pj['n_coll'] = $n_coll;
						$sel_n_foll = mysqli_query($connessione, "SELECT user FROM follow_p WHERE proj = '$idpj'");
						$n_foll = mysqli_num_rows($sel_n_foll);
						$arr_pj['n_foll'] = $n_foll;
						if(strlen($src) < 4){
							$arr_pj['pertinence'] = $as_pj['w'];
						}else{
							$arr_pj['pertinence'] = $as_pj['pert'] + $as_pj['p2'] + $as_pj['t1'] + $as_pj['t2'] + $as_pj['cat'];
						}
						$arr_pj['title'] = $as_pj['title'];
						$arr_pj['categoria'] = $as_pj['categoria'];
						$arr_pj['tag'] = $as_pj['tag'];
						$arr_pj['img'] = $as_pj['img'];
						$arr_pj['idproj'] = $idpj;
						$arr_proj[$x] = $arr_pj;
						$x++;
					}
					$arr['proj'] = $arr_proj;
					
				}else{
					$arr['proj'] = null;
				}
			}
		}
	//$arr['query'] = $qry;
}else{
	$arr['status'] = "not_logged";

}
mysqli_close($connessione);
$result = json_encode($arr);
echo "$result";
?>

