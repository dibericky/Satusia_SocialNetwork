function follow_u(u){
    var fll_u = $.ajax({
              
              url: "https://satusia.com/api/follow.php?followed="+u,
              dataType: "json"
              });
      fll_u.done(function(resp)
              {
                console.log(resp);
                if(resp['status'] == "follow"){
                  $("#followPerson").text("Unfollow");
                  $("#followPerson").addClass("unfollow");

                }else if(resp['status'] == "unfollow"){

                  $("#followPerson").text("Follow");
                  $("#followPerson").removeClass("unfollow");
                }else{
                  console.log(resp['status']);
                }

        
               
        });
      
    fll_u.fail(function(jqXHR, textStatus) {
          
        }); 
}
function shareOnSatusia(id_u){
  var shareOnPj = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=share_on_nf&idu="+id_u,
              dataType: "json"
              });
      shareOnPj.done(function(resp)
              {
                console.log(resp);
                if(resp.status == "ok"){
                  showNotificationPopup(resp.image, "Profilo condiviso", "Il profilo è stato condiviso correttamente");
                }else{
                  alert("Error");
                }
               
        });
      
    shareOnPj.fail(function(jqXHR, textStatus) {
          console.log("Error");
    }); 
}
function animateSendResponse(divId){
   $(divId+" .contentPopup").hide();
      $(divId).removeClass("noanimationClass");
      $(divId).addClass("setTransitionClass");
      $(divId).addClass("animationClass");
      $(divId+" .confirmImg").fadeIn("slow");
       var a = setTimeout(function() {
            $(divId).hide("slow");
            if(divId == "#message_box"){
              $("#container_message_box").hide();
            }
            $(divId+" .confirmImg").hide();
            $(divId).addClass("noanimationClass");
            $(divId).removeClass("setTransitionClass");
            $(divId).removeClass("animationClass");
            $(divId+" .contentPopup").show();
        }, 1500);
}
function sendMessage(u, text){
   if(u != "" && text != ""){
    var sendMsg = $.ajax({
              
              url: "https://satusia.com/api/msg_user.php",
              method: "POST",
              data: {message: text, dest: u},
              dataType: "json"
              });
      sendMsg.done(function(resp)
              {
                console.log(resp);
              if(resp.status == "ok"){
                  animateSendResponse("#message_box");
              }else{
                alert("Errore, riprova più tardi");
              }
              
               
        });
      
    sendMsg.fail(function(jqXHR, textStatus) {
          
        }); 
  }
}
