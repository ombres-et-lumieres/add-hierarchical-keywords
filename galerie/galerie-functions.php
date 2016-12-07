<?php

function images_setup()
  {
	if ( function_exists( 'add_image_size' ) )
		{
			add_image_size( '1000px', 1000, 1000 );
			add_image_size( '700px', 700, 700 );
			add_image_size( '400px', 400, 400 );
			add_image_size( '375px', 375, 375 );
			add_image_size( '300px', 300, 300 );
			add_image_size( '250px', 250, 250 );
			add_image_size( '200px', 200, 200 );
			add_image_size( '225px', 225, 225 );
			add_image_size( '150px', 150, 150 );
			add_image_size( '100px', 100, 100 );
		}
  }
add_action( 'after_setup_theme', 'images_setup' );


function thumbnail_good_size($size, $attachment_id)
{
	$thumb_size =$size . "px";

	$datas_imagette = wp_get_attachment_image_src( $attachment_id, $thumb_size);

	if($datas_imagette )
		{

			$height = $datas_imagette[2];

			$width = $datas_imagette[1];
		}



	if ( ($height < $width) )    // la mesure de base est la hauteur de la photo
	//ici on teste le rapport largeur/hauteur et on décide de de la taille d' imagette à utiliser

		{
			switch ($thumb_size)
				{

					case "100px":
						$thumb_size = "150px";
						break;

					case "150px":
						$thumb_size = "225px";
						break;

					case "200px":
						$thumb_size  = "300px";
						break;

					case "250px":
						$thumb_size  = "375px";
						break;

				}
		}

	return $thumb_size;

}



function get_image_sizes() {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	$liste_sizes = array();

	foreach ( $sizes as $size)
		{
			$size_name = $size["width"] .  __("px in the largest side", "ombres-et-lumieres");

			array_push($liste_sizes, $size_name);
		}



	return $liste_sizes;
}



function galery_attachments($attr)
{
	global $post, $wp_locale;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => '',
        'icontag'    => '',
        'captiontag' => '',
        'columns'    => '',
        'size'       => '',  // via la personalistion du site, liste des tailles disponibles
        'include'    => '',
        'exclude'    => ''
    ), $attr));



    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }


    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

	$attachments_ids = array();

	 foreach ( $attachments as $id => $attachment )
	 {
		 array_push($attachments_ids, $id);
	 }

    $order = get_theme_mod( 'oetl_order' ,  "chronologique");

    if ( "chronologique" == $order)
    {
		$attachments_ids = tri_chronologique($attachments_ids);
    }


return $attachments_ids;
}



function tri_chronologique($photos_ids)
	{

		foreach ( $photos_ids as $photo_id )
			{

				$metas = wp_get_attachment_metadata( $photo_id );

				$full_name = $metas["file"];

				$file_name = explode( ".", $full_name)[0];

				$photos_list [ $photo_id ]  =   $file_name  ;

			}
		asort($photos_list);


		$photos = array();

		foreach ($photos_list as $photo_id  => $name)
			{
				array_push($photos, $photo_id);
			}


		return $photos;
	}


function oetl_post_thumbnail($post_id)
{
	 $post_thumbnail_id = get_post_thumbnail_id( $post_id );

	if (is_front_page())
		{
			$thumb_size = get_theme_mod('oetl_blog_post_thumbnails_size',  '400');
		}
		elseif(in_category("journal", $post_id) and is_archive())
			{
				$thumb_size = get_theme_mod('oetl_blog_post_thumbnails_size',  '700');
			}
			elseif(in_category("portfolio", $post_id))
				{
					$thumb_size = get_theme_mod('oetl_portfolio_post_thumbnails_size',  '200');
				}
				elseif ("page-templates /photos-page.php" !=get_page_template_slug($post_id) )
					{
						$thumb_size = 300;
					}




				else
					{
						$thumb_size = get_theme_mod( 'oetl_post_thumbnails_size',   '200');
					}

	 $thumb_size .= "px";



	$image =  wp_get_attachment_image_src( $post_thumbnail_id, $thumb_size);

	$thumb = array(
								"scr" => wp_get_attachment_image($post_thumbnail_id, $thumb_size),
								"width" => $image[1],
								"height" => $image[2]
	);

	return $thumb;
}



/* ***************** */
/* ajout de classes */
/* **************** */

