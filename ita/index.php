<?php
session_start();
if(isset($_SESSION['id'])){
    header("location: /home.php");
}else if(isset($_COOKIE['usr'])){
 
  include 'connect.php';
  
  $id_cookie = mysqli_real_escape_string($connessione, $_COOKIE['usr']);
  $tkn_cookie = mysqli_real_escape_string($connessione, $_COOKIE['tkn']);
  $str_ck = "SELECT * FROM remember WHERE id = ".$id_cookie." AND string_rem = '".$tkn_cookie."'";
  $sel = mysqli_query($connessione, $str_ck);
  $n = mysqli_num_rows($sel);
  if($n > 0){
    $random = rand()*rand()*rand();
    $random = "qweeet".$random."bhbbi".$random;
    $str_remember = md5($random);
    $up = mysqli_query($connessione, "UPDATE remember SET string_rem = '$str_remember' WHERE id = $id_cookie");
    if($up){
      setcookie("usr", $id_cookie, time()+(3600 * 48));
      setcookie("tkn", $str_remember, time()+(3600 * 48));
      $_SESSION['id'] = $id_cookie; 
      $id = $id_cookie;
      $ip = $_SERVER['REMOTE_ADDR'];
      $q = mysqli_query($connessione, "UPDATE utenti SET ip_last_login = '$ip' WHERE id = '$id'");
      header("location: /home.php");
    }
  }else{
    //setcookie("usr", null);
    //setcookie("tkn", null);
  }
}else if(isset($_GET['key'])){
 
  $connessione = mysqli_connect('localhost','USER',"PASSWORD","DB") or die("Errore nella connessione al database");
  
}

?>

<html>
<head>
  <meta charset="UTF-8">
  <title>Satusia</title>
  <link rel="icon" href="icon/favicon.ico" />
  <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
  <link rel="stylesheet" href="css/style_general.css" />
  <link rel="stylesheet" href="css/footer.css" />
  <link rel="stylesheet" href="css/animate.css" />
  

<style type="text/css">

.sectionHome .main{
  height: 100%;
  position: relative;
}


td {
  padding: 3px;
}
tr {
  font-size: 13px;
  color: #fff;
}
input#email, input#password {
  height: 30px;
  width: 200;
  
  padding: 5px;
}
table {
  position: absolute;
  right: 0;
}
#logo{
  top: 5px!important;
}
#top_header{
    height: 70px!important;
}
.sectionHome {
  width: 100%;
  height: 600px;
  position: relative;
}

#regHome input, #regButton, #fb_btt_log {
  height: 50px;
  padding: 10px;
  font-size: 15px;
  width: 100%;
}
button#regButton {
  background-color: rgb(55, 190, 215);
  border: none;
  color: #fff;
  cursor: pointer;
  font-size: 18px;
}
#center_header{
  right: -110px;
}
#regHome table {
  width: 90%;
  bottom: 8;
  height: 300px;
  margin-right: 5%;
 
}
div#regHome {
  width: 50%;
  height: 100%;
  position: relative;
  float: right;
}
img#satusiaLogo {
  position: absolute;
  top: 50;
  z-index: 99;
  left: calc(50% - 175px);
}
div#leftHome {
  width: 45%;
  height: 100%;
  float: left;
  position: relative;
}
div#cit {
  position: absolute;
  bottom: -8px;
  color: #353535;
  z-index: 9999;
  transform: rotate(-3deg);
  padding: 10px;
  background: #fff;
  text-align: center;
  box-shadow: rgba(150, 150, 150, 0.298039) 0px 0px 2px 1px
}
h2 {
  margin: 0;
  line-height: 1.5;
  padding: 0;
}
button#logbtt {
  padding: 4px 10px;
  color: rgb(255, 255, 255);
  border: 2px solid;
  background-color: rgba(0, 0, 0, 0);
}
#infoCondizioni {
  color: rgb(127, 127, 127);
}
#titleReg {
  font-size: 23px;
  color: rgb(127, 127, 127);
  background-color: #fff;
  padding: 10;
}
#panelreg {
    height: 480px;
    width: 100%;
    position: absolute;
    background-color: rgb(219, 239, 243);
    top: 50px;
    border: 3px solid rgb(255, 255, 255);
    box-shadow: rgba(150, 150, 150, 0.298039) 0px 0px 2px 1px;
    border-radius: 10px;
}

