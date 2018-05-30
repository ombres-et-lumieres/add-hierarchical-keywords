<?php

/*
La raison d' être de ce plugin: l' ajout d' une taxonomie permettant de reprendre les mots clefs, vocabulaire photo, tels que définis dans Lightroom, c' est à dire de manière hiérarchique, et les enregistrer en base de données de la même façon.

Viennent ensuite une série de fonctions permettant leur affichage en front


Ce qui manque: la gestion des mots clefs: que faire s' il est modifié, si la seule photo à l' utiliser est retirée, ... .

Pour mon usage personnel, j' utilise le plugin wp/lr de façon à gérer ma bibliothèque d' images depuis le logiciel Lightroom ce qui résoud le problème.

*/






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
		'query_var' 				 => 'hierarchical_keywords',
		'update_count_callback'		 => '_update_generic_term_count',
		'rewrite'					 => array(
											  'slug' => 'photos',
											  'with_front' => true,
											  'hierarchical' => true
											  )
	);
	register_taxonomy( 'hierarchical_keywords',  array('attachment') , $args );

}
add_action( 'init', 'hierarchical_keywords', 1 );






/* ajout des mots clefs dans la bd pour la taxo "hierarchical_keywords" */



function ol_add_hierarchical_keywords($attachment_id)
{
    global $adobeXMP;

    $xmp = $adobeXMP->get_xmp( $attachment_id );

    $hierar_keywords = $xmp["Hierarchical Keywords"];

    if ( !empty( $hierar_keywords ) )
    {
        // Tableau pour stocker les ID de tous les termes enfants les plus bas de chaque sous tableau
        $terms_to_associate = array();

        foreach ( $hierar_keywords as $keyPrincipale => $tabTerms )
        {
            $parent_id = 0;

            foreach( $tabTerms as $key =>$term )
            {
                // Ici parcourt terme de chaque sous tableau

                // Ici, si le terme existe, on recupère un tableau, l'id du terme est $term_exists["term_id"]

                if ( 0 == $parent_id )
                {
                    // Cas où parent_id = 0, c'est à dire qu'on est dans le plus haut niveau ! Dans le tableau d'exemple c'est "Pays" pour chaque sous tableau
                    // Donc il n'y a pas de parent, c'est le keyword racine, il ne peut pas y avoir de doublon
                    $term_exists = term_exists( $term, "hierarchical_keywords");
                }
                else
	                {
	                    // Cas où parent_id != 0 donc on est dans "Portugal", "Madeira" ou "Funchal"
	                    // On vérifie si le terme existe déjà avec l'id du parent
	                    // Exemple avec le dernier sous tableau
	                    // Premier element : Pays, parent_id = 0, on rentre pas dans ce cas. On imagine que l'id du terme "Pays" est 1
	                    // 2e element : Portugal. On a parent_id = 1, on vérifie si le terme existe avec comme parent "Pays" On imagine que ID de Portugal = 2 donc parent_id devient 2
	                    // 3e element : Madeira. On a parent_id = 2, On imagine que ID Madeira = 3, donc parent_id devient 3
	                    // Etc.
	                    $term_exists = term_exists( $term, "hierarchical_keywords", intval($parent_id) );
	                }

                // S'il existe pas on le créé
                if ( !$term_exists )
                {
                    $term_insert = wp_insert_term( $term, "hierarchical_keywords", array( 'parent' => intval($parent_id ) ) );
                    if ( !is_wp_error( $term_insert ) )
                    {
                        // Si on a aucune erreur pour créer le terme
                        $parent_id = $term_insert["term_id"];
                    }
                }
                else
	                {
	                    $parent_id = $term_exists["term_id"];
	                }
                if ( 0 == $key)  // j' ai supprimé 0== $keyPrincipale parce qu' alors on n' ajoute le terme parent que pour le premier sous tableau rencontré
                {
                    wp_set_object_terms($attachment_id, intval($parent_id),  "hierarchical_keywords", true);
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
    }
}
add_action('add_attachment', 'ol_add_hierarchical_keywords', 15, 1);







/* réorganise une taxonomie hiérarchique en une suite d' ojects hiérarchiques, d' après un script de Matthias Baragoin on slack */

function ol_get_taxonomy_hierarchy($taxonomy, $parent = 0, $level = 0 ) {
        // only 1 taxonomy
        $taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;
        // get all direct decendants of the $parent


        $terms = get_terms( array(
								    'taxonomy' => 'hierarchical_keywords',
								    'hide_empty' => false,
								    'parent' => $parent,
								) );



        // prepare a new array.  these are the children of $parent
        // we'll ultimately copy all the $terms into this new array, but only after they
        // find their own children

        $children = array();

        // go through all the direct decendants of $parent, and gather their children

        foreach ( $terms as $term )
	        {
	            //$term->price = get_field('field_58e9f311e1db9', $term );
	            $term->level = $level;
	            // recurse to get the direct decendants of "this" term
	            $term->children = ol_get_taxonomy_hierarchy($taxonomy, $term->term_id,$level +1 );
	            // add the term to our new array
	            $children[ $term->term_id ] = $term ;
	        }
        // send the results back to the caller
        return $children;
    }


/* réorganise les données trouvées par le script précédent dans un tableau de tableaux dont la clef est l' id du terme et les valeurs sont "parent", "name" et "children" */

function ol_tableau($children)
{
	$exclus = array("état de la photo", "avec personnages", "membres Ars Varia", "Anka", "Brigitte", "Céline D", "François", "Geneviève", "Inessa", "Isa", "laura", "Laura UE", "Simona", "Tiziana", "Virginie", "Katia M.", "Michaela", "Monika", "Stanislava", "Walléria", "Mariana", "Uliana Elina");


	$taxo = array();

	foreach ($children as $child)
	{
		if (!in_array($child -> name, $exclus))
			{
				$term_id = $child -> term_id;

				$taxo[$term_id] = array ("parent" => $child -> parent, "name" => $child -> name, "children" => "");

				if (!empty($child -> children))
					{
						$enfants = ol_tableau($child -> children);

						$taxo[$term_id] = array ("parent" => $child -> parent, "name" => $child -> name, "children" => $enfants);

					}
			}
	}
	return $taxo;
}


/* organise la fonction précédente pour le front avec son html */

function ol_tri_hierarchical($taxo_terms)
{
	foreach($taxo_terms as $term_id => $term)
	{

		if(0 == $term["parent"])
			{
				echo "<ul class= 'groupe'> ";

				$term_children = get_term_children($term_id, "hierarchical_keywords");

				if (0 != count($term_children))
					{
						echo "<h4 class = 'titre-groupe' >". $term["name"] . "</h4>";
					}
					else
						{
							$term_link = get_term_link($term_id, 'hierarchical_keywords');

							echo '<h4 class = "titre-groupe" ><a href="' . esc_url( $term_link ) . '">' . $term["name"] . '</a></h4>';
						}
			}
			else
				{
					echo "<ul class= 'keywords'> ";

					$term_link = get_term_link($term_id, 'hierarchical_keywords');

					if ( is_wp_error( $term_link ) ) { continue; }

					echo '<li class = "keyword" ><a href="' . esc_url( $term_link ) . '">' . $term["name"] . '</a></li>';
						}



		if (!empty($term["children"]))
		{
			$children_terms = $term["children"];

			$terms_links =  ol_tri_hierarchical($children_terms);
		}

		echo "</ul>";
	}

}
add_action("ol_tri_hierarchical", "ol_tri_hierarchical", 20, 1);





/* fonction pour créer un tag cloud personalisé: on retire les mots clefs dont les parents sont à 0 pour les hierarchical keywords ainsi  que le terme "avec personnage et ses enfants*/

function ol_hierarchical_keywords_cloud($taxo_name)
{
	$children = ol_get_taxonomy_hierarchy($taxo_name);

	$taxo = ol_tableau($children);



	?><h3 class="titre-keywords" > Recherche par mots clefs </h3><?php

	 do_action("ol_tri_hierarchical", $taxo);

}
add_action("ol_hierarchical_keywords_cloud", "ol_hierarchical_keywords_cloud", 10, 1);




/* réorganise les termes d' une taxonomie hiérarchique pour une image en une suite d' ojects hiérarchiques, d' après un script de Matthias Baragoin on slack */

function ol_get_img_terms_hierarchy($object_id,$taxonomy, $parent = 0, $level = 0 ) {
        // only 1 taxonomy
        $taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;
        // get all direct decendants of the $parent


		$terms = wp_get_object_terms( $object_id, $taxonomy, array( 'parent' => $parent ) );



        // prepare a new array.  these are the children of $parent
        // we'll ultimately copy all the $terms into this new array, but only after they
        // find their own children

        $children = array();

        // go through all the direct decendants of $parent, and gather their children

        foreach ( $terms as $term )
	        {
	            //$term->price = get_field('field_58e9f311e1db9', $term );
	            $term->level = $level;
	            // recurse to get the direct decendants of "this" term
	            $term->children = ol_get_taxonomy_hierarchy($taxonomy, $term->term_id,$level +1 );
	            // add the term to our new array
	            $children[ $term->term_id ] = $term ;
	        }
        // send the results back to the caller
        return $children;
    }




/* fonction pour créer un tag cloud personalisé: on retire les mots clefs dont les parents sont à 0 pour les hierarchical keywords ainsi  que le terme "avec personnage et ses enfants*/

function ol_hierarchical_img_keywords_cloud($object_id, $taxo_name)
{
	$children = ol_get_img_terms_hierarchy($object_id, $taxo_name);

	$taxo = ol_tableau($children);


	do_action("ol_tri_hierarchical", $taxo);

}
add_action("ol_hierarchical_img_keywords_cloud", "ol_hierarchical_img_keywords_cloud", 10, 2);




?>