/* ce code est censé gérer le comprtement du "tag cloud", mais ne fonctionne pas dans le plugin, donc je l' ai placé dans le site (oups). De plus il sera obsolète d' ici peut, la présentation du "tag cloud" devant changer  */


jQuery(".mots-clefs").ready(function($){


	$( '.titre-groupe' ).css({

							    cursor: "pointer"
							  });


	$( '.titre-groupe').click(function(){

 		$("li.keyword").slideUp();

 		$(this).parent().find("li.keyword").slideDown("800");



	});



});