#corda{
  width: 3px;
  height: 50px;
  background-color: #000;
  position: absolute;
  bottom: 160;
  left: calc(50% - 1px);
  box-shadow: 0px 0px 3px rgba(100,100,100,0.3);
}

.leftSide, .centerSide, .rightSide {
  display: inline-block;
  position: absolute;
  top: 0;
}
.leftSide {
  width: 35%;
  height: 100%;
  margin: 0;
  padding: 0;
  left: 0;
}
.centerSide {
  width: 30%;
  height: 100%;
  left: 35%;
}
#second{
  background-color: rgb(219, 239, 243);
}
img#bulb {
  width: 80%;
  margin-left: 10%;
  position: absolute;
  top: 150px;
  opacity: 0;
}
.rightSide {
  width: 35%;
  height: 100%;
  right: 0;
}
.rightSide img, .leftSide img {
  width: 90%;
  position: absolute;
  z-index: 999;
  opacity: 0;
}
.leftSide img {
  top: 50px;
}
.rightSide img{
  bottom: 50;
}

img.slogan {
  width: 110%!important;
  left: 55%;
  top: 50%!important;
  display: none;
}
.leftSide_2 img {
  position: absolute;
  top: 100;
}
#third img {
    width: 50%;
    position: absolute;
    top: 100;
    margin-left: 25%;
}
.leftSide_2 {
  width: 50%;
  height: 100%;
  position: absolute;
  left: 0;
}
.rightSide_2 {
  width: 50%;
  height: 100%;
  position: absolute;
  right: 0;
}

/*.personPictureNotif{
  background-image: url("https://slackiance.files.wordpress.com/2012/08/spideytobey3.jpeg");
  width: 50px;
  height: 50px;
  position: absolute;
  left: 5px;
  top: 8px;
  background-size: cover;
  border-radius: 100%;
}*/


/*.collabRequestText, .mittente{
  position: absolute;
  top: 7px;
  left: 70px;
  color: #3C4A55;
  font-size: 13px;
}*/
/*.messageText {
  position: absolute;
  top: 40px;
  left: 70px;
  color: rgb(127, 127, 127);
  font-size: 13px;
  padding: 5px;
  box-shadow: 0px 0px 1px rgba(180,180,180,0.5);
  background-color: rgb(242,246,247);
  width: 230px;
  max-height: 40px;
}*/


.ver_key {
    height: 100px !important;
}
#avviso_reg {
    height: 50px;
    line-height: 50px;
    position: absolute;
    bottom: 0;
    text-align: center;
    width: 100%;
    font-size: 18px;
    color: rgb(100,100,100);
}
#alertReg {
    position: absolute;
    width: 100%;
    height: 50px;
    color: rgb(100,100,100);
    bottom: 10;
    text-align: center;
    font-size: 18px;
    line-height: 50px;
}


