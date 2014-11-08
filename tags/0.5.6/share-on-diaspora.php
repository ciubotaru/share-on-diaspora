<?php
/*
Plugin Name: Share on Diaspora
Plugin URI:
Description: This plugin adds a "Share on D*" button at the bottom of your posts.
Version: 0.5.6
Author: Vitalie Ciubotaru
Author URI: https://github.com/ciubotaru
License: GPL2
*/

/*  Copyright 2013 Vitalie Ciubotaru (email : vitalie@ciubotaru.tk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists("ShareOnDiaspora")) {
    class ShareOnDiaspora {
public $button_defaults = array(
    'button_color' => '3c72c2',
    'button_background' => 'ecf2f6',
    'button_color_hover' => '3c72c2',
    'button_background_hover' => 'B8CCD9',
    'button_size' => '1',
    'button_rounded' => '5',
    'button_text' => 'share this'
);

public $image_defaults = array(
    'image_file' => '',
    'use_own_image' => '0'
    );

public $podlist_defaults = array(
    'podlist' => array(
        'joindiaspora.com' => '1',
        'diasp.org' => '1',
        'pod.geraspora.de' => '1',
        'diasp.eu' => '1',
        'diasp.de' => '1',
        'despora.de' => '1'
    )
);

public $plugin_version = array('version' => '0.5');

function set_default() {
    $button_defaults = $this -> button_defaults;
    $image_defaults = $this -> image_defaults;
    $podlist_defaults = $this -> podlist_defaults;
    $plugin_version = $this -> plugin_version;
    $defaults = $button_defaults + $image_defaults + $podlist_defaults + $plugin_version;
    $options_array = get_option('share-on-diaspora-settings');
    foreach ($defaults as $key => $value) {
        if ( empty($options_array[$key]) ) {
            $options_array[$key] = $value;
        }
    }
    $options_array['section'] = 'set_default';
    update_option('share-on-diaspora-settings', $options_array);
}

function register_share_on_diaspora_css() {
    wp_register_style( 'share-on-diaspora', plugins_url( 'share-on-diaspora-css.php' , __FILE__ ) );
    wp_enqueue_style( 'share-on-diaspora' );
}

function register_share_on_diaspora_js() {
    wp_register_script( 'share-on-diaspora', plugins_url( 'share-on-diaspora.js' , __FILE__ ) );
    wp_enqueue_script( 'share-on-diaspora' );
}

function generate_button($preview, $use_own_image) {
    /**
     * if preview == TRUE && $use_own_image == '0', prepare fake link and output standard button
     * if preview == FALSE && $use_own_image == '0', prepare real link and output standard button
     * if preview == FALSE && $use_own_image == '1', prepare real link and output custom button
     * if preview == TRUE && $use_own_image == '1', impossible
     * the button is inside, so let's prepare button first
     */
    $button_defaults = $this -> button_defaults;
    $options_array = get_option('share-on-diaspora-settings');
    if ( $use_own_image ) {
        //use own image
        $button_box = "<div id='diaspora-button-ownimage-div'><img id='diaspora-button-ownimage-img' src='" . $options_array['image_file'] . "'></div>";
    } else {
        //use standard image
        switch ($options_array['button_size']) {
        case '2': $bs = '28'; break;
        case '3': $bs = '33'; break;
        case '4': $bs = '48'; break;
        default: $bs = '23';
        }
        $bt = !empty( $options_array['button_text'] ) ? $options_array['button_text'] : $button_defaults['button_text'];
        $button_box = "<div id='diaspora-button-box'><font>" . $bt  . "</font> <div id='diaspora-button-inner'><img src='" . plugin_dir_url(__FILE__) . "images/asterisk-" . ($bs-3) . ".png'></div></div>";
    }
    if ( $preview ) {
        //add fake link
        $url = "'[".__('Page address here', 'share-on-diaspora' )."]'";
        $title = "'[".__('Page title here', 'share-on-diaspora' )."]'";
    } else {
        //add real link
        if (is_admin()) {
            $url = "'[".__('Page address here', 'share-on-diaspora' )."]'";
            $title = "'[".__('Page title here', 'share-on-diaspora' )."]'";
        } elseif (is_single()) {
            $url = "window.location.href";
            $title = "document.title";
        } else {
            $url = "'".esc_url(get_permalink())."'";
            $title = "'".get_the_title()."'";
        }
    }

    $button = "<div title='Diaspora*' id='diaspora-button-container'><a href=\"javascript:(function(){var url = ". $url . " ;var title = ". $title . ";   window.open('".plugin_dir_url(__FILE__)."new_window.php?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title),'post','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=400')})()\">
