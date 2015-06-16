<?php
class ShareOnDiaspora {
	public function __construct() {
		//        registrer_activation_hook( __FILE__, array( $this, 'share_on_diaspora_activation' ) );
		//        register_deactivation_hook( __FILE__, array( $this, 'share_on_diaspora_deactivation' ) );
		add_action( 'plugins_loaded', array($this, 'i18n_init') );
		// Register style sheet.
		add_action( 'wp_enqueue_scripts', array($this, 'register_share_on_diaspora_css') );
		add_filter( 'the_content', array($this, 'diaspora_button_display') );
	}

	public static function plugin_activation() {
		add_option( 'share-on-diaspora-settings' );
	}

	public static function plugin_uninstall() {
		delete_option( 'share-on-diaspora-settings' );
	}

	public static function i18n_init() {
		load_plugin_textdomain( 'share-on-diaspora', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n' );
	}

	public static function register_share_on_diaspora_css() {
		wp_register_style( 'share-on-diaspora', SHARE_ON_DIASPORA_PLUGIN_URL . '/share-on-diaspora-css.php' );
		wp_enqueue_style( 'share-on-diaspora' );
	}

	public static function register_share_on_diaspora_js() {
		wp_register_script( 'share-on-diaspora', SHARE_ON_DIASPORA_PLUGIN_URL . '/share-on-diaspora.js' );
		wp_enqueue_script( 'share-on-diaspora' );
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


	function generate_button($preview, $use_own_image) {
		/**
		 * if preview == TRUE && $use_own_image == '0', prepare fake link and output standard button
		 * if preview == FALSE && $use_own_image == '0', prepare real link and output standard button
		 * if preview == FALSE && $use_own_image == '1', prepare real link and output custom button
		 * if preview == TRUE && $use_own_image == '1', impossible
		 * the button is inside, so let's prepare button first
		 */
		$button_defaults = $this -> button_defaults;
		$options_array = get_option( 'share-on-diaspora-settings' );
		if ( $use_own_image ) {
			//use own image
			$button_box = "<span id='diaspora-button-ownimage-div'><img id='diaspora-button-ownimage-img' src='" . $options_array['image_file'] . "' alt=''/></span>";
		} else {
			//use standard image
			switch ( $options_array['button_size'] ) {
				case '2': $bs = '28'; break;
				case '3': $bs = '33'; break;
				case '4': $bs = '48'; break;
				default: $bs = '23';
			}
			$bt = ! empty( $options_array['button_text'] ) ? $options_array['button_text'] : $button_defaults['button_text'];
			$button_box = "<span id='diaspora-button-box'><font>" . $bt  . "</font> <span id='diaspora-button-inner'><img src='" . SHARE_ON_DIASPORA_PLUGIN_URL . 'images/asterisk-' . ($bs -3) . ".png' alt=''/></span></span>";
		}
		if ( $preview || is_admin() ) {
			//add fake link
			$url = "'[".__( 'Page address here', 'share-on-diaspora' )."]'";
			$title = "'[".__( 'Page title here', 'share-on-diaspora' )."]'";
		} elseif ( is_single() ) {
			//add real link from DOM
			$url = 'window.location.href';
			$title = 'document.title';
		} else {
			//add real link from WP
			$url = "'".esc_url( get_permalink() )."'";
			$title = "'".get_the_title()."'";
		}
		$button = "<div title='Diaspora*' id='diaspora-button-container'><a href=\"javascript:(function(){var url = ". $url . ' ;var title = '. $title . ";   window.open('". SHARE_ON_DIASPORA_PLUGIN_URL . "new_window.php?url='+encodeURIComponent(url)+'&amp;title='+encodeURIComponent(title),'post','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=400')})()\">" . $button_box . '</a></div>';
		return $button;
	}

	function diaspora_button_display($content) {
		if ( get_post_type() == 'post' && ( ! in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] )) ) {
			$options_array = get_option( 'share-on-diaspora-settings' );
			$button_box = $this -> generate_button( false, $options_array['use_own_image'] );
			return $content . $button_box;
		} else { return $content; }
	}

	/**
	public function filter_plugin_actions($l, $file) {
		$settings_link = '<a href="options-general.php?page=share_on_diaspora_options_page">'.__('Settings').'</a>';
		array_unshift($l, $settings_link);
		return $l;
	}
*/
}
?>
