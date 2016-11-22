<?php

/* http://wordpress.stackexchange.com/questions/4343/how-to-customise-the-output-of-the-wp-image-gallery-shortcode-from-a-plugin
et le codex:  https://developer.wordpress.org/reference/functions/wp_get_attachment_link/*/



add_filter( 'post_gallery', 'ol_post_gallery', 10, 2 );
function ol_post_gallery( $output, $attr)
{
	global $post;

	$attachments_ids =  galery_attachments( $attr);

	if(is_woocommerce())
	{
		$size = get_theme_mod( 'oetl_thumbnail_woo_size',  '200');
	}
	else
		{
			$size = get_theme_mod( 'oetl_thumbnail_size',  '150');
		}

	if("page-templates /portfolio.php" !=get_page_template_slug($post->id) )
		{
			$output = "<ul class = 'gallery'  >";

			$output .= ol_galery_html( $attachments_ids, $size);
		}
		else
			{
				$size = get_theme_mod( 'oetl_portfolio_post_thumbnails_size',  '200');

				$output = "<ul class = 'gallery  grid-pict' ><div class = 'pict-sizer' ></div>";

				$output .= ol_masonry_galery_html($attachments_ids, $size);
			}



    $output .= "</ul></div>";

    return $output;
}

































?>