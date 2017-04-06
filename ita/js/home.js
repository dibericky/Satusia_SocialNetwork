function getNewsFeed(by){
    if(by == null){
      by = "";
      canUp = true;
    }else{
      by = "&by="+by;
    }
   var getNewsFeed = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=news_feed"+by,
              dataType: "json"
              });
      getNewsFeed.done(function(response)
              {
               // console.log(response);
                var html = "";
                if(response.newsfeed != null && response.newsfeed.length > 0){
                  if(by == ""){
                    html ="<ul id='newsfeed_ul' class='page_main_panel'>";
                  }
                  var resp = response.newsfeed;
                  var n_true_news = 0;
                  for(var x=0; x < resp.length; x++){
                   
                    if(resp[x] != null && resp[x].action){
                      n_true_news++;
                     // console.log(resp[x].action);
                        html += "<li class='newsfeed_li'><div class='newsfeed_post'>";
                        if(resp[x].action == "comment_p"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image:url("+resp[x].immagine_profilo+")'></div></a>";
                          html += "<div class='content_newsfeed'><div class='header_content_newsfeed'>";
                          html += "<b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> ha commentato <b class='mittente_action'><a href='project.php?proj="+resp[x].id_proj+"'>"+resp[x].title_proj+"</a></b></div>";
                          html += "<div class='body_content_newsfeed body_with_border'><div class='img_project' style='background-image: url("+resp[x].img_proj+")'></div><div class='comment_user'>"+resp[x].comment+"</div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }else if(resp[x].action == "follow_u"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image:url("+resp[x].immagine_profilo+")''></div></a>";
                          html += "<div class='content_newsfeed'><div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> ha iniziato a seguire <b class='mittente_action'><a href='profile.php?id="+resp[x].id_user_target+"'>"+resp[x].Nome_followed+" "+resp[x].Cognome_followed+"</a></b></div>";
                          html += "<div class='body_content_newsfeed before'><div class='img_user_content_newsfeed' style='background-image: url("+resp[x].immagine_profilo_followed+")'></div><div class='info_user_content_newsfeed'>";
                          html += "<ul class='ul_info_user_content_newsfeed'><li>"+resp[x].professione+"</li><li>Data di nascita: "+resp[x].data_nascita+"</li><li>Residenza: "+resp[x].citta+"</li><li class='fra_pers_nf'>"+resp[x].frase_personale+"</li></ul></div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }else if(resp[x].action == "share_u"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image:url("+resp[x].immagine_profilo+")''></div></a>";
                          html += "<div class='content_newsfeed'><div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> ha condiviso il profilo di <b class='mittente_action'><a href='profile.php?id="+resp[x].id_user_target+"'>"+resp[x].Nome_followed+" "+resp[x].Cognome_followed+"</a></b></div>";
                          html += "<div class='body_content_newsfeed before'><div class='img_user_content_newsfeed' style='background-image: url("+resp[x].immagine_profilo_followed+")'></div><div class='info_user_content_newsfeed'>";
                          html += "<ul class='ul_info_user_content_newsfeed'><li>"+resp[x].professione+"</li><li>Data di nascita: "+resp[x].data_nascita+"</li><li>Residenza: "+resp[x].citta+"</li><li class='fra_pers_nf'>"+resp[x].frase_personale+"</li></ul></div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }/*else if(resp[x].action == "want_help"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image:url("+resp[x].immagine_profilo+")'></div></a><div class='content_newsfeed'>";
                          html += "<div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> collabora al progetto <b class='mittente_action'><a href='project.php?proj="+resp[x].id_proj+"'>"+resp[x].title+"</a></b></div>";
                          html += "<div class='body_content_newsfeed body_with_border'><div class='img_project' style='background-image:url("+resp[x].img_proj+")'></div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }*/
                        else if(resp[x].action == "accept_w_h"){
                          html += "<a href='profile.php?id="+resp[x].id_user_target+"'><div class='img_user_newsfeed' style='background-image:url("+resp[x].immagine_profilo_accepted+")'></div></a><div class='content_newsfeed'>";
                          html += "<div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_target+"'>"+resp[x].Nome_accepted+" "+resp[x].Cognome_accepted+"</a></b> collabora al progetto <b class='mittente_action'><a href='project.php?proj="+resp[x].id_proj+"'>"+resp[x].title+"</a></b></div>";
                          html += "<div class='body_content_newsfeed body_with_border'><div class='img_project' style='background-image:url("+resp[x].img_proj+")'></div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }else if(resp[x].action == "post"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image:url("+resp[x].immagine_profilo+")'></div></a><div class='content_newsfeed'>";
                          html += "<div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> ha pubblicato un aggiornamento su <b class='mittente_action'><a href='project.php?proj="+resp[x].id_proj+"'>"+resp[x].title+"</a></b></div>";
                          html += "<div class='body_content_newsfeed body_with_border'><div class='img_project' style='background-image:url("+resp[x].img_proj+")'></div><div class='comment_user'>"+resp[x].post+"</div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }else if(resp[x].action == "follow_p"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image: url("+resp[x].immagine_profilo+")'></div></a>";
                          html += "<div class='content_newsfeed'><div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> ha iniziato a seguire il progetto <b class='mittente_action'><a href='project.php?proj="+resp[x].id_proj+"'>"+resp[x].title+"</a></b></div>";
                          html += "<div class='body_content_newsfeed body_with_border'><div class='img_project' style='background-image: url("+resp[x].img_proj+")'></div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }else if(resp[x].action == "create"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image: url("+resp[x].immagine_profilo+")'></div></a>";
                          html += "<div class='content_newsfeed'><div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> ha creato il progetto <b class='mittente_action'><a href='project.php?proj="+resp[x].id_proj+"'>"+resp[x].title+"</a></b></div>";
                          html += "<div class='body_content_newsfeed body_with_border'><div class='img_project' style='background-image: url("+resp[x].img_proj+")'></div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>20 Luglio 2015</span></div></div>";
                        }else if(resp[x].action == "share_p"){
                          html += "<a href='profile.php?id="+resp[x].id_user_actor+"'><div class='img_user_newsfeed' style='background-image: url("+resp[x].immagine_profilo+")'></div></a><div class='content_newsfeed'>";
                          html += "<div class='header_content_newsfeed'><b class='user_actor'><a href='profile.php?id="+resp[x].id_user_actor+"'>"+resp[x].Nome+" "+resp[x].Cognome+"</a></b> ha condiviso il progetto <b class='mittente_action'><a href='project.php?proj="+resp[x].id_proj+"'>"+resp[x].title+"</a></b></div>";
                          html += "<div class='body_content_newsfeed body_with_border'><div class='img_project' style='background-image: url("+resp[x].img_proj+")'></div></div>";
                          html += "<div class='footer_content_newsfeed'><span class='date_newsfeed'>"+resp[x].date_action+"</span></div></div>";
                        }

                        html += "</div></li>";
                    }
                  }

                  if(by == ""){
                    html += "</ul>";
                    $("#center_container").html(html);
                  }else{
                    $("#newsfeed_ul").append(html);
                    $("#b_nf").val(parseInt($("#b_nf").val()) + 50);
                  }
                  if(n_true_news == 0){
                    canUp = false;
                    $("#newsfeed_ul").append("<div id='no_more_up'>Non ci sono più notizie da mostrare</div>");
                  }
                }else{
                  canUp = false;
                  $("#newsfeed_ul").append("<div id='no_more_up'>Non ci sono più notizie da mostrare</div>");
                }
               
              });
      
    getNewsFeed.fail(function(jqXHR, textStatus) {
           console.log("Login Fallito.");
        }); 
    

}
function getPjforHome(){
         var getPjHome = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=get_last_pj_for_home",
              dataType: "json"
              });
      getPjHome.done(function(resp)
              {
              //  console.log(resp);
                if(resp.my_project.length > 0){
                  var html = "";
                  for(var x = 0; x < resp.my_project.length; x++){
                    html += "<li><div class='little_img_li'></div><a href='project.php?proj="+resp.my_project[x].idproj+"'><div class='little_text'>"+resp.my_project[x].title+"</div></a></li>";
                  }
                }else{
                  html = "<li>Nessun Progetto</li>";
                }
                $("#ul_mypj").html(html);

                if(resp.general_project.length > 0){
                  var html = "";
                  for(var x = 0; x < resp.general_project.length; x++){
                    html += "<li><div class='little_img_li'></div><a href='project.php?proj="+resp.general_project[x].idproj+"'><div class='little_text'>"+resp.general_project[x].title+"</div></a></li>";
                  }
                }else{
                  html = "<li>Nessun Progetto</li>";
                }
                $("#ul_general_pj").html(html);
               // console.log("OK");
        
               
        });
      
    getPjHome.fail(function(jqXHR, textStatus) {
          
        }); 

}