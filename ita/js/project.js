function getAllInfoProject(pj_id){
	
  getCmm(pj_id, null, null);
  getFllw(pj_id);
  getCollab(pj_id);
  getPost(pj_id);

}

function getCmm(pj_id, by, limit){
      if(by == null){
        by = "";
      }else{
        by = "&by="+by;
      }
      if(limit == null){
        limit = "";
      }else{
        limit = "&limit="+limit;
      }
      var getPjCom = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?limit=10&action=comment_proj&id_proj="+pj_id+by+limit,
              dataType: "json"
              });
      getPjCom.done(function(resp)
              {
              //  console.log(resp);
                var html = "<li class='no_more_li'>Non ci sono altri commenti</li>";
                if(resp.comment.length > 0){
                  html = "";
                  var l_cm = "id='last_comment'";
                  for(var x = 0; x < resp.comment.length; x++){
                    html += "<li "+l_cm+" class='item_background classUpdate'><div class='header_comment_project header_background_item'>";
                    html += " <div class='photo_comment' style='background-image: url("+resp.comment[x].immagine_profilo+")'></div><div class='name_comment'>"+resp.comment[x].Nome+" "+resp.comment[x].Cognome+"</div></div><div class='container_comment container_background_item'>";
                    html += "<div class='textComment'>"+resp.comment[x].text+"</div><div class='footer_comment'>"+resp.comment[x].date_com+"</div></div></li>";
                    l_cm = "";
                  }
                  
                }else{
                  $("#moreComment").hide();

                }
                if(by == ""){
                  $("#comments_ul").html(html);
                  if(resp.comment.length > 2){
                    $("#cmm_n_shwn").val(2);
                  }else{
                   // $("#cmm_n_shwn").val(resp.comment.length);
                  }
                }else{
                  $("#cmm_n_shwn").val(parseInt($("#cmm_n_shwn").val()) + parseInt(resp.comment.length));
                  $("#comments_ul").append(html);
                 // $("#container_comments_panel").height(parseInt($("#cmm_n_shwn").val())*141);
                  
                }
        
               
        });
      
    getPjCom.fail(function(jqXHR, textStatus) {
          
        }); 

 
   
}
function getFllw(pj_id){

    var by = $("#b_fll").val();
   // console.log(by);
  if(by != "-1"){
     var getInfoFollower = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=follow_p&proj="+pj_id+"&by="+by,
              dataType: "json"
              });
      getInfoFollower.done(function(resp)
              {
           //     console.log(resp);
                var n = resp.num;
                $("#nFollowerBox .nLine").text(n);
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


                */
                if(n > 0){
                   
                    for(var x = 0; x < resp.user.length; x++){
                      if(resp.user[x]){
                        var resto = x%3;
                        if(resto == 0){
                          html += "<li class='li_line'><ul class='ul_line'>"
                        }
                        html += "<a href='profile.php?id="+resp.user[x].user+"'><li class='follower_person' style='background-image: url("+resp.user[x].immagine_profilo+")'></li></a>";
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
           console.log("Login Fallito.");
        }); 
  }
}
function getCollab(pj_id){
   var getInfoCollab = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=coll_proj&id="+pj_id,
              dataType: "json"
              });
      getInfoCollab.done(function(resp)
              {
             //   console.log(resp);
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
           console.log("Login Fallito.");
        }); 


}