" . $button_box . "</a></div>";

    return $button;
}

function generate_podlist() {
    $podlist_preview = "<select id='diaspora-button-podlist'>
<option>- " . __('Select from the list', 'share-on-diaspora') . " -</option>";
    $options_array = get_option('share-on-diaspora-settings');
    if (! $options_array) {
        $temp = $this -> podlist_defaults;
        $options_array = array('podlist' => $temp);
    } elseif (empty($options_array['podlist'])) {
        $temp = $this -> podlist_defaults;
        $options_array['podlist'] = $temp;
    }
    foreach ($options_array['podlist'] as $key => $value) {
        $podlist_preview .= '<option  value="' . $value .'" class=dpod title="'.$key.'">'.$key.'</option>';
    }
    $podlist_preview .= "</select>";
    return $podlist_preview;
}

function diaspora_button_display($content) {
    if ( get_post_type() == 'post' && (!in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ))) {
        $options_array = get_option('share-on-diaspora-settings');
        $button_box = $this -> generate_button(FALSE, $options_array['use_own_image']);
        return $content . $button_box;
    } else return $content;
}

function share_on_diaspora_menu() {
    add_options_page( 'Share on D* Options', __( 'Share on D*', 'share-on-diaspora' ), 'manage_options', 'share_on_diaspora_options_page', array($this, 'share_on_diaspora_options_page') );
    //add_filter('plugin_action_links_'.plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2);
}

/**
public function filter_plugin_actions($l, $file) {
    $settings_link = '<a href="options-general.php?page=share_on_diaspora_options_page">'.__('Settings').'</a>';
    array_unshift($l, $settings_link);
    return $l;
}
*/

function show_button_image() {
    $options_array = get_option('share-on-diaspora-settings');
    if (empty($options_array['image_file'])) {
        //$options_array['image_file'] = '';
        $output = '<p>' . __('[No image]', 'share-on-diaspora') . '</p>';
    } else {
        $output = "<p><img src='" . $options_array['image_file'] . "'></p>";
    }
    return $output;
}

