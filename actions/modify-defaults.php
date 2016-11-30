<?php

/* 	source: http://www.geekpress.fr/valeurs-defaut-medias/ */



function olhk_attachment_fields_to_save( $post, $attachment ) {

    // je crée le chemin sur le serveur du fichier et non pas une URL
    $docpath = str_replace( home_url(), $_SERVER['DOCUMENT_ROOT'], $attachment['url'] );

    // Comme au dessus, je liste les mimes
    list( $mime_type, $mime_subtype ) = explode( '/', $post['post_mime_type'] );

    // Je crée un contenu vide
    $content = '';

    // Si le média est une image de type JPEG
    if( $mime_type == 'image' && $mime_subtype == 'jpeg' ) {

        // Je peut modifier son excerpt aussi
	$post['post_excerpt'] = sprintf( 'Image %s (%s)', $post['post_name'], $mime_subtype );

        // Je lis les données EXIF du fameux $docpath
	$exif = wp_read_image_metadata( $docpath );

        // Si il y en a, pour chaque entrée trouvée je vais l'écrire dans la variable <em>$content</em>,
        // petite exception pour "created_timestamp" que je modifie en "Date" pour faire plus propre.
	if( $exif )
	     foreach( $exif as $key => $value )
	          if( $value )
		        if( $key == 'created_timestamp' )
			     $content .= 'Date: ' . date_i18n( get_option( 'date_format') . ' @ ' . get_option( 'time_format' ), $value ) . "n";
			else
			    $content .= ucwords( $key ) . ': ' . $value . "n";
    }

    // Puis je modifie le contenu si il n'est pas déjà renseigné
    $post['post_content'] = $post['post_content']!='' ? $post['post_content'] : $content;
    $post['post_title'] = $post['post_title']!='' ? $post['post_title'] : $post['post_name'];

    // Libre à vous de laisser ouvert les commentaires
    $post['comment_status'] = 'closed';
    $post['ping_status'] = 'closed';
    return $post;
}
add_filter('attachment_fields_to_save', 'olhk_attachment_fields_to_save', 10, 2 );











?>











































?>