<?php
/*
Plugin Name: Share on Diaspora
Plugin URI:
Description: This plugin adds a "Share on D*" button at the bottom of your posts.
Version: 0.4
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
global $defaults;

$defaults = array(
    'button_color' => '3c72c2',
    'button_background' => 'ecf2f6',
    'button_color_hover' => '3c72c2',
    'button_background_hover' => 'B8CCD9',
    'button_size' => '1',
    'button_rounded' => '5',
    'button_text' => 'share this'
    );

function get_default($key)
    {
    global $defaults;
    return $defaults[$key];
    }

function create_css_file()
    {
    $options_array = get_option('share-on-diaspora-settings');
    $bc = !empty( $options_array['button_color'] ) ? $options_array['button_color'] : get_default('button_color');
    $bb = !empty( $options_array['button_background'] ) ? $options_array['button_background'] : get_default('button_background');
    $bc_h = !empty( $options_array['button_color_hover'] ) ? $options_array['button_color_hover'] : get_default('button_color_hover');
    $bb_h = !empty( $options_array['button_background_hover'] ) ? $options_array['button_background_hover'] : get_default('button_background_hover');

    $button_size = isset($options_array['button_size']) ? $options_array['button_size'] : get_default('button_size');
    switch ($button_size)
        {
        case '2': $bs = '28'; $fs = '17'; break;
        case '3': $bs = '33'; $fs = '20'; break;
        case '4': $bs = '48'; $fs = '29'; break;
        default: $bs = '23'; $fs = '14';  
        }

    $br = !empty( $options_array['button_rounded'] ) ? $options_array['button_rounded'] : get_default('button_rounded');

    $css_path = plugin_dir_path( __FILE__ ). 'share-on-diaspora.css';
    $css_content = "#diaspora-button-box {
    box-sizing: content-box;
    -moz-box-sizing: content-box;
    float: left;
    margin-right: 10px;
    height: " . $bs . "px;
    background-color: #" . $bb . ";
    -moz-border-radius: " . $br . "px;
    border-radius: " . $br . "px;
    border-color: #" . $bc . ";
    border-width: 1px;
    color: #" . $bc . ";
    border-style: solid;
    padding: 0 0 0 5px;
    text-align: center;
    font-size: ". $fs . "px;
    font-style: normal;
    font-weight: normal;
    line-height: 100%;
    overflow: auto}

#diaspora-button-box:hover {
    background-color: #" . $bb_h . ";
    border-color: #" . $bc_h . ";
    color: #" . $bc_h . "}  

#diaspora-button-box font {
    font-family: arial,helvetica,sans-serif;
    font-size: " . $fs ."px;
    margin: 0;
    line-height: " . ($bs-2) . "px;
    text-decoration: none} 

#diaspora-button-inner {
    float: right;
    margin: 1px 1px 1px 5px;
    height: " . ($bs-3) . "px;
    background-color: #" . $bb . "}

#diaspora-button-inner img {
    vertical-align: top;
    margin: 0 auto;
    padding: 0;
    border: 0}
";

    file_put_contents( $css_path, $css_content );
    }

function create_pod_list_file()
    {
    include_once plugin_dir_path( __FILE__ ) . 'pod_list_all.php';
    $pod_list_path = plugin_dir_path( __FILE__ ) . 'pod_list_show.php';
    $pod_list_content = '<?php $podlist = array(\'';
    $options_array = get_option('share-on-diaspora-settings2');
    foreach ( $options_array as $key => $value )
        {
        if ( $value == '1' )
           {
           $list[] = $key;
           }
        }
    $pod_list_content .= implode("', '",$list); 
    $pod_list_content .= '\'
); ?>';
    file_put_contents( $pod_list_path, $pod_list_content );
    } 

function set_default()
    {
    global $defaults;
    $options_array = get_option('share-on-diaspora-settings');
    foreach ($defaults as $key => $value)
        {
        if ( empty($options_array[$key]) )
            {
            update_option('$key', '$value');
            }
        $options_array = get_option('share-on-diaspora-settings');
        }
    }

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_share_on_diaspora_css' );
add_action( 'admin_enqueue_scripts', 'register_share_on_diaspora_css' ); 

/**
 * Register style sheet.
 */
function register_share_on_diaspora_css()
    {
    wp_register_style( 'share-on-diaspora', plugins_url( 'share-on-diaspora.css' , __FILE__ ) );
    wp_enqueue_style( 'share-on-diaspora' );
    }