function my_admin_init() {
    $button_defaults = $this -> button_defaults;
    $image_defaults = $this -> image_defaults;
    $podlist_defaults = $this -> podlist_defaults;
    $plugin_version = $this -> plugin_version;
    $defaults = $button_defaults + $image_defaults + $podlist_defaults + $plugin_version;

    //Let's check if it's a fresh install, a fresh update, or normal version
    if (!get_option('share-on-diaspora-settings')) {
        //No saved options. Probably a fresh install.
        $this -> set_default();
    } elseif (($result = get_option('share-on-diaspora-settings')) && ($result['version'] != $plugin_version['version'])) {
        //Saved options exist, but versions differ. Probably a fresh update. Need to save updated options.
        $saved_options = get_option('share-on-diaspora-settings');
        //let's fill the gaps in saved options with defaults and replace the version.
        $current_options = array_merge($defaults, $saved_options);
        if ( $old_settings = get_option('share-on-diaspora-settings2') ) {
            //old settings exist. Let's put them into the new options and delete them.
            $current_options = array_merge($current_options, $old_settings);
            delete_option('share-on-diaspora-settings2');
        }
        $current_options['version'] = $plugin_version['version'];
        $current_options['section'] = 'set_default';
        update_option('share-on-diaspora-settings', $current_options);
    }

    $options_array = get_option('share-on-diaspora-settings');

    //do we really need this line?
    add_option('share-on-diaspora-settings');
    register_setting( 'share_on_diaspora_options', 'share-on-diaspora-settings', array($this, 'my_settings_validate') );
    add_settings_section( 'section-button', __( 'Button properties', 'share-on-diaspora' ), array($this, 'section_one_callback'), 'share_on_diaspora_options-button' );
    add_settings_field( 'button_background', __( 'Background color', 'share-on-diaspora' ), array($this, 'my_text_input'), 'share_on_diaspora_options-button', 'section-button', array(
        'name' => 'share-on-diaspora-settings[button_background]',
        'value' => (isset($options_array['button_background']) ? $options_array['button_background'] : $button_defaults['button_background'])
        )
    );
    add_settings_field( 'button_background_hover', __( 'Background color on mouse-over', 'share-on-diaspora' ), array($this, 'my_text_input'), 'share_on_diaspora_options-button', 'section-button', array(
        'name' => 'share-on-diaspora-settings[button_background_hover]',
        'value' => (isset($options_array['button_background_hover']) ? $options_array['button_background_hover'] : $button_defaults['button_background_hover'])
        )
    );
    add_settings_field( 'button_color', __( 'Text and border color', 'share-on-diaspora' ), array($this, 'my_text_input'), 'share_on_diaspora_options-button', 'section-button', array(
        'name' => 'share-on-diaspora-settings[button_color]',
        'value' => (isset($options_array['button_color']) ? $options_array['button_color'] : $button_defaults['button_color'])
        )
    );
    add_settings_field( 'button_color_hover', __( 'Text and border color on mouse-over', 'share-on-diaspora' ), array($this, 'my_text_input'), 'share_on_diaspora_options-button', 'section-button', array(
        'name' => 'share-on-diaspora-settings[button_color_hover]',
        'value' => (isset($options_array['button_color_hover']) ? $options_array['button_color_hover'] : $button_defaults['button_color_hover'])
        )
    );
    add_settings_field( 'button_rounded', __( 'Rounded corners', 'share-on-diaspora' ), array($this, 'my_radio_group'), 'share_on_diaspora_options-button', 'section-button', array(
        'name' => 'share-on-diaspora-settings[button_rounded]',
        'value' => (isset($options_array['button_rounded']) ? $options_array['button_rounded'] : $button_defaults['button_rounded']),
        'labels' => array('5' => __( 'Rounded', 'share-on-diaspora' ), '0' => __( 'Square', 'share-on-diaspora' ))
        )
    );
    add_settings_field( 'button_size', __( 'Button size', 'share-on-diaspora' ), array($this, 'my_radio_group'), 'share_on_diaspora_options-button', 'section-button', array(
        'name' => 'share-on-diaspora-settings[button_size]',
        'value' => (isset($options_array['button_size']) ? $options_array['button_size'] : $button_defaults['button_size']),
        'labels' => array('1' => __( 'Small', 'share-on-diaspora' ), '2' => __( 'Medium', 'share-on-diaspora' ), '3' => __( 'Large', 'share-on-diaspora' ), '4' => __( 'Huge', 'share-on-diaspora' ))
        )
    );
    add_settings_field( 'button_text', __( 'Text on the button', 'share-on-diaspora' ), array($this, 'my_text_input'), 'share_on_diaspora_options-button', 'section-button', array(
        'name' => 'share-on-diaspora-settings[button_text]',
        'value' => (isset($options_array['button_text']) ? $options_array['button_text'] : $button_defaults['button_text'])
        )
    );
    add_settings_field( 'reset', __( 'Restore defaults', 'share-on-diaspora' ), array($this, 'share_on_diaspora_reset_callback'), 'share_on_diaspora_options-button', 'section-button');

    add_settings_section( 'section-upload', __( 'Upload button image', 'share-on-diaspora' ), array($this, 'section_upload_callback'), 'share_on_diaspora_options-upload' );
    add_settings_field( 'image', __( 'Upload new custom image', 'share-on-diaspora' ), array($this, 'image_upload_callback'), 'share_on_diaspora_options-upload', 'section-upload' );
    add_settings_field( 'image_file', __( 'OR provide image URL', 'share-on-diaspora' ), array($this, 'share_on_diaspora_url_callback'), 'share_on_diaspora_options-upload', 'section-upload' );
    add_settings_field( 'delete_image', __( 'Clear current image', 'share-on-diaspora' ), array($this, 'share_on_diaspora_delete_callback'), 'share_on_diaspora_options-upload', 'section-upload');
    add_settings_field( 'use_own_image', __( 'Use custom image', 'share-on-diaspora' ), array($this, 'use_image_callback'), 'share_on_diaspora_options-upload', 'section-upload' );

    add_settings_section( 'section-podlist', __( 'Pod properties', 'share-on-diaspora' ), array($this, 'section_two_callback'), 'share_on_diaspora_options-podlist' );
    $podlist = file(plugin_dir_path( __FILE__ ).'pod_list_all.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (isset($options_array['podlist'])) {
        $podlist = array_merge($podlist, array_keys($options_array['podlist']));
        $podlist = array_unique($podlist);
        }
    foreach ($podlist as $i) {
        add_settings_field( $i, $i, array($this, 'my_checkboxes'), 'share_on_diaspora_options-podlist', 'section-podlist', array('podname' => $i));
    };
    add_settings_field( 'add_pod', __( 'Add a custom pod', 'share-on-diaspora' ), array($this, 'share_on_diaspora_addfield_callback'), 'share_on_diaspora_options-podlist', 'section-podlist');
}

