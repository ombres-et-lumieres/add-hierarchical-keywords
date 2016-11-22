<?php



function ol_add_hierarchical_keywords($attachment_id)
{
	global $adobeXMP;

	$xmp = $adobeXMP->get_xmp( $attachment_id );

	$hierar_keywords = $xmp["Hierarchical Keywords"];

	if ( !empty( $hierar_keywords ) )
		{
			// Tableau pour stocker les ID de tous les termes enfants les plus bas de chaque sous tableau
			$terms_to_associate = array();

			foreach ( $hierar_keywords as $tabTerms )
				{

					$parent_id = 0;

					foreach( $tabTerms as $term )
						{ // Ici parcourt terme de chaque sous tableau

							// Ici, si le terme existe, on recupère un tableau, l'id du terme est $term_exists["term_id"]
							$term_exists = term_exists( $term, "hierarchical_keywords");

							// S'il existe pas on le créé
							if ( !$term_exists )
								{
									$term_insert = wp_insert_term( $term, "hierarchical_keywords", array( 'parent' => $parent_id ) );
									if ( !is_wp_error( $term_insert ) ) {
										// Si on a aucune erreur pour créer le terme
										$parent_id = $term_insert["term_id"];
									} /*
else
										{
											// Action à faire s'il y a une erreur à la création du terme
										}
*/

								}
								else
									{
										$parent_id = $term_exists["term_id"];
									}
					}

					// On a parcouru tout le sous tableau.
					// Logiquement, $parent_id = l'id du terme enfant le plus bas du sous tableau.
					if ( $parent_id != 0 )
						{
							$terms_to_associate[] = $parent_id; // On l'ajoute au tableau des termes à associer
						}

			}

		$terms_to_associate = array_map( 'intval', $terms_to_associate ); // On s'assure que tous les termes ID soient en int
		$association = wp_set_object_terms( $attachment_id, $terms_to_associate, "hierarchical_keywords", true );

		/*
		if ( is_wp_error( $association ) )
			{
				// Action à faire en cas d'erreur à l'association du terme avec le post type
			}
		*/
	}
}
add_action('add_attachment', 'ol_add_hierarchical_keywords', 15, 1);






// Register Custom Taxonomy
function hierarchical_keywords() {

	$labels = array(
		'name'                       => _x( 'hierarchical keywords', 'Taxonomy General Name', '\'ombres-et-lumieres' ),
		'singular_name'              => _x( 'hierarchical keyword', 'Taxonomy Singular Name', '\'ombres-et-lumieres' ),
		'menu_name'                  => __( 'Hierarchical Keywords', '\'ombres-et-lumieres' ),
		'all_items'                  => __( 'All Keywords', '\'ombres-et-lumieres' ),
		'parent_item'                => __( 'Parent Keywords', '\'ombres-et-lumieres' ),
		'parent_item_colon'          => __( 'Parent Keywords:', '\'ombres-et-lumieres' ),
		'new_item_name'              => __( 'New Keyword', '\'ombres-et-lumieres' ),
		'add_new_item'               => __( 'Add Keyword', '\'ombres-et-lumieres' ),
		'edit_item'                  => __( 'Edit Keyword', '\'ombres-et-lumieres' ),
		'update_item'                => __( 'Update Keyword', '\'ombres-et-lumieres' ),
		'view_item'                  => __( 'View Keyword', '\'ombres-et-lumieres' ),
		'separate_items_with_commas' => __( 'Separate Keywords with commas', '\'ombres-et-lumieres' ),
		'add_or_remove_items'        => __( 'Add or remove Keywords', '\'ombres-et-lumieres' ),
		'choose_from_most_used'      => __( 'Choose from the most used', '\'ombres-et-lumieres' ),
		'popular_items'              => __( 'Popular Keywords', '\'ombres-et-lumieres' ),
		'search_items'               => __( 'Search Keywords', '\'ombres-et-lumieres' ),
		'not_found'                  => __( 'Not Found', '\'ombres-et-lumieres' ),
		'no_terms'                   => __( 'No Keywords', '\'ombres-et-lumieres' ),
		'items_list'                 => __( 'Keywords list', '\'ombres-et-lumieres' ),
		'items_list_navigation'      => __( 'Keywords list navigation', '\'ombres-et-lumieres' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'query_var' => 'hierarchical_keywords'
	);
	register_taxonomy( 'hierarchical_keywords',  'attachment' , $args );

}
add_action( 'init', 'hierarchical_keywords', 1 );



?>