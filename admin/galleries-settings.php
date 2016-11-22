<?php
function olhk_galleries_settings_page_html()
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
            settings_fields('olhk_galeries');
            // output setting sections and their fields
            // (sections are registered for "olhk", each field is registered to a specific section)
            do_settings_sections('olhk_galeries');
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
function olhk_galleries_settings_init()
{
    // register a new setting for "olhk" page
    register_setting('olhk_galeries', 'olhk_options');

    // register a new section in the "olhk" page
    add_settings_section(
        'olhk_section_gallery_display',
       __('choose the thumbnail size for all galeries' , 'ol_hierarchical_keywords'),
        'olhk_section_gallery_cb',
        'olhk_galeries'
    );

    // register a new field in the "olhk_section_developers" section, inside the "olhk" page
    add_settings_field(
        'olhk_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('the thumnail sizes','ol_hierarchical_keywords'),
        'olhk_field_galleries_cb',
        'olhk_galeries',
        'olhk_section_galleries_display',
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
add_action('admin_init', 'olhk_galleries_settings_init');

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function olhk_section_gallery_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__("all thumbnails' galleries will have the same size, except the woocommerce one, if it is activated", 'ol_hierarchical_keywords'); ?></p>
    <?php
}




// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function olhk_field_galleries_cb($args)
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




























?>