.block_cookie {
    padding: 10px;
    border: 2px solid #37BED7;
    margin: 10px;
    border-radius: 10px;
    background-color: #DBEFF3;
    color: rgb(50,50,50);
    line-height: 23px;
    font-size: 14px;
}
#cookie_div {
    min-height: 780px;
    padding: 50px;
    padding-top: 150px;
    display: none;
}
.header_cookie_div {
    text-align: center;
}
.block_cookie a {
    text-decoration: none;
    color: rgb(50,50,50);
    font-style: italic;
}
span#mostra_cookie_info {
    font-weight: bold;
    cursor: pointer;
}
div#cookie_bar {
    display: none;
    font-size: 13px;
    padding: 5px;
    background-color: #1CADC7;
    min-width: 900px;
    color: #fff;
    position: relative;
    height: 40px;
    padding-right: 130px;
    padding-bottom: 0;
}
#close_cookie_bar {
    width: 100px;
    height: 20px;
    position: absolute;
    right: 10px;
    bottom: 10;
    background-color: #FDFFC4;
    text-align: center;
    color: #37BED7;
    padding: 5px;
    border-radius: 20px;
    line-height: 20px;
    cursor: pointer;
    font-weight: bold;
}
div#close_cookie_bar:hover {
    background-color: #F8FBB1;
}
div#nascondi_cookie_div {
    text-align: center;
    width: 80px;
    margin: 0 auto;
    border: 2px solid #37BED7;
    padding: 5px;
    color: #37b3d7;
    cursor: pointer;
    font-weight: bold;
    border-radius: 30px;
}
button#fb_btt_log {
    background: #5674B5;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 18px;
}
#line_log {
    height: 1px;
    width: 80%;
    background: rgba(191, 198, 199, 0.65);
    margin: 5px;
    margin-left: 10%;
}
.description_side {
    font-size: 18;
    color: rgb(100,100,100);
    padding: 10px;
    line-height: 30px;
}
.box_descr{
    height: 200px;
    /* border: 1px solid; */
    font-size: 23px;
    float: right;
    width: 100%;
    text-align: center;
    bottom: 0;
    position: absolute;
    opacity: 0;
    color: #37BED7;
}
#third img{
  opacity: 0;
}
.margin_cck{
  margin-top: 130px !important;
}
#infoCondizioni{
  cursor: pointer;
}
#fb_avviso{
  font-size: 12px;
  color: rgb(127, 127, 127);
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
		$(document).ready(function(){
      $(document).on("click","#mostra_cookie_info", function(){
        $("#cookie_div").slideDown();
      });
    $(document).on("click","#nascondi_cookie_div", function(){
        $("#cookie_div").slideUp();
        $("#container").removeClass("margin_cck");
      });
    $(document).on("click", "#close_cookie_bar", function(){
      $("#cookie_bar").slideUp();
      $("#cookie_div").slideUp();
      $("#container").removeClass("margin_cck");
      confirmCookie();
  });

if (document.cookie.indexOf("cnf_cksr") == -1) {
  $("#container").addClass("margin_cck");
  $("#cookie_bar").slideDown();
}

$("#formLog").submit(function( event ) {
 // console.log("Form log");
  confirmCookie();
  event.preventDefault();
});
 $(document).on("click", "#logBtt", function(){
        confirmCookie();
    
      //  console.log("CLICK")
        var email = $("#email").val();
        var password = $("#password").val();
        var isCheckRem = $("#remember_me").is(":checked");
        var rem = "";
        if(isCheckRem){
          rem = "?remember";
        }
        var querylogin = $.ajax({
              
              url: "api/login.php"+rem,
              type: "POST",
              data: "logmail="+email+ "&logpsswd="+password,
              dataType: "json"
              });
      querylogin.done(function(conferma)
              {
                if(conferma.status == true){
                 if(conferma.isFirst == "ok"){
                  location.href = 'https://satusia.com/settings.php';
                 }else{
                   location.href = 'https://satusia.com/home.php';
                 }
               
                }else{
                 alert("Login fallito, dati non presenti nel database");
                }
              });
      
    querylogin.fail(function(jqXHR, textStatus) {
           console.log("Login Fallito.");
        }); 
      });
 <?php
 if(!isset($_GET['key'])){
 ?> 
  $(document).on("click","#regButton", function(){
    confirmCookie();
    
    var email = $("#emailReg").val();
    var password = $("#password1Reg").val();
    var password2 = $("#password2Reg").val();
    var name = $("#name").val();
    var surname = $("#surname").val();
    if(password == password2 && email != "" && password != "" && name != "" && surname != "" && password.length > 4){
      $("#regButton").text("Attendi...");
      var reg = $.ajax({
            url: "api/register.php",
            type: "POST",
            data: "reg_nome="+name+"&reg_cogn="+surname+"&email="+email+"&reg_pass="+password,
            dataType: "json"
          });
      reg.done(function(conferma)
            {
            if(conferma.status == "ok"){
              $("#alertReg").text("Ti è stata invia un'email a: "+email+" , controlla anche tra le email indesiderate");
            }else if(conferma.status == "email_presente"){
              $("#alertReg").text("Ti sei già registrato con questa email");
            }else if(conferma.status == "request_presente"){
              $("#alertReg").text("Hai già richiesto l'iscrizione con questa email, controlla nella tua casella di posta");
            }else if(conferma.status == "passord_empty"){
              $("#alertReg").text("La password non può essere vuota");
            }else if(conferma.status == "invalid_email"){
              alert("Email invlida");
            }else{
              $("#alertReg").text("Errore inaspettato");
            }
            $("#regButton").text("Registrati");
          });
      reg.fail(function(jqXHR, textStatus) {
              $("#alertReg").text("Registrazione fallita, riprova più tardi");
              $("#regButton").text("Registrati");
          });
    }else{
      if(name == ""){
        $("#name").focus();

      }else if(surname == ""){
        $("#surname").focus();
      }else if(password == ""){
        $("#password1Reg").focus();
      }else if(password.length < 5){
        $("#alertReg").text("La password deve avere minimo 5 caratteri");
      }else if(password != password2){
        $("#password2Reg").focus();
        $("#alertReg").text("Le password non coincidono");
      }
    }
  });
  <?php
}else{
  $key = mysqli_real_escape_string($connessione, $_GET['key']);

  ?>
  function verificaReg(){
    var key = '<?php echo $key; ?>';
    if(key != ""){
      var ver = $.ajax({
              url: "api/register.php?key_temp="+key,
              dataType: "json"
            });
        ver.done(function(conferma)
              {
              if(conferma.status == "ok"){
                $("#avviso_reg").text("Registrazione effettuata! Ora puoi effettuare l'accesso");
              }else if(conferma.status == "key_not_found"){
                 $("#avviso_reg").text("Chiave di verifica non trovata");
              }else{
                $("#avviso_reg").text("Errore... Contatta l'assistenza:  help@satusia.com");
              }
            });
        ver.fail(function(jqXHR, textStatus) {
                   console.log("Registrazione Fallita.");
            });
    }else{
      $("#avviso_reg").text("Chiave di verifica non valida");
    }
  }
  verificaReg();
<?php
}
?>
			$('#arrow_left').on('click', function(){
				$("#container_show_projects ul").stop().animate({scrollLeft:'-=585'}, 500);
			});
		$('#arrow_right').on('click', function(){
			$("#container_show_projects ul").stop().animate({scrollLeft:'+=585'}, 500);
		});
		function selectedNavControl(){
			var selectedNav = $('.selectedNav');
			var swiper = $('#swiper');
			var selectedNav_left = $(selectedNav).position().left;
			var selectedNav_width = $(selectedNav).outerWidth();
			$(swiper).animate({left : selectedNav_left-7, width : selectedNav_width}, 500, function(){
				
			});
		}
		selectedNavControl();
		$(document).on("click", ".sub_header_item", function(){
			$(".sub_header_item").removeClass("selectedNav");
			$(this).addClass("selectedNav");
			selectedNavControl();
		});
		$(document).on("click", "#myPicture", function(){
			var subMenuHeader = $("#menu_profile_header");
			if(subMenuHeader.css("display") == "none"){
				subMenuHeader.slideDown( "fast", function(){});
			}else{
				subMenuHeader.slideUp( "fast", function(){});
			}
		});
    $(document).on("click", "#myMessage", function(){
      var subMenuHeader = $("#divMessage");
      if(subMenuHeader.css("display") == "none"){
        subMenuHeader.slideDown( "fast", function(){});
      }else{
        subMenuHeader.slideUp( "fast", function(){});
      }
    });

    $(document).on("click", "#myRequest", function(){
      var subMenuHeader = $("#ul_Request");
      if(subMenuHeader.css("display") == "none"){
        subMenuHeader.slideDown( "fast", function(){});
      }else{
        subMenuHeader.slideUp( "fast", function(){});
      }
    });
    $("#infoCondizioni").click(function(){
      $(".iubenda-ibadge").contents().find("a").click();
    });
		$(document).on('click', function(event) {
			 var target = $( event.target );
			 closeTabs(target);
			
		});
    
    $(document).on('click', "#lightbulbNotif", function(event) {
      var subMenuHeader = $("#ul_notif");
      if(subMenuHeader.css("display") == "none"){
        subMenuHeader.slideDown( "fast", function(){});
      }else{
        subMenuHeader.slideUp( "fast", function(){});
      }
    });


		var previousScroll = 0;
    var forma_team_display = true;
		 $(window).scroll(function(event) {
      $("#bulb").addClass("fadeInUp");
			var currentScroll = $(this).scrollTop();
      /*
         if (currentScroll > (previousScroll+40)){
	   //        console.log('down');
	            previousScroll = currentScroll;
	            $("#sub_header").slideUp("fast", function(){});
	       } else if(currentScroll < (previousScroll-20)) {
	     //     console.log('up');
	           previousScroll = currentScroll;
	           $("#sub_header").slideDown( "fast", function(){});
	       }
	      */
        if(currentScroll + $(window).height() > 1100){
            //$(".leftSide img").fadeIn();
            $(".leftSide img").addClass("fadeInLeft");
        }

        if(currentScroll + $(window).height() > 1300){
            //$(".rightSide img").fadeIn();
            $(".rightSide img").addClass("fadeInRight");
        }
        if(currentScroll + $(window).height() > 1900){
            $("#third img").addClass("fadeInDown");
            setTimeout(function(){
             // $("#forma_team").fadeOut();
              //$(".rightSide_2").slideDown();
            //  $("#punta_successo").fadeIn();
             // forma_team_display = false;
             $(".box_descr").addClass("fadeInUp");
            },500);
            

        }/*else if(currentScroll + $(window).height() > 1750 && forma_team_display == true){
           // $(".leftSide_2").slideDown();
           //$("#forma_team").fadeIn();       
        }*/

      });

      $(document).on('click', "#contentReg", function(){
       $('html, body').animate({
            scrollTop: $("#first").offset().top -100
        }, 800);
     });                     
		 $(document).on('click', "#contentFunction", function(){
		 	 $('html, body').animate({
		        scrollTop: $("#second").offset().top -100
		    }, 800);
		 });
		 $(document).on('click', "#contentTarget", function(){
		 	 $('html, body').animate({
		        scrollTop: $("#third").offset().top - 150
		    }, 800);
		 });
		 $(document).on('click', "#contentInfo", function(){
		 	$('html, body').animate({
		        scrollTop: $("footer").offset().top 
		    }, 800);
		 });
		function closeTabs(target){  //chiude gli altri menu aperti
      var id = target[0].id;
      if(id != "myPicture"){
        $("#menu_profile_header").slideUp( "fast", function(){});
       
      }
      if(id != "lightbulbNotif"){
        $("#ul_notif").slideUp( "fast", function(){});
      }
      if(id != "myMessage"){
        $("#divMessage").slideUp( "fast", function(){});
      }
      if(id != "myRequest"){
        $("#ul_Request").slideUp( "fast", function(){});
      }
      
    }
	});
