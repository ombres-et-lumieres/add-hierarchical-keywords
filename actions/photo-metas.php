<?php

/*
cette page contient le code pour trois actions:
la primordiale, celle qui ajoute les métas dont j' ai besoin dans les métas de la photo lors de l' upload
dans la page d' admin d' un media, ajout d' un groupe de champs éditables comprenant les métas de la photo
une metabox classique posant la question "photo à vendre?"

problème: je n' arrive pas à faire la sauvegarde
*/







function ol_modify_attachment_datas($data, $id)
{


	//  pour enlever ce que d' autres plugiins auraient ajouté et revenir au format de base de wordpress

	$newdata = array(
								"width" =>$data ["width"],
								"height" =>$data ["height"],
								"file" =>$data ["file"],
								"size" =>$data["sizes"],
								);

	/* la manière la plus simple de récupérer les valeurs qui m' intéressent est d' utiliser le plugin JSM's Adobe XMP / IPTC for WordPress */


	global $adobeXMP;

	$xmp = $adobeXMP->get_xmp( $id );

	/* je fais une recherche des exifs et iptc au cas où je n' aurais rien récupéré via le plugin */
	/* la fonction php exif_read_data nécessite un chemin absolu */

	$scr =  wp_get_attachment_image_src( $id);

	$upload = wp_upload_dir();

	$url = $scr[0];

	$path = $upload["basedir"] . "/" . $data["file"];

	$img_exif = exif_read_data($path, NULL, true, false); //fonction php au lieu de wp_read_image_metadata parce que celle-ci me renvoie une erreur

	$img_iptc = getimagesize($url, $info);

	$iptc = iptcparse( $info['APP13'] );



	/* je vais chercher les informations d' abord dans le tableau des xmp, ensuite, si c' est vide, dans celui des exifs et, enfin, si c' est toujours vide, dans les iptc */



	$author = ( isset($xmp["Owner Name"]) ) ? $xmp["Owner Name"] : "";

	$author =( ("" ==$author) and isset($xmp["Creator"]) and is_array($xmp["Creator"])) ? $xmp["Creator"][0] : "";

// les deux lignes ci-dessous posent problème: tel quel, j' ai la bonne information à la sortie de la ligne précédente, donc elles devraient être sans influence. Malheureusement, la première remet ma variable à ""
/*
	$author = ((""== $author) and isset($img_exif["IFD0"]["Artist"])) ? $img_exif["IFD0"]["Artist"] : "";
	$author =  (("" == $author) and isset($iptc["2#080"])) ? $iptc["2#080"] :  null;
*/



	$credit = isset($xmp["Credit"])  ? $xmp["Credit"] : "";
	$credit = (("" == $credit) and isset($iptc["2#116"]) and is_array($iptc["2#116"])) ? $iptc["2#116"][0] :  "";

/* *************************************************** */
/* les conditions ternaires suivantes commentées ont un problème parce qu' elles modifient les valeurs de la variable alors qu' elle n' est pas vide */
/* **************************************************** */

	$headline = isset($xmp["Headline"]) ? $xmp["Headline"] : "";
//$headline = (("" == $headline) and isset($iptc["2#105"]) and is_array($iptc["2#105"])) ? $iptc["2#105"][0] : null;

	$title = (isset($xmp["Title"]) and is_array($xmp["Title"])) ? $xmp["Title"][0] : "";
//	$title =  (("" == $title) and isset($iptc["2#005"]) and is_array($iptc["2#005"])) ? $iptc["2#005"][0] : null;

	$description = (isset($xmp["Description"]) and is_array($xmp["Description"])) ? $xmp["Description"][0] : "";
//	$description = ((""== $description) and isset($iptc["2#120"]) and is_array($iptc["2#120"])) ? $iptc["2#120"][0] : null;

	$country = isset($xmp["Country"]) ? $xmp["Country"] : "";
//	$contry = ((""== $country) and isset($iptc["2#101"]) and is_array($iptc["2#101"])) ? $iptc["2#101"][0] : null;

	$state = isset($xmp["State"]) ? $xmp["State"] : "";
//	$state = ((""== $state) and isset($iptc["2#095"]) and is_array($iptc["2#095"])) ? $iptc["2#095"][0] : null;

	$city = isset($xmp ["City"]) ? $xmp ["City"] : "";
//	$city = ((""== $city) and isset($iptc["2#090"])) ? $iptc["2#090"] [0] : 0;

	$location = isset($xmp["Location"]) ?  $xmp["Location"] : "";
	$location = ((""== $location) and isset($iptc["2#026"]) and is_array($iptc["2#026"])) ? $iptc["2#026"][0] : null;

	$creation_date = isset($xmp["Creation Date"]) ? $xmp["Creation Date"] : "";

	$creation_date = ((""== $creation_date) and isset($img_exif["IFD0"]["DateTime"])) ? $img_exif["IFD0"]["DateTime"] : "";
	$creation_date = ((""== $creation_date) and isset($iptc["2#055"]) and is_array($iptc["2#055"])) ? $iptc["2#055"][0] : null;


	$modification_date = isset($xmp["Modification Date"]) ? $xmp["Modification Date"] : null;

	$sous_emplacement = isset($iptc["2#092"]) ? $iptc["2#092"] : null;



	$photografic_style = (isset($iptc["2#015"]) and (is_array($iptc["2#015"]))) ? $iptc["2#015"][0] : null;

	$gps = isset($img_exif["GPS"]) ? $img_exif["GPS"] : "";




			$newdata["oetl" ] =  array  (
											"Author" => $author,
											 "Copyright" =>$credit,
											 "headline" => $headline,
											 "title" => $title,
											 "Description" => $description,
											 "Country" => $country,
											 "State" => $state,
											 "City" => $city,
											 "Location" => $location,
											 "Creation_Date" => $creation_date,
											 "Modification_Date" => $modification_date,
											 "photografic_style" => $photografic_style,
											 "gps" => $gps,
											 "sous-emplacement" => $sous_emplacement
										);




/* ici j' ai récupéré les informations qui m' intéressent */

/* format des coordonnées dans Lr: 39°27'36" N 0°21'27" W */

/*
liste ses codes iptc

["1#090"] ????
["2#000"]???
["2#005"]titre
["2#015"]catégorie
["2#055"]date création
["2#060"]heure création
["2#062"]????
["2#063"]?????
["2#080"]auteur
 ["2#085"]byliine title du créateur
["2#090"]city
["2#101"]pays
["2#105"]headline
["2#116"]copyright
["2#120"]caption
["2#122"]caption writer
"2#118" Contact
"2#110" crédit
"2#095" province, état
"2#092"  région
"2#026" location
"2#022" identifiant
"2#020" catégorie supplémentaire, tableau
"2#092" sous emplacement
*/




/* 	ajout des informations strctement nécessaires pour éviter l' effet usine à gaz */



	return $newdata ;


}
add_filter( 'wp_update_attachment_metadata' ,'ol_modify_attachment_datas', 10, 2 );









