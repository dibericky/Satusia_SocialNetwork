
function ntf(by){
	 if(by == null){
    by = "";
   }else{
    by = "&by="+by;
   }
   if($("#b_gen_ntf").val() != "-1"){
    	 var getNtf = $.ajax({
                  
                  url: "https://satusia.com/api/notif.php?general"+by,
                  dataType: "json"
                  });
          getNtf.done(function(resp)
                  {
                    if(resp.status == "ok"){
                    	if(resp.notif != null && resp.notif.length > 0){
                    		var html = "";
                    		var action = "";
                        var url = "";
                    		for(var x = 0; x < resp.notif.length; x++){
                    			action = resp.notif[x].action;
                          if( action == "comment_p"){
                            url = "project.php?proj="+resp.notif[x].id_proj+"&comment";
                          }else if(action == "post"){
                            url = "project.php?proj="+resp.notif[x].id_proj+"&post";
                          }else if(action == "follow_u" || action == "share_u"){
                            url = "profile.php?id="+resp.notif[x].id_user_actor;
                          }else if(action == "share_p" || action == "follow_p" || action == "accept_w_h"){
                            url = "project.php?proj="+resp.notif[x].id_proj;
                          }
                          html += "<a href='"+url+"'>";
                          var new_ntf = "";
                          if(resp.notif[x].new_notif){
                            new_ntf = " new_ntf ";
                          }
                    			if(resp.notif[x].action == "comment_p" || resp.notif[x].action == "post"){
                    				html += "<li class='notifElement"+new_ntf+" messageNotif'>";
                   
                    			}else{
                    				html += " <li class='notifElement"+new_ntf+"'>";
                    			}
                          if(action != "accept_w_h"){
                      			html += "<div class='personPictureNotif' style='background-image: url("+resp.notif[x].immagine_profilo+")'></div>";
                          }else{
                            html += "<div class='personPictureNotif' style='background-image: url("+resp.notif[x].img+")'></div>";
                          }
                    			var txt = "";

                    		/*	if(action == "comment_p"){
                    				txt = "<b><a href='profile.php?id="+resp.notif[x].id_user_actor+"'>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</a></b> ha commentato il tuo progetto: <b><a href='project.php?proj="+resp.notif[x].id_proj+"'>"+resp.notif[x].title+"</a></b>";
                    			}else if(action == "post"){
                    				txt = "Aggiornamento al progetto <b><a href='project.php?proj="+resp.notif[x].id_proj+"'>"+resp.notif[x].title+"</a></b>"
                    			}else if(action == "follow_u"){
                            txt = "<b><a href='profile.php?id="+resp.notif[x].id_user_actor+"'>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</a></b> ha iniziato a seguirti";
                          }else if(action == "share_u"){
                            txt = "<b><a href='profile.php?id="+resp.notif[x].id_user_actor+"'>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</a></b> ha condiviso sul newsfeed il tuo profilo";
                          }else if(action == "follow_p"){
                            txt = "<b><a href='profile.php?id="+resp.notif[x].id_user_actor+"'>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</a></b> ha iniziato a seguire il progetto a cui collabori: <b><a href='project.php?proj="+resp.notif[x].id_proj+"'>"+resp.notif[x].title+"</a></b>";
                          }else if(action == "share_p"){
                            txt = "<b><a href='profile.php?id="+resp.notif[x].id_user_actor+"'>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</a></b> ha condiviso sul newsfeed il progetto: <b><a href='project.php?proj="+resp.notif[x].id_proj+"'>"+resp.notif[x].title+"</a></b>";
                          }else if(action == "accept_w_h"){
                            txt = "<b><a href='project.php?proj="+resp.notif[x].id_proj+"'>"+resp.notif[x].title+"</a></b> ha accettato la tua richiesta di collaborazione";
                            
                          }*/
                          if(action == "comment_p"){
                            txt = "<b>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</b> ha commentato il tuo progetto: <b>"+resp.notif[x].title+"</b>";
                          }else if(action == "post"){
                            txt = "Aggiornamento al progetto <b>"+resp.notif[x].title+"</b>"
                          }else if(action == "follow_u"){
                            txt = "<b>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</b> ha iniziato a seguirti";
                          }else if(action == "share_u"){
                            txt = "<b>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</b> ha condiviso sul newsfeed il tuo profilo";
                          }else if(action == "follow_p"){
                            txt = "<b>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</b> ha iniziato a seguire il progetto a cui collabori: <b>"+resp.notif[x].title+"</b>";
                          }else if(action == "share_p"){
                            txt = "<b>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+"</b> ha condiviso sul newsfeed il progetto: <b>"+resp.notif[x].title+"</b>";
                          }else if(action == "accept_w_h"){
                            txt = "<b>"+resp.notif[x].title+"</b> ha accettato la tua richiesta di collaborazione";
                            
                          }
                    			html += "<div class='collabRequestText'>"+txt+"</div>";
                      		if(action == "post" || action == "comment_p"){
                            html += " <div class='messageText commentMessageText'>"+resp.notif[x].Nome+" "+resp.notif[x].Cognome+": "+resp.notif[x].text+"</div>";
                          }
                          html += "<div class='notifDate'>"+resp.notif[x].date_action+"</div></li></a>";
                    		}

                        if(by == ""){
                          $("#mCSB_1_container").html(html);
                        }else{
                          $("#mCSB_1_container").append(html);
                        }
                     //   console.log("OK");
                    	}else{
                         $("#mCSB_1_container").append("<div class='no_more_ntf'>Non ci sono nuove notifiche</div>");
                         $("#b_gen_ntf").val("-1");
                      }
                    }else{
                    	alert("error notification");
                    }
                    
                  });
          
        getNtf.fail(function(jqXHR, textStatus) {
              
            }); 
    }

}
$(document).on("click","#lightbulbNotif .viewAllNotif", function(){
    var by = $("#b_gen_ntf").val();
    if(by != "-1"){
      ntf(by);
      $("#b_gen_ntf").val(parseInt($("#b_gen_ntf").val()) + 10);
    }
});
function msg_personal(){
   var getMsgP = $.ajax({
              
              url: "https://satusia.com/api/notif.php?msg_personal",
              dataType: "json"
              });
      getMsgP.done(function(resp)
              {
                if(resp.status == "ok"){
                  if(resp.messaggi != null && resp.messaggi.length > 0){
                    var html = "";
                    for(var x = 0; x < resp.messaggi.length; x++){
                    /*
                     <li class="notifElement messageNotif">
                          <div class="personPictureNotif"></div>
                          <div class="mittente"><b>Andrea Vallone</b> </div>
                          <div class="messageText">Un gran bel progetto, complimenti a tutti!</div>
                          <div class="notifDate">12 Dicembre</div>
                        </li>
                        */
                        var new_ntf = "";
                        if(resp.messaggi[x].new_notif){
                          new_ntf = " new_ntf ";
                        }
                        html += "<a href='message.php?user="+resp.messaggi[x].id+"'><li class='notifElement "+new_ntf+" messageNotif'>";
                        html += " <div class='personPictureNotif' style='background-image: url("+resp.messaggi[x].immagine_profilo+")'></div><div class='mittente'><b>"+resp.messaggi[x].Nome+" "+resp.messaggi[x].Cognome+"</b> </div>";
                        html += "<div class='messageText'>"+resp.messaggi[x].text+"</div><div class='notifDate'>"+resp.messaggi[x].date_message+"</div></li></a>";
                    }
                    $("#mCSB_2_container").html(html);
                  //  console.log("OK");
                  }
                }else{
                  alert("error notification");
                }
                
              });
      
    getMsgP.fail(function(jqXHR, textStatus) {
          
        }); 
    

}
function msg_proj(){
   var getMsgP = $.ajax({
              
              url: "https://satusia.com/api/notif.php?msg_project",
              dataType: "json"
              });
      getMsgP.done(function(resp)
              {
                if(resp.status == "ok"){
                  if(resp.message_proj != null && resp.message_proj.length > 0){
                    var html = "";
                    for(var x = 0; x < resp.message_proj.length; x++){
                    /*
                     <li class="notifElement messageNotif">
                          <div class="personPictureNotif"></div>
                          <div class="mittente"><b>Andrea Vallone</b> </div>
                          <div class="messageText">Un gran bel progetto, complimenti a tutti!</div>
                          <div class="notifDate">12 Dicembre</div>
                        </li>
                        */
                        var new_ntf = "";
                        if(resp.message_proj[x].new_notif){
                          new_ntf = " new_ntf ";
                        }
                        html += "<a href='message.php?proj="+resp.message_proj[x].id_proj+"'><li class='notifElement "+new_ntf+"messageNotif'>";
                        html += " <div class='personPictureNotif' style='background-image: url("+resp.message_proj[x].immagine_profilo+")'></div><div class='mittente'><b>"+resp.message_proj[x].Nome+" "+resp.message_proj[x].Cognome+"</b> <span class='title_msg'>"+resp.message_proj[x].title+"</span></div>";
                        html += "<div class='messageText'>"+resp.message_proj[x].message+"</div><div class='notifDate'>"+resp.message_proj[x].date_message+"</div></li></a>";
                    }
                    $("#mCSB_3_container").html(html);
                   // console.log("OK");
                  }
                }else{
                  console.log("error notification");
                }
                
              });
      
    getMsgP.fail(function(jqXHR, textStatus) {
          
        }); 
    

}
function req_collab(){
  var getReqC = $.ajax({
              
              url: "https://satusia.com/api/notif.php?req_collab",
              dataType: "json"
              });
      getReqC.done(function(resp)
              {
                if(resp.status == "ok"){
                  var html = "";
                  if(resp.request != null && resp.request.length > 0){
                    
                    for(var x = 0; x < resp.request.length; x++){
                    /*
                      <li class="notifElement requestNotif">
                    <div class="containerRequest messageText">
                      <div class="nameRequest "><b>Andrea Vallone</b> vuole collaborare a: <b>Satusia</b></div><div class="acceptRequest buttonRequest"></div>                  
                      <div class="personPictureNotif personPictureNotifRequest"></div>
                      <div class="refuseRequest buttonRequest"></div>
                    </div>
                    
                   <!-- <div class="messageText commentMessageText">Un gran bel progetto, complimenti a tutti!</div>-->
                    <div class="notifDate">12 Dicembre</div>
                  </li>
                        */
                        html += "<li id='li_req_"+resp.request[x].id_wh+"' class='notifElement requestNotif'><div class='containerRequest messageText'>";
                        html += "  <div class='nameRequest ''><b><a href='profile.php?id="+resp.request[x].id_mit+"'>"+resp.request[x].Nome+" "+resp.request[x].Cognome+"</a></b> vuole collaborare a: <b><a href='project.php?proj="+resp.request[x].idproj+"'>"+resp.request[x].title+"</a></b></div><div id='accept_"+resp.request[x].id_wh+"' class='acceptRequest buttonRequest'></div>     ";
                        html += "<input type='hidden' autocomplete='off' class='pjID' value='"+resp.request[x].idproj+"'>";
                        html += "<input type='hidden' class='hddn_rq' value='"+resp.request[x].id_wh+"'>";
                        html += " <div class='personPictureNotif personPictureNotifRequest' style='background-image: url("+resp.request[x].immagine_profilo+")'></div><div id='refuse_"+resp.request[x].id_wh+"'class='refuseRequest buttonRequest'></div></div><div class='notifDate'>"+resp.request[x].date+"</div></li>";
                    }
                    
                  }else{
                    html += "<li class='no_more_ntf'>Non ci sono nuove richieste</li>"
                  }
                  $("#mCSB_4_container").html(html);
               //   console.log("OK");
                }else{
                  alert("error notification");
                }
                
              });
      
    getReqC.fail(function(jqXHR, textStatus) {
          
        }); 
    
}
function manageRequest(accept, id_wh){
   var manageReq = $.ajax({
              
              url: "https://satusia.com/api/wanthelp_service.php?accept="+accept+"&id_wh="+id_wh,
              dataType: "json"
              });
      manageReq.done(function(resp)
              {
                if(resp.status == "ok"){
                  $("#li_req_"+id_wh).hide("slow");
                  var what = "rifiutat";
                  if(resp.action == "1"){
                    what = "accettat";
                  }
                  showNotificationPopup(resp.img, "Richiesta "+what+"a", "Hai  "+what+"o la richiesta di "+resp.Nome+" "+resp.Cognome);
                }
                 
                
              });
      
    manageReq.fail(function(jqXHR, textStatus) {
          
        }); 
}