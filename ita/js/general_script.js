$(document).ready(function(){
  controlNNotif();
  setInterval(function(){ 
    controlNNotif();
  }, 1000*60);
  

  $(document).on("click", ".buttonRequest", function(event){
        event.stopPropagation();
        var btt = event.target; 
       // console.log(btt);
        var bttId = btt.id;
        var ar = bttId.split("_");
      //  console.log(ar);
        var accept = false;
        if(ar[0] == "accept"){
          accept = true;
        }
        var id_wh = ar[1];
        manageRequest(accept, id_wh);

    });

  $(document).on("click", "#myMessage", function(){
    //  console.log("CLICK");
      $("#msg_n_notif").text(0);
      $("#msg_n_notif").hide();
      var subMenuHeader = $("#divMessage");
      if(subMenuHeader.css("display") == "none"){
        subMenuHeader.slideDown( "fast", function(){});
        msg_personal();
        msg_proj();
      }else{
        if( $("#myMessage").is(event.target)){
          subMenuHeader.slideUp( "fast", function(){});
        }
      }
    });

   $(document).on("click", "#myRequest", function(event){
    //  console.log("CLICK");
         $("#request_n_notif").text(0);
         $("#request_n_notif").hide();
        var subMenuHeader = $("#ul_Request");
        if(subMenuHeader.css("display") == "none"){
          subMenuHeader.slideDown( "fast", function(){});
          req_collab();
         }else{
          if( $("#myRequest").is(event.target)){
            subMenuHeader.slideUp( "fast", function(){});
          }
        }
      
    });

   $(document).on('click', function(event) {
       var target = $( event.target );
       closeTabs(target);
      
    });
    
    $(document).on('click', "#lightbulbNotif", function(event) {
      $("#general_n_notif").text(0);
      $("#general_n_notif").hide();
      var subMenuHeader = $("#ul_notif");
      if(subMenuHeader.css("display") == "none"){
        ntf();
        subMenuHeader.slideDown( "fast", function(){});
      }else{
        if( $("#lightbulbNotif").is(event.target)){
          subMenuHeader.slideUp( "fast", function(){});
        }
      }
    });
    $(document).on("click", "#myPicture", function(){
      var subMenuHeader = $("#menu_profile_header");
      if(subMenuHeader.css("display") == "none"){
        subMenuHeader.slideDown( "fast", function(){});
      }else{
        if( $("#myPicture").is(event.target)){
          subMenuHeader.slideUp( "fast", function(){});
        }
      }
    });





    $(document).on("click","#closeCreateNew", function(){
      $("#createNew").hide();
      $("#overlay_create").hide();
      $("#inputTextCreateNew").text = "";

    });
    $(document).on("click", "#crea", function(){
      var titoloProject = "";
      var categoriaProject = "";
    //  nextCreateProject();

      $("#overlay_create").show();
      $("#createNew").show();

    });
    $(document).on("click","#bttCreateNew", function(){
        var input = $("#inputTextCreateNew").val();
        var textarea = $("#textareaCreateNew").val();
        var categ = $("#categCreateNew").val();
        if(input != "" && textarea != ""){
          //RICHIESTA AJAX
          var upProj = $.ajax({
                  
                  url: "https://satusia.com/api/upproject.php",
                  method: "POST",
                  data: { titolo: input, descr: textarea, categoria: categ},
                  dataType: "json"
                  });
          upProj.done(function(resp)
                  {
                   //console.log(resp);

                    console.log("OK");
                    if(resp.status == "ok"){
                      animationResponse(resp.id_proj);
                    }else if(resp.status == "data_null"){
                      if(resp.what == "titolo"){
                        $("#inputTextCreateNew").focus();
                      }else if(resp.what == "descr"){
                        $("#textareaCreateNew").focus();
                      }else{
                        $("#headerTextCreateNew").text("Errore...");
                        setTimeout(function(){
                            $("#headerTextCreateNew").text("Nuovo progetto");
                        }, 1500);
                      }
                    }
                   
                  });
          
        upProj.fail(function(jqXHR, textStatus) {
               console.log("Errore");
            }); 
        

        }else{
          console.log("no ok");
         // console.log(input+"  "+textarea);
        }
    });

    function animationResponse(pj){
    //  console.log("ANIMATION");
      $("#centerCreateNew").hide();
      $("#headerCreateNew").hide();
      $("#bttCreateNew").hide();
      $("#createNew").removeClass("noanimationClass");
      $("#createNew").addClass("setTransitionClass");
      $("#createNew").addClass("animationClass");
      $("#confirmed").fadeIn("slow");
       var a = setTimeout(function() {
            $("#createNew").hide("slow");
            $("#overlay_create").hide();
            window.location.replace('https://satusia.com/project.php?proj='+pj);
        }, 1500);
    }


   

});

 function showNotificationPopup(img, title, text){
    $("#imgNotif").css("background-image", "url("+img+")");
    $("#topContainerNotification").text(title);
    $("#bottomContainerNotification").text(text);
    $("#notificationPopup").show();
     $("#notificationPopup").addClass("fadeInRight");
    // console.log("SNP");
     var a = setTimeout(function() {
            $("#notificationPopup").removeClass("fadeInRight");
          $("#notificationPopup").addClass("fadeOutRight");
           var a = setTimeout(function() {
                  $("#notificationPopup").removeClass("fadeOutRight");
                 $("#notificationPopup").css("opacity","0");
                 $("#notificationPopup").hide();
              }, 1000);
        }, 3000);
        
}




