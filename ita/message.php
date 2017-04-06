<?php
session_start();
if(isset($_SESSION['id'])){
$id = $_SESSION['id'];


 
  include 'connect.php';
  $selMyInfo = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = $id");
  $ascMyInfo = mysqli_fetch_assoc($selMyInfo);
  $myImgProfilo = $ascMyInfo['immagine_profilo'];
$show = "user";
$proj = null;
$user = null;
if(isset($_GET['show'])){
  $show = mysqli_real_escape_string($connessione, $_GET['show']);
}
if(isset($_GET['user'])){
  $user = mysqli_real_escape_string($connessione, $_GET['user']);
}else if(isset($_GET['proj'])){
  $proj = mysqli_real_escape_string($connessione, $_GET['proj']);
}
?>



<html>
<head>
  <meta charset="UTF-8">
  <title>Satusia - Messaggi</title>
  <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css" />
  
  <link rel="stylesheet" href="css/style_general.css" />
  
   <link rel="stylesheet" href="css/animate.css">
<style type="text/css">






#container_update_panel, #container_comments_panel{
 /*padding-left: 10px;
  padding-right: 10px;*/
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
#moreUpdate, #moreComment {
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
}
#tag_project {
  min-height: 0px!important;
  box-shadow: 0px -5px 5px rgba(100,100,100,0.25);
  z-index: 9999;
}
.photo_comment{
  background-image: url(https://lh5.googleusercontent.com/-jkYbauBfvV8/AAAAAAAAAAI/AAAAAAAAA-s/QfMUXePiXU0/photo.jpg);
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
    background-image: url(http://opencamera.sourceforge.net/ic_launcher.png);
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
.withColor{
  background-color: rgba(55, 190, 215, 0.1) !important;
}
#header_msg_proj{
    font-size: 16px;
    line-height: 25px;
}
div#mCSB_5_container, #mCSB_6_container {
    margin-right: 0px !important;
}
#viewAllMsgProj{
  height: 25px !important;
}
#messagesContainer, #messagesPjContainer{
  padding: 0px;
  margin: 0px;
}
#messagesContainer li, #messagesPjContainer li {
    height: 80px;
    width: calc(100% - 20px);
    padding: 10;
    position: relative;
    overflow: hidden;
    list-style-type: none;
}
#left_container{

}
#ul_chat {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
.div_chat {
    /*height: calc(100% - 20px);
    */color: rgba(255, 255, 255, 0.80);
    width: calc(80% - 20px);
   /* position: absolute;
    */border-radius: 5px;
    position: relative;
    
}
.other_chat {
    background-color: #66DA75;
    /*left: 50px;*/
    margin-left: 30px;

}
.chat_li {
    min-height: 50px;
    width: calc(100% - 20px);
    padding: 5px;
    position: relative;
    padding-bottom: 20px;
}
.my_chat {
   /* right: 50px;*/
    background-color: #79C2EA;
   /* float: right;*/
   margin-left: 18%;
}
.other_chat .img_chat {
   /* left: -21px;*/
   left: -23px;

}
.my_chat .img_chat {
    right: -21px;
}
.img_chat {
    height: 40px;
    width: 40px;
    border-radius: 100%;
    border: 3px solid rgb(250,250,250);
    background-size: cover;
    background-image: url(https://lh5.googleusercontent.com/-jkYbauBfvV8/AAAAAAAAAAI/AAAAAAAAA-s/QfMUXePiXU0/photo.jpg);
    position: absolute;
    top: -21px;
}

.my_chat .text_chat {
    margin-right: 10px;
}
.other_chat .text_chat {
    margin-left: 10px;
}
.text_chat {
    padding: 15px;
}/*
.other_chat .date_chat {
    margin-right: 25%;
}*//*
.my_chat .date_chat {
    left: 10px;
}*/
.date_chat {
    position: absolute;
    bottom: 3px;
    font-size: 10px;
    right: 5px;
}

#containerChatWithUser {
    padding-top: 20px;
}
html{
  overflow: hidden;
}
#container{
    height: calc(100% - 150px);
   
    position: relative;
}
.main{
  height: 100% !important;
}
#section_container{
  height: 100%;
  position: absolute;
}
#center_container{
  height: 100%;
}
#container_central_message{
      height: 100%;
    /* position: absolute; */
    bottom: 0;

}
#ul_chat .scrollableMain{
      height: 100%;
      max-height: 100% !important;

}
#mCSB_7{
  height: 100%;
}
#messagesPjContainer{
  display: none;
}
#input_div {
    position: relative;
    display: inline-block;
    width: calc(100% - 40px);
    height: 30px;
}
div#input_message_div {
    height: 30px;
    padding: 5px;
    border-radius: 0px 0px 5px 5px;
    background-color: #37BED7;
    position: relative;
    top: -3px;
    z-index: -1;
    padding-top: 8px;
    box-shadow: 0px 3px 10px rgba(10,10,10,0.2) inset;
    display: none;
}
input#message_input {
    width: 100%;
    height: 30px;
    position: absolute;
    top: 0;
    border: 0px;
    border-radius: 5px;
    padding-left: 5px;
}
#send_message_button {
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: inline-block;
    /* border-radius: 100%; */
    border: 0px;
    background-image: url(icon/send.png);
    background-size: cover;
}
#left_container a {
    text-decoration: none;
    color: #37BED7;
}
#form_chat{
  margin: 0;
  padding: 0;
}
div#selected_chat {
    padding: 10px;
    height: 30px;
    line-height: 30px;
    font-size: 13px;
    color: rgb(150,150,150);
    position: relative;
}
#img_sel_chat {
    position: absolute;
    right: 10;
    top: 11px;
}
.new_chat {
    font-weight: bold;
    box-shadow: 2px 0px 5px -2px rgb(55, 190, 215) inset;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>

<script src="js/general_script.js"></script>
<script src="js/notif.js"></script>

<script type="text/javascript">
    var clck = false;
    var firstTime = true;
    var id_chat = 0;
    <?php
      if(isset($_GET['user'])){
        echo "id_chat = ".$user.";";  
      }else if(isset($_GET['proj'])){
        echo "id_chat = ".$proj.";";
      }
    ?>
		$(document).ready(function(){
  
   
    $("#form_chat").submit(function(event){
      event.preventDefault();
      $("#send_message_button").click();
      return false;
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
		
   
   
    $("#containerChats").on("click", "li", function(event){
        if(clck == false){
          var obj = $(this);
          var id = obj[0].id;
          clck = true;
          id = id.replace("chat_","");
          id_chat = id;
          $("#what_type").val("u");
          getChatWithUser(id, true);
          if($(this).hasClass("new_chat")){
            $(this).removeClass("new_chat");
          }
        }
    });
    
    $("#containerProjChats").on("click", "li", function(event){
        if(clck == false){
          var obj = $(this);
          var id = obj[0].id;
          clck = true;
          id = id.replace("chat_proj_","");
          id_chat = id;
          $("#what_type").val("p");
          getChatWithProj(id, true);
          if($(this).hasClass("new_chat")){
            $(this).removeClass("new_chat");
          }
        }
    });
        

   

		var previousScroll = 0;

		 $(window).scroll(function(event) {
			var currentScroll = $(this).scrollTop();
	       if (currentScroll > (previousScroll+40)){
	        //   console.log('down');
	            previousScroll = currentScroll;
	            $("#sub_header").slideUp("fast", function(){});
	       } else if(currentScroll < (previousScroll-20)) {
	          //console.log('up');
	           previousScroll = currentScroll;
	           $("#sub_header").slideDown( "fast", function(){});
	       }
	      
		 });                     
		
		 $(document).on('click', "#contentProjects", function(){
		 	  $("#messagesPjContainer").show();
        $("#messagesContainer").hide();
        updateListChatProj();
		 });
		 $(document).on('click', "#contentPeople", function(){
		   
        $("#messagesPjContainer").hide();
        updateListChatPers();
        $("#messagesContainer").show();
		 });
		$("#input_message_div").on("click","#send_message_button", function(){
        var txt = $("#message_input").val();
        var cntrl_txt = txt.replace(" ", "");
        if(cntrl_txt != ""){
          var type = $("#what_type").val();
          if(type == "p"){
             sendMessageP(id_chat, txt);
          }else if(type == "u"){
            sendMessageU(id_chat, txt);
          }else{
            alert("Error");
          }
        }
    });

      <?php
      if($show == "proj" || $proj != null){ 
          echo "$('#contentProjects').click();";
     
          if($proj != null){  
            echo "getChatWithProj(".$proj.", true);";
          }else{
            echo "$('#containerProjChats .firstMsg').click();";
            
          }
      }else{ 
          echo "$('#contentPeople').click();";
       
          if($user != null){  
            echo "getChatWithUser(".$user.", true);";
          }else{
             echo "$('#containerChats .firstMsg').click();";
          }
      }
    ?>
  
	});
function updateListChatPers(){
  var upChat = $.ajax({
              
              url: "api/msg_user.php?get_list_chat=user",
             
              dataType: "json"
              });
      upChat.done(function(resp)
              {
                if(resp.status == "ok"){
                  var html = "";
                  var who = "";
                  var firstMsg = "firstMsg ";
                  var ty = $("#what_type").val();
                  var cu_c = $("#sel_hddn_chat").val();
                  for(var x = 0; x < resp.chat.length; x++){
                    var id = "";
                    var new_c = "";
                     if(resp.chat[x].who_wrote == "tu"){
                      who = "Tu: ";
                      id = resp.chat[x].id_ric;
                    }else{
                      who = "";
                      id = resp.chat[x].id_mit;
                    }
                    if(resp.chat[x].new == true){
                      new_c = " new_chat ";
                      if(ty == "u"){
                        if(cu_c == resp.chat[x].id){
                          getChatWithUser(cu_c, true);
                        }
                      }
                    }
                    if(x%2 == 0){
                      html += "<li class='"+firstMsg+new_c+"' id='chat_"+id+"'>";
                    }else{
                       html += "<li class='"+firstMsg+new_c+"withColor' id='chat_"+id+"'>";
                    }
                    firstMsg = "";
                    html += "<div class='img_msg' style='background-image: url("+resp.chat[x].immagine_profilo+")'></div><div class='nameMsg'><a href='profile.php?id="+resp.chat[x].id+"'>"+resp.chat[x].Nome+" "+resp.chat[x].Cognome+"</a></div><div class='text_message'>"+who+resp.chat[x].text+"</div></li>";
                  }
                  $("#containerChats").html(html);
                  if(firstTime){
                    $("#containerChats .firstMsg").click();
                   // console.log("a");
                    firstTime = false;
                  }
                  
                }else{
                  alert("errore");
                }
               
        });
      
    upChat.fail(function(jqXHR, textStatus) {
         
        }); 
}
function updateListChatProj(){
  var upChat = $.ajax({
              
              url: "api/msg_user.php?get_list_chat=proj",
             
              dataType: "json"
              });
      upChat.done(function(resp)
              {
                if(resp.status == "ok"){
                  var html = "";

                  var who = "";
                  var ty = $("#what_type").val();
                  var cu_c = $("#sel_hddn_chat").val();
                  var firstMsg = "firstMsg ";
                  for(var x = 0; x < resp.chat.length; x++){
                    var id = "";
                    var new_c = "";
                     if(resp.chat[x].who_wrote == "tu"){
                      who = "Tu: ";
                     
                    }else{
                      who = "";
                      
                    }
                    if(resp.chat[x].new == true){
                      new_c = " new_chat ";
                      if(ty == "p"){
                        if(cu_c == resp.chat[x].idproj){
                          getChatWithProj(cu_c, true);
                        }
                      }
                    }
                    if(x%2 == 0){
                      html += "<li class='"+firstMsg+new_c+"' id='chat_proj_"+resp.chat[x].idproj+"'>";
                    }else{
                       html += "<li class='"+firstMsg+new_c+"withColor' id='chat_proj_"+resp.chat[x].idproj+"'>";
                    }
                    firstMsg = "";
                    html += "<div class='img_msg' style='background-image: url("+resp.chat[x].img+")'></div><div class='nameMsg'><a href='project.php?proj="+resp.chat[x].idproj+"'>"+resp.chat[x].title+"</a></div><div class='text_message'>"+who+resp.chat[x].message+"</div></li>";
                  }
                  $("#containerProjChats").html(html);
                   if(firstTime){
                    $("#containerProjChats .firstMsg").click();
                    firstTime = false;
                  }
                }else{
                  alert("errore");
                }
               
        });
      
    upChat.fail(function(jqXHR, textStatus) {
         
        }); 
}

function getChatWithUser(user_chat, first){
  var userChat = $.ajax({
              
              url: "api/msg_user.php?get_chat_with_user="+user_chat,
             
              dataType: "json"
              });
      userChat.done(function(resp)
              {
                clck = false;
                if(resp.status == "ok"){
                  var html = "";
                  var who = "";
                  var chat_li = "";
                  var img = "";
                  setSelected(resp.Nome+" "+resp.Cognome, resp.other_img, resp.id_o);
                  for(var x = 0; x < resp.chat.length; x++){
                    chat_li = "<li class='chat_li'>";
                    if(resp.chat[x].who_wrote == "tu"){
                      chat_li += "<div class='div_chat my_chat'>";
                      img = resp.me_img;
                    }else{
                      chat_li += "<div class='div_chat other_chat'>";
                      img = resp.chat[x].immagine_profilo;
                    }
                    if(x != (resp.chat.length -1)){
                      if(resp.chat[x + 1].who_wrote != resp.chat[x].who_wrote){
                        chat_li += "<div class='img_chat' style='background-image: url("+img+")'></div>";
                      }else{
                        if(resp.chat[x].who_wrote == "other"){
                          if(resp.chat[x + 1].id_user != resp.chat[x].id_user){
                             chat_li += "<div class='img_chat' style='background-image: url("+img+")'></div>";
                          }
                        }
                      }
                    }else{
                      chat_li += "<div class='img_chat' style='background-image: url("+img+")'></div>";
                    }
                    chat_li += "<div class='text_chat'>"+resp.chat[x].text+"</div><div class='date_chat'>"+resp.chat[x].date_message+"</div></div></li>";
                    html = chat_li + html;
                  }
                  $("#containerChatWithUser").html(html);
                  if(first){
                      setTimeout(function(){ 
                         $("#_mCSB_6").mCustomScrollbar('scrollTo',['bottom',null]);}, 100); 
                  }
                  $("#input_message_div").show();
                }else{
                  $("#input_message_div").hide();
                  alert("errore");
                }
               
        });
      
    userChat.fail(function(jqXHR, textStatus) {
        clck = false;
    }); 
}
function setSelected(text, img, id){
  $("#selected_chat span").text(text);
  $("#img_sel_chat").css("background-image", "url("+img+")");
  $("#sel_hddn_chat").val(id);
}
function getChatWithProj(proj_chat, first){
  var userChat = $.ajax({
              
              url: "api/msg_user.php?msg_proj="+proj_chat,
             
              dataType: "json"
              });
      userChat.done(function(resp)
              {
                clck = false;
                if(resp.status == "ok"){
                  var html = "";
                  var who = "";
                  var chat_li = "";
                  var img = "";
                  setSelected(resp.title, resp.img, resp.id_p);
                  for(var x = 0; x < resp.chat.length; x++){
                    chat_li = "<li class='chat_li'>";
                    if(resp.chat[x].who_wrote == "tu"){
                      chat_li += "<div class='div_chat my_chat'>";
                     
                    }else{
                      chat_li += "<div class='div_chat other_chat'>";
                     
                    }
                    if(x != (resp.chat.length -1)){
                      if(resp.chat[x + 1].who_wrote != resp.chat[x].who_wrote){
                        chat_li += "<div class='img_chat' style='background-image: url("+resp.chat[x].immagine_profilo+")'></div>";
                      }else{
                        if(resp.chat[x].who_wrote == "other"){
                          if(resp.chat[x + 1].id_user != resp.chat[x].id_user){
                             chat_li += "<div class='img_chat' style='background-image: url("+resp.chat[x].immagine_profilo+")'></div>";
                          }
                        }
                      }
                    }else{
                      chat_li += "<div class='img_chat' style='background-image: url("+resp.chat[x].immagine_profilo+")'></div>";
                    }
                    chat_li += "<div class='text_chat'>"+resp.chat[x].message+"</div><div class='date_chat'>"+resp.chat[x].date_message+"</div></div></li>";
                    html = chat_li + html;
                  }
                  $("#containerChatWithUser").html(html);
                  if(first){
                      setTimeout(function(){ 
                         $("#_mCSB_6").mCustomScrollbar('scrollTo',['bottom',null]);}, 100); 
                  }
                   $("#input_message_div").show();
                }else{
                   $("#input_message_div").hide();
                  alert("errore");
                }
               
        });
      
    userChat.fail(function(jqXHR, textStatus) {
        clck = false;
    }); 
}

function sendMessageU(u, text){
   if(u != "" && text != ""){
    var sendMsg = $.ajax({
              
              url: "api/msg_user.php",
              method: "POST",
              data: {message: text, dest: u},
              dataType: "json"
              });
      sendMsg.done(function(resp)
              {
              if(resp.status == "ok"){
                 addMessageIntoChat(text, resp.date)
              }else{
                alert("Errore, riprova più tardi");
              }
              
               
        });
      
    sendMsg.fail(function(jqXHR, textStatus) {
          
        }); 
  }
}
function sendMessageP(idpj, text){
   if(idpj != "" && text != ""){
    var sendMsg = $.ajax({
              
              url: "api/msg_user.php",
              method: "POST",
              data: {message: text, proj: idpj},
              dataType: "json"
              });
      sendMsg.done(function(resp)
              {
               if(resp['status'] == "ok"){
                    addMessageIntoChat(text, resp.date)
               }else{
                  alert("ERRORE, riprova più tardi");
               }
              
               
        });
      
    sendMsg.fail(function(jqXHR, textStatus) {
          
        }); 
  }
}
function addMessageIntoChat(txt, date){
    $("#message_input").val("");
    var list = $("#containerChatWithUser").children();
    var img = "<div class='img_chat' style='background-image: url(<?php echo "$myImgProfilo"; ?>)'></div>";
    if(list.length > 0){
      var last = list[list.length - 1];
      var child = last.children[0];
     // console.log("length");
      if(child.className.indexOf("my_chat") > -1){
        img = "";
      }
    }
    
    var html ="<li class='chat_li'><div class='div_chat my_chat'>"+img+"<div class='text_chat'>"+txt+"</div><div class='date_chat'>"+date+"</div></div></li>";
    $("#containerChatWithUser").append(html);
    setTimeout(function(){ 
         $("#_mCSB_6").mCustomScrollbar('scrollTo',['bottom',null]);
    }, 50); 

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
    
		<div id="sub_header">
			<div id="main_subheader" class="main">
				<div id="contentPeople" class="sub_header_item selectedNav">Messaggi personali</div>
        <div id="contentProjects" class="sub_header_item">Messaggi progetti</div>
				<div id="swiper" style="left: -6px; width: 74px;"></div>
			</div>
		</div>
	</header>
	<div id="container">
		<div class="main" id="sub_container">
		  	<div id="left_container" class="page_main_panel">
				  <div id="selected_chat">
            <span>Nessuna chat selezionata</span><div id='img_sel_chat' class="img_msg"></div>
            <input type="hidden" id="sel_hddn_chat" value="0" />
          </div>
					<ul id="messagesContainer">
            <div class="scrollableMain mCustomScrollbar" data-mcs-theme="dark">
              <div id="containerChats">
    					   
               </div>
             </div>
          </ul>
          <ul id="messagesPjContainer">
            <div class="scrollableMain mCustomScrollbar" data-mcs-theme="dark">
              <div id="containerProjChats">
                 
               </div>
             </div>
          </ul>
					

				</div>
				<div id="section_container">
					<div id="center_container" >
            
            <div id="container_central_message" class="page_main_panel">
                <ul id="ul_chat">
                  <div class="scrollableMain mCustomScrollbar" data-mcs-theme="dark">
                    <div id="containerChatWithUser">
                      
                    </div>
                  </div>
                </ul>
                <div id="input_message_div">
                  <form id="form_chat">
                    <div id="input_div">
                      <input type="text" id="message_input">
                      <?php
                      $valHidden = "";
                        if(isset($_GET['user'])){  
                               $valHidden = "value='u'";
                        }else if(isset($_GET['proj'])){
                              $valHidden = "value='p'";
                        }


                      ?>
                      <input type="hidden" id="what_type" <?php echo $valHidden; ?> >
                    </div>
                    <div id="send_message_button"></div></form>
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