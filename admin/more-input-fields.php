<?php

/* ***************************************************************************** */
/* Création et sauvegarde de champs de métas dans l' admin de chaque photo */
/* ************************************************************** ***************/


function ol_attachment_metas_fields ($form_fields, $post )
{

  $metas =  wp_get_attachment_metadata($post->ID);

  $metas = $metas["oetl"];

  foreach ($metas as $key => $value)
  	{
	  	 $form_fields[] = array(

									        'label' => $key,

									        'input' => 'text',

									        'value' => $metas[$key],

									        'helps' => esc_html_e("If provided, the value will be displayed", "ombres-et-lumieres"),
									  );
  	}



    return $form_fields;

}

add_filter( 'attachment_fields_to_edit', 'ol_attachment_metas_fields', 10, 2 );  // création des champs






function ol_attachment_metas_fields_save( $post, $attachment )
{
	$post_id = $post->ID;

	$metas =  wp_get_attachment_metadata($post_id);

	$metas = $metas["oetl"];

	foreach($metas as $key => $value)
		{
			if( isset( $attachment[$key] ) )

			update_post_meta( $post_id, $key, esc_html($attachment[$key]));
		}

    return $post;

}

add_filter( 'attachment_fields_to_save', 'ol_attachment_metas_fields_save', 10, 2 ); //sauvegarde et modification des champs









/*
	Champs créée

			"Author"
			 "Copyright"
			 "label"
			 "title"
			 "Description"
			 "Country"
			 "State"
			 "City"
			 "Location"
			 "Creation Date"
			 "Modification Date"
			 "style photographique"
			 "gps" => ""
*/





	?>