function section_one_callback() {
    printf( __( 'Use the parameters below to change the look and feel of your share button. All colors are six-digit hexadecimal numbers like %1$s or %2$s. Leave empty to restore the default value.', 'share-on-diaspora' ), '<code>000000</code>', '<code>ffffff</code>');
}

function my_text_input( $args ) {
    $name = esc_attr( $args['name'] );
    $value = esc_attr( $args['value'] );
    echo "<input type='text' name='$name' value='$value' /> ";
}

function my_radio_group( $args ) {
    $name = esc_attr( $args['name'] );
    $value = esc_attr( $args['value'] );
    $labels = $args['labels'];
    foreach ($labels as $row => $row_label) {
        echo "<input type='radio' name='$name' value='$row' ".( ($value == $row) ? "checked" : "")."/> $row_label<br>";
    }
}

function share_on_diaspora_reset_callback() {
    echo "<input type='submit' name='share-on-diaspora-settings[reset]' value='" . __('Defaults', 'share-on-diaspora') . "'>";
}

function section_upload_callback() {
    echo __('Select an image to upload and use as button.', 'share-on-diaspora');
}

function image_upload_callback() {
    echo "<label for='image'>" . __('Filename', 'share-on-diaspora') . ":</label><input type='file' name='file' value='image' />";
}

function share_on_diaspora_url_callback() {
    $settings = (array) get_option( 'share-on-diaspora-settings' );
    $url = $settings['image_file'];
    echo "<input type='text' name='share-on-diaspora-settings[image_file]' value='$url' placeholder='" . __('Example:', 'share-on-diaspora') . " http://example.com/image1.png' style='width:100%'/>";
}

function share_on_diaspora_delete_callback() {
    echo "<input type='submit' name='share-on-diaspora-settings[delete]' value='" . __('Clear', 'share-on-diaspora') . "'>";
}