function generate_button($preview)
    {
    $options_array = get_option('share-on-diaspora-settings');
    switch ($options_array['button_size'])
        {
        case '2': $bs = '28'; break;
        case '3': $bs = '33'; break;
        case '4': $bs = '48'; break;
        default: $bs = '23';
        }
    $bt = !empty( $options_array['button_text'] ) ? $options_array['button_text'] : get_default('button_text');

    $button_box = "<a href=\"javascript:(function(){var url = ". (($preview) ? "'[Page address here]'" : "window.location.href") . " ;var title = ". (($preview) ?  "'[Page title here]'" :  "document.title") . ";   window.open('".plugin_dir_url(__FILE__)."new_window.php?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title),'post','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=400')})()\">
<div id=\"diaspora-button-box\"><font>" . $bt  . "</font> <div id=\"diaspora-button-inner\"><img src=\"" . plugin_dir_url(__FILE__) . "images/asterisk-" . ($bs-3) . ".png\"></div>
</div></a>";
    return $button_box;
    }

function diaspora_button_display($content)
    {
    if( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) return $content;

    $button_box = generate_button(FALSE);
    return $content . "<br>" . $button_box;
    }

add_action("the_content", "diaspora_button_display");

add_action( 'admin_menu', 'share_on_diaspora_menu' );

function share_on_diaspora_menu()
    {
    add_options_page( 'Share on D* Options', 'Share on D*', 'manage_options', 'share_on_diaspora_options_page', 'share_on_diaspora_options_page' );
    }

add_action( 'admin_init', 'my_admin_init' );

function my_admin_init() {
    register_setting( 'share_on_diaspora_options-group', 'share-on-diaspora-settings', 'my_settings_validate' );
//    register_setting( 'share_on_diaspora_options-group', 'share-on-diaspora-settings' );
    $options_array = get_option('share-on-diaspora-settings');
    add_settings_section( 'section-one', 'Button properties', 'section_one_callback', 'share_on_diaspora_options' );
    add_settings_field( 'button_background', 'Background color', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_background]',
        'value' => (isset($options_array['button_background']) ? $options_array['button_background'] : get_default('button_background'))
        )
    );
    add_settings_field( 'button_background_hover', 'Background color on mouse-over', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_background_hover]',
        'value' => (isset($options_array['button_background_hover']) ? $options_array['button_background_hover'] : get_default('button_background_hover'))
        )
    );
    add_settings_field( 'button_color', 'Text and border color', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_color]',
        'value' => (isset($options_array['button_color']) ? $options_array['button_color'] : get_default('button_color'))
        )
    );
    add_settings_field( 'button_color_hover', 'Text and border color on mouse-over', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_color_hover]',
        'value' => (isset($options_array['button_color_hover']) ? $options_array['button_color_hover'] : get_default('button_color_hover'))
        )
    );
    add_settings_field( 'button_rounded', 'Rounded corners', 'my_radio_group', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_rounded]',
        'value' => (isset($options_array['button_rounded']) ? $options_array['button_rounded'] : get_default('button_rounded')),
        'labels' => array('5' => 'Rounded', '0' => 'Square')
        )
    );
    add_settings_field( 'button_size', 'Button size', 'my_radio_group', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_size]',
        'value' => (isset($options_array['button_size']) ? $options_array['button_size'] : get_default('button_size')),
        'labels' => array('1' => 'Small', '2' => 'Medium', '3' => 'Large', '4' => 'Huge')
        )
    );
    add_settings_field( 'button_text', 'Text on the button', 'my_text_input', 'share_on_diaspora_options', 'section-one', array(
        'name' => 'share-on-diaspora-settings[button_text]',
        'value' => (isset($options_array['button_text']) ? $options_array['button_text'] : get_default('button_text'))
        )
    );
    add_settings_field( 'reset', 'Restore defaults', 'share_on_diaspora_reset_callback', 'share_on_diaspora_options', 'section-one');
    
    register_setting( 'share_on_diaspora_options-group2', 'share-on-diaspora-settings2', 'my_settings_validate2' );
    add_settings_section( 'section-two', 'Pod properties', 'section_two_callback', 'share_on_diaspora_options2' );
    require_once(plugin_dir_path( __FILE__ ).'pod_list_all.php');
    foreach ($podlist as $i)
        {
        add_settings_field( $i, $i, 'my_checkboxes', 'share_on_diaspora_options2', 'section-two', array('podname' => $i));
        }
    set_default();
    }

