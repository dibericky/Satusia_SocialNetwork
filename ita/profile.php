<?php
session_start();
if(isset($_SESSION['id'])){
  
  include 'connect.php';
  $idLog = $_SESSION['id'];
  $selMyInfo = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = $idLog");
  $ascMyInfo = mysqli_fetch_assoc($selMyInfo);
  if(isset($_GET['id'])){
    $idPage = htmlspecialchars($_GET['id'], ENT_QUOTES);
  }else{
    $idPage = $idLog;
  }
  $selThisPage = mysqli_query($connessione, "SELECT Nome, Cognome FROM utenti WHERE id = $idPage");
  $nTP = mysqli_num_rows($selThisPage);
  if($nTP > 0){
    $esiste = true;
  }else{
    $esiste = false;
  }
  $nFll = 0;
if($esiste){
  $ascThisPage = mysqli_fetch_assoc($selThisPage);
  $titlePage = $ascThisPage['Nome']." ".$ascThisPage['Cognome'];
  $selSoc = mysqli_query($connessione, "SELECT * FROM social_user WHERE id_user = $idPage");
  $nSoc = mysqli_num_rows($selSoc);
  $fb = "";
  $twt = "";
  $sito_pers = "";
  if($nSoc > 0){
    $asc_social = mysqli_fetch_assoc($selSoc);
    $fb = $asc_social['facebook'];
    $twt = $asc_social['twitter'];
    $sito_pers = $asc_social['personal'];
  }

  if($idLog != $idPage){
    $isMyPage = false;
    $selIsFollowing = mysqli_query($connessione, "SELECT * FROM follow WHERE follower = $idLog AND followed = $idPage");
    $nIsFo = mysqli_num_rows($selIsFollowing);
    if($nIsFo > 0){
      $isFollowing = true;
    }else{
      $isFollowing = false;
    }
  }else{
    $isMyPage = true;
  }

  $selNFll = mysqli_query($connessione, "SELECT * FROM follow WHERE followed = $idPage");
  $nFll = mysqli_num_rows($selNFll);
}else{
  $titlePage = "Non trovato";
}
?>
<html>
<head>
  <meta charset="UTF-8">
  <title>Satusia - <?php echo $titlePage;?></title>
  <link rel="icon" href="favicon.ico" />
  <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
  <link rel="stylesheet" href="css/style_general.css" />
  <link rel="stylesheet" href="css/animate.css">
<style type="text/css">
#changePhotoUser {
    background-image: url(icon/camera_sat.png);
    height: 40px;
    width: 40px;
    background-size: cover;
    position: absolute;
    top: 5;
    right: 5;
    opacity: 1;
    cursor: pointer;
}
#changePhotoUserInput {
    height: 100%;
    width: 100%;
    opacity: 0;
}
#carica{

    display: none;
    background: rgb(55, 190, 215);
    color: #fff;
    border: 1px solid rgb(55, 190, 215);
    margin: 0 auto;
    margin-top: 10px;
    text-align: center;
    height: 30px;
    width: 100px;
    line-height: 30px;
    font-size: 15px;
    cursor: pointer;
    -webkit-border-radius: 999px;
    -moz-border-radius: 999px;
    border-radius: 999px;
    letter-spacing: 1.3px;
    -moz-user-select: none;
    -khtml-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}


.title_project_list > a{
  text-decoration: none;
  color: rgb(80,80,80);
}

#downloadButton {
    text-decoration: none;
    color: rgb(110,110,110);
}
div#downloadContainer {
    width: 100%;
    height: 50px;
    line-height: 50px;
    text-align: center;
}



.li_div_pj_container {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
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
div#megabox_message {
    position: relative;
}
div#message_box {
    position: absolute;
    top: 0;
    left: 200;
    z-index: 999999999999999999999;
    width: 500px;
    background-color: rgb(242, 246, 247);
    padding: 10px;
    padding-left: 20px;
    padding-right: 20px;
    border: 1px solid rgba(200,200,200,1);
    box-shadow: 0px 0px 3px rgba(150,150,150,0.5);
    padding-bottom: 50px;
}
#message_box textarea {
    width: 100%;
    border: 1px solid rgba(180,180,180,0.5);
}
#container_message_box{
  display: none;
}

