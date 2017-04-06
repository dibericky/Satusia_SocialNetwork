<?php
function timeAgo($time_ago){
	$cur_time 	= time();
	$time_ago = strtotime($time_ago);
	$time_elapsed 	= $cur_time - $time_ago;

	$seconds 	= $time_elapsed ;
	$minutes 	= round($time_elapsed / 60 );
	$hours 		= round($time_elapsed / 3600);
	$days 		= round($time_elapsed / 86400 );
	$weeks 		= round($time_elapsed / 604800);
	$months 	= round($time_elapsed / 2600640 );
	$years 		= round($time_elapsed / 31207680 );

	// Seconds
	if($seconds <= 60){
		return "$seconds secondi fa";
	}
	//Minutes
	else if($minutes <=60){
		if($minutes==1){
			return "un minuto fa";
		}
		else{
			return "$minutes minuti fa";
		}
	}
	//Hours
	else if($hours <=24){
		if($hours==1){
			return "un'ora fa";
		}else{
			return "$hours ore fa";
		}
	}
	//MIA MODIFICA
	else if($days == 1){
		return "Ieri";
	
	}else{
		$data_form = date("d-M-Y",$time_ago);
		$data_form = str_replace("-"," ",$data_form);
		$mesi_en = Array('Jan' , 'Feb' , 'Mar' , 'Apr' , 'May' , 'Jun' , 'Jul' , 'Aug' , 'Sep' , 'Oct' , 'Nov' , 'Dec');
		$mesi_it = Array('Gennaio' , 'Febbraio' , 'Marzo' , 'Aprile' , 'Maggio' , 'Giugno' , 'Luglio' , 'Agosto' , 'Settembre' , 'Ottobre' , 'Novembre' , 'Dicembre');
		for($y = 0; $y < 12; $y++){
			if(strpos($data_form, $mesi_en[$y])){
				$data_form = str_replace($mesi_en[$y], $mesi_it[$y], $data_form);
				break;
			}
		}
		if($data_form[0] == "0"){
			$data_form[0] = " ";
		}
		return $data_form;
	}
	//Days
/*	else if($days <= 7){
		if($days==1){
			return "yesterday";
		}else{
			return "$days days ago";
		}
	}
	//Weeks
	else if($weeks <= 4.3){
		if($weeks==1){
			return "a week ago";
		}else{
			return "$weeks weeks ago";
		}
	}
	//Months
	else if($months <=12){
		if($months==1){
			return "a month ago";
		}else{
			return "$months months ago";
		}
	}
	//Years
	else{
		if($years==1){
			return "one year ago";
		}else{
			return "$years years ago";
		}
	}*/
}

?>