function confirmCookie(){
    $("#cookie_div").slideUp();
    $("#cookie_bar").slideUp();
    var scadenza = new Date();
    var adesso = new Date();
    scadenza.setTime(adesso.getTime() + (parseInt(20000) * 60000));
    document.cookie = 'cnf_cksr= 1; expires=' + scadenza.toGMTString() + '; path=/';

}

</script>

</head>
<body>


<div id="fb-root"></div>

<script>
var a_f_b = 0;
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    $("#fb_btt_log").text("Attendi...");
    console.log(response);
    if (response.status === 'connected') {
      conFB(response);
    } else if (response.status === 'not_authorized') {
      logFB(response);
    } else {
      logFB(response);
    }
  }
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '155342505829',
    cookie     : true,  // enable cookies to allow the server to access 
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });

  };
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  function logFB(r) {
   // console.log('Welcome!  Fetching your information.... ');
    FB.login(function(response){
     // console.log(response);
      conFB(response);
    }, {scope: 'email'});
}
    function conFB(r){
      FB.api('/me', function(response) {
      // console.log(response);
       if(r.authResponse != null && response.email != null){
        
            var chck = $.ajax({
                url: "api/fb_mng_log.php?crpt",
                 type: "POST",
                          data: "fbid="+response.id+ "&nome="+response.first_name+"&cognome="+response.last_name+"&data="+response.birthday+"&email="+response.email+"&k="+r.authResponse.signedRequest,
                   
                          dataType: "json"
                        });
                    chck.done(function(conf)
                          {
                          //  console.log(conf);
                          if(conf.status == "ok"){
                            var str = conf.str;
                            var lgfb = $.ajax({
                                url: "api/fb_mng_log.php",
                                type: "POST",
                                data: "fbid="+response.id+ "&nome="+response.first_name+"&cognome="+response.last_name+"&data="+response.birthday+"&email="+response.email+"&crpt="+str,
                                 
                               dataType: "json"
                             });
                                  lgfb.done(function(conferma)
                                        {
                                        if(conferma.status == "ok_log"){
                                           $("#fb_btt_log").text("Accesso in corso");
                                           location.href = 'https://satusia.com/home.php';
                                        }else{
                                           $("#fb_btt_log").text("Errore...");
                                        }
                                      });
                                  lgfb.fail(function(jqXHR, textStatus) {
                                      $("#fb_btt_log").text("Errore...");
                            });
                          }
                          

                        });
                    chck.fail(function(jqXHR, textStatus) {
                        $("#fb_btt_log").text("Errore...");
              });
                  console.log("REGISTRA");
            }else if(a_f_b == 0){
              a_f_b = 1;
              $("#fb_btt_log").click();
            }else{
              $("#fb_btt_log").text("Accesso Facebook rifiutato");
            }
       
      });
    
  }
