<?php
/*
Plugin Name: Share on Diaspora
Plugin URI: https://github.com/ciubotaru/share-on-diaspora
Description: This plugin adds a "Share on D*" button at the bottom of your posts.
Version: 0.6.2
Author: Vitalie Ciubotaru
Author URI: https://github.com/ciubotaru
License: GPL2
*/

/*  Copyright 2013, 2014, 2015 Vitalie Ciubotaru (email : vitalie@ciubotaru.tk)

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

define( 'SHARE_ON_DIASPORA_VERSION', '0.6.2' );
define( 'SHARE_ON_DIASPORA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SHARE_ON_DIASPORA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'ShareOnDiaspora', 'plugin_activation' ) );
register_uninstall_hook( __FILE__, array( 'ShareOnDiaspora', 'plugin_uninstall' ) );

require_once( SHARE_ON_DIASPORA_PLUGIN_DIR . 'class.php' );
if ( is_admin() ) {
	require_once( SHARE_ON_DIASPORA_PLUGIN_DIR . 'admin.php' );
		$shareondiaspora_admin = new ShareOnDiaspora_Admin;
	//  add_action( 'init', array( 'ShareOnDiaspora_Admin', '__construct' ) );
}

$shareondiaspora = new ShareOnDiaspora;
?>
