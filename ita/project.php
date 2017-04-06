<?php
session_start();
include 'api/time_ago.php';
if(isset($_SESSION['id'])){
  $id = $_SESSION['id'];



  if(isset($_GET['proj']) && $_GET['proj'] != ""){
   
    include 'connect.php';
    $selMyInfo = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = $id");
    $ascMyInfo = mysqli_fetch_assoc($selMyInfo);

    $idproj = $_GET['proj'];
    $q = "SELECT * FROM progetti, utenti WHERE idproj = '$idproj' AND utenti.id = iduser LIMIT 1";
    $sel = mysqli_query($connessione, $q);
    $n = mysqli_num_rows($sel);
    if($n > 0){
      $esiste = true;
    }else{
      $esiste = false;
    }
  if($esiste){
      $asc = mysqli_fetch_assoc($sel);
      $title = $asc['title'];
      $descr = $asc['descr'];
      $create_date = $asc['date_create'];
      $create_date = timeAgo($create_date);
      $descr_setting = str_replace("<br />", "&#13;", $descr);
      $isCollab = false;
      if($asc['iduser'] == $id){
        $isFounder = true;
      }else{
        $isFounder = false;
        $selIsColl = mysqli_query($connessione, "SELECT * FROM want_help WHERE id_mit = $id AND id_proj = $idproj ORDER BY date DESC LIMIT 1");
        $nIsColl = mysqli_num_rows($selIsColl);
        if($nIsColl > 0){
          $ascIsColl = mysqli_fetch_assoc($selIsColl);
          $view = $ascIsColl['view'];
          if($view == "1"){
            $isCollab = true;
          }else{
            $isCollab = false;
          }
        }else{
          $isCollab = false;
          $view = null;
        }
      }
      $sel_fll_this = mysqli_query($connessione, "SELECT * FROM follow_p WHERE user = '$id' AND proj = '$idproj'");
      $rw = mysqli_num_rows($sel_fll_this);
      if($rw > 0){
        $isFollow = true;
      }else{
        $isFollow = false;
      }
  }else{
    $title = "Non trovato";
  }
  ?>
  <html>
  <head>
    <meta charset="UTF-8">
    <title>Satusia - <?php echo $title;?></title>
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="css/style_general.css" />
     <link rel="stylesheet" href="css/style_settings.css" />
     <link rel="stylesheet" href="css/animate.css">
  <style type="text/css">





  div#wallpaperProject {
    width: 100%;
    height: 250px;
    transition: all 1s;
    position: relative;
    overflow: hidden;
    cursor: pointer;
  }
  #wallpaperImg{
    width: 100%;
  }
  #container_update_panel, #container_comments_panel{
   /*padding-left: 10px;
    padding-right: 10px;*/
    min-height: 80px;
  }
  #updates_ul, #comments_ul{
    padding: 0;
    margin: 0;
    list-style-type: none;
  }
  .classUpdate, .classComment{
    padding: 10px;
    border-bottom: 1px solid rgb(225, 232, 237);
    min-height: 120!important;
  }
  .classUpdate:hover, .classComment:hover{
   background: #f5f8fa;
  }
  .header_update_project, .header_comment_project {
    font-size: 25px;
    height: 50px;

   }
   .header_update_project{
    font-variant: small-caps;
   }
   #commentsProject .container_background_item, #updateProject .container_background_item {
    padding: 0;
    word-break: break-word;
  }
  #commentsProject .container_background_item{
     left: 30;
  }
  #updateProject .container_background_item{
    left: -18;
  }
  .textUpdate , .textComment{
    padding-bottom: 20px;
    font-size: 16px;
    font-weight: normal;
    line-height: 26px;
    letter-spacing: 0;
    color: #2a2a2a;
    margin-left: 50px;
  }
  .footer_update , .footer_comment{
    font-size: 13px;
    color: rgb(166,176,186);
    
  }
 /* #moreUpdate, #moreComment {
    width: 100%;
    height: 50px;
    text-align: center;
    line-height: 50px;
    color: rgb(55, 190, 215);
    font-size: 20px;
    cursor:pointer;
    transition-property: all;
    transition-duration: 0.5s;
    background: #f5f8fa;
  }
  #moreUpdate:hover, #moreComment:hover{
      color: rgb(0,184,245);
    background: #f7fafc;
  }*/
  #tag_project {
    min-height: 0px!important;
    box-shadow: 0px -5px 5px rgba(100,100,100,0.25);
    z-index: 9999;
  }
  .photo_comment{
    background-size: cover;
    height: 40px;
    width: 40px;
    border-radius: 100px;
    position: absolute;
    top: 10;
    left: 10;
  }
  .name_comment {
      position: absolute;
      left: 60px;
      font-size: 16px;
    font-weight: bold;
    line-height: 26px;
    letter-spacing: 0;
  }
  #changePhotoProject {
      background-image: url(icon/camera.png);
      height: 40px;
      width: 40px;
      background-size: cover;
      position: absolute;
      top: 5;
      right: 5;
      opacity: 1;
      cursor: pointer;
  }
  #changePhotoProjectInput {
      height: 100%;
      width: 100%;
      opacity: 0;
  }
  #carica{
      position: absolute;
      top: 0;
      right: 60;
      display: none;
      background: rgb(55, 190, 215);
      color: #fff;
      border: 1px solid rgb(55, 190, 215);
      padding: 8px 1px;
      margin: 0 auto;
      margin-top: 10px;
      text-align: center;
      height: 30px;
      width: 120px;
      line-height: 12px;
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
  #ttlPost {
      display: inline-block;
      /* position: absolute; */
      width: 80%;
      margin-left: 10%;
      /* top: 30px; */
      border: none;
      background-color: rgb(255, 255, 255);
      font-size: 14px;
      padding: 5px;
      border-radius: 5px;
  }
  div#megabox_comment {
      position: relative;
  }
  div#comment_box {
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
  #comment_box textarea {
      width: 100%;
      border: 1px solid rgba(180,180,180,0.5);
  }
  #container_comment_box{
    display: none;
  }
  #right_container ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
  }
  #right_container li {
      height: 80px;
      width: calc(100% - 20px);
      padding: 10;
      position: relative;
      overflow: hidden;
  }

  .containerRight{
      width: 25%;
      border: none !important;
      top: 0;
      background-color: #fff;
      box-shadow: 0px 0px 5px rgba(150,150,150,0.3);
      border-radius: 5px;

  }

  div#mCSB_5_container {
      margin-right: 12px !important;
  }
  #viewAllMsgProj{
    height: 25px !important;
  }

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
  #right_container a {
      text-decoration: none;
  }
  #viewAllMsgProj{
    cursor: pointer;
  }
  .block_setting input, #descr_setting{
    width: 350px !important;
  }
  #descr_setting{
      height: 300px;
      margin-left: 130;

  }
  #li_descr_setting{
    position: relative;
  }
  #li_descr_setting .label_setting{
    position: absolute;
      top: 0;

  }
  #edit_info_project{
    display: none;
  }
  #container_comments_panel{
    overflow: hidden;
   /* height: 242;
*/  }
li.no_more_li {
    height: 50px;
    text-align: center;
    font-size: 15px;
    color: rgb(100,100,100);
    line-height: 50px;
}
  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="js/autoresize.jquery.min.js"></script>
  <?php
    if($esiste && $isFounder){
  ?>
    <script src="js/upimg.js"></script>
  <?php
  }
  ?>
  <script src="js/general_script.js"></script>
  <script src="js/notif.js"></script>
  <script src="js/project.js"></script>
  <script type="text/javascript">
  		$(document).ready(function(){
        //scrollbar notif
    /*  $(".mCustomScrollbar").mCustomScrollbar({
            theme:"dark",
            scrollInertia: 300,
            mouseWheel:{ preventDefault: true, scrollAmount: 35 },
      });
  */
        //
  <?php
    if($esiste){
  ?>
        $("#moreComment").click(function(){
          moreComm(<?php echo $idproj; ?>);
        });
        $(".mainLinkElement .facebook_icon").click(function(){

          shareOnFb();
        });

        $("#message_box textarea").autoResize();
        $("#comment_box textarea").autoResize();

        $("#carica").click(function(){
            uploadFile(<?php echo $_GET['proj']; ?>); 
        });
      <?php
      if(!$isFounder){
      ?>
        $("#followPerson").click(function(){
            follow_p(<?php echo "$idproj";?>);
        });
       <?php
       }
      ?>

  			$('#arrow_left').on('click', function(){
  				$("#container_show_projects ul").stop().animate({scrollLeft:'-=585'}, 500);
  			});
  		$('#arrow_right').on('click', function(){
  			$("#container_show_projects ul").stop().animate({scrollLeft:'+=585'}, 500);
  		});

      $("#changePhotoProject").click(function(){

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
  		
     
      $(document).on('click', "#updateButton", function(){
        $("#overlay_UpdatePost").show();
        $("#divUpdatePost").show();

      });
      $(document).on('click', "#updateButton", function(){
        $("#overlay_UpdatePost").show();
        $("#divUpdatePost").show();

      });
      $(document).on('click', "#messagePerson", function(){
        if($("#container_comment_box").css("display") == "none"){
          $("#container_comment_box").show();
          $("#comment_box").show();
          $("#messagePerson").text("Annulla");
        }else{
          $("#container_comment_box").hide();
          $("#comment_box").hide();

          $("#messagePerson").text("Commenta");
        
        }

      });
      $(document).on('click', "#messageProject", function(){
       if($("#container_message_box").css("display") == "none"){
          $("#container_message_box").show();
          $("#message_box").show();
          $("#messageProject").text("Annulla");
        }else{
          $("#container_message_box").hide();
          $("#message_box").hide();

          $("#messageProject").text("Messaggio");
        
        }

      });
      $(document).on("click", "#save_info_proj", function(){
          saveSettingProj(<?php echo "$idproj"; ?>);
      });

      $(document).on("click", "#bttSendMsg", function(){
        var msg = $("#message_box textarea").val();
        if(msg != ""){
          var p = '<?php echo "$idproj"; ?>';
          
          $("#message_box textarea").val("");
          sendMessage(p, msg);
          $("#messageProject").text("Messaggio");
        }else{
          $("#message_box textarea").focus();
        }
      });
      
      $(document).on("click","#closeSendUpadte", function(){
        $("#overlay_UpdatePost").slideUp();
        
      });
      $(document).on("click", "#bttSendUpdate", function(){
        var pj = <?php echo "$idproj"; ?>;
        var msg = $("#txtUpdt").val();
        var ttl = $("#ttlPost").val();
        sendUpdate(pj, msg, ttl);
      });

      $(document).on("click", "#bttSendComment", function(){
        var msg = $("#comment_box textarea").val();
          if(msg != ""){
          var pj = <?php echo "$idproj"; ?>;
          
          $("#comment_box textarea").val("");
          sendComment(pj, msg);
          $("#messagePerson").text("Commenta");
        }else{
          $("#comment_box textarea").focus();
        }
      });

      $(document).on("click", "#collabPerson", function(){
        clickCollab(<?php echo "$idproj";?>);
      });


     

      $(document).on("click", "#shareOnSatusia", function(){
        shareOnSatusia(<?php echo "$idproj"; ?>);
      });

  		var previousScroll = 0;

  		 $(window).scroll(function(event) {
  			var currentScroll = $(this).scrollTop();
  	       if (currentScroll > (previousScroll+40)){
  	            previousScroll = currentScroll;
  	            $("#sub_header").slideUp("fast", function(){});
  	       } else if(currentScroll < (previousScroll-20)) {
  	           previousScroll = currentScroll;
  	           $("#sub_header").slideDown( "fast", function(){});
  	       }
  	      
  		 });                     
  		 $(document).on('click', "#contentUpdates", function(){
          controlEdit();
  		 	 $('html, body').animate({
  		        scrollTop: $("#updateProject").offset().top
  		    }, 800);

  		 });
       $(document).on('click', "#contentComments", function(){
         controlEdit();
         $('html, body').animate({
              scrollTop: $("#commentsProject").offset().top
          }, 800);
       });
       $(document).on('click', "#contentOverview", function(){
         controlEdit();
         $('html, body').animate({
              scrollTop: $("#overviewProject").offset().top
          }, 800);
       });
  		 $(document).on('click', "#contentProjects", function(){
  		 	 controlEdit();
         $('html, body').animate({
  		        scrollTop: $("#container_show_projects").offset().top - 150
  		    }, 800);
  		 });
       $(document).on('click', "#editProjects", function(){
         $('html, body').animate({
              scrollTop: 0
          }, 800);
         $("#edit_info_project").slideDown();
       });
       
  		 $(document).on('click', "#contentPeople", function(){
  		 	controlEdit();
        $('html, body').animate({
  		        scrollTop: $("#container_people_panel").offset().top - 150
  		    }, 800);
  		 });
  		
      function controlEdit(){
         if($("#edit_info_project").css("display") == "block"){
           $("#edit_info_project").hide();
         }
      }

      $("#wallpaperProject").click(function(){
          var a = $(this).height();
          if(a < 300){
            $(this).height($("#wallpaperProject img").height());
          }else{
            $(this).height("250");
          }
        });

      getAllInfoProject(<?php echo "$idproj"; ?>);
      $("#moreFll").click(function(){
        getFllw(<?php echo "$idproj"; ?>);
      });
      $("#morePst").click(function(){
        getPost(<?php echo "$idproj"; ?>);
      });
     <?php
       if($isCollab || $isFounder){
    ?>
          getLastMessageProj(<?php echo "$idproj"; ?>);
    <?php
       }

       if(isset($_GET['comment'])){ ?>
          setTimeout(function(){ 
           $("#contentComments").click();
          }, 400); 
     <?php  }else if(isset($_GET['post'])){
          echo "$('#contentUpdates').click();";
       }
  } //fine esiste
    ?>
  	});
  </script>

  </head>
  <body>
  <?php
    if($esiste){
  ?>
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
        name: '<?php echo $title;?>',
        link: 'https://satusia.com/project.php?proj=<?php echo $idproj; ?>',
        caption: 'Progetto su Satusia - <?php echo $asc['categoria']; ?>',
        picture: 'https://satusia.com/icon/satusia_icon.png'
      }, function(response){});
    }
  </script>
  <?php
  }
  ?>
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

    <div id="overlay_UpdatePost" class="overlay_black">

      <div id="divUpdatePost" class="noAnimationClass">
      <div id="confirmedPost" class="confirmImg" style="background-image: url(icon/correct.png)"></div>
      <div class="contentPopup">
          <div class="headerPopup"><span>Cosa vuoi dire ai tuoi seguaci?</span><span class="close_popup" id="closeSendUpadte">Chiudi</span></div>
          <input type="text" id="ttlPost" placeholder="Titolo" />
          <textarea id="txtUpdt"></textarea>
          <button class="btt_submit" id="bttSendUpdate">Invia</button>
        </div>
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
                  <li><div class="imgFastResult"></div><div class="descr_FastResult"></div></li>  
                  
                  
                </ul>
                <div class="titleFastResult">Progetti</div>
                <ul id="ul_fastResult_proj" class="ul_fastResult">
                  <li><div class="imgFastResult"></div><div class="descr_FastResult"></div></li>  
                  
                  
                  
                </ul>
              </div>
              <!-- END FastSearch -->


            </div>
  				
  					<div id="menu">
  				    <div id="lightbulbNotif"  class="opt_menu iconHeader">
              <div id="general_n_notif" class="notifNumber">0</div>
               <ul id="ul_notif" class="scrollNotif">
                <div class="menuarrow notif_arrow"></div>
                <li class="minititle minititleTop">Notifiche</li>
                <div class="scrollableMain mCustomScrollbar" data-mcs-theme="dark">
                  
                  
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
                        <li id="minititle_proj" class="minititle minititleTop">Messagi progetti </li>
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
  				<div id="contentOverview" class="sub_header_item selectedNav">Informazioni</div>
  				<div id="contentUpdates" class="sub_header_item ">Aggiornamenti</div>
          <div id="contentComments" class="sub_header_item ">Commenti</div>
          <div id="contentPeople" class="sub_header_item ">Persone</div>
    <?php /*      <div id="contentProjects" class="sub_header_item ">Simili</div>*/
    
    if($isFounder){  ?>
          <div id="editProjects" class="sub_header_item ">Modifica progetto</div>
    <?php
    } ?>
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

  					<a href="<?php echo "profile.php?id=".$asc['iduser'] ?>"><div id="profile_img" <?php echo "style='background-image: url( ".$asc['immagine_profilo'].")'>"; ?></div></a>
  					<div id="person_name"><a href="<?php echo "profile.php?id=".$asc['iduser'] ?> "><?php echo $asc['Nome']." ".$asc['Cognome'];?></a></div>
  					<div id="job" class="generalInfoPerson"> </div>
  					<div id="locate" class="generalInfoPerson"><div id="houseIcon" class="icon_info"></div> <span><?php echo $asc['citta']; ?></span></div>
  					<div id="short-description" class="generalInfoPerson"><?php echo $asc['frase_personale']; ?></div>
            <?php
              if(!$isFounder){
                if(!$isCollab && $view != "-1"){      

                    if($view  != null && $view == 0){  ?>

                      <div id="collabPerson"> Annulla</div>

            <?php   }else{   // view == 2 o 3  (annullato o abbandonato precedentemente) ?>

    			     	      <div id="collabPerson">Collabora</div>
            <?php  
                  }
            

                }else if($isCollab){   //view == 1  ?>

                    <div id="collabPerson">Abbandona</div>

          <?php }

              if(!$isCollab){
                if($isFollow){
                  echo "<div id='followPerson' class='unfollow'>Unfollow</div>";
                }else{
                  echo "<div id='followPerson'>Follow</div>";
                }
              }
              ?>
          	  
  					<?php
              }
              if($isFounder){
                 echo "<div id='updateButton'>Post</div>";
              }
              if($isFounder || $isCollab){
            ?>
                 <div id="megabox_message">
                  <div id="messageProject">Messaggio</div>
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
          <?php    }
            ?>
  					<div id="megabox_comment">
              <div id="messagePerson">Commenta</div>
              <div id="container_comment_box">
                <div id="comment_box"class="noAnimationClass">
                  <div id="confirmedCom" class="confirmImg" style="background-image: url(icon/correct.png)"></div>
                  <div class="contentPopup">
                    <textarea></textarea>
                    <button class="btt_submit" id="bttSendComment">Invia</button>
                  </div>
                </div>
              </div>
            </div>

  					<div id="nContainerBox">
  						
  						<div class="infoBoxItem" id="nCollabBox">
  							<div class="descriptionLine">Collaboratori</div>
  							<div class="nLine"></div>
  						</div>
  						<div class="infoBoxItem" id="nFollowerBox">
  							<div class="descriptionLine">Followers</div>
  							<div class="nLine"></div>
  						</div>
  					</div>
            <div id="otherInfo_profile">
               <div id="dateSignUp" class="generalInfoPerson"><div id="dateSignUpIcon" class="icon_info"></div>Creato:<span><?php echo "$create_date";?></span></div>
            
            </div>
      <?php
      /*
  					<div id="mainLinkContainer">
  						<div class="mainLinkElement">
  							<div class="mainLinkIcon icon_info"></div>
  							<div class="mainLinkDescription"><a href="https://twitter.com/rickydibe">Twitter</a></div>
  						</div>
  						<div class="mainLinkElement">
  							<div class="mainLinkIcon icon_info"></div>
  							<div class="mainLinkDescription"><a href="https://www.facebook.com/riccardodb">Facebook</a></div>
  						</div>
  					</div>
  */ ?>
          <div id="shareContainer">
              <span>Condividi</span>
                <div class="mainLinkElement">
                  <?php
                 echo "<a target='_blank' href='https://twitter.com/intent/tweet?original_referer=https%3A%2F%2Fwww.satusia.com/project.php?proj=".$idproj."&text=".$title."&tw_p=tweetbutton&url=https%3A%2F%2Fwww.satusia.com/project.php?proj=".$idproj."&via=satusia_tweet'>";
                ?>
                  <div class="mainLinkIcon icon_info twitter_icon"></div></a>
                   <div class="mainLinkIcon icon_info facebook_icon"></div>
                   <div id="shareOnSatusia" class="mainLinkIcon icon_info satusia_icon"></div>
              </div>
            </div>

  				</div>

  				</div>
  				<div id="section_container">
  					<div id="center_container" >

   <?php
    if($isFounder){  ?>
              <div id="edit_info_project" class="page_main_panel">
                 <div class="block_setting" id="project_setting_block">
                  <div class="icon_setting"></div><div class="title_setting">Modifica informazioni progetto</div>
                  <ul class="ul_setting_central">
                    <li class="setting_li">
                      <div class="div_setting">
                        <ul class="ul_setting_element">
                          <li><div class="contenuto_li"><div class="label_setting">Titolo:</div><input type="text" id="title_setting" value="<?php echo $asc['title']; ?>" /></div></li>
                           <li><div class="contenuto_li"><div class="label_setting">Categoria:</div><select id="categ_setting">
                                                                                                   <?php echo "<option value='".$asc['categoria']."'>".$asc['categoria']."</option>";  ?>
                                                                                                     <option value="informatica">Informatica & Tecnologia</option>
                                                                                                      <option value="scienze">Scienze</option>
                                                                                                      <option value="arte">Arte</option>
                                                                                                      <option value="musica">Musica</option>
                                                                                                      <option value="moda">Moda</option>
                                                                                                      <option value="giornalismo">Giornalismo</option>
                                                                                                      <option value="culinaria">Culinaria</option>
                                                                                                      <option value="noprofit">No-Profit</option>
                                                                                                  </select></div></li>
                          <li><div class="contenuto_li" id="li_descr_setting"><div class="label_setting">Descrizione:</div><textarea id="descr_setting"><?php echo "$descr_setting"; ?></textarea></div></li>
                        </ul>
                      </div>
                      <button id="save_info_proj" class="button_setting">Salva</button>
                    </li>
                    
                  </ul>
                </div>
              </div>
  <?php  }  ?>
              <div id="overviewProject" class="page_main_panel">
                <div id="header_background_user_info" class="header_panel">
                  <span><?php echo $asc['title']; ?></span>
                  
                </div>
                <div id="container_background_user_info" class="container_panel">
                  <div id="wallpaperProject">
                    <img <?php echo "src=\"".$asc['img']."\" "; ?> id="wallpaperImg" />
                    <?php
                      if($isFounder){
                    ?>
                    <button id="carica">Conferma</button>
                    <div id="changePhotoProject"><input type="file" id="changePhotoProjectInput" accept="image/*"/></div>
                    <?php
                      }
                    ?>
                  </div>
                  
                  <div id="tag_project" class="item_background">
                    <div id="header_category" class="header_background_item">
                      <div id="category_icon" class="icon_background"></div><span id="category"><?php echo $asc['categoria']; ?></span>
                    </div>
                    
                  </div>

                  <div id="biography_user" class="item_background">
                    <div id="header_biography" class="header_background_item">

                      <div id="biography_icon" class="icon_background"></div><span>Descrizione</span>
                    </div>
                    <div id="container_biography" class="container_background_item">
                    <?php echo "$descr"; ?>
                    </div>
                  </div>

                  	</div>

  						</div>
              


              <div id="updateProject" class="page_main_panel">
                <div id="header_people_panel" class="header_panel">
                  <span>Updates</span>
                  
                </div>

                <div id="container_update_panel" class="container_panel">
                  
                  <ul id="updates_ul">
                      
                  </ul>

                   </div>
                  <div id='morePst' class='shwMoreThing'>Mostra altri</div>
                    <input type="hidden" id="b_pst" value="0" />
                 

                

              </div>

              <div id="commentsProject" class="page_main_panel">
                <div id="header_people_panel" class="header_panel">
                  <span>Commenti</span>
                  
                </div>

                <div id="container_comments_panel" class="container_panel">
                  <input type="hidden" id="cmm_n_shwn" value="0" />
                  <ul id="comments_ul">
                      
                  </ul>

                   </div>
                  <div id="moreComment" class='shwMoreThing'>Mostra Altri</div>
                 

                

              </div>
  <!--
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
  										<ul>
  											<li class="first_people collab_person"></li>
  											<li class="second_people collab_person"></li>
  											<li class="more_people collab_person"><span> +53</span></li>

  										<ul>
  									</div>
  								</div>


  								<div id="follow_people_panel" class="item_background">
  									<div id="header_follow_people" class="header_background_item">
  										<div id="follow_people_icon" class="icon_background"></div><span>Follower</span>
  									</div>
  									<div id="container_follow_people" class="container_background_item">
  										<ul>
  											<li class="first_people follower_person"></li>
  											<li class="second_people follower_person"></li>
  											<li class="more_people follower_person"><span> +123</span></li>

  										<ul>
  									</div>
  								</div>
  							</div>

  						</div>-->
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
                    <div id='moreFll'  class='shwMoreThing'>Mostra altri</div>
                    <input type="hidden" id="b_fll" value="0" />
                  </div>
                </div>

              </div>
  						
      <?php 

           /*   <div id="show_projects_user" class="page_main_panel">
                <div id="header_show_projects" class="header_panel">
                  <span>Progetti simili</span>
                  <div id="arrow_left" class="arrow_project"></div><div id="arrow_right" class="arrow_project"></div>
                </div>
                <div id="container_show_projects" class="container_panel">
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
                    
                  </ul>
                </div>
              </div> */
              ?>

  					</div>

            <?php
            $txtMsg = "";
            if($isFounder || $isCollab){
              $txtMsg = "class='containerRight'";
            }

            ?>
  					<div id="right_container" <?php echo "$txtMsg"; ?> >

  <?php
            if($isCollab || $isFounder){
  ?>
              <div id="header_msg_proj" class="header_panel"><span>Messaggi progetto</span></div>
              <ul>
              <div class="scrollableMain mCustomScrollbar" data-mcs-theme="dark">
                <div id="containerMsg">
                
              </div>
              </div>

              <a href='message.php?proj=<?php echo $idproj; ?>'><li id="viewAllMsgProj" class="viewAllNotif">Mostra Tutti</li></a>
              </ul>
  <?php
            }
  ?>
            </div>
  				</div>
  			</div>
  			
  		</div>
  	</div>
  <?php
  }else{  //fine esiste
    include 'not_found.php';
  }
  ?>
  </body>
  </html>


  <?php

  }
mysqli_close($connessione);
}else{
  header("location: index.php");
}
?>