</script>
	<header>
    <div id="cookie_bar">Questo sito o gli strumenti terzi da questo utilizzati si avvalgono di cookie necessari al funzionamento ed utili alle finalità illustrate nella cookie policy.
    Proseguendo la navigazione, acconsenti all’uso dei cookie. <span id="mostra_cookie_info">Mostra informazioni sull'uso dei cookie</span>
    <div id="close_cookie_bar">Ho capito</div>
    </div>
		<div id="top_header">
			<div class="main">
				<div id="logo" class="header_item"><div id="sLogo"></div>atusia</div>
				<div id="center_header" class="header_item">
					
					<div id="logDivHeader">
          <form id="formLog">
					  <table>
						<tr><td><input type="email" id="email" placeholder="email" /></td><td> <input type="password" placeholder="password" id="password"/> </td><td><button id="logBtt">Accedi</button></tr>
						<tr><td><a href='recupera_password.php'>Hai dimenticato la password?</a></td><td id='td_remember'>Ricordami<input type="checkbox" id="remember_me" value="remember_me" /></td><td></td></tr>
					  </table>
          </form>
					</div>
				</div>
				
			</div>
		</div>
		<div id="sub_header">
			<div id="main_subheader" class="main">
				<div id="contentReg" class="sub_header_item selectedNav">Registrati</div>
				<div id="contentFunction" class="sub_header_item ">Obiettivo</div>
				<!--<div id="contentTarget" class="sub_header_item ">Obiettivo</div>-->
				<div id="contentInfo" class="sub_header_item ">Informazioni</div>
				<div id="swiper" style="left: -6px; width: 74px;"></div>
			</div>
		</div>
	</header>
  <div id="cookie_div">
