<?php
/*
Plugin Name: Share on Diaspora
Plugin URI:
Description: This plugin adds a "Share on D*" button at the bottom of your posts.
Version: 0.3.2
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
$defaults = array(
    'button_color' => '3c72c2',
    'button_background' => 'ecf2f6',
    'button_color_hover' => '3c72c2',
    'button_background_hover' => 'B8CCD9',
    'button_size' => '1',
    'button_rounded' => '5',
    );

function get_default($key)
    {
    global $defaults;
    return $defaults[$key];
    }

function set_default()
    {
    global $defaults;
    foreach ($defaults as $key => $value)
        {
        update_option('$key', '$value');
        }
    }

function generate_button($preview)
    {
    $options_array = get_option('share-on-diaspora-settings');
    $bc = ( $options_array['button_color'] != '' ) ? $options_array['button_color'] : get_default('button_color');
    $bb = ( $options_array['button_background'] != '' ) ? $options_array['button_background'] : get_default('button_background');
    $bc_h = ( $options_array['button_color_hover'] != '' ) ? $options_array['button_color_hover'] : get_default('button_color_hover');
    $bb_h = ( $options_array['button_background_hover'] != '' ) ? $options_array['button_background_hover'] : get_default('button_background_hover');

    switch ($options_array['button_size'])
        {
        case '2': $bs = '28'; $fs = '17'; $bwidth = '120'; break;
        case '3': $bs = '33'; $fs = '20'; $bwidth = '140'; break;
        case '4': $bs = '48'; $fs = '29'; $bwidth = '204'; break;
        default: $bs = '23'; $fs = '14'; $bwidth = '98'; 
        }
    $br =  ( $options_array['button_rounded'] != '' ) ? $options_array['button_rounded'] : get_default('button_rounded');

    $button_box = "<a href=\"javascript:(function(){var url = ". (($preview) ? "'[Page address here]'" : "window.location.href") . " ;var title = ". (($preview) ?  "'[Page title here]'" :  "document.title") . ";   window.open('".plugin_dir_url(__FILE__)."new_window.php?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title),'post','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=400')})()\">
<div id=\"diaspora-button-box\" style=\"box-sizing: content-box; -moz-box-sizing: content-box; float:left; margin-right: 10px; width:" . $bwidth . "px; height:" . $bs . "px; background-color: #" . $bb . "; -moz-border-radius:" . $br . "px; border-radius:" . $br . "px; border-color: #" . $bc . "; border-width: 1px; color: #" . $bc . "; border-style: solid; padding: 0 5px 0 5px; text-align: center;\" onMouseOver=\"this.style.backgroundColor='#" . $bb_h . "'; this.style.color='#" . $bc_h . "'; this.style.borderColor='#" . $bc_h . "'\" onMouseOut=\"this.style.backgroundColor='#" . $bb . "'; this.style.color='#" . $bc . "' ; this.style.borderColor='#" . $bc . "'\"><font style=\"font-family:arial,helvetica,sans-serif;font-size:" . $fs ."px;margin: 0; line-height:" . ($bs-2) . "px;\">share this</font> <div id=\"diaspora-button-inner\" style=\"float: right; margin: 1px 1px 1px 1px;height:" . ($bs-3) . "px; background-color: #" . $bb . ";\"><img style=\"vertical-align: top; margin:0 auto; padding:0; border:0;\" src=\"" . plugin_dir_url(__FILE__) . "/images/asterisk-" . ($bs-3) . ".png\"></div>
</div></a>";
    return $button_box;
    }

function diaspora_button_display($content)
    {
    $button_box = generate_button(FALSE);
    return $content . "<br>" . $button_box;
    }

add_action("the_content", "diaspora_button_display");

add_action( 'admin_menu', 'share_on_diaspora_menu' );

function share_on_diaspora_menu() {
    add_options_page( 'Share on D* Options', 'Share on D*', 'manage_options', 'share_on_diaspora_options_page', 'share_on_diaspora_options_page' );

//'My Plugin', 'My Plugin', 'manage_options', 'my-plugin', 'my_options_page' );
}

add_action( 'admin_init', 'my_admin_init' );
function my_admin_init() {
    register_setting( 'share_on_diaspora_options-group', 'share-on-diaspora-settings', 'my_settings_validate' );
//    register_setting( 'share_on_diaspora_options-group', 'share-on-diaspora-settings' );
    $options_array = get_option('share-on-diaspora-settings');
    add_settings_section( 'section-one', 'Button properties', 'section_one_callback', 'share_on_diaspora_options' );
    add_settings_field( 'button_background', 'Background color', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_background]',
        'value' => (($options_array['button_background'] != '' ) ? $options_array['button_background'] : get_default('button_background'))
        )
    );
    add_settings_field( 'button_background_hover', 'Background color on mouse-over', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_background_hover]',
        'value' => (($options_array['button_background_hover'] != '' ) ? $options_array['button_background_hover'] : get_default('button_background_hover'))
        )
    );
    add_settings_field( 'button_color', 'Text and border color', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_color]',
        'value' => (($options_array['button_color'] != '' ) ? $options_array['button_color'] : get_default('button_color'))
        )
    );
    add_settings_field( 'button_color_hover', 'Text and border color on mouse-over', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_color_hover]',
        'value' => (($options_array['button_color_hover'] != '' ) ? $options_array['button_color_hover'] : get_default('button_color_hover'))
        )
    );
    add_settings_field( 'button_size', 'Button size', 'my_radio_group', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_size]',
        'value' => (($options_array['button_size'] != '' ) ? $options_array['button_size'] : get_default('button_size')),
        'labels' => array('1' => 'Small', '2' => 'Medium', '3' => 'Large', '4' => 'Huge')
        )
    );
    add_settings_field( 'button_rounded', 'Rounded corners', 'my_radio_group', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_rounded]',
        'value' => (($options_array['button_rounded'] != '' ) ? $options_array['button_rounded'] : get_default('button_rounded')),
        'labels' => array('5' => 'Rounded', '0' => 'Square')
        )
    );
    add_settings_field( 'reset', 'Restore defaults', 'share_on_diaspora_reset_callback', 'share_on_diaspora_options', 'section-one');
}

add_action( 'set_default', 'set_option_defaults' );

function prefix_on_deactivate() {
       delete_option('share-on-diaspora-settings');
}

register_deactivation_hook(__FILE__, 'prefix_on_deactivate');

function section_one_callback() {
    echo 'Use the parameters below to change the look and feel of your share button. All colors are six-digit hexadecimal numbers like <strong>000000</strong> or <strong>ffffff</strong>. Leave empty to restore the default value.';
}

function field_one_callback() {
    $settings = (array) get_option( 'share-on-diaspora-settings' );
    $color = esc_attr( $settings['color'] );
    echo "<input type='text' name='share-on-diaspora-settings[color]' value='$color' />";

}

function my_text_input( $args ) {
    $name = esc_attr( $args['name'] );
    $value = esc_attr( $args['value'] );
//    $comment = $args['comment'];
    echo "<input type='text' name='$name' value='$value' /> ";
}

function my_radio_group( $args ) {
    $name = esc_attr( $args['name'] );
    $value = esc_attr( $args['value'] );
    $labels = $args['labels'];
    foreach ($labels as $row => $row_label)
        {
        echo "<input type='radio' name='$name' value='$row' ".( ($value == $row) ? "checked" : "")."/> $row_label<br>";
        }
    }

function share_on_diaspora_reset_callback()
    {
    echo "<input type='submit' name='share-on-diaspora-settings[reset]' value='Defaults'>";
    }

function my_settings_validate( $input ) {
//    $output = get_option( 'share-on-diaspora-settings' );
    $output = $input;
    $colors = array('button_color', 'button_background');
    foreach ($colors as $i)
        {
        if ($output[$i] != '')
            {
            preg_match('/^[a-f0-9]{6}$/i', $output[$i], $match_array);
            $output[$i] = $match_array[0];
            if ($output[$i] == '')
                {
                add_settings_error( 'share-on-diaspora-settings', 'invalid-color', 'Invalid value for \''. $i . '\'. Reverting to default.' );
                }
            }
        else
            {
            add_settings_error( 'share-on-diaspora-settings', 'missing-color', 'Value missing for \''. $i . '\'. Reverting to default.' );
            }
        }
    if ($output['reset'] != '')
        {
        add_settings_error( 'share-on-diaspora-settings', 'reverted to defaults', 'All parameters reverted to their default values.' );
        return $defaults;
        }
    else return $output;
    }

function share_on_diaspora_options_page() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	};
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Share on Diaspora (ver. <?php $plugin_data_array = get_plugin_data(__FILE__); echo $plugin_data_array['Version']; ?>) Options</h2>
        <h3>Button Preview</h3>
        <?php echo generate_button(TRUE); ?>
        <br>
        <form action="options.php" method="POST">
            <?php settings_fields( 'share_on_diaspora_options-group' ); ?>
            <?php do_settings_sections( 'share_on_diaspora_options' ); ?>
            <?php submit_button('Update', 'primary',  'submit-form', false); ?>
        </form>
    </div>
    <?php

};
 
?>
