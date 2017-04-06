<?php
session_start();
if(isset($_SESSION['id'])){
  $id = $_SESSION['id'];


 
    include 'connect.php';
    $selMyInfo = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = $id");
    $ascMyInfo = mysqli_fetch_assoc($selMyInfo);
    $dataNasc = $ascMyInfo['Data_Nascita'];
    $lingue_str = $ascMyInfo['lingue'];
    $uni_str = $ascMyInfo['universita'];
    $biografia = $ascMyInfo['biografia'];
    $hasPsw = $ascMyInfo['gotPsw'];
   
    $biografia = str_replace("<br />", "&#13;", $biografia);
    $lingue_str = str_replace("[", "", $lingue_str);
    $lingue_str = str_replace("]", "", $lingue_str);
    
    $lingue_arr = explode(",", $lingue_str);

    $uni_str = str_replace("[", "", $uni_str);
    $uni_str = str_replace("]", "", $uni_str);
    
    $uni_arr = explode(",", $uni_str);
    $frase_pers = $ascMyInfo['frase_personale'];
    $sel_social = mysqli_query($connessione, "SELECT * FROM social_user WHERE id_user = $id");
    $n_social = mysqli_num_rows($sel_social);
    $fb = "";
    $twt = "";
    $sito_pers = "";
    if($n_social > 0){
      $asc_social = mysqli_fetch_assoc($sel_social);
      $fb = $asc_social['facebook'];
      $twt = $asc_social['twitter'];
      $sito_pers = $asc_social['personal'];
    }

  ?>



  <html>
  <head>
    <meta charset="UTF-8">
    <title>Satusia - Impostazioni</title>
    <link rel="icon" href="icon/favicon.ico" />
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
    
    <link rel="stylesheet" href="css/style_general.css" />

    <link rel="stylesheet" href="css/style_settings.css" />
    
     <link rel="stylesheet" href="css/animate.css">
  <style type="text/css">





  #container {
      margin-top: 70px !important;
  }
  #settingContainer li:hover {
      background-color: rgba(55, 190, 215, 0.15);
  }
  #settingContainer li {
      height: 40px;
      position: relative;
      width: calc(100% - 60px);
      line-height: 40px;
      padding-left: 60px;
      font-size: 16px;
      color: rgb(100,100,100);
      cursor: pointer;
  }
  .iconSetting {
      height: 20px;
      width: 20px;
      position: absolute;
      background-size: cover;
      background-image: url(icon/link_colored.png);
      left: 30;
      top: 10;
  }
  ul#settingContainer {
      list-style-type: none;
      margin: 0;
      padding: 0;
  }

  #account_block{
    display: none;
  }
  li.absolute_li_setting {
      position: relative;
  }
  .absolute_li_setting .label_setting {
      position: absolute;
  }
  #lingue_div ul, #uni_div ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
  }
  textarea#biografia_setting {
      margin-left: 130px;
      width: 350px;
      min-height: 250px;
  }
  textarea#frase_pers_setting{
    margin-left: 130px;
    width: 350px;
    min-height: 80px;
  }
  #lingue_div li, #uni_div li {
      padding-left: 0px;
  }
  #lingue_div, #uni_div {
      margin-left: 130px;
  }
  button#addLingua, #addUni {
      padding: 5px 25px;
      color: #fff;
      border: 0px;
      background-color: #37BED7;
      font-weight: bold;
      border-radius: 100px;
  }
  .pre_site {
      display: inline-block;
      font-size: 14px;
      margin-right: 5px;
      color: rgb(100,100,100);
  }
  .input_text_post_div {
      border: none !important;
  }
  .error_limit{
    border: 1px solid rgb(150,50,50) !important;
    /* background-color: rgba(200,100,100,1); */
    color: rgb(200,50,50) !important;
  }
  div#limit_f_p {
    position: absolute;
    top: 40px;
    font-size: 12px;
    color: rgb(150,150,150);
  }
  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>

  <script src="js/general_script.js"></script>
  <script src="js/notif.js"></script>

  <script type="text/javascript">
  		$(document).ready(function(){

        $(document).on("click","#save_info_pers", function(){
            var name = $("#nome_setting").val();
            var surname = $("#cognome_setting").val();
            var job = $("#job_setting").val();
            var city = $("#city_setting").val();
            var dateNasc = $("#dateNasc_setting").val();
            var sex = $("#sex_setting").val();
            var scuola_sup = $("#scuolaSuperiore_setting").val();
            var biografia = $("#biografia_setting").val();
            var li_lingue = $("#lingue_div ul").children();
            var n_lingue = li_lingue.length;
            var array_lingue = new Array();
            var this_li_lingue = "";
            for(var x = 0; x < n_lingue; x++){
              this_li_lingue = li_lingue[x].children[0];
              array_lingue[x] = this_li_lingue.value;
             // console.log(array_lingue[x]);
            }
            var li_uni = $("#uni_div ul").children();
            var n_uni = li_uni.length;
            var array_uni = new Array();
            var this_li_uni = "";
            for(var x = 0; x < n_uni; x++){
              this_li_uni = li_uni[x].children[0];
              array_uni[x] = this_li_uni.value;
             // console.log(array_uni[x]);
            }
            var facebook = $("#facebook_setting").val();
            var twitter = $("#twitter_setting").val();
            var sito_personal = $("#sito_p").val();
            var frase_pers = $("#frase_pers_setting").val();
            if(frase_pers.length <= 50){
                var src = $.ajax({
                        
                        url: "api/mng_settings.php?type=info_pers",
                        method: "POST",
                        data: { nome: name, cognome: surname, professione: job, citta: city, dateNasc: dateNasc, sex: sex, scuola_sup: scuola_sup, biografia: biografia, lingue: array_lingue, universita: array_uni, site_web: sito_personal, twitter:twitter, facebook: facebook, frase_pers: frase_pers},
                        dataType: "json"
                        });
                src.done(function(resp)
                        {
                          
                          if(resp.status == "ok"){
                            showNotificationPopup("<?php echo $ascMyInfo['immagine_profilo']; ?>", "", "Informazioni personali aggiornate");
                            
                          }else{
                            showNotificationPopup("<?php echo $ascMyInfo['immagine_profilo']; ?>", "Errore!", "Riprova più tardi.");
                          }
                          
                         
                         
                        });
                
              src.fail(function(jqXHR, textStatus) {
                     console.log("errore");
                     
                  });    
            }

        });
        
  		
      $("#addLingua").click(function(){
        $("#lingue_div ul").append("<li><input type='text' class='lingue_setting' value='' /></li>");

      });
      $("#addUni").click(function(){
        $("#uni_div ul").append("<li><input type='text' class='uni_setting' value='' /></li>");

      });
      
      $(document).on("click", "#save_info_account", function(){
            var email = $("#email_setting").val();
            var now_pssw = $("#now_password").val();
            var new_pssw = $("#new_password").val();
            var renew_pssw = $("#renew_password").val();

            if(new_pssw == renew_pssw){
                      var src = $.ajax({
                              
                              url: "api/mng_settings.php?type=info_account",
                              method: "POST",
                              data: { email: email, new_pssw:new_pssw, now_pssw:now_pssw},
                              dataType: "json"
                              });
                      src.done(function(resp)
                              {
                                console.log(resp);
                                if(resp.status == "ok"){
                                  showNotificationPopup("<?php echo $ascMyInfo['immagine_profilo']; ?>", "", "Informazioni account aggiornate");
                                  if(resp.up_psw){
                                    $("#now_psw").show();
                                  }
                                }else{
                                  showNotificationPopup("<?php echo $ascMyInfo['immagine_profilo']; ?>", "Errore!", "Riprova più tardi.");
                                }
                               
                              });
                      
                    src.fail(function(jqXHR, textStatus) {
                           console.log("Errore.");
                        });    

               
            }else{
               $("#renew_password").focus();
            }
        });
        
        $(document).on("click", "#li_prof_set", function(){
            displayBlock("info_pers_block");
        });
        $(document).on("click", "#li_account_set", function(){
            displayBlock("account_block");
        });
          
      
      <?php
      if(!isset($_GET['type']) || $_GET['type'] == "info_pers"){
        echo "displayBlock('info_pers_block');";
      }else if($_GET['type'] == "account"){
        echo "displayBlock('account_block');";
      }else{
         echo "displayBlock('info_pers_block');"; 
      }

      ?>
    
  	});

  function displayBlock(block){
    $(".block_setting").hide();
    $("#"+block).show();

  }
  function ContaCaratteri(){
        var l = $("#frase_pers_setting").val().length;
        if(l > 50){
            $("#frase_pers_setting").addClass("error_limit");
        }else{
          $("#frase_pers_setting").removeClass("error_limit");
        }

      }
  </script>

  </head>
  <body>


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
                <li class="minititle minititleTop">Notifiche </li>
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
      <!--
  		<div id="sub_header">
  			<div id="main_subheader" class="main">
  				<div id="contentOverview" class="sub_header_item selectedNav">Overview</div>
  				<div id="contentUpdates" class="sub_header_item ">Updates</div>
          <div id="contentComments" class="sub_header_item ">Commenti</div>
          <div id="contentPeople" class="sub_header_item ">People</div>
          <div id="contentProjects" class="sub_header_item ">Simili</div>
  				<div id="swiper" style="left: -6px; width: 74px;"></div>
  			</div>
  		</div>-->
  	</header>
  	<div id="container">
  		<div class="main" id="sub_container">
  		  	<div id="left_container" class="page_main_panel">
  				
  					<ul id="settingContainer">
  					  <li id="li_prof_set"><div class="iconSetting"></div>Profilo</li>
              <li id="li_account_set"><div class="iconSetting"></div>Account</li>
              <!--<li><div class="iconSetting"></div>Principali</li>-->
            </ul>
  					

  				</div>
  				<div id="section_container">
  					<div id="center_container" >

              <div id="container_central_setting" class="page_main_panel">
                <div class="block_setting" id="info_pers_block">
                  <div class="icon_setting"></div><div class="title_setting">Profilo</div>
                  <ul class="ul_setting_central">
                    <li class="setting_li">
                      <div class="div_setting">
                        <ul class="ul_setting_element">
                          <li><div class="contenuto_li"><div class="label_setting">Nome:</div><input type="text" id="nome_setting" value="<?php echo $ascMyInfo['Nome']; ?>" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Cognome:</div><input type="text" id="cognome_setting" value="<?php echo $ascMyInfo['Cognome']; ?>" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Sesso:</div><select id="sex_setting">
                                                                                                   <?php echo "<option value='".$ascMyInfo['sesso']."'>".$ascMyInfo['sesso']."</option>";  ?>
                                                                                                    <option value="Uomo">Uomo</option>
                                                                                                    <option value="Donna">Donna</option>
                                                                                                  </select></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Professione:</div><input type="text" id="job_setting" value="<?php echo $ascMyInfo['professione']; ?>" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Dove vivi:</div><input type="text" id="city_setting" value="<?php echo $ascMyInfo['citta']; ?>" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Data di nascita:</div><input type="date" id="dateNasc_setting" value="<?php echo $ascMyInfo['Data_Nascita']; ?>" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Scuola superiore:</div><input type="text" id="scuolaSuperiore_setting" value="<?php echo $ascMyInfo['scuola_superiore']; ?>" /></div></li>
                          <li class="absolute_li_setting"><div class="contenuto_li"><div class="label_setting">Università:</div>
                          <div id="uni_div">
                            <button id="addUni">Aggiungi</button>
                            <ul>
                              <?php 
                                for($j = 0; $j < count($uni_arr); $j++){
                              ?>
                                  <li><input type="text" class="uni_setting" value="<?php echo $uni_arr[$j]; ?>" /></li>
                              <?php
                              }
                              ?>
                            </ul>
                          </div></div></li>
                          <li class="absolute_li_setting"><div class="contenuto_li"><div class="label_setting">Frase personale:</div><div id='limit_f_p'>max: 50 caratteri</div><textarea id="frase_pers_setting" onKeyUp="ContaCaratteri()"><?php echo $frase_pers; ?></textarea></div></li>
                          <li class="absolute_li_setting"><div class="contenuto_li"><div class="label_setting">Biografia:</div><textarea id="biografia_setting"><?php echo $biografia; ?></textarea></div></li>
                          <li class="absolute_li_setting"><div class="contenuto_li"><div class="label_setting">Lingue parlate:</div>
                          <div id="lingue_div">
                            <button id="addLingua">Aggiungi</button>
                            <ul>
                              <?php 
                                for($j = 0; $j < count($lingue_arr); $j++){
                              ?>
                                  <li><input type="text" class="lingue_setting" value="<?php echo $lingue_arr[$j]; ?>" /></li>
                              <?php
                              }
                              ?>
                            </ul>
                          </div></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Facebook:</div><div class="pre_site">https://facebook.com/</div><input type="text" id="facebook_setting" class='input_text_post_div' value="<?php echo $fb; ?>" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Twitter:</div><div class="pre_site">https://twitter.com/</div><input type="text" id="twitter_setting" class='input_text_post_div' value="<?php echo $twt; ?>" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Sito personale:</div><input type="text" id="sito_p" value="<?php echo $sito_pers; ?>" /></div></li>
                          
                          
                          
                        </ul>
                      </div>
                      <button id="save_info_pers" class="button_setting">Salva</button>
                    </li>
                    
                  </ul>
                </div>

                <div class="block_setting" id="account_block">
                  <div class="icon_setting"></div><div class="title_setting">Account</div>
                  <ul class="ul_setting_central">
                    <li class="setting_li">
                      <div class="div_setting">
                        <ul class="ul_setting_element">
                          <li><div class="contenuto_li"><div class="label_setting">Email:</div><input type="text" id="email_setting" value="<?php echo $ascMyInfo['email']; ?>" /></div></li>
                          <?php
                          $cl_p = "";
                          if($hasPsw == 0){
                            $cl_p = "style='display: none;'";
                          }
                          ?>
                          <li id="now_psw" <?php echo $cl_p;?> ><div class="contenuto_li"><div class="label_setting">Password attuale:</div><input type="password" id="now_password" value="" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Nuova Password:</div><input type="password" id="new_password" value="" /></div></li>
                          <li><div class="contenuto_li"><div class="label_setting">Ripeti Password:</div><input type="password" id="renew_password" value="" /></div></li>
                        </ul>
                      </div>
                      <button id="save_info_account" class="button_setting">Salva</button>
                    </li>
                    
                  </ul>
                </div>
              </div>
     
            

  					</div>

  					<div id="right_container">

            </div>
  				</div>
  			</div>
  			
  		</div>
  	</div>
  </body>
  </html>


<?php
  mysqli_close($connessione);
}else{
  header("location: index.php");
}
?>