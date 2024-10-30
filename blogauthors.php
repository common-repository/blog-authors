<?php
/*
Plugin Name: Blog Authors
Description: Allows you to add your own authors page, a card under each post and an embedable card to show your profile.
Version: 1.5
Author: Andre Yonadam
Author URI: http://andreyonadam.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/* includes */
include('includes/adminpage.php'); //saving functions and options
include('includes/shortcodehandler.php'); //manages the bauthors shortcode
include('includes/functions.php'); //saving display functions
include('includes/authorcards.php'); //saving card options
include('includes/cardhandler.php'); //manages the author cards
include('includes/embedsettings.php'); //manages the author cards
/* hooks */
$ba_plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$ba_plugin", 'ba_plugin_settings_link');
add_filter('user_contactmethods', 'ba_contactmethods');
register_activation_hook(__FILE__, 'blog_authors_install');
register_deactivation_hook(__FILE__, 'ba_pluginUninstall');
add_action('show_user_profile', 'ba_show_embed_field');
add_action('edit_user_profile', 'ba_show_embed_field');
function ba_pluginUninstall() {
    global $wpdb;
    $ba_table        = $wpdb->prefix . "blog_authors";
    $ba_all_user_ids = get_users('fields=ID');
    $ba_names        = array(
        "facebookba",
        "flickrba",
        "googleba",
        "linkedinba",
        "twitterba",
        "youtubeba",
        "userrole"
    );
    for ($i = 0; $i < count($ba_names); $i++) {
        $meta_type  = 'user';
        $user_id    = 0;
        $meta_key   = $ba_names[$i];
        $meta_value = '';
        $delete_all = true;
        delete_metadata($meta_type, $user_id, $meta_key, $meta_value, $delete_all);
    }
    $wpdb->query("DROP TABLE IF EXISTS $ba_table");
    $ba_settingids = ba_settingids('blog-card');
    for ($s = 0; $s < count($ba_settingids); ++$s) {
        unregister_setting('blog-card', $ba_settingids[$s]);
        delete_option($ba_settingids[$s]);
    }
    $ba_settingids = ba_settingids('blog-authors');
    for ($s = 0; $s < count($ba_settingids); ++$s) {
        unregister_setting('blog-card', $ba_settingids[$s]);
        delete_option($ba_settingids[$s]);
    }
    $ba_settingids = ba_settingids('ba-embed-settings');
    for ($s = 0; $s < count($ba_settingids); ++$s) {
        unregister_setting('blog-card', $ba_settingids[$s]);
        delete_option($ba_settingids[$s]);
    }
}
function blog_authors_install() {
    global $wpdb;
    $ba_table_name = $wpdb->prefix . "blog_authors";
    $ba_sql        = "CREATE TABLE  $ba_table_name (	
	id bigint(20) NOT NULL,
	ordr bigint(20) NOT NULL,
	PRIMARY KEY (id));";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($ba_sql);
}
/* Adds the social site to the added users */
function ba_contactmethods($ba_user_contactmethods) {
    global $wpdb;
    global $user_id;
    $ba_id           = $user_id;
    $ba_table        = $wpdb->prefix . "blog_authors";
    $ba_myrows       = NULL;
    $ba_myrows       = $wpdb->query($wpdb->prepare('SELECT 1 FROM ' . $ba_table . ' WHERE id = %d LIMIT 1', $ba_id));
    $ba_names        = array(
        "facebook",
        "flickr",
        "google",
        "linkedin",
        "twitter",
        "youtube"
    );
    $ba_linknames    = array(
        "facebook",
        "flickr",
        "plus.google",
        "linkedin",
        "twitter",
        "youtube"
    );
    $ba_namescapital = array(
        "Facebook",
        "Flickr",
        "Google",
        "Linkedin",
        "Twitter",
        "Youtube"
    );
    if ($ba_myrows == 1) {
        for ($i = 0; $i < count($ba_names); $i++) {
            $ba_user_contactmethods[$ba_names[$i] . 'ba'] = $ba_namescapital[$i] . ' social link ' . $ba_linknames[$i] . '.com/yourlinkhere (Used By Blog Authors)';
        }
        $ba_user_contactmethods['userrole'] = 'User role (Used By Blog Authors)';
    }
    return $ba_user_contactmethods;
}
function ba_plugin_settings_link($links) {
    $ba_settings_link = '<a href="admin.php?page=blog-authors">Settings</a>';
    array_unshift($links, $ba_settings_link);
    return $links;
}
function ba_settingids($ba_arrayname) {
    $cars = array(
        'blog-card' => array(
            'card_enable',
            'card_avatar_check',
            'card_avatar_shape',
            'card_display_role',
            'card_social_networking',
            'card_font_color',
            'card_user_color',
            'card_background_style',
            'card_background_color',
            'card_border_style',
            'card_border_color',
            'card_corner_style',
            'card_recent_check',
            'card_shadow_enabled',
            'card_shadow_color'
        ),
        'blog-authors' => array(
            'display_style',
            'card_columns',
            'avatar_check',
            'avatar_shape',
            'display_role',
            'social_networking',
            'font_color',
            'user_color',
            'background_style',
            'background_color',
            'corner_style',
            'recent_check',
            'shadow_enabled',
            'shadow_color'
        ),
        'ba-embed-settings' => array(
            'embed_enabled'
        )
    );
    return $cars[$ba_arrayname];
}
function ba_show_embed_field($ba_user) {
    global $wpdb;
    $ba_id     = $ba_user->ID;
    $ba_table  = $wpdb->prefix . "blog_authors";
    $ba_myrows = NULL;
    $ba_myrows = $wpdb->query($wpdb->prepare('SELECT 1 FROM ' . $ba_table . ' WHERE id = %d LIMIT 1', $ba_id));
    if ($ba_myrows == 1) {
        if (checked(1, get_option('embed_enabled', 1), false)) {
            echo '
	<h3>Blog Authors</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Blog Authors Embed Code</label></th>

			<td>
				<input type="text" value="<iframe src=&quot;' . plugins_url('/includes/cardlink.php', __FILE__) . '?id=' . $ba_id . '&quot; width=&quot;540px&quot; height=&quot;240px&quot;></iframe>" readonly />
			</td>
		</tr>

	</table>

';
        }
    }
}
?>