function section_one_callback() {
    echo 'Use the parameters below to change the look and feel of your share button. All colors are six-digit hexadecimal numbers like <code>000000</code> or <code>ffffff</code>. Leave empty to restore the default value.';
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

function section_two_callback() {
    echo 'Below is the list of Diaspora pods. Check the ones that you what to appear in the drop-down menu in the pod selection window.';
}

function my_checkboxes($args)
    {
    $options_array = get_option('share-on-diaspora-settings2');
    $podname = esc_attr( $args['podname'] );
    echo "<input type='checkbox' name='share-on-diaspora-settings2[" . $podname . "]' value='1' ";
    echo !empty( $options_array[$podname] ) ? "checked":"";
    echo "/>";
    }

function my_settings_validate( $input ) {
    $output = $input;
    $colors = array('button_color', 'button_background', 'button_color_hover', 'button_background_hover');
    foreach ($colors as $i)
        {
        if (!empty( $output[$i] ))
            {
            preg_match('/^[a-f0-9]{6}$/i', $output[$i], $match_array);
            $output[$i] = $match_array[0];
            if (empty( $output[$i] ))
                {
                add_settings_error( 'share-on-diaspora-settings', 'invalid-color', 'Invalid value for \''. $i . '\'. Reverting to default.' );
                }
            }
        else
            {
            add_settings_error( 'share-on-diaspora-settings', 'missing-color', 'Value missing for \''. $i . '\'. Reverting to default.' );
            }
        }
    if (!empty( $output['reset'] ))
        {
        add_settings_error( 'share-on-diaspora-settings', 'reverted to defaults', 'All parameters reverted to their default values.' );
        global $defaults;
        $output = $defaults;
        }
    if ( !is_writable(plugin_dir_path(__FILE__) ) )
        {
        add_settings_error( 'share-on-diaspora-settings', 'not writable', 'Plugin directory is not writable. Can not save css file.' );
        }
    return $output;
    }

//just a placeholder for tab #2.
function my_settings_validate2( $input ) { return $input; } 

function share_on_diaspora_tab1()
    {
    echo "<h3>Button Preview</h3>";
    echo generate_button(TRUE); 
    echo "<br>" .
    "<form action=\"options.php\" method=\"POST\">";
    settings_fields( 'share_on_diaspora_options-group' );
    do_settings_sections( 'share_on_diaspora_options' ); 
    submit_button('Update', 'primary',  'submit-form', false);
    echo "</form>";
    }

function share_on_diaspora_tab2()
    {
    echo "<form action=\"options.php\" method=\"POST\">";
    settings_fields( 'share_on_diaspora_options-group2' );
    do_settings_sections( 'share_on_diaspora_options2' ); 
    submit_button('Update', 'primary',  'submit-form', false);
    echo "</form>";
    }

function share_on_diaspora_options_page() {
    if ( !current_user_can( 'manage_options' ) )
        {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        };
    if ( isset($_GET['settings-updated']) && $_GET['settings-updated'])
        {
        $tab = isset($_GET['tab']) ? $_GET['tab'] : '1';
        switch ($tab)
            {
            //button settings have been saved. Put them into a css file.
            case '1': create_css_file(); break;
            //pod list settings saved. Put the into a file.
            case '2': create_pod_list_file(); break;
            }
        }
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Share on Diaspora (ver. <?php $plugin_data_array = get_plugin_data(__FILE__); echo $plugin_data_array['Version']; ?>) Options</h2>
        <h2 class="nav-tab-wrapper">
        <a href="?page=share_on_diaspora_options_page&tab=1" class="nav-tab <? if ( @( $_GET['tab'] == '1' ) || !isset($_GET['tab'])) echo "nav-tab-active"; ?>">Button options</a>
        <a href="?page=share_on_diaspora_options_page&tab=2" class="nav-tab <? if ( $_GET['tab'] == '2' ) echo "nav-tab-active"; ?>">Pod list options</a>
        </h2>
        <?php $current_tab = $_GET['tab'];
        switch ($current_tab)
            {
            case '2' : share_on_diaspora_tab2(); break;
            default: share_on_diaspora_tab1();
            } ?>
    </div>
    <?php
};
?>