$(document).on("click","#iconSearch", function(){
  var txt = $("#search").val();
  location.href = 'https://satusia.com/search.php?src='+encodeURIComponent(txt);
});

 $(document).on("change keyup paste click","#search",  function(){
    var textForSearch = $("#search").val();
   // console.log("searching: "+textForSearch);
    if(textForSearch.length > 2){
      if($("#fastResult").css("opacity") == "0" || $("#fastResult").css("display") == "none"){
        $("#fastResult").show();
        $("#fastResult").css("opacity","1");
      }
      fastSearch("user", textForSearch);
      fastSearch("proj", textForSearch);
      
    }else{
      $("#fastResult").css("opacity","0");
      $("#fastResult").hide();
    }

 });
 function fastSearch(type, src){

     var src = $.ajax({
                  
                  url: "https://satusia.com/api/srch.php?fast",
                  method: "GET",
                  data: { type: type, src: src},
                  dataType: "json"
                  });
          src.done(function(resp)
                  {
                   // console.log(resp);

                    if(resp.status == "logged"){
                      if(type == "user"){
                        var obj = resp.user
                        var target = $("#ul_fastResult_user");
                      }else if(type == "proj"){
                        var obj = resp.proj;
                        var target = $("#ul_fastResult_proj");
                      }
                      if(obj != null){
                        var n = obj.length;
                        var html = "";
                        if(n > 0){
                          
                          for(var x = 0; x < n; x++){
                            if(type == "user"){
                              html +=  "<a href='https://satusia.com/profile.php?id="+obj[x].id+"'><li><div class='imgFastResult' style='background-image: url("+obj[x].immagine_profilo+")'></div><div class='descr_FastResult'>"+obj[x].Nome+" "+obj[x].Cognome+"</div></li></a>";
                            }else{
                              html +=  "<a href='https://satusia.com/project.php?proj="+obj[x].idproj+"'><li><div class='imgFastResult' style='background-image: url("+obj[x].img+")'></div><div class='descr_FastResult'>"+obj[x].title+"</div></li></a>";
                            }
                          }
                        }else{
                          html = "<li class='no_result_fastResult'>Nessun Risultato</li>";
                        }

                       
                      }else{
                          html = "<li class='no_result_fastResult'>Nessun Risultato</li>";
                        }
                       target.html(html);
                    }
                   
                   
                  });
          
        src.fail(function(jqXHR, textStatus) {
               console.log("Errore.");
            }); 
 }



 function closeTabs(target){  //chiude gli altri menu aperti
    //  console.log(target);
      var myPic = $("#myPicture");
      var mainNotif = $("#lightbulbNotif");
      var myMess = $("#myMessage");
      var myReq = $("#myRequest");
      var mySearch = $("#fastResult");

      if(!myPic.is(target) && myPic.has(target).length === 0){
        $("#menu_profile_header").slideUp( "fast", function(){});
       
      }
     if(!mainNotif.is(target) && mainNotif.has(target).length === 0){
        $("#ul_notif").slideUp( "fast", function(){});
      }
      if(!myMess.is(target) && myMess.has(target).length === 0){
        $("#divMessage").slideUp( "fast", function(){});
      }
      if(!myReq.is(target) && myReq.has(target).length === 0){
        $("#ul_Request").slideUp( "fast", function(){});
      }
      if(!mySearch.is(target) && mySearch.has(target).length === 0){
        mySearch.slideUp( "fast", function(){});
      }
      

    }

    function controlNNotif(){
       var cntrlNNotif = $.ajax({
              
              url: "https://satusia.com/api/notif.php?n",
              dataType: "json"
              });
      cntrlNNotif.done(function(resp)
              {
               // console.log(resp);
                if(resp.status == "ok"){
                  var n_g = resp.general;
                  var n_msg_u = resp.msg_user;
                  var n_msg_p = resp.msg_proj;
                  var n_req = resp.req;
                  if(n_g > 0){
                    $("#general_n_notif").text(n_g);
                    $("#general_n_notif").show();
                    $("#b_gen_ntf").val("10");
                    if(n_g > 0){
                      var newS = "nuove";
                      if(n_g == 1){
                         newS = "nuova";
                      }
                    }
                    $("#ul_notif .minititle").text("Notifiche ("+n_g+" "+newS+")");

                  }else{
                    $("#general_n_notif").text("0");
                    $("#general_n_notif").hide();
                    $("#ul_notif .minititle").text("Notifiche");
                  }


                  if((n_msg_p + n_msg_u) > 0){
                     if($("#ul_chat").length){
                    //  var id_sl = $("#sel_hddn_chat").val();
                      var WType = $("#what_type").val();
                      if(WType == "u"){
                        updateListChatPers();
                      }else{
                        updateListChatProj();
                      }
                    }
                    
                    $("#msg_n_notif").text(n_msg_u + n_msg_p);
                    if(n_msg_p > 0){
                       var newS = "nuovi";
                      if(n_msg_u == 1){
                        newS = "nuovo";
                      }
                      $("#projectMessage .minititle").text("Messaggi progetti ("+n_msg_p+" "+newS+")");
                    }else{

                      $("#projectMessage .minititle").text("Messaggi progetti");
                    }
                    if(n_msg_u > 0){
                      var newS = "nuovi";
                      if(n_msg_u == 1){
                        newS = "nuovo";
                      }
                      $("#personalMessage .minititle").text("Messaggi personali ("+n_msg_u+" "+newS+")");
                    }else{

                      $("#personalMessage .minititle").text("Messaggi personali");
                    }
                    
                    $("#msg_n_notif").show();
                  }else{
                    $("#msg_n_notif").text("0");
                    $("#msg_n_notif").hide();
                    $("#projectMessage .minititle").text("Messaggi progetti");
                    $("#personalMessage .minititle").text("Messaggi personali");
                  }


                  if(n_req > 0){
                    $("#request_n_notif").text(n_req);
                    $("#request_n_notif").show();
                  }else{
                    $("#request_n_notif").text("0");
                    $("#request_n_notif").hide();
                  }
                  
                  
               }else{
                  console.log("error");
               }

        
               
        });
      
    cntrlNNotif.fail(function(jqXHR, textStatus) {
          
        }); 
    }