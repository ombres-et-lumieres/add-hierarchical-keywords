<?php


function olhk_page()
{
    add_menu_page (
						        'Hierarchical keywords settings',
						        'Hierarchical keywords settings',
						        'manage_categories',
						        'hierarchical_keywords',
						        'olhk_page_html',
								'dashicons-camera'
						        //20
						    );
}
add_action('admin_menu', 'olhk_page');











function olhk_settings_page()
{
    // page to define the thumbnail sizes
    add_submenu_page (
						       'hierarchical_keywords',
						        'Thumbnails settings',
						        'Thumbnails settings',
						        'manage_categories',
						        'thumbnails_settings',
						        'olhk_thumb_settings_page_html'
						    );


	//page to define how to display the galeries
    add_submenu_page (
						       'hierarchical_keywords',
						        'Galeries settings',
						        'Galeries settings',
						        'manage_categories',
						        'galeries_settings',
						        'olhk_galleries_settings_page_html'
						    );


}
add_action('admin_menu', 'olhk_settings_page');









































?>