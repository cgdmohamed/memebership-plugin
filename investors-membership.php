<?php
/*
Plugin Name: Investors membership
Plugin URI: https://www.facebook.com/mmmoo94/
Description: An Investor membership wordpress plugin based on subscriber role, This plugin creates a role named "Investor" with capabilities like Subscriber role.
Version: 0.0.1
Author: Mohamed Mostafa
Author URI: https://www.facebook.com/mmmoo94/
License: GPLv2 or later
Text Domain: investors-membership
Domain Path: /languages
*/

//showing activation message
class Activation {

	public static function init() {
		add_action('admin_notices', array(__CLASS__, 'text_admin_notice'));
	}

	public static function text_admin_notice() {
		?>
		<div class="notice notice-success is-dismissible">
			<?php
			echo __('<p> Go to Pages > and check the login page there. The login form shortcode is <code>[inm_login]</code> .</p>', 'investors-membership');
			?>
		</div>
		<?php
	}

}
add_action('init', array('Activation', 'init'));


//register text domain
function inm_textdomain( $mofile, $domain ) {
	if ( 'investors-membership' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
		$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'inm_textdomain', 10, 2 );


//add role capabilities
add_role(
	'investor', //  Role Slug.
	__( 'Investor'  ), // Role Name.
	array(
		'read'  => true,
		'delete_posts'  => false,
		'delete_published_posts' => false,
		'edit_posts'   => false,
		'publish_posts' => false,
		'upload_files'  => false,
		'edit_pages'  => false,
		'edit_published_pages'  =>  false,
		'publish_pages'  => false,
		'delete_published_pages' => false,
	)
);


//creating page with login form on plugin activation
function inm_login_page() {
	// Create post object
	$login_page_array = array(
		'post_title'    => wp_strip_all_tags( 'Login' ),
		'post_content'  => '[inm_login]',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_type'     => 'page',
	);

	// Insert the post into the database
	wp_insert_post( $login_page_array );
}

register_activation_hook(__FILE__, 'inm_login_page');


//deleting login page on deactivation
function inm_deactivating_plugin() {

	$page = get_page_by_path( 'login' );
	wp_delete_post($page->ID);

}
register_deactivation_hook( __FILE__, 'inm_deactivating_plugin' );
