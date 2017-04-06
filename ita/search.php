<?php
session_start();
$id = $_SESSION['id'];

if(isset($_SESSION['id'])){
 
  include 'connect.php';
  $selMyInfo = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = $id");
  $ascMyInfo = mysqli_fetch_assoc($selMyInfo);


?>
<html>
<head>
  <meta charset="UTF-8">
  <title>Satusia - Ricerca</title>
  <link rel="icon" href="favicon.ico" />
  <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
  <link rel="stylesheet" href="css/style_general.css" />
    
<style type="text/css">









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
#container {
    margin-top: 65px!important;
}
#sub_header{
  display: none!important;
}
.img_search.search_people {
    border-radius: 100%;
    
}
.img_search {
    height: 100;
    width: 100;
    background-size: cover;
    position: absolute;
    top: 10;
    border: 5px solid #fff;
}

ul.ul_list_search {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

ul.ul_info_search {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
.ul_info_search li {
    padding: 0;
    min-height: 10px !important;
    line-height: 22px;
}
.ul_list_search li {
    position: relative;
    padding: 5;
    min-height: 130px;
}
.info_search {
    /* border: 1px solid; */
    width: calc(100% - 135px);
    /* position: absolute; */
    left: 65px;
    top: 5;
    padding-left: 65px;
    margin-left: 65px;
    background-color: rgba(55, 190, 215, 0.15);
    min-height: 120px;
}

.icon_house {
    background-image: url("icon/house.png");
    background-size: 100% 100%;
    opacity: 0.8;
    display: inline-block;
    position: relative;
    top: 4px;
}
li.other_general_info_search {
    color: rgb(127, 127, 127);
    line-height: 13px;
    font-size: 15px;
}
li.name_search {
    font-size: 18px;
}
.search_house{
  font-weight: bold;
}

ul.follow_collab_ul .icon_follower,  ul.follow_collab_ul .icon_collab {
    height: 40px;
    width: 40px;
    left: -10;
}
ul.follow_collab_ul span {
    margin-left: 30px;
    position: relative;
    top: 15px;
    color: rgb(127, 127, 127);
    font-weight: bold;
}
ul.follow_collab_ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    padding-bottom: 10px;
}
.follow_collab_ul li {
    display: inline-block;
    min-width: 80px;
    height: 30px;
}
.founder_project span {
    font-weight: bold;
    font-style: italic;
}
li.founder_project {
    margin-left: 20px;
}
.viewAllSearch {
    height: 30px;
    box-shadow: none;
    line-height: 30px;
    cursor: pointer;
}

#center_search_left_panel {
    color: rgb(100, 100, 100);
    font-size: 15;
    padding: 10px;
    line-height: 30px;
}
#top_search_left_panel {
    padding: 10px;
    font-size: 18px;
    color: rgb(127, 127, 127);
}
#termineRicerca {
    font-weight: bold;
}
.ul_list_search a {
    text-decoration: none;
    color: rgba(110,110,110,1);
}
span.label_src {
    font-size: 13px;
    color: rgb(150,150,150);
}
.div_filter_type{
  padding: 3px;
  padding-left: 10px;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<!--
<link href="https://hayageek.github.io/jQuery-Upload-File/4.0.2/uploadfile.css" rel="stylesheet">

<script src="https://hayageek.github.io/jQuery-Upload-File/4.0.2/jquery.uploadfile.min.js"></script>
-->
<script src="js/general_script.js"></script>
<script src="js/notif.js"></script>
<script type="text/javascript">
<?php
  $src = mysqli_real_escape_string($connessione, $_GET['src']);
?>
  var srcWord = '<?php echo "$src";  ?>';
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
   if(isset($_GET['src'])){ ?>

      src('user', srcWord, false, false);
      src('proj', srcWord, false, false);

 <?php      
    }

  ?>      

  $("#setFilter").click(function(){
      if($("#Filterpeople").is(":checked")){
         $("#b_ppl").val("0");
          src('user', srcWord, true, false);
         
      }
      if($("#Filterproject").is(":checked")){
        $("#b_pj").val("0");
        src("proj",srcWord, true, false);
      }
      
  });
    $("#container_people_search .viewAllSearch").click(function(){
         src('user', srcWord, false, true);
    });
    $("#container_project_search .viewAllSearch").click(function(){
         src('proj', srcWord, false, true);
    });
    $("#Filterproject").click(function(){
      if($(this).is(":checked")){
        $("#categ").prop('disabled', false);
      }else{
        $("#categ").prop('disabled', true);
      }
    });
    $("#Filterpeople").click(function(){
      if($(this).is(":checked")){
        $("#filtroLuogo").prop('disabled', false);
        $("#filtroProfessione").prop('disabled', false);

      }else{
        $("#filtroLuogo").prop('disabled', true);
        $("#filtroProfessione").prop('disabled', true);
      }
    });
  });

  function src(type, src, filter, more){
    var url = "api/srch.php?limit=3"
    var by = "";

    
    if(more){
      if(type == "proj"){
        var suffisso = $("#_pj").val();
      }else{
        var suffisso = $("#_ppl").val();
      }
      
      url += suffisso;
    }else{
      var urlFilter = "";
      if(filter){
        
        if(type == "proj"){
          
          var categFilter = $("#categ").val();
          if(categFilter != "all"){
            urlFilter += "&categ="+categFilter;
          }
        }else{
          var cityFilter = $("#filtroLuogo").val();
          if(cityFilter != ""){
            urlFilter += "&city="+cityFilter;
          }

          var jobFilter = $("#filtroProfessione").val();
          if(jobFilter != ""){
            urlFilter += "&professione="+jobFilter;
          }
        }

      }else{
        urlFilter = "";
      }
      url += urlFilter;
      
      if(type == "proj"){
        $("#_pj").val(urlFilter);
      }else{
        $("#_ppl").val(urlFilter);
      }
    }
    if(type == "proj"){
      by = "&by="+$("#b_pj").val();
    }else{
      by = "&by="+$("#b_ppl").val();
    }
    url += by;
    $("#termineRicerca").text(src);
    var srch = $.ajax({
                  
                  url: url,
                  method: "GET",
                  data: { type: type, src: src},
                  dataType: "json"
                  });
          srch.done(function(resp)
                  {
                    
                    if(resp.status == "logged"){
                      if(type == "user"){
                        var obj = resp.user
                        var target = $("#container_people_search .ul_list_search");
                       
                      }else if(type == "proj"){
                        var obj = resp.proj;
                        var target = $("#container_project_search .ul_list_search");
                        
                      }
                      if(obj != null){
                        var n = obj.length;
                        var html = "";
                        if(n > 0){
                         
                          for(var x = 0; x < n; x++){
                            var img = "";
                            var url = "";
                            if(type == "user"){
                              img = obj[x].immagine_profilo;
                              url = "profile.php?id="+obj[x].id;
                            }else{
                              img = obj[x].img;
                              url = "project.php?proj="+obj[x].idproj;
                            }
                            html += "<li><a href='"+url+"'><div class='img_search search_people' style='background-image: url("+img+")'></div></a><div class='info_search'>";
                            html += "<ul class='ul_info_search'>";
                            
                            if(type == "user"){
                              html += "<li class='name_search'><a href='"+url+"'>"+obj[x].Nome+" "+obj[x].Cognome+"</a></li>";
                              html += "<li class='other_general_info_search search_house'><div class='icon_house icon_info'></div> "+obj[x].citta+"</li>";
                              html += "<li class='other_general_info_search'>"+obj[x].professione+"</li>";
                              html += "<li class='other_general_info_search short_description_search'>Fondatore e programmatore di Cose</li>";
                            }else{
                              html += "<li class='name_search'><a href='"+url+"'>"+obj[x].title+"</a></li>";
                             // html += "<li class='other_general_info_search founder_project'>di <span>Riccardo Di Benedetto</span></li>";
                              html += "<li class='other_general_info_search categ_search'>"+obj[x].categoria+"</li>";
                              html += "<li class='other_general_info_search search_house'><div class='icon_locate icon_info'></div> Milano</li>";
                            }
                            html += "<li><ul class='follow_collab_ul'><li><div class='icon_follower'></div><span class='span_follower_search'>"+obj[x].n_foll+"</span></li><li><div class='icon_collab'></div><span class='span_collab_search'>"+obj[x].n_coll+"</span></li></ul>";
                            html += "</li></ul></div></li>";
                          }
                          if(n < 3){
                             if(type == "user"){
                                $("#container_people_search .viewAllSearch").hide();
                            }else{
                                $("#container_project_search .viewAllSearch").hide();
                            }
                          }else{
                              if(type == "user"){
                                    $("#b_ppl").val(parseInt($("#b_ppl").val()) + 3);
                                   
                              }else{
                                    $("#b_pj").val(parseInt($("#b_pj").val()) + 3);
                              }
                          }
                        }else{
                          html = "<li class='no_result_fastResult'>Nessun Risultato</li>";
                          if(type == "user"){
                              $("#container_people_search .viewAllSearch").hide();
                          }else{
                              $("#container_project_search .viewAllSearch").hide();
                          }
                        }

                       
                      }else{
                          html = "<li class='no_result_fastResult'>Nessun Risultato</li>";
                           if(type == "user"){
                              $("#container_people_search .viewAllSearch").hide();
                          }else{
                              $("#container_project_search .viewAllSearch").hide();
                          }
                      }
                      if(more){
                        target.append(html);
                      }else{
                        target.html(html);  
                      }
                      
                    }
                   
                   
                  });


/*

                        <li>
                            <div class="img_search"></div>
                            <div class="info_search">
                                <ul class="ul_info_search">
                                  <li class="name_search">Jet Spaziale</li>
                                  <li class="other_general_info_search founder_project">di <span>Riccardo Di Benedetto</span></li>
                                  <li class="other_general_info_search categ_search">Elettronica</li>
                                  <li class="other_general_info_search search_house"><div class="icon_locate icon_info"></div> Milano</li>
                                  <li class="other_general_info_search short_description_search">This project is based on the page layout that Tokyopop created for the German edition and ...</li>
                                  <li>
                                     <ul class="follow_collab_ul">
                                        <li><div class="icon_follower"></div><span class="span_follower_search">80</span></li>
                                        <li><div class="icon_collab"></div><span class="span_collab_search">30</span></li>
                                    </ul>
                                  </li>
                                </ul>
                            </div>
                        </li>


                        <li>
                            <div class="img_search search_people"></div>
                            <div class="info_search">
                                <ul class="ul_info_search">
                                  <li class="name_search">Mario Rossi</li>
                                  <li class="other_general_info_search search_house"><div class="icon_house icon_info"></div> Milano</li>
                                  <li class="other_general_info_search">Studente</li>
                                  <li class="other_general_info_search short_description_search">Fondatore e programmatore di Cose</li>
                                  <li>
                                     <ul class="follow_collab_ul">
                                        <li><div class="icon_follower"></div><span class="span_follower_search">80</span></li>
                                        <li><div class="icon_collab"></div><span class="span_collab_search">30</span></li>
                                    </ul>
                                  </li>
                                </ul>
                            </div>
                        </li>


*/
          
        srch.fail(function(jqXHR, textStatus) {
               console.log("Login Fallito.");
            }); 
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
                
                
              </ul>
              <div class="titleFastResult">Progetti</div>
              <ul id="ul_fastResult_proj" class="ul_fastResult">
                
                
              </ul>
            </div>
            <!-- END FastSearch -->


          </div>
        
          <div id="menu">
            <div id="lightbulbNotif"  class="opt_menu iconHeader">
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
    <div id="sub_header">
      <div id="main_subheader" class="main">
        <div id="contentOverview" class="sub_header_item selectedNav">Overview</div>
        <div id="contentUpdates" class="sub_header_item ">Updates</div>
        <div id="contentComments" class="sub_header_item ">Commenti</div>
        <div id="contentPeople" class="sub_header_item ">People</div>
        <div id="contentProjects" class="sub_header_item ">Simili</div>
        <div id="swiper" style="left: -6px; width: 74px;"></div>
      </div>
    </div>
	</header>
	<div id="container">
		<div class="main" id="sub_container">
			<div id="left_container">
				<div id="search_left_panel" class="page_main_panel">
          <div id="top_search_left_panel">
             <span id="headerSearch"> Ricerca per: <span id="termineRicerca"> </span></span>
              
          </div>
          <div id="center_search_left_panel">
          <form>
                <span>Filtra risultati per:</span><br />
                <input type="checkbox" id="Filterpeople" checked="checked" value="people">Persone
                <br>
                <input type="checkbox" id="Filterproject" checked="checked" value="project">Progetti
                <br>
                <span class='label_src'>Persone</span>
                <div class='div_filter_type'>
                  <input type="text" id="filtroLuogo" placeholder="Luogo" />
                  <br>
                  <input type="text" id="filtroProfessione" placeholder="Professione" />
                </div>
               <!-- <span>Età minima: </span>
                <select id="eta">
                  <option value="16" selected="selected">16</option>
                  <option value="17">17</option>
                  <option value="18">18</option>
                  <option value="19">19</option>
                  <option value="20">20</option>
                  <option value="20">21</option>
                  <option value="20">22</option>
                  <option value="20">23</option>
                  <option value="20">24</option>
                  <option value="20">25</option>
                  <option value="20">26</option>
                  <option value="20">27</option>
                  <option value="20">28</option>
                  <option value="20">29</option>
                  <option value="20">30</option>
                  <option value="20">31</option>
                  <option value="20">32</option>
                  <option value="20">33</option>
                  <option value="20">34</option>
                  <option value="20">35</option>
                  <option value="20">36</option>
                  <option value="20">37</option>
                  <option value="20">38</option>
                  <option value="20">39</option>
                  <option value="20">40</option>
               </select>
                <br>-->
                <span class='label_src'>Progetti</span>
                <div class='div_filter_type'>
                  <span>Categoria: </span>
                  <select id="categ">
                    <option value="informatica">Informatica & Tecnologia</option>
                    <option value="scienze">Scienze</option>
                    <option value="arte">Arte</option>
                    <option value="musica">Musica</option>
                    <option value="moda">Moda</option>
                    <option value="giornalismo">Giornalismo</option>
                    <option value="culinaria">Culinaria</option>
                    <option value="noprofit">No-Profit</option>

                  </select>
                </div>
              </form>
              <button id="setFilter">Applica filtri</button>
          </div>
				</div>

				</div>
				<div id="section_container">
					<div id="center_container" >

						
					   <div id="peopleSearch" class="page_main_panel">
                <div id="header_people_search" class="header_panel">
                    <span>Persone</span>
                </div>
                <div id="container_people_search" class="container_panel">
                    <ul class="ul_list_search">
                        


                    </ul>
                    <div class="viewAllSearch">Mostra tutti</div>
                    <input type="hidden" id='b_ppl' value="0" />
                    <input type="hidden" id='_ppl' value="" />
                </div>
            </div>


             <div id="projectSearch" class="page_main_panel">
                <div id="header_project_search" class="header_panel">
                    <span>Progetti</span>
                </div>
                <div id="container_project_search" class="container_panel">
                    <ul class="ul_list_search">
                        

                          

                    </ul>

                    <div class="viewAllSearch">Mostra tutti</div>
                    <input type="hidden" id='b_pj' value="0" />
                    <input type="hidden" id="_pj" value ="" />
                </div>
            </div>


						
						
					</div>
					<div id="right_container"></div>
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