function use_image_callback() {
    $image_defaults = $this -> image_defaults;
    $options_array = get_option('share-on-diaspora-settings');
    if (!isset($options_array['use_own_image'])) { $options_array['use_own_image'] = $image_defaults['use_own_image']; } 
    echo "<input type='checkbox' name='share-on-diaspora-settings[use_own_image]' value='checked'" . ( ($options_array['use_own_image'] == '1') ? 'checked' : '') . ">";
}

function section_two_callback() {
    echo __( 'Below is the list of Diaspora pods. Check the ones that you what to appear in the drop-down menu in the pod selection window.', 'share-on-diaspora' );
}

function my_checkboxes($args) {
    $options_array = get_option('share-on-diaspora-settings');
    if (! $options_array) {
        $temp = $this -> podlist_defaults;
        $options_array = array('podlist' => $temp);
    }
    $podname = esc_attr( $args['podname'] );
    echo "<input type='checkbox' name='share-on-diaspora-settings[podlist][" . $podname . "]' value='1' ";
    echo !empty( $options_array['podlist'][$podname] ) ? "checked":"";
    echo "/>";
}

function my_settings_validate( $input ) {
    if ( $input && $input['section'] && $input['section'] == 'button' ) {
        $output = $this -> button_settings_validate($input);
    } elseif ( $input && $input['section'] && $input['section'] == 'image' ) {
        $output = $this -> image_settings_validate($input);
    } elseif ( $input && $input['section'] && $input['section'] == 'podlist' ) {
        $output = $this -> podlist_settings_validate($input);
    } elseif ( $input && $input['section'] && $input['section'] == 'set_default' ) {
        unset($input['section']);
        return $input;
    } else {
        return $input;
    }
    unset($output['section']);
    //getting the saved options, or creating an empty array if fresh install
    $options_array = ( $result = get_option('share-on-diaspora-settings')) ? $result : array();
    $output = array_merge($options_array, $output);
//    unset($output['0']);
//    update_option('share-on-diaspora-settings', $output);
    return $output;
}

function share_on_diaspora_addfield_callback() {
    echo "<input type='text' name='newpodname' value='' placeholder='" . __('Example:', 'share-on-diaspora') . " mypod.com'/><input type='button' value='" . __('Add', 'share-on-diaspora') . "' onclick='addCheckbox();'>";
}


function button_settings_validate($input) {
    $button_defaults = $this -> button_defaults;
    if (!empty( $input['reset'] )) {
        add_settings_error( 'share-on-diaspora-settings', 'reverted to defaults', __('All parameters reverted to their default values.', 'share-on-diaspora' ), 'updated' );
        //pick all button-related settings from defaults (and leaving custom image and podlist stuff)
        $input = $button_defaults;
        unset($input['reset']);
    } else {
        $colors = array('button_color', 'button_background', 'button_color_hover', 'button_background_hover');
        foreach ($colors as $i) {
            if ( isset( $input[$i] ) && !empty( $input[$i] )) {
            preg_match('/^[a-f0-9]{6}$/i', $input[$i], $match_array);
                $input[$i] = $match_array[0];
                if (empty( $input[$i] )) {
                    add_settings_error( 'share-on-diaspora-settings', 'invalid-color', sprintf( __('Invalid value for %s. Reverting to default.', 'share-on-diaspora' ), "'$i'") );
                    $input[$i] = $button_defaults[$i];
                }
            } elseif ( isset( $input[$i] ) && empty( $input[$i] ) ) {
                add_settings_error( 'share-on-diaspora-settings', 'missing-color', sprintf( __('Value missing for %s. Reverting to default.', 'share-on-diaspora' ), "'$i'") );
                $input[$i] = $button_defaults[$i];
            }
        }
    }
    return $input;
}

function image_settings_validate($input) {
    if ($input['use_own_image'] == '1' && empty($input['image_file'])) {
        add_settings_error( 'share-on-diaspora-settings', 'toggle_disabled', __('No image file specified. Falling back to standard button.', 'share-on-diaspora'), 'error' );
        $input['use_own_image'] = '0';
    } elseif (empty($input['use_own_image'])) {
        $input['use_own_image'] = '0';
    }
    return $input;
}