li.item_project.one_pj {
    width: 50% !important;
    margin-left: 25%;
    box-shadow: 0px 0px 5px rgba(150,150,150,.8);
}
li.item_project.two_pj{
  width: 50% !important;

}
.one_pj .description_project_list, .two_pj .description_project_list {
    height: 90px !important;
}
.one_pj .image_project_list, .two_pj .image_project_list {
    height: 200px;
}
.one_pj .imgProjectLi, .two_pj .imgProjectLi  {
    height: 200px !important;
}


</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/autoresize.jquery.min.js"></script>
<script src="js/general_script.js"></script>
<script src="js/notif.js"></script>
<?php
if($esiste){
?>
<script src="js/profile.js"></script>
      <?php
  if($idPage == $idLog){
?>  
<script src="js/upimg.js"></script>
<?php } ?>
<script type="text/javascript">
		$(document).ready(function(){

      $(".mainLinkElement .facebook_icon").click(function(){

        shareOnFb();
      });
     $(".mainLinkElement .satusia_icon").click(function(){
        shareOnSatusia(<?php echo $idPage; ?>)
      });
     $("#message_box textarea").autoResize();
     
      //scrollbar notif
  /*  $(".mCustomScrollbar").mCustomScrollbar({
          theme:"dark",
          scrollInertia: 300,
          mouseWheel:{ preventDefault: true, scrollAmount: 35 },
    });
*/
      //
      var idProfile = <?php echo "$idPage";  ?>;
       var getInfoUser = $.ajax({
              
              url: "api/webservice.php?action=info_id&id="+idProfile+"&nome&cognome&nascita&img&citta&sesso&professione&frase_personale&biografia&scuola_superiore&universita&lingue&data_reg",
              dataType: "json"
              });
      getInfoUser.done(function(resp)
              {
                $("#nameProfileLeft").text(resp.Nome + " "+resp.Cognome);
                var citta = resp.citta;
                var dataNasc = resp.Data_Nascita;
                var biog = resp.biografia;
                var imgProf = resp.immagine_profilo;
                var prof = resp.professione;
                var edu = resp.scuola_superiore;
                var html = "<li class='title_edu'>Università</li>";
                $("#signed_span").text(resp.data_reg);
                if(resp.universita.length > 0){
                  var uniIsEmpty = true;
                  for(var x = 0; x < resp.universita.length; x++){
                    if(resp.universita[x] != ""){
                      html += "<li class='place_edu'>"+resp.universita[x]+"</li>";
                      uniIsEmpty = false;
                    }
                  }
                  if(uniIsEmpty){
                     html += "<li class='place_edu'>Nessuna università inserita</li>";
                  }
                 
                }else{
                  html += "<li class='place_edu'>Nessuna università inserita</li>";
                }
                $("#uni_div").html(html);
                html = "";
                if(resp.lingue.length > 0){
                  var lingueIsEmpty = true;
                  for(var x = 0; x < resp.lingue.length; x++){
                    if(resp.lingue[x] != ""){
                      html += "<li>"+resp.lingue[x]+"</li>";
                      lingueIsEmpty = false;
                    }
                  }
                  if(lingueIsEmpty){
                     html = "<li>Nessuna lingua inserita</li>";
                  }
                 
                }else{
                  html = "<li>Nessuna lingua inserita</li>";
                }
                 $("#container_language ul").html(html);
                $("#job").text(prof);
                $("#locate span").text(citta);
                $("#short-description").text(resp.frase_personale);
                $("#birthday span").text(dataNasc);
                $("#profile_img").css("background-image", "url("+imgProf+")");
                $("#container_biography").html(biog);
                if(edu != ""){
                  $("#scuola_sup_div .place_edu").text(edu);
                }else{
                 $("#scuola_sup_div .place_edu").text("Nessuna scuola superiore inserita");
                }
              });
      
    getInfoUser.fail(function(jqXHR, textStatus) {
           console.log("errore.");
        }); 

     $("#followPerson").click(function(){
          follow_u(<?php echo "$idPage";?>);
      });
      

function getFll(){
  var by = $("#b_fll").val();
  if(by != "-1"){
        var getInfoFollower = $.ajax({
              
              url: "api/webservice.php?action=followed&id="+idProfile+"&by="+by,
              dataType: "json"
              });
      getInfoFollower.done(function(resp)
              {
                var n = resp.num;
               // $("#nFollowerBox .nLine").text(n);
                var html = "";
                /*


                    <ul class="twoLine">
                      <li class="firstLinepeople first_people follower_person"></li>
                      <li class="firstLinepeople second_people follower_person"></li>
                      <li class="firstLinepeople  third_people follower_person"></li>
                      
                      <li class="secLinepeople first_people follower_person"></li>
                      <li class="secLinepeople second_people follower_person"></li>
                      <li class="secLinepeople more_people follower_person"><span> +53</span></li>

                    <ul>


                    <ul class="ul_big">
                      <li class='li_line'>
                        <ul class='ul_line'>
                          <li class="firstLinepeople first_people follower_person"></li>
                          <li class="firstLinepeople second_people follower_person"></li>
                          <li class="firstLinepeople  third_people follower_person"></li>
                        </ul>
                      </li> 
                      <li class='li_line'>
                        <ul class='ul_line'>
                          <li class="firstLinepeople first_people follower_person"></li>
                          <li class="firstLinepeople second_people follower_person"></li>
                          <li class="firstLinepeople  third_people follower_person"></li>
                        </ul>
                      </li> 
                    <ul>


                */
              
                if(n > 0){
                   
                    for(var x = 0; x < resp.user.length; x++){
                      if(resp.user[x]){
                        var resto = x%3;
                        if(resto == 0){
                          html += "<li class='li_line'><ul class='ul_line'>"
                        }
                        html += "<a href='profile.php?id="+resp.user[x].follower+"'><li class='follower_person' style='background-image: url("+resp.user[x].immagine_profilo+")'></li></a>";
                        if(resto == 2){
                          html += "</ul></li>";
                        }
                        
                      }
                    }
                   
                     $("#b_fll").val(parseInt(by) + parseInt(resp.user.length));
                     if(n < 9){
                        $("#moreFll").hide();
                     }
                }else{
                  $("#b_fll").val("-1");
                  $("#moreFll").hide();
                }
                
                $("#container_follow_people .ul_big").append(html);
               
              });
      
    getInfoFollower.fail(function(jqXHR, textStatus) {
           console.log("error.");
        }); 
  }

}
getFll();
$("#moreFll").click(function(){
  getFll();
});
 var getInfoCollab = $.ajax({
              
              url: "api/webservice.php?action=connection_u&user="+idProfile,
              dataType: "json"
              });
      getInfoCollab.done(function(resp)
              {
                var n = resp.length;
                $("#nCollabBox .nLine").text(n);
                var html = "";
                 html = "<ul class='ul_big'>";
                if(n > 0){
                   
                    for(var x = 0; x < resp.length; x++){
                      if(resp[x]){
                        var resto = x%3;
                        if(resto == 0){
                          html += "<li class='li_line'><ul class='ul_line'>"
                        }
                        html += "<a href='profile.php?id="+resp[x].id+"'><li class='collab_person' style='background-image: url("+resp[x].immagine_profilo+")'></li></a>";
                        if(resto == 2){
                          html += "</ul></li>";
                        }
                        
                      }
                    }
                   

                }
                 html += "</ul>"

                $("#container_collab_people").html(html);
               
              });
      
    getInfoCollab.fail(function(jqXHR, textStatus) {
           console.log("errore.");
        }); 


    var getProj = $.ajax({
              
              url: "api/webservice.php?action=proj_user&user="+idProfile,
              dataType: "json"
              });
      getProj.done(function(resp)
              {
                 var n = resp.length;
                $("#nProjectBox .nLine").text(n);
                /*
<ul id="ul_projects">
                  <li class="item_project">
                    <div class="image_project_list">
                      <div class="imgProjectLi"></div><div class="categSub">
                        <h3><span class="firstPartCategSub">M</span><span class="secondPartCategSub">usica</span></h3>
                      </div>
                      
                    </div>
                    <div class="mainInfo_project_list">
                      <ul>
                        <li class="title_project_list">Titolo Progetto1</li>
                        <li class="founder_project_list">Riccardo Di Benedetto</li>
                        <li class="locate_project_list"><div class='icon_locate icon_info'></div>Milano, Mi</li>
                      </ul>
                    </div>
                    <div class="description_project_list">
                      This project is based on the page layout that Tokyopop created for the German edition and should this campaign succeed, the printing of the English edition will also be 
                    </div>
                    <div class="number_info_project_list">
                      <ul>
                        <li class="li_follow_count"><div class='icon_follower'></div><span class='count_follower'>100</span></li>
                        <li class="li_collab_count"><div class='icon_collab'></div><span class='count_collab'>100</span></li>
                      </u>
                    </div>
                  </li>


                */
                var html = "";
                var cl_pj = "";
                if(n == 1){
                  cl_pj = " one_pj";
                }else if(n == 2){
                  cl_pj = " two_pj";
                }
                if(n > 0){
                  html = "<ul id='ul_projects'>";
                  for(var x = 0; x < n; x++){
                    html += "<li class='item_project "+cl_pj+"'><div class='li_div_pj_container'><div class='image_project_list'>";
                    html +=  "<div class='imgProjectLi' style='background-image: url("+resp[x].img+")'></div><div class='categSub'><h3><span class='firstPartCategSub'>"+resp[x].categoria.substring(0,1).toUpperCase()+"</span>";
                    html += "<span class='secondPartCategSub'>"+resp[x].categoria.substring(1, resp[x].categoria.length)+"</span></h3></div></div>";

                    html += "<div class='mainInfo_project_list'><ul><li class='title_project_list'><a href='project.php?proj="+resp[x].idproj+"'>"+resp[x].title+"</a></li><li class='founder_project_list'>"+resp[x].nome+" "+resp[x].cognome+"</li>";
                    html += "<li class='locate_project_list'><div class='icon_locate icon_info'></div>"+resp[x].citta+"</li></ul></div>";

                    html += "<div class='description_project_list'>"+resp[x].descr+"</div><div class='number_info_project_list'><ul>";
                    html += "<li class='li_follow_count'><div class='icon_follower'></div><span class='count_follower'>"+resp[x].follower+"</span></li>";
                    html += "<li class='li_collab_count'><div class='icon_collab'></div><span class='count_collab'>"+resp[x].collab+"</span></li></ul></div></div></li>";
                  }
                  html += "</ul>";
                }else{
                  html = "<div class='no_more_ntf'>Nessun progetto</div>";
                  $("#show_projects_user").addClass("no_pjct");
                  $(".arrow_project").hide();
                }
                $("#container_show_projects").html(html);
                
              });
      
    getProj.fail(function(jqXHR, textStatus) {
           console.log("error.");
        }); 

      ////////////////////

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
		//	console.log("CLICK");
			$(".sub_header_item").removeClass("selectedNav");
			$(this).addClass("selectedNav");
			selectedNavControl();
		});
		
    

    $(document).on("click", "#bttSendMsg", function(){
      var msg = $("#message_box textarea").val();
        if(msg != ""){
        var u = '<?php echo "$idPage"; ?>';
        
        $("#message_box textarea").val("");
        sendMessage(u, msg);
        $("#messagePerson").text("Messaggio");
      }else{
        $("#message_box textarea").focus();
      }
    });
    $(document).on('click', "#messagePerson", function(){
      if($("#container_message_box").css("display") == "none"){
        $("#container_message_box").show();
        $("#message_box").show();
        $("#messagePerson").text("Annulla");
      }else{
        $("#container_message_box").hide();
        $("#message_box").hide();

        $("#messagePerson").text("Messaggio");
      
      }

    });


		var previousScroll = 0;

		 $(window).scroll(function(event) {
			var currentScroll = $(this).scrollTop();
	       if (currentScroll > (previousScroll+40)){
	          // console.log('down');
	            previousScroll = currentScroll;
	            $("#sub_header").slideUp("fast", function(){});
	       } else if(currentScroll < (previousScroll-20)) {
	          //console.log('up');
	           previousScroll = currentScroll;
	           $("#sub_header").slideDown( "fast", function(){});
	       }
	      
		 });                     
		 $(document).on('click', "#contentBackground", function(){
		 	 $('html, body').animate({
		        scrollTop: $("#background_user_info").offset().top
		    }, 800);
		 });
		 $(document).on('click', "#contentProjects", function(){
		 	 $('html, body').animate({
		        scrollTop: $("#container_show_projects").offset().top - 150
		    }, 800);
		 });
		 $(document).on('click', "#contentPeople", function(){
		 	$('html, body').animate({
		        scrollTop: $("#container_people_panel").offset().top - 150
		    }, 800);
		 });
<?php
  if($idPage == $idLog){
?>		
    $("#carica").click(function(){
          upImgProf(); 
    });
<?php
  }
?>
	});