<div class="header_cookie_div">Cookie Policy for Satusia</div>
<div class="block_cookie">
<div class="header_cookie_div">What Are Cookies</div>

As is common practice with almost all professional websites this site uses cookies, which are tiny files that are downloaded to your computer, to improve your experience. This page describes what information they gather, how we use it and why we sometimes need to store these cookies. We will also share how you can prevent these cookies from being stored however this may downgrade or 'break' certain elements of the sites functionality.

For more general information on cookies see the <a href="https://en.wikipedia.org/wiki/HTTP_cookie">Wikipedia article on HTTP Cookies...</a>
</div>
<div class="block_cookie">
<div class="header_cookie_div">
How We Use Cookies</div>

We use cookies for a variety of reasons detailed below. Unfortunately in most cases there are no industry standard options for disabling cookies without completely disabling the functionality and features they add to this site. It is recommended that you leave on all cookies if you are not sure whether you need them or not in case they are used to provide a service that you use.
</div>
<div class="block_cookie">
<div class="header_cookie_div">Disabling Cookies</div>

You can prevent the setting of cookies by adjusting the settings on your browser (see your browser Help for how to do this). Be aware that disabling cookies will affect the functionality of this and many other websites that you visit. Disabling cookies will usually result in also disabling certain functionality and features of the this site. Therefore it is recommended that you do not disable cookies.
</div>
<div class="block_cookie">
<div class="header_cookie_div">
The Cookies We Set</div>

