<?php
header('Content-Type: application/json');	
session_start();
$resp = array();
if(isset($_SESSION['id'])){
	$resp['status'] = "id_ok";
	/*$output_dir = "uploads/";
	if(isset($_FILES["upload_file"]))
	{
		
		
	
		$error =$_FILES["upload_file"]["error"];
		//You need to handle  both cases
		//If Any browser does not support serializing of multiple files using FormData() 
		if(!is_array($_FILES["upload_file"]["name"])) //single file
		{
	 	 //	$fileName = $_FILES["myfile"]["name"];
			$fileName = "img"+$_SESSION['id'];
	 		move_uploaded_file($_FILES["upload_file"]["tmp_name"],$output_dir.$fileName);
	    	$ret[]= $fileName;
		}
		else  //Multiple files, file[]
		{
		  $fileCount = count($_FILES["upload_file"]["name"]);
		  for($i=0; $i < $fileCount; $i++)
		  {
		  	$fileName = $_FILES["upload_file"]["name"][$i];
			move_uploaded_file($_FILES["upload_file"]["tmp_name"][$i],$output_dir.$fileName);
		  	$ret[]= $fileName;
		  }
		
		}
	   
	 }*/
	 if (isset($_FILES['upload_file']) && isset($_POST['idproj'])){
	 	
	 	$iduser = $_SESSION['id'];
		$idproj = $_POST['idproj'];
	 	include 'connect.php';
	 	//controllo se utente è creatore progetto
	 	$sel = mysqli_query($connessione, "SELECT * FROM progetti WHERE idproj = '$idproj' AND iduser = '$iduser'");
	 	$n = mysqli_num_rows($sel);
	 	if($n > 0){ //se è lui il founder
		 	$file = $_FILES['upload_file'];
		 	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		  	$name = "imgProj".$idproj.".".$ext;
		    if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "../uploads/" .$name)){
		        $resp['status'] =  $_FILES['upload_file']['name']. " OK";
		        $newName = "uploads/".$name;
		        $upImg = mysqli_query($connessione, "UPDATE progetti SET img = '$newName' WHERE idproj = '$idproj'");
		    } else {
		        $resp['status'] =  $_FILES['upload_file']['name']. " KO";
		    }
		    $resp['name'] = $name;
	    	
		}else{
			$resp['status'] = "no_founder";
		}
		mysqli_close($connessione);
	}else if(isset($_FILES['upload_file']) && isset($_GET['user'])){
		$iduser = $_SESSION['id'];
		include 'connect.php';
		$sel = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = $iduser");
		$n = mysqli_num_rows($sel);
		if($n > 0){
			$file = $_FILES['upload_file'];
		 	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		  	$name = "imgUser".$iduser.".".$ext;
		    if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "../uploads/" .$name)){
		        $resp['status'] =  $_FILES['upload_file']['name']. " OK";
		        $newName = "uploads/".$name;
		        $upImg = mysqli_query($connessione, "UPDATE utenti SET immagine_profilo = '$newName' WHERE id = '$iduser'");
		        if(!$upImg){
		        	$resp['status'] = "error_Up";
		        }
		    } else {
		        $resp['status'] =  $_FILES['upload_file']['name']. " KO";
		    }
		    $resp['name'] = $name;
		}else{
			$resp['status'] = "errore_critico";
		}
	}else {
	    $resp['status'] =  "No files uploaded ...";
	}


	mysqli_close($connessione);
}else{
	$resp['status'] = "false";
}

$j = json_encode($resp);
 echo "$j";
?>