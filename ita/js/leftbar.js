$(document).ready(function(){
	  var leftBarUserInfo = $.ajax({
              
              url: "https://satusia.com/api/webservice.php?action=info_id&id="+idProfile+"&nome&cognome&nascita&img&citta&sesso&professione&biografia&scuola_superiore",
              dataType: "json"
              });
      leftBarUserInfo.done(function(resp)
              {
              //  console.log(resp);
                $("#nameProfileLeft").text(resp.Nome + " "+resp.Cognome);
                var citta = resp.citta;
                var dataNasc = resp.Data_Nascita;
                var biog = resp.biografia;
                var imgProf = resp.immagine_profilo;
                var prof = resp.professione;
                var edu = resp.scuola_superiore;
                $("#job").text(prof);
                $("#locate span").text(citta);
                $("#birthday span").text(dataNasc);
                $("#profile_img").css("background-image", "url("+imgProf+")");
               
               
              });
      
    leftBarUserInfo.fail(function(jqXHR, textStatus) {
           console.log("error.");
        }); 
      
});