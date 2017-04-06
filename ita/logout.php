<?php
session_start();

if(isset($_SESSION['id'])){
	$_SESSION = array();
	
}

if(isset($_COOKIE['usr'])){
	include 'connect.php';
	
	$id_cookie = mysqli_real_escape_string($connessione,$_COOKIE['usr']);
	$tkn_cookie = mysqli_real_escape_string($connessione,$_COOKIE['tkn']);
	$del = mysqli_query($connessione,"DELETE FROM remember WHERE id = $id_cookie AND string_rem = '$tkn_cookie'");
	setcookie("usr", null);
	setcookie("tkn", null);
	mysqli_close($connessione);
}
session_destroy();
header("location: index.php");

?>