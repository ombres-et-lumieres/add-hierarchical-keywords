<?php
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






	$author = isset($xmp["Owner Name"]) ? $xmp["Owner Name"] : "";

	$author =( ("" ==$author) and isset($xmp["Creator"]) and is_array($xmp["Creator"])) ? $xmp["Creator"][0] : "";

// les deux lignes ci-dessous posent problème: tel quel, j' ai la bonne information à la sortie de la ligne précédente, donc elles devraient être sans influence. Malheureusement, la première remet ma variable à ""
/*
	$author = ((""== $author) and isset($img_exif["IFD0"]["Artist"])) ? $img_exif["IFD0"]["Artist"] : "";
	$author =  (("" == $author) and isset($iptc["2#080"])) ? $iptc["2#080"] :  null;
*/



	$credit = isset($xmp["Credit"])  ? $xmp["Credit"] : "";
	$credit = (("" == $credit) and isset($iptc["2#116"]) and is_array($iptc["2#116"])) ? $iptc["2#116"][0] :  "";

/* *************************************************** */
/* les conditions ternaires suivantes ont un problème parce qu' elles modifient les valeurs de la variable alors qu' elle n' est pas vide */
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
/*
	$creation_date = ((""== $creation_date) and isset($exif["IFD0"]["DateTime"])) ? $exif["IFD0"]["DateTime"] : "";
	$creation_date = ((""== $creation_date) and isset($iptc["2#055"]) and is_array($iptc["2#055"])) ? $iptc["2#055"][0] : null;
*/

	$modification_date = isset($xmp["Modification Date"]) ? $xmp["Modification Date"] : null;

	$photografic_style = (isset($iptc["2#015"]) and (is_array($iptc["2#015"]))) ? $iptc["2#015"][0] : null;

	$gps = isset($exif["GPS"]) ? $exif["GPS"] : "";




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
														 "gps" => $gps
													);




/* ici j' ai récupéré les informations qui m' intéressent */



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
*/




/* 	ajout des informations strctement nécessaires pour éviter l' effet usine à gaz */



	return $newdata ;


}
add_filter( 'wp_update_attachment_metadata' ,'ol_modify_attachment_datas', 10, 2 );





?>