If you create an account with us then we will use cookies for the management of the signup process and general administration. These cookies will usually be deleted when you log out however in some cases they may remain afterwards to remember your site preferences when logged out.

We use cookies when you are logged in so that we can remember this fact. This prevents you from having to log in every single time you visit a new page. These cookies are typically removed or cleared when you log out to ensure that you can only access restricted features and areas when logged in.

</div>


<div class="block_cookie">
<div class="header_cookie_div">Third Party Cookies</div>

In some special cases we also use cookies provided by trusted third parties. The following section details which third party cookies you might encounter through this site.

We also use social media buttons and/or plugins on this site that allow you to connect with your social network in various ways. For these to work the following social media sites including; Facebook, Twitter, will set cookies through our site which may be used to enhance your profile on their site or contribute to the data they hold for various purposes outlined in their respective privacy policies.
</div>

<div class="block_cookie">
<div class="header_cookie_div">More Information</div>

Hopefully that has clarified things for you and as was previously mentioned if there is something that you aren't sure whether you need or not it's usually safer to leave cookies enabled in case it does interact with one of the features you use on our site. However if you are still looking for more information then you can contact us through one of our preferred contact methods.
<div id="email_cookie">Email: privacy@satusia.com</div>

</div><div id="nascondi_cookie_div">Chiudi</div>
  </div>
	<div id="container">
      <div class="sectionHome" id="first">
        <div class="main">
          <div id="leftHome">
              <img src="icon/satusia_icon.png" id="satusiaLogo" />
              <div id="corda"></div>
              <div id="cit">
                <h2>"Le idee migliori non vengono dalla ragione,</h2>
                <h2>ma da una lucida, visionaria follia."</h2>
                <h3>-Erasmo da  Rotterdam-</h3>
              </div>
          </div>
          <div id="regHome">
            
              <?php if(!isset($_GET['key'])){ ?>
              <div id="panelReg">
                <div id="titleReg">Registrati</div>

                <table>
                  
                  <tr><td><input type="text" id="name" class="inputContainer" placeholder="Nome" /></td><td> <input type="text" class="inputContainer" placeholder="Cognome" id="surname"/></td></tr>
                  <tr><td colspan="2"><input type="email" id="emailReg" class="inputContainer" placeholder="Email" /></td></tr>
                  <tr><td colspan="2"><input type="password" id="password1Reg" class="inputContainer" placeholder="Password" /></td></tr>
                  <tr><td colspan="2"><input type="password" id="password2Reg" class="inputContainer" placeholder="Ripeto Password" /></td></tr>
                  <tr><td id="infoCondizioni" colspan="2">Registrandoti dichiarate di aver letto e accettato i
    Termini e Condizioni d'uso e di Privacy.</td></tr>
                  <tr><td colspan="2"><button id="regButton">Registrati</button></td></tr>
                  <tr><td colspan="2"><div id="line_log"></div></td></tr>
                  <tr><td colspan="2"><!--<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>--> <button id="fb_btt_log" onclick="checkLoginState()">Accedi con Facebook</button></td></tr>
                 <!-- <tr id="fb_avviso"><td colspan="2">Se la vostra email di Facebook non è presente nel database verrete registrati automaticamente</td></tr>-->
                </table>
                 
               </div>
               <div id="alertReg"></div>
              <?php
            }else{
              ?>
                <div id="panelReg" class='ver_key'>
               <div id="titleReg">Verifica registrazione</div>
               <div id="avviso_reg">Attendi...</div>
             </div>
          <?php } ?>
         
          </div>
        </div>
			</div>

      <div class="sectionHome" id="second">
        <div class="main">
            <div class="leftSide">
                <img src="icon/crea.png" class="animated" />
            </div>
            <div class="centerSide">
                <img src="icon/bulb.png" class="animated"id="bulb" />
            </div>
            <div class="rightSide">
                <img src="icon/aiuta.png" class="animated" />
            </div>
        </div>
      </div>

      <div class="sectionHome" id="third">
        <div class="main">
            <div class="leftSide_2">
                <img src="icon/collabs.png" class="animated"/>
             <!--   <img src="icon/forma_team.png" class="slogan" id="forma_team" />-->
               <!-- <img src="icon/punta_successo.png" class="slogan" id="punta_successo" /> -->
               <!--<div id="punta_successo">Cerca collaboratori per i tuoi progetti.</div>-->
               <div class="box_descr animated">In gruppo si lavora meglio
                  <div class="description_side">
                  Cerca i collaboratori per il tuo progetto, o unisciti ai progetti altrui.
                  Satusia permette di inviare richieste di collaborazione, così da formare un team per trasformare le vostre idee in realtà
                  </div>
              </div>
            </div>
           
            <div class="rightSide_2">
                <img src="icon/follower.png" class="animated" />
                <div class="box_descr animated">Segui i talenti nel loro percorso
                  <div class="description_side">
                    Segui le persone e i progetti che ti interessano, tutte le loro attività compariranno nella tua home.
                    Vedrai nascere e crescere numerosi progetti innovativi.
                  </div>
              </div>
            </div>
        </div>
      </div>

			 <footer >
        <div id="footer_head">
          <div id="social_foot">
            <div id="fll_foot"><span id="scritta_supp">Supporta Satusia!</span>
            <div id="twt_butt_footer"><a href="https://twitter.com/satusia_tweet" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @satusia_tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></div>
            <div class="fb-like" data-href="https://satusia.com" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
          </div>
           
          </div>
        </div>
        <div id='footer_foot'>
            <span id='scritta_footer'>Copyright © 2014-2015 Satusia · Via Lando Conti 17, 20070 Cerro Al Lambro (Milano) · info@satusia.com · Tutti i diritti riservati</span> <a href="//www.iubenda.com/privacy-policy/222723" class="iubenda-white iubenda-embed" title="Privacy Policy">Privacy Policy</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src = "//cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
        </div>
       </footer>
	</div>

 
	
</body>
</html>