function podlist_settings_validate($input) {
    if ( !is_writable(plugin_dir_path(__FILE__) ) )
        {
        add_settings_error( 'share-on-diaspora-settings', 'not writable', __( 'Plugin directory is not writable. Can not save css file.', 'share-on-diaspora' ) );
        }
    if (empty($input['podlist'])) {
        add_settings_error( 'share-on-diaspora-settings', 'empty-podlist', sprintf( __('Value missing for %s. Reverting to default.', 'share-on-diaspora' ), "'podlist'") );
        $input = array_merge($input, $this -> podlist_defaults);
    }
    return $input;
}

function share_on_diaspora_tab1() {
    echo "<h3>".__( "Button Preview", 'share-on-diaspora' )."</h3>";
    echo $this -> generate_button(TRUE, '0');
    echo "<form action='options.php' method='post' name='button'>";
    echo "<input type='hidden' name='share-on-diaspora-settings[section]' value='button'>";
    settings_fields( 'share_on_diaspora_options' );
    do_settings_sections( 'share_on_diaspora_options-button' ); 
    submit_button(__( 'Update', 'share-on-diaspora' ), 'primary',  'submit-form', false);
    echo "</form>";
}

function share_on_diaspora_tab2() {
    echo "<h3>".__( "Custom Image Preview", 'share-on-diaspora' )."</h3>";
    echo $this -> show_button_image();
    echo "<form method='post' name='upload' enctype='multipart/form-data'>";
    echo "<input type='hidden' name='share-on-diaspora-settings[section]' value='image'>";
    settings_fields( 'share_on_diaspora_options' );
    do_settings_sections( 'share_on_diaspora_options-upload' );
    submit_button(__('Update', 'share-on-diaspora' ), 'primary',  'submit-form', false);
    echo "</form>";
}

function share_on_diaspora_tab3() {
    echo "<h3>" . __('Podlist Preview', 'share-on-diaspora') . "</h3>";
    echo $this -> generate_podlist(); 
    echo "<br>";
    echo "<form action='options.php' method='POST'>";
    echo "<input type='hidden' name='share-on-diaspora-settings[section]' value='podlist'>";
    settings_fields( 'share_on_diaspora_options' );
    do_settings_sections( 'share_on_diaspora_options-podlist' ); 
    submit_button(__( 'Update', 'share-on-diaspora' ), 'primary',  'submit-form', false);
    echo "</form>";
}

