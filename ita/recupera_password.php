<?php
session_start();

if(isset($_SESSION['id'])){
    header("location: /home.php");
}

include 'connect.php';
  
if(isset($_COOKIE['usr'])){
  
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
  $key = mysqli_real_escape_string($connessione, $_GET['key']);
  $sel = mysqli_query($connessione, "SELECT * FROM miss_password WHERE key_msspswd = '$key'");
  $n = mysqli_num_rows($sel);
  $key_exist = false;
  if($n > 0){
    $key_exist = true;
  }
}

?>

<html>
<head>
  <meta charset="UTF-8">
  <title>Satusia</title>
  <link rel="icon" href="favicon.ico" />
  <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
  <link rel="stylesheet" href="css/style_general.css" />
    <link rel="stylesheet" href="css/footer.css" />
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

#email_box input, #buttonRecupera, #changePssw {
  height: 50px;
  padding: 10px;
  font-size: 15px;
  width: 100%;
  cursor: pointer;
}
button#buttonRecupera, #changePssw {
  background-color: rgb(55, 190, 215);
  border: none;
  color: #fff;
  font-size: 18px;
}
#center_header{
  right: -110px;
}
#email_box table {
  width: 90%;
  bottom: 8;
  height: 240px;
  margin-right: 5%;
 
}
#email_box {
    position: relative;
    width: 500px;
    margin-left: calc(50% - 250px);
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
  height: 300px;
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
  top: 200px;
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
  display: none;
}
.leftSide img {
  top: 150px;
}
.rightSide img{
  bottom: 10;
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

}
.leftSide_2 {
  width: 50%;
  height: 100%;
  position: absolute;
  left: 0;
  display: none;
}
.rightSide_2 {
  width: 50%;
  height: 100%;
  position: absolute;
  right: 0;
  display: none;
}
.rightSide_2 img {
  position: absolute;
  bottom: 0;
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

#td_remember{
  position: relative;
  bottom: 2px;
}
#td_remember input{
  position: relative;
  top: 2px;
}

body, html{
  height: auto !important;
}
footer{
  position: absolute;
  bottom: 0;
}

#no_key_valid {
    font-size: 18px;
    padding: 10px;
    color: rgb(80,80,80);
}
.no_key_class {
    height: 180px !important;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
		$(document).ready(function(){
$("#formLog").submit(function( event ) {
 // console.log("Form log");
  event.preventDefault();
});
 $(document).on("click", "#logBtt", function(){
       // console.log("CLICK")
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
                 console.log("Login Eseguito. Reindirizzamento in corso...");
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
var can = true;
<?php
if(isset($_GET['key'])  && $key_exist){

?>

      $("#changePssw").click(function(){
      //  console.log("CLICK");
        if($("#pssw_new").val() == $("#re_pssw_new").val()){
          if(can){
            var pssw = $("#pssw_new").val();
            var key = "<?php echo $key; ?>";
            if(pssw != ""){
               can = false;
           
               var cng = $.ajax({
                          
                          url: "api/recpssw_service.php",
                          type: "POST",
                          data: "psw="+pssw+"&key="+key,
                          dataType: "json"
                          });
                  cng.done(function(resp)
                          {
                            if(resp.status == "ok"){
                              $("#titleReg").text("Password modificata, effettua l'accesso");
                            }else if(resp.status == "password_not_valid"){
                              $("#titleReg").text("Password non valida");
                              $("#pssw_new").focus();
                            }else if(resp.status == "key_not_found"){
                              $("#titleReg").text("Chiave di verifica non valida");
                            }
                            can = true;
                          });
                  
                cng.fail(function(jqXHR, textStatus) {
                       console.log("Fallito.");
                       can = true;
                    }); 
            }else{
              $("#pssw_new").focus();
            }
          }
        }else{
          $("#re_pssw_new").focus();
        }
      });

<?php
}else{
?>

    $("#buttonRecupera").click(function(){
      var email = $("#email_recupera").val();
      if(can){
        can = false;
        if(email != ""){
           $("#titleReg").text("Attendi...");
           var rec = $.ajax({
                      
                      url: "api/recpssw_service.php",
                      type: "POST",
                      data: "email="+email,
                      dataType: "json"
                      });
              rec.done(function(resp)
                      {
                        if(resp.status == "ok"){
                          $("#titleReg").text("Email inviata!");
                        }else if(resp.status == "email_not_found"){
                          $("#titleReg").text("Email non esistente");
                        }
                        can = true;
                      });
              
            rec.fail(function(jqXHR, textStatus) {
                  console.log("Fallito.");
                  $("#titleReg").text("Errore inaspettato");
                  can = true;
                }); 
        }else{
          $("#email_recupera").focus();
        }
      }
    });
<?php
}
?>
	
	
		
	});
</script>

</head>
<body>
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.4&appId=1647377795529048";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

	<header>
		<div id="top_header">
			<div class="main">
				<div id="logo" class="header_item"><a href="index.php"><div id="sLogo"></div>atusia</div></a>
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
		
	</header>
	<div id="container">
<?php
if(isset($_GET['key'])){
  
?>
    <div id="email_box">
       
               <?php 
                if($key_exist){

                ?>
                <div id="panelReg">
                 <div id="titleReg">Imposta nuova password</div>
                
                <table>
                  <tr><td colspan="2"><input type="password" id="pssw_new" class="inputContainer" placeholder="Nuova password" /></td></tr>
                 <tr><td colspan="2"><input type="password" id="re_pssw_new" class="inputContainer" placeholder="Ripeti password" /></td></tr>
                  <tr><td colspan="2"><button id="changePssw">Imposta</button></td></tr>
                </table>
                <?php
              }else{ ?>
                 <div id="panelReg" class="no_key_class">
                 <div id="titleReg">Chiave di verifica non valida</div>
                  <div id="no_key_valid"><p>La chiave di verifica non è valida.</p> Contatta l'assistenza all'email <b>help@satusia.com</b></div>
           <?php   } ?>
          </div>
       
     </div>
<?php
}else{
?>
     <div id="email_box">
       <div id="panelReg">
                <div id="titleReg">Recupera password</div>
                <table>
                  <tr><td colspan="2"><input type="email" id="email_recupera" class="inputContainer" placeholder="Inserisci la tua email" /></td></tr>
                  <tr><td id="infoCondizioni" colspan="2">Ti sarà inviata un'email, potrebbe essere messa tra le email indesiderate o spam</td></tr>
                  <tr><td colspan="2"><button id="buttonRecupera">Recupera</button></td></tr>
                </table>
          </div>
       
     </div>

<?php
} ?>
  </div>
<footer>
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
</body>
</html>
<?php
mysqli_close($connessione);
?>