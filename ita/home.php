<?php
session_start();
if(isset($_SESSION['id'])){
  $idLog = $_SESSION['id'];
 
  include 'connect.php';
  $sel = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = '$idLog' LIMIT 1");
  $n = mysqli_num_rows($sel);
  if($n > 0){
    $asc = mysqli_fetch_assoc($sel);

  }
?>

<html>
<head>
  <meta charset="UTF-8">
  <title>Satusia - Newsfeed</title>
  <link rel="icon" href="icon/favicon.ico" />
  <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
  <link rel="stylesheet" href="css/style_general.css" />
   <link rel="stylesheet" href="css/animate.css">
<style type="text/css">
#no_more_up {
    padding: 10px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    color: rgb(100,100,100);
    font-size: 14px;
}
#container{
  margin-top: 65px!important
}
#sub_header{
  display: none!important;
}
ul#newsfeed_ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}
.img_user_newsfeed{
  width: 50px;
  height: 50px;
  position: absolute;
  left: 5px;
  top: 9px;
  background-size: cover;
  border-radius: 100%;
  border: 3px solid #fff;
}
.newsfeed_post {
  min-height: 70px;
  position: relative;
  padding: 5px;
    padding-bottom: 15px;
}
.content_newsfeed {
  background-color: #DBEFF3;
  height: 90%;
  margin-left: 30px;
  padding-left: 35px;
  padding-top: 10px;
    margin-bottom: 10px;
  min-height: 55px;
}
.header_content_newsfeed{
    color: #3C4A55;
  font-size: 14px;
}
.body_content_newsfeed {
  position: relative;
  min-height: 170px;
  margin-right: 50px;
  margin-top: 10px;
  border-radius: 10px;
  margin-bottom: 10px;
  overflow: hidden;
}
.date_newsfeed{
    position: absolute;
  bottom: 5px;
  left: 5px;
  color: rgb(127, 127, 127);
  font-size: 9px;

}
.comment_user {
    padding: 10px;
  color: rgb(127, 127, 127);
  font-size: 15px;
  max-height: 40px;
  width: calc(100% - 20px);
  /* margin-bottom: 10px; */
  background-color: #fff;
  /* border-radius: 0px 0px 10px 10px; */;
}
.img_project {
  width: 100%;
  height: 300px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  /* border-radius: 5px; */
  /* box-shadow: 0px 0px 3px 1px rgba(255, 255, 255, 1); */
  

}
.date_newsfeed {
  position: absolute;
  bottom: 5px;
  left: 5px;
  color: rgb(127, 127, 127);
  font-size: 9px;
}

.img_user_content_newsfeed {

  width: 150;
  height: 150;
  background-size: cover;
  margin-top: 10px;
  margin-bottom: 10px;
  border-radius: 100%;
  border: 1px solid rgba(189, 199, 203, 1);
  display: inline-block;
  z-index: 9999;
  position: absolute;
  cursor: pointer;
  left: 0;
  transition: all 0.5s;
}
.img_user_content_newsfeed:hover {
  opacity: 0.8;
}  
.before>.img_user_content_newsfeed{
   left: calc(50% - 105px)!important;
}
.before>.info_user_content_newsfeed {
  left: calc(50% - 105px + 75px)!important;
  width: 0px !important;
  padding: 0 !important;
}
ul.ul_info_user_content_newsfeed {
  list-style-type: none;
  line-height: 22px;
  padding: 15;
}
.info_user_content_newsfeed {
  display: inline-block;
  width: 50%;
  /* position: absolute; */
  /* top: 10px; */
  /* line-height: 25px; */
  box-shadow: rgba(10, 10, 10, 0.2) 1px 1px 5px;
  background: rgb(250, 250, 250);
  border-radius: 3px;
  /* margin-bottom: 20px; */
  padding: 4px;
   overflow: hidden;
  font-size: 14px;
  color: rgb(166, 176, 186);
  height: 144px;
  /* left: 75px; */
  position: relative;
  left: 75px;
  top: 10px;
  padding-left: 80px;
  transition: left 0.5s, width 0.7s, padding 0.7s;
}
li.newsfeed_li {
  margin-bottom: 10px;
  border-bottom: 1px solid rgba(200,200,200,0.2);
  margin: 3px;
  padding-top: 10px;
}
.body_with_border{
   border: 3px solid #fff; 
   box-shadow: 0px 0px 2px 1px rgba(150, 150, 150, 0.3); 
}
.newsfeed_post a {
    text-decoration: none;
    color: #3C4A55;
}



