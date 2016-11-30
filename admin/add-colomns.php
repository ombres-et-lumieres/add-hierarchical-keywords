<?php
function ol_post_add_columns($columns, $post_type)
{

	$columns["thumbnail"] = "post thumbnail";


return $columns;


}

add_filter('manage_posts_columns', 'ol_post_add_columns', 10, 2);




function ol_content_post_add_columns($columns_name, $post_id)
{
	$thumb_id = get_post_thumbnail_id($post_id);

	if ("post thumbnail" == $columns_name)
		{
			return   wp_get_attachment_image($thumb_id, "100px");
		}


}

add_action('manage_posts_custom_column', 'ol_content_post_add_columns', 10, 2);




function ol_media_add_columns($media_columns)
{
	$media_columns["hierarchical_keywords"] = "hierarchical keywords";

	return $media_columns;

}
add_filter("manage_media_columns", "ol_media_add_columns", 10, 1);



function ol_content_media_keywords_columns($columns_name, $attachment_id)
{
	if("hierarchical keywords" == $columns_name)
		{
			return printf( "c' est ici");
		}
}
add_action("manage_media_custom_columns", "ol_content_media_keywords_columns", 10,2);





















?>