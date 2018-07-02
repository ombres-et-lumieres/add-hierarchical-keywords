<?php
/**
 * Filter the Media list table columns to add a URL column.
 *
 * @param array $posts_columns Existing array of columns displayed in the Media list table.
 * @return array Amended array of columns to be displayed in the Media list table.
 */






/* Ce code affiche les métas des photos dans la vue en grille d la bibliothèque, mais sans les rendre éditables, ce qu' il faudrait pouvoir faire, à minma avec le plugin "admin colomn" */

/* pas utile dans un premier temps */


function oetl_media_columns_copyright( $posts_columns )
{
	$posts_columns['media_copyright'] = 'Copyright';

	$posts_columns["auteur_photo"] = "photo author";

	$posts_columns["headline"] = "headline";

	$posts_columns["my_title"] = "title";

	$posts_columns["Description"] = "Description";

	$posts_columns["pays"] = "pays";

	$posts_columns["city"] = "city";

	$posts_columns["Location"] = "Location";

	$posts_columns["Creation_Date"] = "Creation Date";


	return $posts_columns;
}
add_filter( 'manage_media_columns', 'oetl_media_columns_copyright' );











/**
 * Display URL custom column in the Media list table.
 *
 * @param string $column_name Name of the custom column.
 */
function oetl_media_custom_column_copyright( $column_name )
{

	$attachment_metadata = wp_get_attachment_metadata();


	if ("media_copyright" == $column_name )
	{
		echo $attachment_metadata["oetl"]["Copyright"];
	}

	if ("auteur_photo" == $column_name )
	{
		echo $attachment_metadata["oetl"]["Author"];
	}

	if ("headline" == $column_name )
	{
		echo $attachment_metadata["oetl"]["headline"];
	}

	if ("my_title" == $column_name )
	{
		$attachment_metadata = wp_get_attachment_metadata();

		echo $attachment_metadata["oetl"]["title"];
	}

	if ("Description" == $column_name )
	{
		echo $attachment_metadata["oetl"]["Description"];
	}

	if ("pays" == $column_name )
	{
		echo $attachment_metadata["oetl"]["Country"];
	}

	if ("city" == $column_name )
	{
		echo $attachment_metadata["oetl"]["City"];
	}

	if ("Location" == $column_name )
	{
		echo $attachment_metadata["oetl"]["Location"];
	}

	if ("Creation_Date" == $column_name )
	{
		echo $attachment_metadata["oetl"]["Creation_Date"];
	}

}
add_action( 'manage_media_custom_column', 'oetl_media_custom_column_copyright' );









/**
 * Add custom CSS on Media Library page in WP admin
 */
function oetl_copyright_column_css() {
	echo '<style>
			@media only screen and (min-width: 1400px) {
				.fixed .column-media_url {
					width: 15%;
				}
			}
		</style>';
}
//add_action( 'admin_print_styles-upload.php', 'oetl_copyright_column_css' );




?>