.little_panel {
    position: relative;
    /* top: 20; */
    margin-top: 20;
    margin-bottom: 20px;
}
.little_second{
  top: 20;
}
.little_header {
    width: 100%;
    height: 30px;
}
.little_img {
    background-size: cover;
    height: 50px;
    width: 50px;
    border-radius: 100%;
    position: absolute;
    top: -20;
    left: -20;
}
.little_img_li {
    height: 18px;
    width: 18px;
    background-size: cover;
    background-image: url("icon/education_colored.png");
    display: inline-block;
    position: absolute;
    top: 0;
}
.little_text {
    font-size: 12px;
    /* padding: 3px; */
    color: rgb(127, 127, 127);
    display: inline-block;
    height: 18px;
    position: relative;
    line-height: 18px;
    bottom: 3px;
    margin-left: 23px;
    width: 170px;
}
.little_panel a{
  color: rgb(127, 127, 127);
  text-decoration: none;
}
.little_panel li {
    width: 100%;
    margin: 0;
    padding: 5px;
    height: 30px;
    position: relative;
}
.little_panel ul {
    list-style-type: none;
    margin: 0;
    padding-left: 30;
    margin-top: 10px;
}
.little_title_header {
    font-size: 14px;
    color: rgb(150,150,150);
    padding: 10px;
    padding-left: 35px;
}



</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/home.js"></script>
<script src="js/leftbar.js"></script>
<script src="js/general_script.js"></script>
<script src="js/notif.js"></script>
<script type="text/javascript">
    var idProfile = <?php echo "$idLog"; ?>;
    var canUp = false;
    getPjforHome();
    getNewsFeed(null);
		$(document).ready(function(){
      $(window).scroll(function(event) {
  			if($(window).scrollTop() == $(document).height() - $(window).height()) {
          if(canUp){
            getNewsFeed(parseInt($("#b_nf").val()));
          }
       }
		 });                   
	


    $(document).on("click",".img_user_content_newsfeed", function(){
    //  console.log("CLICK");
       var thisParent = $(this).parent();
       if(thisParent.hasClass("before")){
          thisParent.removeClass("before");
       }else{
          thisParent.addClass("before");
       }
    });
	});
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
					<div id="lightbulbNotif" class="opt_menu iconHeader">
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
                      <li class="minititle minititleTop">Messaggi personali</li>
                      <div class="scrollableMessage mCustomScrollbar" data-mcs-theme="dark">
                        
                      </div>
                      
                    </ul>
                </div>
                <div id="projectMessage">
                    <ul class="ulMessage">
                      <div class="menuarrow notif_arrow"></div>
                      <li id="minititle_proj" class="minititle minititleTop">Messaggi progetti</li>
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
                <li class="minititle minititleTop">Richieste collaborazione</li>
                <div class="scrollableMain mCustomScrollbar" data-mcs-theme="dark">
                  
                  
                </div>
                <li class="viewAllNotif">Mostra Tutti</li>
              </ul>
            </div>
					</div>
				</div>
				<div id="right_header" class="header_item">
          <div id="crea" class="opt_menu iconHeader"></div>
				<?php	echo "<div id='myPicture' style='background-image: url(".$asc['immagine_profilo'].")'></div>"; ?>
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
		<div id="sub_header">
			<div id="main_subheader" class="main">
				<div id="contentOverview" class="sub_header_item selectedNav">Overview</div>
				<div id="contentProjects" class="sub_header_item ">Projects</div>
				<div id="contentBackground" class="sub_header_item ">Background</div>
				<div id="contentPeople" class="sub_header_item ">People</div>
				<div id="swiper" style="left: -6px; width: 74px;"></div>
			</div>
		</div>
	</header>
	<div id="container">
		<div class="main" id="sub_container">
			<div id="left_container">
				<div id="profile_view" class="">

					<div class="little_panel page_main_panel">
              <div class="little_header">
                <div class="little_img" style="background-image: url(icon/personal_latest_pj.png)"></div>
                <div class="little_title_header">Ultimi tuoi progetti attivi</div>
              </div>
              <ul id="ul_mypj">
                <li><div class="little_img_li"></div><div class="little_text"></div></li>
                
              </ul>
            </div>

            <div class="little_panel page_main_panel little_second">
              <div class="little_header">
                <div class="little_img" style="background-image: url(icon/web.png);"></div>
                <div class="little_title_header">Ultimi progetti attivi</div>
              </div>
              <ul id="ul_general_pj">
                <li><div class="little_img_li"></div><div class="little_text"></div></li>
                
              </ul>
            </div>


				</div>

				</div>
				<div id="section_container">
          <input type="hidden" id="b_nf" value="50" />
					<div id="center_container" >

						<ul id="newsfeed_ul" class="page_main_panel">
             

              
             


            </ul>
						
					</div>
					<div id="right_container"></div>
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