function getPost(pj_id){
  var by = $("#b_pst").val();
 // console.log(by);
  if(by != "-1"){
   var getPjPost = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?limit=4&action=post_p&proj="+pj_id+"&by="+by,
              dataType: "json"
              });
      getPjPost.done(function(resp)
              {
            //    console.log(resp);
               // console.log(resp.length);
                var html = "";
                if(resp.length > 0){
                  
                 
                  for(var y = 0; y < resp.length; y++){
                    html += "<li id='last_update' class='item_background classUpdate'><div class='header_update_project header_background_item'>";
                    html += resp[y].title_post+"</div><div class='container_update container_background_item'>";
                    html += "<div class='textUpdate'>"+resp[y].message+"</div><div class='footer_update'>"+resp[y].date_post+"</div></div></li>";
                    
                    
                  }
                  
                  $("#b_pst").val(parseInt(by) + parseInt(resp.length));
                  if(resp.length < 5){
                    html += "<li class='no_more_li'>Non ci sono altri aggiornamenti</li>";
                    $("#morePst").hide();
                  }
                }else{
                  $("#b_pst").val("-1");
                  $("#morePst").hide();
                  html = "<li class='no_more_li'>Non ci sono altri aggiornamenti</li>";
                }
                $("#updates_ul").append(html);
        
               
        });
      
    getPjPost.fail(function(jqXHR, textStatus) {
          
        }); 
  }
}
function follow_p(pj){
    var fll_p = $.ajax({
              
              url: "https://satusia.com/api/follow_proj.php?proj="+pj,
              dataType: "json"
              });
      fll_p.done(function(resp)
              {
            //    console.log(resp);
                if(resp['status'] == "follow"){
                  $("#followPerson").text("Unfollow");
                  $("#followPerson").addClass("unfollow");

                }else if(resp['status'] == "unfollow"){

                  $("#followPerson").text("Follow");
                  $("#followPerson").removeClass("unfollow");
                }else{
               //   console.log(resp['status']);
                }

        
               
        });
      
    fll_p.fail(function(jqXHR, textStatus) {
          
        }); 
}



function sendUpdate(pj, msg, title){
    if(pj != "" && msg != ""){
    var fll_p = $.ajax({
              
              url: "https://satusia.com/api/send_news.php",
              method: "POST",
              data: {message: msg, id_p: pj, title: title},
              dataType: "json"
              });
      fll_p.done(function(resp)
              {
             //   console.log(resp);
             //   console.log("AAA");
                if(resp['status'] == "ok"){
                  animateSendResponse("#divUpdatePost"); 
                  getPost(pj);
                }else{
                  alert("Errore, riprova più tardi");
             //     console.log(resp['status']);
                }

        
               
        });
      
    fll_p.fail(function(jqXHR, textStatus) {
          
        }); 
  }
}

function animateSendResponse(divId){
   $(divId+" .contentPopup").hide();
      $(divId).removeClass("noanimationClass");
      $(divId).addClass("setTransitionClass");
      $(divId).addClass("animationClass");
      $(divId+" .confirmImg").fadeIn("slow");
       var a = setTimeout(function() {
            $(divId).hide("slow");
            if(divId == "#divUpdatePost"){
              $("#overlay_UpdatePost").hide();
            }else if(divId == "#comment_box"){
              $("#container_comment_box").hide();
            }else if(divId == "#message_box"){
              $("#container_message_box").hide();
            }
            $(divId+" .confirmImg").hide();
            $(divId).addClass("noanimationClass");
            $(divId).removeClass("setTransitionClass");
            $(divId).removeClass("animationClass");
            $(divId+" .contentPopup").show();
        }, 1500);
}
function sendComment(idpj, text){
   if(idpj != "" && text != ""){
    var sendCom = $.ajax({
              
              url: "https://satusia.com/api/send_comment_p.php",
              method: "POST",
              data: {text: text, id_p: idpj},
              dataType: "json"
              });
      sendCom.done(function(resp)
              {
             //   console.log(resp);
               if(resp['status'] == "ok"){
                 animateSendResponse("#comment_box");
                 getCmm(idpj);
               }else{
                  alert("ERRORE, riprova più tardi");
               }
              
               
        });
      
    sendCom.fail(function(jqXHR, textStatus) {
          
        }); 
  }
}
function sendMessage(idpj, text){
   if(idpj != "" && text != ""){
    var sendMsg = $.ajax({
              
              url: "https://satusia.com/api/msg_user.php",
              method: "POST",
              data: {message: text, proj: idpj},
              dataType: "json"
              });
      sendMsg.done(function(resp)
              {
             //   console.log(resp);
               if(resp['status'] == "ok"){
                 animateSendResponse("#message_box");
                
               }else{
                  alert("ERRORE, riprova più tardi");
               }
              
               
        });
      
    sendMsg.fail(function(jqXHR, textStatus) {
          
        }); 
  }
}



