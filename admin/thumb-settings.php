<?php

/**
 * top level menu:
 * callback functions
 */

function olhk_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_categories')) {
        return;
    }  ?>

    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <h2><?php  esc_html_e("How to use this plugin", "ol_hierarchical_keywords")      ?></h2>
		<?php
			esc_html_e("This plugin adds a lot of sizes to the usual ones to help you displaying your photos on your website. The photos setting page is there to let you explain which sizes you want to use and where.   The galery settings page explains how you want the galeries to be displayed: using a classic html way, with the same height for all the photos, or the masonry way, with all the pictures' surfaces identical. On the same page you have the possibility to tell how you want the galery to be ordered: they will be sort by alphabetic, by date or manually, as you did in the wordpress gallery.", "ol_hierarchical_keywords")
			?>
    </div>
<?php
}














function olhk_thumb_settings_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_categories')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('olhk_messages', 'olhk_message', __('Settings Saved', 'ol_hierarchical_keywords'), 'updated');
    }

    // show error/update messages
    settings_errors('olhk_messages');
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "olhk"
            settings_fields('olhk_thumbnails');
            // output setting sections and their fields
            // (sections are registered for "olhk", each field is registered to a specific section)
            do_settings_sections('olhk_thumbnails');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}









/**
 * @internal    never define functions inside callbacks.
 *              these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function olhk_thumb_settings_init()
{
    // register a new setting for "Thumbnails_settings" page
    register_setting('olhk_thumbnails', 'olhk_thumbnails_options');


    // registerthe general section in the "Thumbnails_settings" page

    add_settings_section(
        'olhk_section_thumb_size',
       __('choose the thumbnail size for all galeries' , 'ol_hierarchical_keywords'),
        'olhk_section_thumb_size_cb',
        'olhk_thumbnails'
    );

    // register a new field for the thubnail general setting
    add_settings_field(
        'olhk_field_thumb_size', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('the thumnail sizes','ol_hierarchical_keywords'),
        'olhk_field_sizes_cb',
        'olhk_thumbnails',
        'olhk_section_thumb_size',
        [
            'label_for'         => 'olhk_field_sizes',
            'class'             => 'olhk_row',
            'olhk_custom_data' => 'custom',
        ]
    );


     // register a new section for the wooo commerce settings
    add_settings_section(
        'olhk_section_woothumb_size',
       __('choose the thumbnail size for woocommerce galeries' , 'ombres-et-lumieres'),
        'olhk_section_woothumb_size_cb',
        'olhk_thumbnails'
    );

    // register a new field ifor the woocommerce setting
    add_settings_field(
        'olhk_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('the woocommerce thumbnail sizes','ol_hierarchical_keywords'),
        'olhk_field_woosizes_cb',
        'olhk_thumbnails',
        'olhk_section_woothumb_size',
        [
            'label_for'         => 'olhk_field_woosizes',
            'class'             => 'olhk_row',
            'olhk_custom_data' => 'custom',
        ]
    );



       // register a new section for the general post thumbnail
    add_settings_section(
        'olhk_section_postthumb_size',
       __('choose the size for the posts thumbnails' , 'ombres-et-lumieres'),
        'olhk_section_postthumb_size_cb',
        'olhk_thumbnails'
    );

    // register a new field for the general post thumbnail
    add_settings_field(
        'olhk_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('the post thumnail size','ol_hierarchical_keywords'),
        'olhk_field_postsize_cb',
        'olhk_thumbnails',
        'olhk_section_postthumb_size',
        [
            'label_for'         => 'olhk_field_sizes',
            'class'             => 'olhk_row',
            'olhk_custom_data' => 'custom',
        ]
    );

    // register a new section for the home post thumbnail
    add_settings_section(
        'olhk_section_homethumb_size',
       __('choose the post thumbnail size for the home page' , 'ombres-et-lumieres'),
        'olhk_section_homethumb_size_cb',
        'olhk_thumbnails'
    );

    // register a new field for the home post thumbnail
    add_settings_field(
        'olhk_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('the homepage post thumnail sizes','ol_hierarchical_keywords'),
        'olhk_field_homesizes_cb',
        'olhk_thumbnails',
        'olhk_section_homethumb_size',
        [
            'label_for'         => 'olhk_field_sizes',
            'class'             => 'olhk_row',
            'olhk_custom_data' => 'custom',
        ]
    );



    // register a new section ifor the blog post thumbnail
    add_settings_section(
        'olhk_section_blogthumb_size',
       __('choose the thumbnail size for the blog page' , 'ombres-et-lumieres'),
        'olhk_section_blogthumb_size_cb',
        'olhk_thumbnails'
    );

    // register a new field for the blog page post thumbnail
    add_settings_field(
        'olhk_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('the blog thumnail sizes','ol_hierarchical_keywords'),
        'olhk_field_blogsizes_cb',
        'olhk_thumbnails',
        'olhk_section_blogthumb_size',
        [
            'label_for'         => 'olhk_field_sizes',
            'class'             => 'olhk_row',
            'olhk_custom_data' => 'custom',
        ]
    );



    // register a new section for the portfolio page post thumbnail
    add_settings_section(
        'olhk_section_portfoliothumb_size',
       __('choose the thumbnail size for the portfolio page' , 'ombres-et-lumieres'),
        'olhk_section_portfoliothumb_size_cb',
        'olhk_thumbnails'
    );

    // register a new field in the "olhk_section_developers" section, inside the "olhk" page
    add_settings_field(
        'olhk_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('the portfolio thumnail sizes','ol_hierarchical_keywords'),
        'olhk_field_portfoliosizes_cb',
        'olhk_thumbnails',
        'olhk_section_portfoliothumb_size',
        [
            'label_for'         => 'olhk_field_sizes',
            'class'             => 'olhk_row',
            'olhk_custom_data' => 'custom',
        ]
    );









}

/**
 * register our olhk_settings_init to the admin_init action hook
 */
