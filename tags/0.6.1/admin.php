<?php
class ShareOnDiaspora_Admin {
    public function __construct() {
        add_action( 'plugins_loaded', array('ShareOnDiaspora', 'i18n_init') );
        // Register style sheet.
        add_action( 'admin_enqueue_scripts', array('ShareOnDiaspora', 'register_share_on_diaspora_css') );
        add_action( 'admin_enqueue_scripts', array('ShareOnDiaspora', 'register_share_on_diaspora_js') );
        add_filter( 'the_content', array('ShareOnDiaspora', 'diaspora_button_display') );
        add_action( 'admin_menu', array($this, 'share_on_diaspora_menu') );
        add_action( 'admin_init', array($this, 'my_admin_init') );
    }

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

public $podlist_defaults = array( 'podlist' => array( 'example.com' => '1') );

public $color_profiles = array(
    'Vitalie' => array(
        'button_color' => '3b5998',
        'button_background' => 'eceef5',
        'button_color_hover' => '3b5998',
        'button_background_hover' => 'ffffff'
        ),
    'Ramoth' => array(
        'button_color' => 'cccccc',
        'button_background' => '222222',
        'button_color_hover' => 'ffffff',
        'button_background_hover' => '222222'
        ),
    'Simons' => array(
        'button_color' => '51A2C1',
        'button_background' => 'ffffff',
        'button_color_hover' => '51A2C1',
        'button_background_hover' => 'ffffff'
        ),
    'F&ucirc;do' => array(
        'button_color' => '006633',
        'button_background' => 'A9C599',
        'button_color_hover' => 'fef4cc',
        'button_background_hover' => '006633'
        ),
   'Irene' => array(
        'button_color' => '0d9b50',
        'button_background' => 'edf9d2',
        'button_color_hover' => 'dd3333',
        'button_background_hover' => 'e4c0bb'
        ),
    'Asso' => array(
        'button_color' => 'FF9D45',
        'button_background' => '333333',
        'button_color_hover' => 'ff9d45',
        'button_background_hover' => '222222'
        )
    );

public $plugin_version = array( 'version' => SHARE_ON_DIASPORA_VERSION );

//public $podlist_update_url = 'http://the-federation.info/pods.json';
public $podlist_update_url = 'http://podupti.me/api.php?format=json&key=4r45tg';

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

function generate_podlist() {
    $podlist_preview = "<select id='diaspora-button-podlist'>
<option>- " . __('Select from the list', 'share-on-diaspora') . " -</option>";
    $options_array = get_option('share-on-diaspora-settings');
    if (empty($options_array['podlist'])) {
        $options_array = $this -> podlist_defaults;
    }
    foreach ($options_array['podlist'] as $key => $value) {
        $podlist_preview .= '<option  value="' . $value .'" class=dpod title="'.$key.'">'.$key.'</option>';
    }
    $podlist_preview .= "</select>";
    return $podlist_preview;
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
**/

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
    $color_profiles = $this -> color_profiles;
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

    register_setting( 'share_on_diaspora_options', 'share-on-diaspora-settings', array($this, 'my_settings_validate') );

    add_settings_section( 'section-colorprofile', __( 'Choose a preset color profile', 'share-on-diaspora' ), array($this, 'section_colorprofile_callback'), 'share_on_diaspora_options-colorprofile' );
    foreach ($color_profiles as $profile_name => $profile) {
        add_settings_field( $profile_name, $profile_name, array($this, 'my_color_profile'), 'share_on_diaspora_options-colorprofile', 'section-colorprofile', $profile);
    };

    add_settings_section( 'section-button', __( 'Button properties', 'share-on-diaspora' ), array($this, 'section_button_callback'), 'share_on_diaspora_options-button' );
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

    add_settings_section( 'section-podlist', __( 'List of pods shown to user', 'share-on-diaspora' ), array($this, 'section_podlist_callback'), 'share_on_diaspora_options-podlist' );
    $podlist = $options_array['podlist'];
    $podlist_from_dpu = get_option('dpu-podlist');
    if ( !empty( $podlist_from_dpu ) ) {
        $podlist_unchecked = array_diff($podlist_from_dpu, array_keys($podlist));
    }
    elseif ( !empty( $options_array['podlist-all'] ) ) {
        $podlist_unchecked = array_diff($options_array['podlist-all'], array_keys($podlist));
    }
    else {
        $podlist_unchecked = array('example.com');
    }
    add_settings_field( 'add_pod', __( 'Add a pod', 'share-on-diaspora' ), array($this, 'share_on_diaspora_addfield_callback'), 'share_on_diaspora_options-podlist', 'section-podlist', $podlist_unchecked);
    foreach ($podlist as $key => $value) {
        add_settings_field( $key, $key, array($this, 'my_checkboxes'), 'share_on_diaspora_options-podlist', 'section-podlist', array('podname' => $key));
    };
    add_settings_field( 'update_podlist', sprintf( __( 'Download the latest podlist from %s', 'share-on-diaspora' ), "<a href='" . ($this -> podlist_update_url) . "' target='_blank'>Podupti.me</a>"), array($this, 'share_on_diaspora_update_podlist_callback'), 'share_on_diaspora_options-podlist', 'section-podlist');
}

function section_colorprofile_callback() {
    echo __( 'You can choose a preset color profile for your button on this tab, or fine-tune it on the \'Button Options\' tab', 'share-on-diaspora' );
    echo "<input type='hidden' name='share-on-diaspora-settings[button_background]'/>";
    echo "<input type='hidden' name='share-on-diaspora-settings[button_background_hover]' />";
    echo "<input type='hidden' name='share-on-diaspora-settings[button_color]' />";
    echo "<input type='hidden' name='share-on-diaspora-settings[button_color_hover]' />";
}

function my_color_profile( $args ) {
    $bg = esc_attr( $args['button_background'] );
    $bg_mouse = esc_attr( $args['button_background_hover'] );
    $text = esc_attr( $args['button_color'] );
    $text_mouse = esc_attr( $args['button_color_hover'] );
    $settings = (array) get_option( 'share-on-diaspora-settings' );
    switch ($settings['button_size']) {
        case '2': $bs = '28'; break;
        case '3': $bs = '33'; break;
        case '4': $bs = '48'; break;
        default: $bs = '23';
        };
    $bt = $settings['button_text'];
    echo "<input type='radio' name='colorprofile' onclick=\"updateColorProfile('" . $bg . "', '" . $bg_mouse . "', '" . $text . "', '" . $text_mouse . "');\"/> <div id='diaspora-button-box' style='background-color: #" . $bg . "; border-color: #" . $text . "; color: #" . $text . ";' onMouseOver=\"this.style.backgroundColor='#" . $bg_mouse ."'; this.style.color='#" . $text_mouse . "'; this.style.border='1px solid #" . $text_mouse . "';\"
   onMouseOut=\"this.style.backgroundColor='#" . $bg ."'; this.style.color='#" . $text . "'; this.style.border='1px solid #" . $text . "';\"><font>" . $bt . "</font> <div id='diaspora-button-inner'><img src='" . plugin_dir_url(__FILE__) . "images/asterisk-" . ($bs-3) . ".png'></div></div>";
}

function section_button_callback() {
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
    echo "<input type='text' name='share-on-diaspora-settings[image_file]' value='$url' placeholder='" . sprintf( __('Example: %s', 'share-on-diaspora'), 'http://example.com/image1.png') . " style='width:100%'/>";
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

function section_podlist_callback() {
    echo sprintf( __( 'This is the list of Diaspora* pods that appear in the drop-down menu of the Share dialog window. To add a pod, type its name in the box below. To remove a pod, uncheck it. Press %s to apply changes.', 'share-on-diaspora' ), __( 'Update', 'share-on-diaspora' ) );
}

function my_checkboxes($args) {
    $options_array = get_option('share-on-diaspora-settings');
    $podname = esc_attr( $args['podname'] );
    echo "<input type='checkbox' name='share-on-diaspora-settings[podlist][" . $podname . "]' value='1' checked />";
}

function my_settings_validate( $input ) {
    if ( $input && $input['section'] && $input['section'] == 'colorprofile' ) {
        $output = $input;
    } elseif ( $input && $input['section'] && $input['section'] == 'button' ) {
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
    return $output;
}

function share_on_diaspora_addfield_callback($podlist_unchecked) {
    echo "<input type='text' name='newpodname' value='' placeholder='" . sprintf( __('Example: %s', 'share-on-diaspora'), $podlist_unchecked[array_rand($podlist_unchecked)] ) . "' list='datalist1' autocomplete='on'/><input type='button' value='" . __('Add', 'share-on-diaspora') . "' onclick='addCheckbox();'>";
    echo "<datalist id='datalist1'>";
    foreach ($podlist_unchecked as $value) {
        echo '<option  value="' . $value .'"></option>';
    }
    echo "</datalist>";
	echo "<br />" . __('Use up/down keys to choose from the list of suggested pods.', 'share-on-diaspora');
}

function share_on_diaspora_update_podlist_callback() {
    echo "<input type='submit' name='share-on-diaspora-settings[download]' value='" . __('Retrieve', 'share-on-diaspora') . "'>";
	echo "<br />";
    $dpu_installed = get_plugins('/diaspora-podlist-updater');
    if ( $dpu_installed ) {
        //plugin installed. let's see if it's activated
        $dpu_active = is_plugin_active('diaspora-podlist-updater/diaspora-podlist-updater.php');
        if ( $dpu_active ) {
            //active
            printf( __("%s plugin is installed and enabled. You don't need to manually retrieve the list of active Diaspora* pods.", 'share-on-diaspora'), '<a href="http://wordpress.org/plugins/diaspora-podlist-updater">Diaspora Podlist Updater</a>' );
        }
        else {
            //installed, but not active
            printf( __('%s plugin is installed, but not activated. You can activate it to automatically retrieve and update the list of active Diaspora* pods.', 'share-on-diaspora' ), '<a href="http://wordpress.org/plugins/diaspora-podlist-updater">Diaspora Podlist Updater</a>' );
		}
    }
    else {
        //not installed
        printf( __('You can install %s plugin to automatically retrieve and update the list of active Diaspora* pods.', 'share-on-diaspora' ), '<a href="http://wordpress.org/plugins/diaspora-podlist-updater">Diaspora Podlist Updater</a>' );
    }
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
    if (!empty($input['download'])) {
        $json = file_get_contents($this -> podlist_update_url);
        if (empty($json)) {
            add_settings_error( 'share-on-diaspora-settings', 'download failed', __( 'Could not update the podlist.', 'share-on-diaspora' ) );
            return array();
        }
        $podlist_raw = json_decode($json, true);
//        if ( json_last_error() !== JSON_ERROR_NONE) {
        if ( $podlist_raw === null ) {
            add_settings_error( 'share-on-diaspora-settings', 'not json', __( 'Could not update the podlist.', 'share-on-diaspora' ) );
            return array();
        }
        $podlist_clean = $podlist_raw['pods'];
        $output = array();
        foreach ( $podlist_clean as $pod ) {
            //if ($pod['network'] == "Diaspora") {
            if ($pod['hidden'] == 'no') {
               // array_push($output, $pod['host']);
                array_push($output, $pod['domain']);

            }
        }
        $input['podlist-all'] = $output;
    } elseif (empty($input['podlist'])) {
        add_settings_error( 'share-on-diaspora-settings', 'empty-podlist', sprintf( __('Value missing for %s. Reverting to default.', 'share-on-diaspora' ), "'podlist'") );
        $input = array_merge( $input, array( 'podlist' => array('example.com' => '1') ) );
    }
    return $input;
}

// color profiles
function share_on_diaspora_tab1() {
    echo "<form action='options.php' method='post' name='button'>";
    echo "<input type='hidden' name='share-on-diaspora-settings[section]' value='colorprofile'>";
    settings_fields( 'share_on_diaspora_options' );
    do_settings_sections( 'share_on_diaspora_options-colorprofile' );
    submit_button(__( 'Update', 'share-on-diaspora' ), 'primary',  'submit-form', false);
    echo "</form>";
}

function share_on_diaspora_tab2() {
    echo "<h3>".__( "Button Preview", 'share-on-diaspora' )."</h3>";
    echo ShareOnDiaspora::generate_button(TRUE, '0');
    echo "<form action='options.php' method='post' name='button'>";
    echo "<input type='hidden' name='share-on-diaspora-settings[section]' value='button'>";
    settings_fields( 'share_on_diaspora_options' );
    do_settings_sections( 'share_on_diaspora_options-button' );
    submit_button(__( 'Update', 'share-on-diaspora' ), 'primary',  'submit-form', false);
    echo "</form>";
}

function share_on_diaspora_tab3() {
    echo "<h3>".__( "Custom Image Preview", 'share-on-diaspora' )."</h3>";
    echo $this -> show_button_image();
    echo "<form method='post' name='upload' enctype='multipart/form-data'>";
    echo "<input type='hidden' name='share-on-diaspora-settings[section]' value='image'>";
    settings_fields( 'share_on_diaspora_options' );
    do_settings_sections( 'share_on_diaspora_options-upload' );
    submit_button(__('Update', 'share-on-diaspora' ), 'primary',  'submit-form', false);
    echo "</form>";
}

function share_on_diaspora_tab4() {
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
        //form in tab 3 is updated manually, so settings errrors are not shown properly. Thus workaround.
        if (isset($_GET['tab']) && $_GET['tab'] == '3') settings_errors('share-on-diaspora-settings');
        ?>
        <h2><?php $plugin_data_array = get_plugin_data(__FILE__); printf( __('Share on Diaspora (ver. %s) Options', 'share-on-diaspora' ), SHARE_ON_DIASPORA_VERSION ); ?></h2>
        <p><?php printf( __('Need help? Please read %1$splugin\'s FAQ page%2$s.', 'share-on-diaspora'), "<a href='http://wordpress.org/plugins/share-on-diaspora/faq'>", '</a>'); ?></p>
        <h2 class="nav-tab-wrapper">
        <a href="?page=share_on_diaspora_options_page&amp;tab=1" class="nav-tab <?php if ( $tab == '1' ) echo "nav-tab-active"; ?>"><?php echo __('Color profiles', 'share-on-diaspora'); ?></a>
        <a href="?page=share_on_diaspora_options_page&amp;tab=2" class="nav-tab <?php if ( $tab == '2' ) echo "nav-tab-active"; ?>"><?php echo __('Button options', 'share-on-diaspora'); ?></a>
        <a href="?page=share_on_diaspora_options_page&amp;tab=3" class="nav-tab <?php if ( $tab == '3' ) echo "nav-tab-active"; ?>"><?php echo __('Custom image', 'share-on-diaspora'); ?></a>
        <a href="?page=share_on_diaspora_options_page&amp;tab=4" class="nav-tab <?php if ( $tab == '4' ) echo "nav-tab-active"; ?>"><?php echo __('Pod list options', 'share-on-diaspora'); ?></a>
        </h2>
        <?php switch ($tab)
            {
            case '2' : $this -> share_on_diaspora_tab2(); break;
            case '3' : $this -> share_on_diaspora_tab3(); break;
            case '4' : $this -> share_on_diaspora_tab4(); break;
            default: $this -> share_on_diaspora_tab1();
            } ?>
<hr>
<p><?php echo __('Source code repository', 'share-on-diaspora'); ?>: <a href='https://github.com/ciubotaru/share-on-diaspora'>https://github.com/ciubotaru/share-on-diaspora</a></p>
<p><?php echo __('Donate BTC', 'share-on-diaspora'); ?>: 1At3bPLGDBTXucAPeVRK6DHNkHLFLNXmhj</p>
    </div>
    <?php
}
} //end class
?>