function category_class( $classes ) {
	global $post;
	global $wp_query;

	$post_id = $post->ID;


			if (is_attachment($post_id))
				{
					$args = array(
											"fields" => "names"
										);

					$attachment_terms = wp_get_post_terms($post_id, "photos_keywords", $args);

					$classes[] = array_diff($classes, $attachment_terms);

					$classes[] = "AAAAAAAAAAAAAAAAAAAAAAAA";
				}

	$thumb = oetl_post_thumbnail($post_id);
	if ($thumb["width"]>$thumb["height"])
		{
			$sens = "horizontale";
		}
		else
			{
				$sens = "verticale";
			}

$classes[] = $sens;



	return $classes;
}
add_filter( 'post_class', 'category_class' );







/**
 * Get an attachment ID given a URL.
 *
 * @param string $url
 *
 * @return int Attachment ID on success, 0 on failure
 *
 *http://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
 *
 */
function ol_get_attachment_id( $url ) {
	$attachment_id = 0;
	$dir = wp_upload_dir();
	if ( false !== strpos( $url, $dir['baseurl'] . '/' ) )   // Is URL in uploads directory?
	{
		//$file = basename( $url );

		$file = explode("/", $url);


	    $position = count($file)-1;
	    $file = $file[$position];


		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			)
		);
		$query = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {
				$meta = wp_get_attachment_metadata( $post_id );
				$original_file       = basename( $meta['file'] );
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
				if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
					$attachment_id = $post_id;
					break;
				}
			}
		}
	}
	return $attachment_id;
}




/* ******************************** */
/* display html for normal galeries*/
/* **************** ****************/

function ol_galery_html( $attachments_ids, $size)
{
    $list = "";

    foreach ( $attachments_ids as $id)
    {
		$href = wp_get_attachment_url($id);
		$thumb_size = thumbnail_good_size($size, $id);

		$datas_imagette = wp_get_attachment_image_src( $id, $thumb_size);

		$width = $datas_imagette[1];

		$src = wp_get_attachment_image($id, $thumb_size);
		$link = '<a  class = "pop-up"    rel = "lightbox"  href =" ' .   $href   . ' "  ><section class="entry-post-thumbnail" > ' . $src . ' </section><section class="extrait-texte"> ' .  get_the_excerpt($id)  . '</section></a>';

        $list .= '<li class="gallery-item  px' .  $size    . ' " width = " ' . $width . 'px" ><section class="entry-content">' . $link . '</section></li>';
     }
      return $list;
}






/* ***************** *******************/
/* display html for masonry galleries */
/* **************** *******************/

function ol_masonry_galery_html($attachments_ids, $size)
{
   	$list = "";

    foreach ( $attachments_ids as $id)
    {
		$href = wp_get_attachment_url($id);

		$thumb_size = $size . "px";

		$datas_imagette = wp_get_attachment_image_src( $id, $thumb_size);

		$width = $datas_imagette[1];

		$attr = xmp_title($id);


		$src = wp_get_attachment_image($id, $thumb_size);
		$link = '<a  class = "pop-up"    rel = "lightbox"  href =" ' .   $href   . ' "  > ' . $src . ' </a>';

        $list .= '<li class="gallery-item masonry-pict px' .  $size    . ' " width = " ' . $width . 'px" >' . $link . '</li>';
     }
      return $list;

}




function xmp_title($atts, $attachment)
	{
		$attachment_id = $attachment -> ID;

		global $adobeXMP;
		$xmp = $adobeXMP->get_xmp( $attachment_id );

		 $attachment_metadata = wp_get_attachment_metadata( $attachment_id);


		 $atts["alt"] = $xmp["Headline"] . ",  une photo d' " .  $attachment_metadata ["papt_meta"]["exif"]["Artist"] .  ", pour le compte de: " . $attachment_metadata ["papt_meta"]["exif"]["Copyright"];

		 return $atts;

	}
//add_filter( 'wp_get_attachment_image_attributes', 'xmp_title', 10, 2 );








function photos_excerpt($the_excerpt)
{
	global $post;

	$post_id = $post -> ID;

	$post_type = get_post_type($post_id);

	if ("attachment" == $post_type)
		{
			global $adobeXMP;
			$xmp = $adobeXMP->get_xmp( $post_id );
			$the_excerpt = $xmp["Headline"];
		}
	return $the_excerpt;
}

add_filter("the_excerpt", "photos_excerpt", 11, 1);



























?>