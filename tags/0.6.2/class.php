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

	function plugin_activation() {
		add_option( 'share-on-diaspora-settings' );
	}

	function plugin_uninstall() {
		delete_option( 'share-on-diaspora-settings' );
	}

	function i18n_init() {
		load_plugin_textdomain( 'share-on-diaspora', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n' );
		error_log( 'i18n activated' );
	}

	function register_share_on_diaspora_css() {
		wp_register_style( 'share-on-diaspora', SHARE_ON_DIASPORA_PLUGIN_URL . '/share-on-diaspora-css.php' );
		wp_enqueue_style( 'share-on-diaspora' );
	}

	function register_share_on_diaspora_js() {
		wp_register_script( 'share-on-diaspora', SHARE_ON_DIASPORA_PLUGIN_URL . '/share-on-diaspora.js' );
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
		$button = "<div title='Diaspora*' id='diaspora-button-container'><a href=\"javascript:(function(){var url = ". $url . ' ;var title = '. $title . ";   window.open('". SHARE_ON_DIASPORA_PLUGIN_URL .'new_window.php?id=' . $post -> ID . "&amp;url='+encodeURIComponent(url)+'&amp;title='+encodeURIComponent(title),'post','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=400')})()\">" . $button_box . '</a></div>';
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