</script>
<?php
}
?>
</head>
<body>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : 'APPID',
      xfbml      : true,
      version    : 'v2.4'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));



  function shareOnFb(){
    FB.ui({
      method: 'feed',
      name: '<?php echo $ascThisPage['Nome']." ".$ascThisPage['Cognome'];?>',
      link: 'https://satusia.com/profile.php?id=<?php echo $idPage; ?>',
      caption: 'Profilo di Satusia',
      picture: 'https://satusia.com/satusia_icon.png'
    }, function(response){});
  }
</script>

<div id="notificationPopup" class="animated"><div id="imgNotif"></div><div id="containerNotif"><div id="topContainerNotification">Title</div><div id="bottomContainerNotification">Contenuto</div></div></div>


   <div id="overlay_create" class="overlay_black">
     <div id="createNew" class="noAnimationClass">
      <div id="confirmed" class="confirmImg" style="background-image: url(icon/correct.png)"></div>
        <div id="headerCreateNew">
            <span id="headerTextCreateNew">Nuovo Progetto</span><span class="close_popup" id="closeCreateNew">Chiudi</span>
        </div>
        <div id="centerCreateNew">
            <input type="text" id="inputTextCreateNew" placeholder="Titolo progetto"/>
            <select id="categCreateNew">
                <option value="informatica">Informatica & Tecnologia</option>
                <option value="scienze">Scienze</option>
                <option value="arte">Arte</option>
                <option value="musica">Musica</option>
                <option value="moda">Moda</option>
                <option value="giornalismo">Giornalismo</option>
                <option value="culinaria">Culinaria</option>
                <option value="noprofit">No-Profit</option>
              </select>
            <textarea id="textareaCreateNew" placeholder="Descrizione progetto"></textarea>
           
        </div>
        
        <button class="btt_submit" id="bttCreateNew">Crea</button>

    </div>
  </div>

	<header>
		<div id="top_header">
			<div class="main">
				<a href="home.php"><div id="logo" class="header_item"><div id="sLogo"></div>atusia</div></a>
				<div id="center_header" class="header_item">
					<div id="searchBar"><div id="iconSearch"></div><input type="text" id="search" placeholder="Cerca Persone, Progetti, Tags, Categorie..."/>

           <!-- FastSearch -->
            <div id="fastResult">
              <div class="titleFastResult">Utenti</div>
              <ul id="ul_fastResult_user" class="ul_fastResult">
                
                
              </ul>
              <div class="titleFastResult">Progetti</div>
              <ul id="ul_fastResult_proj" class="ul_fastResult">
               
                
              </ul>
            </div>
            <!-- END FastSearch -->

          </div>
					
					<div id="menu">
						<div id="lightbulbNotif"  class="opt_menu iconHeader" >
            <div id="general_n_notif" class="notifNumber">0</div>
             <ul id="ul_notif" class="scrollNotif">
              <div class="menuarrow notif_arrow"></div>
              <li class="minititle minititleTop">Notifiche</li>
              <div class="scrollableMain mCustomScrollbar"  data-mcs-theme="dark">
                
              </div>
              <li class="viewAllNotif">Mostra altri</li>
              <input type="hidden" id="b_gen_ntf" value="10" />
            </ul>
          </div>
            <div id="myMessage" class="opt_menu iconHeader">
             <div id="msg_n_notif" class="notifNumber">0</div>
              <div id="divMessage" class="scrollNotif">
                <div id="personalMessage">
                     <ul class="ulMessage">
                      <div class="menuarrow notif_arrow"></div>
                      <li class="minititle minititleTop">Messaggi personali </li>
                      <div class="scrollableMessage mCustomScrollbar" data-mcs-theme="dark">
                        
                      </div>
                      
                    </ul>
                </div>
                <div id="projectMessage">
                    <ul class="ulMessage">
                      <div class="menuarrow notif_arrow"></div>
                      <li id="minititle_proj" class="minititle minititleTop">Messaggi progetti </li>
                      <div class="scrollableMessage mCustomScrollbar" data-mcs-theme="dark">
                        
                        

                      </div>
                      <a href="message.php"><li class="viewAllNotif">Mostra Tutti</li></a>
                    </ul>
                </div>
              </diV>

            </div>
            <div id="myRequest" class="opt_menu iconHeader">
              <div id="request_n_notif" class="notifNumber">0</div>
              <ul id="ul_Request" class="scrollNotif">
                <div class="menuarrow notif_arrow"></div>
                <li class="minititle minititleTop">Richieste collaborazione </li>
                <div class="scrollableMain mCustomScrollbar" data-mcs-theme="dark">
                  
                  
                </div>
                <li class="viewAllNotif"></li>
              </ul>
            </div>
					</div>
				</div>
				<div id="right_header" class="header_item">
          <div id="crea" class="opt_menu iconHeader"></div>
					<?php  echo "<div id='myPicture' style='background-image: url(".$ascMyInfo['immagine_profilo'].")'></div>"; ?>
          <ul id="menu_profile_header" style="display: none;">
								<div class="menuarrow"></div>
								<a href="profile.php"><li>Il Mio Profilo</li></a>
                <li class="linediv"></li>
                <a href="settings.php"><li>Impostazioni</li></a>
								<li class="linediv"></li>
								<a href="help.php"><li>Centro Assistenza</li></a>
                <li class="linediv"></li>
								<li><a href="logout.php">Esci</a></li>
					</ul>
				</div>
			</div>
		</div>
