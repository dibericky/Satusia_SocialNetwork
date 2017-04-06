<?php
session_start();
$nome = "";
$cognome = "";
$email = "";
if(isset($_SESSION['id'])){
  include 'connect.php';
  $idLog = mysqli_real_escape_string($connessione, $_SESSION['id']);
  $sel = mysqli_query($connessione, "SELECT * FROM utenti WHERE id = '$idLog' LIMIT 1");
  
  $n = mysqli_num_rows($sel);
  
  if($n > 0){
    $asc = mysqli_fetch_assoc($sel);
    $nome = $asc['Nome'];
    $cognome = $asc['Cognome'];
    $email = $asc['email'];
  }
}
?>
  <html>
  <head>
    <meta charset="UTF-8">
    <title>Satusia - Assistenza</title>
    <link rel="icon" href="icon/favicon.ico" />
   
    <link rel="stylesheet" href="css/style_general.css" />

    <link rel="stylesheet" href="css/style_settings.css" />
    
     <link rel="stylesheet" href="css/animate.css">
  <style type="text/css">
#top_header{
   text-align: center;
}

#head_container {
    text-align: center;
    color: rgb(100,100,100);
}

button#send {
    position: absolute;
    bottom: 10;
    padding: 5px 30;
    right: 20px;
    background-color: rgba(0,0,0,0);
    border: 2px solid #37BED7;
    border-radius: 20px;
    cursor: pointer;
    color: #37BED7;
    font-weight: bold;
}
#avviso {
    position: absolute;
    bottom: 10;
    text-align: center;
    width: 100%;
}
textarea#txtHelp {
    width: 80%;
    margin-left: 10%;
    margin-top: 20px;
    border-radius: 10px;
    height: 300px;
    padding: 5px;
}
table {
    width: 80%;
    margin: 0 auto;
}
td{
      width: 50%;
    height: 30px;
}
#div_help {
    margin: 0 auto;
    width: 70%;
    padding: 20px;
    margin-top: 20px;
    min-height: 400px;
    background-color: #fefefe;
    border-radius: 10px;
    color: rgb(100,100,100);
    box-shadow: 0px 0px 5px rgba(100,100,100,0.3);
    padding-bottom: 50px;
    position: relative;
}
input {
    width: 100%;
    height: 25px;
    border-radius: 10px;
    padding-left: 10px;
    border: 1px solid rgb(150,150,150);
}

  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 

  <script type="text/javascript">
  		$(document).ready(function(){

      $("#send").click(function(){
          <?php
           if($nome != "" && $cognome != "" & $email != ""){
            echo "var nome = '".$nome."';";
            echo "var cognome = '".$cognome."';";
            echo "var email = '".$email."';";
          }else{ ?>
            var nome = $("#nomeInput").val();
            var cognome = $("#cognomeInput").val();
            var email = $("#emailInput").val();
       <?php   }

          ?>
          var txt = $("#txtHelp").val();
          if(txt != ""){
              var hlp = $.ajax({
                  url: "api/help_service.php",
                  method: "POST",
                  data: {txt: txt, nome: nome, cognome: cognome, email, email},
                  dataType: "json"
                });
            hlp.done(function(conferma)
                  {
                  console.log(conferma);
                  if(conferma.status == "ok"){
                    $("#avviso").text("Segnalazione inviata! sarai reindirizzato alla home a breve");
                    setTimeout(function(){
                      window.location.href = "https://satusia.com/index.php";;
                    },2000);
                  }else {
                    $("#avviso").text("C'è stato un problema... Invia un email a help@satusia.com");
                  }
                  $("#send").hide();
                });
            hlp.fail(function(jqXHR, textStatus) {
                   $("#avviso").text("C'è stato un problema... Invia un email a help@satusia.com");
                   $("#send").hide();
                });
          }
      });

      });
</script>
      
    


  </head>
  <body>
  	<header>
  		<div id="top_header">
  			
  				<a href="home.php"><div id="logo" class="header_item"><div id="sLogo"></div>atusia</div></a>
  				
  				
  					
  		</div>
     
  	</header>
  	<div id="container">
      <div id="head_container">Compila il form sottostante o invia un email a help@satusia.com</div>
  		<div id="div_help">
        <table>
        <?php
          if($nome != "" && $cognome != "" & $email != ""){
      
         echo "<tr><td><span class='label_help'>Nome: </span></td><td><span id='nomeSpan'>".$nome."</span></td></tr>";
         echo "<tr><td><span class='label_help'>Cognome: </span></td><td><span id='nomeSpan'>".$cognome."</span></td></tr>";
         echo "<tr><td><span class='label_help'>Email: </span></td><td><span id='nomeSpan'>".$email."</span></td></tr>";
         
      
          }else{ ?>
          <tr><td><span class='label_help'>Nome: </span></td><td><input type="text" id='nomeInput' /></td></tr>
          <tr><td><span class='label_help'>Cognome: </span></td><td><input type="text" id='cognomeInput' /></td></tr>
          <tr><td><span class='label_help'>Email: </span></td><td><input type="email" id='emailInput' /></td></tr>
    <?php      }
        ?>
       </table>
        <textarea id="txtHelp"></textarea>
        <button id="send">Invia</button>
          <div id="avviso"></div>

      </div>
  	</div>
  </body>
  </html>
