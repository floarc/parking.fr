  $(function() {
	  

	  
	 $( "form.search-form input,form.search-form radio" ).change(function() {
		 
		 var searchFormData = jQuery("form.search-form").serialize();
		 console.clear();
		 console.log(searchFormData);
		 
		 
		 
		   $.ajax({
			   	  type: "POST",
		          // chargement du fichier externe monfichier-ajax.php 
		          url      : "/ajax-search",
		          // Passage des données au fichier externe (ici le nom cliqué)  
		          //data     : {NomEleve: $(this).html()},
		          data     : searchFormData,
		          cache    : false,
		          //dataType : "json",
		          error    : function(request, error) { // Info Debuggage si erreur         
		                       alert("Erreur : responseText: "+request.responseText);
		                     },
		          success  : function(data) {  
		                       // Informe l'utilisateur que l'opération est terminé et renvoie le résultat
		                       //alert(data.PrenomEleve);  
		                       // J'écris le résultat prénom de l'élève dans le h1
		                       //$(#prenom_eleve).html(data.PrenomEleve);
		        	  			alert(data);
		                     }       
		     });  		 
		 
		 
	});
    
	  
	  
    
    


  });