<?php

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




/* recherche tous les id des mots clefs enfants de "avec personnage" */


function exclude_personnages()
{
	/*on récupère l' iD du terme parent "avec personnages */
	$term_id = get_term_by("name","avec personnages", "hierarchical_keywords") -> term_id;

	/* on recherce ses enfants directs */
	$children = get_term_children($term_id, "hierarchical_keywords");

	/* le premier élément à stocker est l' ID parent */
	$personnages[] = $term_id;

	foreach ($children as $child)
		{
			$personnages[] = $child;
		}
	return $personnages;
}


/* fonction pour créer un tag cloud personalisé: on retire les mots clefs dont les parents sont à 0 pour les hierarchical keywords ainsi  que le terme "avec personnage et ses enfants*/



function ol_hierarchical_keywords_cloud($queried_term_id)
{
	$exclude = exclude_personnages();

	$terms =  get_terms(
								array (
										"taxonomy" => "hierarchical_keywords",
										"hide_empty" => false
										)
								);
	foreach($terms as $term)
		{
			if (0 == $term -> parent)
				{
					$exclude[]= intval($term -> term_id);
				}
		}

	$exclude[]= $queried_term_id;

	$args = array (
							'link' => 'view',
							'taxonomy' => "hierarchical_keywords",
							'unit'          => 'em',
							'smallest'   => 1,
							'largest'     => 2,
							'number'   => 25,
							'hide_empty' => false,
							'separator'  => '    ',
							'format'  => 'list',
							'exclude' => $exclude
						);

				 wp_tag_cloud($args);
}
add_action("ol_hierarchical_keywords_cloud", "ol_hierarchical_keywords_cloud", 10, 1);


/* filtre les terms pour la taxo hierarchical keywords pour supprimer les mots clefs dont le parent est 0  ainsi  que le terme "avec personnage et ses enfants*/


function ol_hierarchical_keywords_terms($terms, $attachment_id, $taxonomy)
{
	if(("attachment" == get_post_type($attachment_id)) and ("hierarchical_keywords" == $taxonomy) )
		{
			$personnages = exclude_personnages();

			$ol_terms = array();

			foreach ($terms as $term)
				{
					$term_id = $term -> term_id;

					if ((0 != $term -> parent) and (!in_array( $term_id, $personnages)))
						{
							$ol_terms[] = $term;
						}
				}

				return $ol_terms;
		}
}
add_filter("get_the_terms", "ol_hierarchical_keywords_terms", 10, 3);








/* modification du nombre d' attachment visible par page d' archive pour la taxo "hierarchical_keywords" */

// cela ne fonctionnne pas

function ol_display_archive_attachment( $query )
{
	if( !is_admin() && "hierarchical_keywords" == $query->get("taxonomy"))
	{
		$query->set( 'posts_per_page', 100 );
	}

}
add_action( 'pre_get_posts', 'ol_display_archive_attachment', 10, 1 );







/******************************************************************************************************************************
deux fonctions pour récupérer les termes d' une taxonomie hiérarchique sous la forme d' une suite de tableaux respectant cette hiérarchie.
on pose la donnée suivante: $terms = get_the_terms( $attachment_id, "hierarchical_keywords", "ombres-et-lumieres");
**************************************************************************************************************************/



//Encore à tester




//fonction qui construt le tableau final

/*
function make_array($terms)
{
	$hierarchical_keywords = array();

	foreach ($terms as $key => $term)
		{
			// si le terme n' est pas u Ancêtre, je lance la recherche pour les trouver tous
			if (0 != $term -> parent)
				{
					$hierarchical_keywords[] = search_origin($terms, $term);
				}
		}
	return $hierarchical_keywords;
}
*/



//fonction récursive pour remonter à l' ancêtre ultime et en stockant les termes au passage
/*
function search_origin($terms ,$term)
{
	if (0 !=$term -> parent)
		{
			$tab_terms[] = $term -> name;

			// on parcourt le tableau pour retrouver les ancêtres du $term
			foreach ($terms as $term_tab)
				{
					if($term -> parent = $term_tab -> term_id)
						{ // si l' élément est un ancêtre, on le met dans le tableau $tab_terms
							$tab_terms[] = $term_tab -> name;
						}
						else
							{ // sinon on reprend la recherche
								search_origin($terms ,$term_tab);
							}
				}
		} // on sort de la boucle infernale lorsque on a un parent id à o
		else
		{  // et, donc, on retourne le tableau
			return $tab_terms;
		}
}
*/











?>