function share_on_diaspora_options_page() {
    //open first tab by default
    $tab = isset($_GET['tab']) ? $_GET['tab'] : '1';
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' , 'share-on-diaspora') );
    }
    if (!empty($_POST)) {
        // now lets use the info from the post
        $image_settings = array();
        if (!empty($_POST['share-on-diaspora-settings']['delete'])) {
            // if "clear" was pressed, then clear image_file and ignore other options
            $image_settings['image_file'] = '';
            add_settings_error( 'share-on-diaspora-settings', 'image deleted', __('Image URL cleared.', 'share-on-diaspora' ), 'updated' );
        } elseif (!empty($_FILES) && !empty($_FILES['file']) && ($_FILES['file']['error'] == '0')) {
            // if something was uploaded, handle it and ignore other options
            $uploadedfile = $_FILES['file'];
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
            if ( $movefile ) {
                $wp_filetype = $movefile['type'];
                $filename = $movefile['file'];
                $wp_upload_dir = wp_upload_dir();
                $attachment = array(
                    'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ),
                    'post_mime_type' => $wp_filetype,
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                $attach_id = wp_insert_attachment( $attachment, $filename);
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id,$attachment['guid'] );
                $result = wp_update_attachment_metadata( $attach_id, $attach_data );
            }
            $image_settings['image_file'] = $movefile['url'];
            add_settings_error( 'share-on-diaspora-settings', 'image uploaded', __('Custom image file uploaded.', 'share-on-diaspora' ), 'updated' );
        } elseif (!empty($_POST['share-on-diaspora-settings']['image_file'])) {
            //finally, if image URL was provided, use it
            $image_settings['image_file'] = $_POST['share-on-diaspora-settings']['image_file'];
        }
        // now let's handle the use_image toggle
        $image_settings['use_own_image'] = (!empty($_POST['share-on-diaspora-settings']['use_own_image'])) ? '1' : '0';
        //let's merge it with existing options
        $options_array = (array) get_option('share-on-diaspora-settings');
        $options_array = array_merge($options_array, $image_settings);
        $options_array['section'] = 'image';
        // well, this sends the entire option array to validation function. kinda double work
        update_option('share-on-diaspora-settings', $options_array);
    }

    ?>
    <div class="wrap">
        <?php
        screen_icon();
        //form in tab 2 is updated manually, so settings errrors are not shown properly. Thus workaround.
        if (isset($_GET['tab']) && $_GET['tab'] == '2') settings_errors('share-on-diaspora-settings');
        ?>
        <h2><?php $plugin_data_array = get_plugin_data(__FILE__); printf( __('Share on Diaspora (ver. %s) Options', 'share-on-diaspora' ), $plugin_data_array['Version'] ); ?></h2>
        <p><?php printf( __('Need help? Please read %1$s plugin\'s FAQ page %2$s.', 'share-on-diaspora'), "<a href='http://wordpress.org/plugins/share-on-diaspora/faq'>", '</a>'); ?></p>
        <h2 class="nav-tab-wrapper">
        <a href="?page=share_on_diaspora_options_page&tab=1" class="nav-tab <?php if ( $tab == '1' ) echo "nav-tab-active"; ?>"><?php echo __('Button options', 'share-on-diaspora'); ?></a>
        <a href="?page=share_on_diaspora_options_page&tab=2" class="nav-tab <?php if ( $tab == '2' ) echo "nav-tab-active"; ?>"><?php echo __('Custom image', 'share-on-diaspora'); ?></a>
        <a href="?page=share_on_diaspora_options_page&tab=3" class="nav-tab <?php if ( $tab == '3' ) echo "nav-tab-active"; ?>"><?php echo __('Pod list options', 'share-on-diaspora'); ?></a>
        </h2>
        <?php switch ($tab)
            {
            case '2' : $this -> share_on_diaspora_tab2(); break;
            case '3' : $this -> share_on_diaspora_tab3(); break;
            default: $this -> share_on_diaspora_tab1();
            } ?>
<hr>
<p><?php echo __('Source code repository', 'share-on-diaspora'); ?>: <a href='https://github.com/ciubotaru/share-on-diaspora'>https://github.com/ciubotaru/share-on-diaspora</a></p>
<p><?php echo __('Donate BTC', 'share-on-diaspora'); ?>: 1At3bPLGDBTXucAPeVRK6DHNkHLFLNXmhj</p>
    </div>
    <?php
}

function i18n_init() {
    load_plugin_textdomain( 'share-on-diaspora', false, dirname( plugin_basename( __FILE__ ) ).'/i18n' );
}

public function __construct() {
    add_action('plugins_loaded', array($this, 'i18n_init'));
    // Register style sheet.
    add_action( 'wp_enqueue_scripts', array($this, 'register_share_on_diaspora_css') );
    add_action( 'admin_enqueue_scripts', array($this, 'register_share_on_diaspora_css') ); 
    add_action( 'admin_enqueue_scripts', array($this, 'register_share_on_diaspora_js') ); 
    add_filter('the_content', array($this, 'diaspora_button_display') );
    add_action( 'admin_menu', array($this, 'share_on_diaspora_menu') );
    add_action( 'admin_init', array($this, 'my_admin_init') );
} //end function
} //end class
} //end if clause

if (class_exists("ShareOnDiaspora")) {
    $share_on_diaspora = new ShareOnDiaspora;
}
?>