/* ***************************************************************************** */
/* Création et sauvegarde de champs de métas dans l' admin de chaque photo */
/* ************************************************************** ***************/

/* le code qui suit est une esquisse de ce qu' il faudrait ajouter aux photos si je veux les vendre, donc pas utile maintenant */

function ol_attachment_metas_fields ($form_fields, $attachment ) //provoque une recherche infinie pour la vue en grille de la bibliothèque des médias
{

  $metas =  wp_get_attachment_metadata( $attachment->ID);

  $metas = $metas["oetl"];

/* la boucle suivante génère une erreur dans l' écran des médias, format grille; en liste tout va bien */

  foreach ($metas as $key => $value)
  	{

	  	 $form_fields[$key] = array(

									        'label' => $key,

									        'input' => 'text',

									        'value' => $metas[$key],

									        'helps' => esc_html_e("If provided, the value will be displayed", "ombres-et-lumieres"),
									  );

  	}

  return $form_fields;

}

add_filter( 'attachment_fields_to_edit', 'ol_attachment_metas_fields', 10, 2 );  // création des champs




function ol_save_attachment_metas_fields(  $attachment )
{

  $attachment_id = $post["ID"];

  $metas =  wp_get_attachment_metadata( $attachment_id);

  $metas = $metas["oetl"];

  foreach ($metas as $key => $value)
  	{
	  	if ( isset( $attachment[$key] ) )
		    {
		       $datas[$key] = $attachment[$key];

		       //update_post_meta( $attachment_id, $key, $attachment[$key]);
		    }
  	}


  $datas["location"] = "cité des sciences";

   wp_update_attachment_metadata( $attachment_id, $data);


  $datas = array(
	  				"location" => "cité des sciences",
	  				"ville" => "Valencia"
  				);


   update_post_meta( $attachment_id, "oetl", $datas);
}

add_filter( 'attachment_fields_to_save', 'ol_save_attachment_metas_fields', 1 );


/* cette dernière fonction ne marche pas */











function init_metabox()
{
  add_meta_box('vente', 'Photo à vendre?', 'vente_photo', 'attachment', 'side');
}
add_action('add_meta_boxes','init_metabox');





function vente_photo($post){
  //$meta = get_post_meta($post->ID,'_vente',false);

  $values = get_post_custom( $post->ID );

  $check = isset( $values['my_meta_box_check'] ) ? esc_attr( $values['my_meta_box_check'] ) : '';
  ?>
  <label for="my_meta_box_check">Vendre cette photo?</label>
  <input type="checkbox" id="my_meta_box_check" name="my_meta_box_check" <?php checked( $check, 'on' ); ?> />
  <?php
}







function save_metabox($post_id)
{

    $chk = isset( $_POST['my_meta_box_check'] ) && $_POST['my_meta_box_select'] ? 'on' : 'off';
    update_post_meta( $post_id, 'my_meta_box_check', $chk );


}
add_action('save_post','save_metabox');


































?>