<?php
/*
Plugin Name: add-hierarchical-keywords new
Plugin URI: http://ombres-et-lumieres.eu
Description: récupération des mots clefs des photos et autre éléments nécessaires
Version: 1.0.0
Author: Eric Wayaffe
Author URI: ombres-et-lumieres.eu
 License: GPL2
*/




if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');



define( 'HIERARCHICAL_KEYWORDS_URL', plugin_dir_url( __FILE__ ) );





function olhk_load_text_domain()
{
	load_plugin_textdomain("ol_hierarchical_keywords", false, 'WP_PLUGIN_DIR' . "/languages");
}
add_action("init", "olhk_load_text_domain");


defined('ABSPATH') or die("No script kiddies please!");














include( "actions/photo-metas.php");
include("actions/photos-taxo.php");
include("actions/add-colomns.php");
include( "galerie/galerie-functions.php");
include("galerie/galerie.php");
include("admin/view.php");




function add_styles()
{
	wp_register_style( 'add-galeries-to-posts',   plugin_dir_url( __FILE__ )."galerie/css-galerie.css" );

	wp_enqueue_style( 'add-galeries-to-posts' );

/*
	wp_register_script("keywords",  plugin_dir_url( __FILE__ )."js/keywords.js", array());

	wp_enqueue_script("keywords",  plugin_dir_url( __FILE__ )."js/keywords.js", array());
*/

}
add_action( 'wp_enqueue_scripts', 'add_styles' );






























?>