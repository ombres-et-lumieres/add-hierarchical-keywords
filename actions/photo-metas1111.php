<?php
function ol_modify_attachment_datas($data, $id)
{
	global $adobeXMP;

	$xmp = $adobeXMP->get_xmp( $id );

	$scr =  wp_get_attachment_image_src( $id);

	$upload = wp_upload_dir();

	$url = $scr[0];

	$path = $upload["basedir"] . "/" . $data["file"];

	$img_exif = exif_read_data($path, NULL, true, false); //fonction php au lieu de wp_read_image_metadata parce que celle-ci me renvoie une erreur

	$img_iptc = getimagesize($url, $info);

	$iptc = iptcparse( $info['APP13'] );

	$data_temp = array(
									"width" =>$data ["width"],
									"height" =>$data ["height"],
									"file" =>$data ["file"],
									"size" =>$data["sizes"],
									);

	$newdata = $data_temp;  //  pour enlever ce que d' autres plugiins auraient ajouté et revenir au format de base de wordpress


/* 	ajout des informations strctement nécessaires pour éviter l' effet usine à gaz */









	$newdata["oetl" ] =  array  (
												"Author" => $img_exif["IFD0"]["Artist"],
												 "Copyright" => $img_exif["COMPUTED"]["Copyright"],
												 "label" => $xmp["Headline"],
												 "title" => $xmp["Title"][0],
												 "Description" => $xmp ["Description"][0],
												 "Country" => $xmp["Country"],
												 "State" => $xmp["State"],
												 "City" => $xmp ["City"],
												 "Location" => $xmp["Location"],
												 "Creation Date" => $xmp["Creation Date"],
												 "Modification Date" => $xmp["Modification Date"],
												 "style photographique" => $xmp["Label"],
//												 "gps" => $img_exif["GPS"]
											);

	return $newdata ;


}
add_filter( 'wp_update_attachment_metadata' ,'ol_modify_attachment_datas', 10, 2 );





?>