function clickCollab(pj_id){
      var clickColl = $.ajax({
              
              url: "https://satusia.com/api/wanthelp_service.php?idproj="+pj_id,
              dataType: "json"
              });
      clickColl.done(function(resp)
              {
             //   console.log(resp);
                if(resp["status"] == "ok"){
                  var act = resp['action'];
                  if(act == "annullato" || act == "abbandonato"){
                    $("#collabPerson").text("Collabora");
                  }else if(act = "send request"){
                    $("#collabPerson").text("Annulla");
                  }
                }else{
                  alert("errore, riprova più tardi");
                }
        });
      
    clickColl.fail(function(jqXHR, textStatus) {
          
        }); 


 
   
}

function getLastMessageProj(id_proj){
   var lastMessagePj = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=msg_proj&proj="+id_proj,
              dataType: "json"
              });
      lastMessagePj.done(function(resp)
              {
                var html = "";
                    
                if(resp["status"] == "ok"){
                  var n = resp.message.length;
                  if(n > 0){
                    var msg = resp.message;
                    for(var x = 0; x < msg.length; x++){
                      if(x%2 == 0){
                        html += "<li class='firstMsg withColor'>";
                      }else{
                        html += "<li class='firstMsg'>";
                      }
                      html += "<div class='img_msg' style='background-image: url("+msg[x].immagine_profilo+")'></div><div class='nameMsg'>"+msg[x].Nome+" "+msg[x].Cognome+"</div><div class='text_message'>"+msg[x].message+"</div></li>";
                    }

                  }
                }else{
                  html = "<li>Errore, riprova più tardi</li>"
                }
                $("#containerMsg").html(html);
        });
      
    lastMessagePj.fail(function(jqXHR, textStatus) {
          console.log("Error");
        }); 
}
function shareOnSatusia(id_proj){
  var shareOnPj = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=share_on_nf&idp="+id_proj,
              dataType: "json"
              });
      shareOnPj.done(function(resp)
              {
                if(resp.status == "ok"){
                  showNotificationPopup(resp.image, "Progetto condiviso", "Il progetto è stato condiviso correttamente");
                }else{
                  alert("Error");
                }
               
        });
      
    shareOnPj.fail(function(jqXHR, textStatus) {
          console.log("Error");
    }); 
}

function saveSettingProj(idproj){
  
    var title = $("#title_setting").val();
    var categ = $("#categ_setting").val();
    var descr = $("#descr_setting").val();
    var src = $.ajax({
                  
        url: "https://satusia.com/api/mng_settings.php?type=proj&proj="+idproj,
        method: "POST",
        data: { title: title, categ: categ, descr: descr},
        dataType: "json"
    });
    src.done(function(resp)
      {
       // console.log(resp);
        if(resp.status == "ok"){
            showNotificationPopup("<?php echo $ascMyInfo['immagine_profilo']; ?>", "", "Informazioni progetto aggiornate");
            $("#header_background_user_info").html("<span>"+title+"</span>");
            document.title = "Satusia - "+title;
            $("#category").text(resp.cat);
            $("#container_biography").html(descr);
            $("#edit_info_project").slideUp();
        }else{
            showNotificationPopup("<?php echo $ascMyInfo['immagine_profilo']; ?>", "Errore!", "Riprova più tardi.");
        }
                    
                   
                   
    });
          
    src.fail(function(jqXHR, textStatus) {
      //  console.log("Login Fallito.");
   });    
}

function moreComm(pj_id){
  var arr_li = $("#comments_ul").children();
  var n_li = arr_li.length;
  var n_shown = parseInt($("#cmm_n_shwn").val());
  var x = 0
  if(n_shown < n_li){
     while(n_shown + x < n_li && x < 3){

      //  console.log(n_shown + x);
      //  console.log(n_li);
         x++;
      }
      $("#cmm_n_shwn").val((parseInt(n_shown) + parseInt(x)));
     // $("#container_comments_panel").height((parseInt(n_shown) + parseInt(x))*141);
  }else{
  
    getCmm(pj_id, n_shown + x + 1, 3);
  }

}