add_action('admin_init', 'olhk_thumb_settings_init');

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function olhk_section_thumb_size_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__("all thumbnails' galleries will have the same size, except the woocommerce one, if it is activated", 'ol_hierarchical_keywords'); ?></p>
    <?php
}

function olhk_section_woothumb_size_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('the sizes for the woocommerce galleries', 'ol_hierarchical_keywords'); ?></p>
    <?php
}




function olhk_section_postthumb_size_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('The size for the post thumnail (displayed with te post excerpt). The value is the same for the whole site, except some special pages as the blog, the homepage and the portfolio ones.', 'ol_hierarchical_keywords'); ?></p>
    <?php
}

function olhk_section_homethumb_size_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('the size for the post thumbnail on the home page', 'ol_hierarchical_keywords'); ?></p>
    <?php
}

function olhk_section_blogthumb_size_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('the size for the post thumbnail on the blog page', 'ol_hierarchical_keywords'); ?></p>
    <?php
}


function olhk_section_portfoliothumb_size_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('the size for the post thumbnails on the portfolio page', 'ol_hierarchical_keywords'); ?></p>
    <?php
}




// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function olhk_field_sizes_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('olhk_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['olhk_custom_data']); ?>" name="olhk_options[<?= esc_attr($args['label_for']); ?>]" >

        <option value="100" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '100', false)) : (''); ?>>
            <?= esc_html('100 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="150" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '150', false)) : (''); ?>>
            <?= esc_html('150 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="200" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '200', false)) : (''); ?>>
            <?= esc_html('200 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="250" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '250', false)) : (''); ?>>
            <?= esc_html('250 px', 'ol_hierarchical_keywords'); ?>
        </option>

    </select>

    <p class="description">
        <?= esc_html('This size will be used on the whole website except on woocommerce, if the plugin is activated.', 'ol_hierarchical_keywords'); ?>
    </p>


    <?php
}



function olhk_field_woosizes_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('olhk_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['olhk_custom_data']); ?>" name="olhk_options[<?= esc_attr($args['label_for']); ?>]" >

        <option value="100" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '100', false)) : (''); ?>>
            <?= esc_html('100 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="150" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '150', false)) : (''); ?>>
            <?= esc_html('150 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="200" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '200', false)) : (''); ?>>
            <?= esc_html('200 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="250" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '250', false)) : (''); ?>>
            <?= esc_html('250 px','ol_hierarchical_keywords'); ?>
        </option>

    </select>

    <p class="description">
        <?= esc_html('This size will be used in the woocommerce galeries.', 'ol_hierarchical_keywords'); ?>
    </p>


    <?php
}



function olhk_field_postsize_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('olhk_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['olhk_custom_data']); ?>" name="olhk_options[<?= esc_attr($args['label_for']); ?>]" >

        <option value="100" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '100', false)) : (''); ?>>
            <?= esc_html('100 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="150" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '150', false)) : (''); ?>>
            <?= esc_html('150 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="200" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '200', false)) : (''); ?>>
            <?= esc_html('200 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="250" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '250', false)) : (''); ?>>
            <?= esc_html('250 px', 'ol_hierarchical_keywords'); ?>
        </option>

          <option value="300" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '300', false)) : (''); ?>>
            <?= esc_html('300 px', 'ol_hierarchical_keywords'); ?>
        </option>

    </select>

    <p class="description">
        <?= esc_html('This size will be used for the post thumbnail, on the whole site, except the following pages.', 'ol_hierarchical_keywords'); ?>
    </p>


    <?php
}


function olhk_field_homesizes_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('olhk_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['olhk_custom_data']); ?>" name="olhk_options[<?= esc_attr($args['label_for']); ?>]" >

        <option value="200" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '200', false)) : (''); ?>>
            <?= esc_html('200 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="250" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '250', false)) : (''); ?>>
            <?= esc_html('250 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="300" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '300', false)) : (''); ?>>
            <?= esc_html('300 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="400" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '400', false)) : (''); ?>>
            <?= esc_html('400 px', 'ol_hierarchical_keywords'); ?>
        </option>

    </select>

    <p class="description">
        <?= esc_html('This size will be used for the post thumbnail on the homepage.', 'ol_hierarchical_keywords'); ?>
    </p>


    <?php
}


function olhk_field_blogsizes_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('olhk_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['olhk_custom_data']); ?>" name="olhk_options[<?= esc_attr($args['label_for']); ?>]" >

        <option value="400" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '400', false)) : (''); ?>>
            <?= esc_html('400 px','ol_hierarchical_keywords'); ?>
        </option>

        <option value="700" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '700', false)) : (''); ?>>
            <?= esc_html('700 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="1000" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '1000', false)) : (''); ?>>
            <?= esc_html('1000 px', 'ol_hierarchical_keywords'); ?>
        </option>


    </select>

    <p class="description">
        <?= esc_html('This size will be used for the post thumbnail on the blog page.', 'ol_hierarchical_keywords'); ?>
    </p>


    <?php
}



function olhk_field_portfoliosizes_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('olhk_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['olhk_custom_data']); ?>" name="olhk_options[<?= esc_attr($args['label_for']); ?>]" >

        <option value="100" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '100', false)) : (''); ?>>
            <?= esc_html('100 px','ol_hierarchical_keywords'); ?>
        </option>

        <option value="150" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '150', false)) : (''); ?>>
            <?= esc_html('150 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="200" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '200', false)) : (''); ?>>
            <?= esc_html('200 px', 'ol_hierarchical_keywords'); ?>
        </option>

        <option value="250" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], '250', false)) : (''); ?>>
            <?= esc_html('250 px', 'ol_hierarchical_keywords'); ?>
        </option>

    </select>

    <p class="description">
        <?= esc_html('This size will be used for the post thumbnail on portfolio page.', 'ol_hierarchical_keywords'); ?>
    </p>


    <?php
}



















?>