<?php
/*
Plugin Name: Astahub - Disable Comments
Plugin URI: https://github.com/astahub/astahub-disable-comments
Description: Disable build in comment system on backend and frontend.
Author: harisrozak
Author URI: https://github.com/harisrozak
Version: 0.1
Text Domain: astahub-disable-comments
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

// Disable support for comments and trackbacks in post types
function astahub_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'astahub_disable_comments_post_types_support');

// Close comments on the front-end
function astahub_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'astahub_disable_comments_status', 20, 2);
add_filter('pings_open', 'astahub_disable_comments_status', 20, 2);

// Hide existing comments
function astahub_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'astahub_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function astahub_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'astahub_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function astahub_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'astahub_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function astahub_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'astahub_disable_comments_dashboard');

// Remove comments links from admin bar
function astahub_remove_admin_bar__comments(){
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'astahub_remove_admin_bar__comments' );