<?php
if($esiste){
?>
		<div id="sub_header">
			<div id="main_subheader" class="main">
			<!--	<div id="contentOverview" class="sub_header_item ">Overview</div> -->
				<div id="contentProjects" class="sub_header_item selectedNav">Progetti</div>
				<div id="contentBackground" class="sub_header_item ">Informazioni</div>
				<div id="contentPeople" class="sub_header_item ">Persone</div>
				<div id="swiper" style="left: -6px; width: 74px;"></div>
			</div>
		</div>
<?php

}
?>
	</header>
<?php
if($esiste){
?>
	<div id="container">
		<div class="main" id="sub_container">
			<div id="left_container">
				<div id="profile_view" class="page_main_panel">
					<a href="profile.php<?php echo "?id=".$idPage;?>"><div id="profile_img" ></div></a>
        <?php
  if($idPage == $idLog){
?>  
          <div id="changePhotoUser"><input type="file" id="changePhotoUserInput" accept="image/*"/></div>
          <div id="carica">Carica</div>
<?php
}
?>
					<div id="person_name"><a href="profile.php<?php echo "?id=".$idPage;?>" id="nameProfileLeft"></a></div>
					<div id="job" class="generalInfoPerson"></div>
					<div id="locate" class="generalInfoPerson"><div id="houseIcon" class="icon_info"></div> <span></span></div>
					<div id="short-description" class="generalInfoPerson"></div>
          <?php 
          if(!$isMyPage){ 

            if($isFollowing){   ?>

                <div id="followPerson" class="unfollow">Unfollow</div>
      <?php  }else{
            ?>
    					<div id="followPerson">Follow</div>
    				<?php
             } ?>
    				
            <div id="megabox_message">
            <div id="messagePerson">Messaggio</div>
            <div id="container_message_box">
              <div id="message_box"class="noAnimationClass">
                <div id="confirmedMsg" class="confirmImg" style="background-image: url(icon/correct.png)"></div>
                <div class="contentPopup">
                  <textarea></textarea>
                  <button class="btt_submit" id="bttSendMsg">Invia</button>
                </div>
              </div>
            </div>
          </div>
   <?php } ?>

					<div id="nContainerBox">
						<div class="infoBoxItem" id="nProjectBox">
							<div class="descriptionLine">Progetti</div>
							<div class="nLine">0</div>
						</div>
						<div class="infoBoxItem" id="nCollabBox">
							<div class="descriptionLine">Collaboratori</div>
							<div class="nLine">0</div>
						</div>
						<div class="infoBoxItem" id="nFollowerBox">
							<div class="descriptionLine">Followers</div>
							<div class="nLine"><?php echo "$nFll";?></div>
						</div>
					</div>
          <div id="otherInfo_profile">
            <div id="birthday" class="generalInfoPerson"><div id="birthdayIcon" class="icon_info"></div> <span></span></div>
            <div id="dateSignUp" class="generalInfoPerson"><div id="dateSignUpIcon" class="icon_info"></div> <span>Iscritto </span><span id='signed_span'></span></div> 
          
          </div>
    <?php
      if($twt != "" || $fb != "" || $sito_pers != ""){

    ?>
					<div id="mainLinkContainer">
            <?php
            if($twt != ""){
            ?>
						<div class="mainLinkElement">
							<div class="mainLinkIcon icon_info"></div>
							<div class="mainLinkDescription"><a target='_blank' href="<?php echo "https://twitter.com/".$twt; ?>">Twitter</a></div>
						</div>
            <?php
          }
          if($fb != ""){
            ?>
						<div class="mainLinkElement">
							<div class="mainLinkIcon icon_info"></div>
							<div class="mainLinkDescription"><a target='_blank' href="<?php echo "https://facebook.com/".$fb; ?>">Facebook</a></div>
						</div>
					
          <?php
        }
        if($sito_pers != ""){  
            //$siteweb = "http://";
            if(strpos($sito_pers, "http") === false || strpos($sito_pers, "http") !== 0){
               $sito_pers = "http://".$sito_pers;
            }else{
              $sito_pers = $sito_pers."/";
            }
          ?>
          <div class="mainLinkElement">
              <div class="mainLinkIcon icon_info"></div>
              <div class="mainLinkDescription"><a target='_blank' href="<?php echo $sito_pers; ?>">Sito Personale</a></div>
            </div>
          


 <?php    }
          ?>
          </div>
    <?php
    }
    ?>
          <div id="shareContainer">
            <span>Condividi</span>
              <div class="mainLinkElement">
               <?php
               echo "<a target='_blank' href='https://twitter.com/intent/tweet?original_referer=https%3A%2F%2Fwww.satusia.com/profile.php?id=".$idPage."&text=".$ascThisPage['Nome']."%20".$ascThisPage['Cognome']."&tw_p=tweetbutton&url=https%3A%2F%2Fwww.satusia.com/profile.php?id=".$idPage."&via=satusia_tweet'>";
              ?>
                <div class="mainLinkIcon icon_info twitter_icon"></div></a>
                 <div class="mainLinkIcon icon_info facebook_icon"></div>
               <div class="mainLinkIcon icon_info satusia_icon"></div>
            </div>
          </div>
      <?php //    <div id="downloadContainer"><a id="downloadButton" href="<?php echo "createDoc.php?user=".$idPage;?> <?php //">Scarica profilo</a></div> ?>
				</div>

				</div>
				<div id="section_container">
					<div id="center_container" >

						<div id="show_projects_user" class="page_main_panel">
							<div id="header_show_projects" class="header_panel">
								<span>Progetti</span>
								<div id="arrow_left" class="arrow_project"></div><div id="arrow_right" class="arrow_project"></div>
							</div>
							<div id="container_show_projects" class="container_panel">
								<ul id="ul_projects">
									
                  
									

								</ul>
							</div>
						</div>

						<div id="background_user_info" class="page_main_panel">
							<div id="header_background_user_info" class="header_panel">
								<span>Background</span>
								
							</div>
							<div id="container_background_user_info" class="container_panel">
								<div id="biography_user" class="item_background">
									<div id="header_biography" class="header_background_item">
										<div id="biography_icon" class="icon_background"></div><span>Biografia</span>
									</div>
									<div id="container_biography" class="container_background_item">
									  
									</div>
								</div>

								<div id="education_user" class="item_background">
									<div id="header_education" class="header_background_item">
										<div id="education_icon" class="icon_background"></div><span>Formazione</span>
									</div>
									<div id="container_education" class="container_background_item">
										<ul>
                      <div id="scuola_sup_div">
  											<li class="title_edu">Istruzione Secondaria</li>
  											<li class="place_edu"></li>
                      </div>
                      <div id="uni_div">
                        <li class="title_edu">Università</li>
                        <li class="place_edu"> </li>
                      </div>
                    </ul>

									</div>
								</div>

								<div id="language_user" class="item_background">
									<div id="header_language" class="header_background_item">
										<div id="language_icon" class="icon_background"></div><span>Lingue Conosciute</span>
									</div>
									<div id="container_language" class="container_background_item">
										<ul>
											
										</ul>
									</div>
								</div>






							</div>

						</div>


						<div id="people_panel" class="page_main_panel">
							<div id="header_people_panel" class="header_panel">
								<span>People</span>
								
							</div>

							<div id="container_people_panel" class="container_panel">
								

								<div id="collab_people_panel" class="item_background">
									<div id="header_collab_people" class="header_background_item">
										<div id="collab_people_icon" class="icon_background"></div><span>Collaboratori</span>
									</div>
									<div id="container_collab_people" class="container_background_item">
										<ul class="twoLine">
											

										<ul>
									</div>
								</div>


								<div id="follow_people_panel" class="item_background">
									<div id="header_follow_people" class="header_background_item">
										<div id="follow_people_icon" class="icon_background"></div><span>Follower</span>
									</div>
									<div id="container_follow_people" class="container_background_item">
										<ul class='ul_big'>

                    </ul>
									</div>
                  <div id='moreFll' class='shwMoreThing'>Mostra altri</div>
                  <input type="hidden" id="b_fll" value="0" />
								</div>
							</div>

						</div>
						
					</div>
					<div id="right_container"></div>
				</div>
			</div>
			
		</div>
	</div>
<?php
}else{
  include 'not_found.php';
}
?>
</body>
</html>
<?php
mysqli_close($connessione);
 }else{
  header("location: index.php");
}
?>