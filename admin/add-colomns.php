<?php
function ol_post_add_columns($colomn, $post_type)
{

	$colomn["thumbnail"] = "post thumbnail";


return $colomn;


}

add_filter('manage_posts_columns', 'ol_post_add_columns', 10, 2);




function ol_content_post_add_colomn($colomn_name, $post_id)
{
	$thumb_id = get_post_thumbnail_id($post_id);

	if ("post thumbnail" == $colomn_name)
		{
			//echo   wp_get_attachment_image($thumb_id, "100px");

			echo $post_id;
		}


}

add_action('manage_posts_custom_column', 'ol_content_post_add_colomn', 10, 2);
































?>