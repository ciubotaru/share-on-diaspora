<?php
/*
Plugin Name: Share on Diaspora
Plugin URI:
Description: This plugin adds a "Share on D*" button at the bottom of your posts.
Version: 0.2
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

function diaspora_button_display($content)
{
$button_box =
		"<div id=\"diaspora-button-box\" style=\"float:left; margin-right: 10px;\">
			<a href=\"javascript:(function(){var url = window.location.href;var title = document.title;   window.open('".plugin_dir_url(__FILE__)."new_window.php?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title),'post','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=400')})()\"><div id=\"header_sharetodiaspora\" title=\"Share this on Diaspora!\"><img src=\"".plugin_dir_url(__FILE__)."/images/shareondiaspora_button_100x25.png\"  style=\"border:0 none;\"></div></a>
		</div>";
		return $content . $button_box;
}

add_action("the_content", "diaspora_button_display");

?>