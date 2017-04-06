$(document).ready(function(){
  if(document.getElementById("changePhotoProjectInput") != null){
	document.getElementById("changePhotoProjectInput").addEventListener("change", function(){
	  if(this.files.length == 1){
	    console.log(this.files[0].name);
	    previewImage(this.files[0], true);
	  }
	}, false);
}else{
  document.getElementById("changePhotoUserInput").addEventListener("change", function(){
    if(this.files.length == 1){
      console.log(this.files[0].name);
      previewImage(this.files[0], false);
    }
  }, false);
}
	function previewImage(file, isProj) {
    if(isProj){
	    var galleryId = "wallpaperImg";
    }else{
      var galleryId = "profile_img";
    }
	    var gallery = document.getElementById(galleryId);
	    var imageType = /image.*/;

	    if (!file.type.match(imageType)) {
	        throw "File Type must be an image";
	    }

	    var thumb = document.createElement("div");
	    thumb.classList.add('thumbnail'); // Add the class thumbnail to the created div

	   //var img = document.createElement("img");
	 //  var img = document.getElementById()
    console.log(file);
     // if(isProj){
	    gallery.file = file;
    //}
	   // img.id = "preview";
	  //  $("#centerCreateNew").height(400);
	  //  gallery.appendChild(img);
	    // Using FileReader to display the image content
	    var reader = new FileReader();
      if(isProj){
  	    reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(gallery);
      }else{
        reader.onload = (function(aImg) { return function(e) { aImg.style.backgroundImage = "url("+e.target.result+")"; }; })(gallery);
      }
	    reader.readAsDataURL(file);
	    $("#carica").show();
	  //  uploadFile(file);
	}


/*
function upPhoto(idProj){
	 //Creazione di un oggetto FormData…
  var datiForm = new FormData();
  //… aggiunta del nome…
  //… aggiunta del file
  datiForm.append("imageProj",$("#changePhotoProjectInput")[0].files[0]);

  $.ajax({
   url: "upimg.php",
   type: "POST", //Le info testuali saranno passate in POST
   data: datiForm, //I dati, forniti sotto forma di oggetto FormData
   cache: false,
   processData: false, //Serve per NON far convertire l’oggetto
            //FormData in una stringa, preservando il file
   contentType: false, //Serve per NON far inserire automaticamente
            //un content type errato
   success: function(data)
   {
    //Qui verranno effettuate le operazioni necessarie
    //in caso di successo dell’operazione di upload,
    //es: l’aggiornamento della lista degli utenti, …
    console.log(data);
    console.log(datiForm);
    return;
   }
}

 }
);*/

});
function uploadFile(idproj){
    var url = 'https://satusia.com/api/upimg.php';
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Every thing ok, file uploaded
            console.log("OK");
            console.log(xhr.responseText); // handle response.
            var jsonResp = JSON.parse(xhr.responseText);
            console.log("aa");
            $("#carica").hide();
            showNotificationPopup("uploads/"+jsonResp.name,"","Immagine progetto aggiornata");
            console.log("OK");
        }
    };
    var file = document.getElementById("changePhotoProjectInput").files[0];

    fd.append("upload_file", file);
    fd.append("idproj", idproj);
    xhr.send(fd);
}
function upImgProf(){
  var url = 'https://satusia.com/api/upimg.php?user';
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Every thing ok, file uploaded
            console.log("OK");
            console.log(xhr.responseText); // handle response.
            var jsonResp = JSON.parse(xhr.responseText);
            console.log("aa");
            $("#carica").hide();
            showNotificationPopup("uploads/"+jsonResp.name,"","Immagine profilo aggiornata");
            console.log("OK");
        }
    };
    var file = document.getElementById("changePhotoUserInput").files[0];

    fd.append("upload_file", file);
    xhr.send(fd); 
}