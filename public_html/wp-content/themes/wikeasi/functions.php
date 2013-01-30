<?php

function my_connection_types() {
  if ( !function_exists('p2p_register_connection_type') )
          return;

	/*
	p2p_register_connection_type( array(
		'name' => 'posts_to_pages',
		'from' => 'post',
		'to' => 'page'
	) );
  */
  
	p2p_register_connection_type( array(
		'name' => 'posts_to_treatments',
		'from' => 'post',
		'to' => 'treatments'
	) );

	p2p_register_connection_type( array(
		'name' => 'posts_to_conditions',
		'from' => 'post',
		'to' => 'conditions'
	) );

	p2p_register_connection_type( array(
		'name' => 'posts_to_symptoms',
		'from' => 'post',
		'to' => 'symptoms'
	) );

	p2p_register_connection_type( array(
		'name' => 'conditions_to_treatments',
		'from' => 'conditions',
		'to' => 'treatments'
	) );

	p2p_register_connection_type( array(
		'name' => 'symptoms_to_conditions',
		'from' => 'symptoms',
		'to' => 'conditions'
	) );

	p2p_register_connection_type( array(
		'name' => 'testimonials_to_treatments',
		'from' => 'testimonials',
		'to' => 'treatments'
	) );

	p2p_register_connection_type( array(
		'name' => 'testimonials_to_conditions',
		'from' => 'testimonials',
		'to' => 'conditions'
	) );

	p2p_register_connection_type( array(
		'name' => 'testimonials_to_symptoms',
		'from' => 'testimonials',
		'to' => 'symptoms'
	) );
}
add_action( 'p2p_init', 'my_connection_types' );

function change_mce_options( $init ) {
 //$init['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
 $init['theme_advanced_disable'] = 'link,unlink,wp_more,wp_adv,woothemes_shortcodes_button';
 return $init;
}
add_filter('tiny_mce_before_init', 'change_mce_options');

function add_custom_contactmethod( $contactmethods ) {
	unset($contactmethods['aim']);
	unset($contactmethods['jabber']);
	unset($contactmethods['yim']);
	//$contactmethods['user_description'] = 'Tell us your story';

  return $contactmethods;
}
add_filter('user_contactmethods','add_custom_contactmethod');

remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");

add_filter( 'gettext', 'wpse6096_gettext', 10, 2 );
function wpse6096_gettext( $translation, $original )
{
    if ( 'Biographical Info' == $original ) {
        return 'Tell us your health story';
    }
    if ( 'Share a little biographical information to fill out your profile. This may be shown publicly.' == $original ) {
        return '';
    }


    return $translation;
}

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Set path to WooFramework and theme specific functions
$functions_path = get_template_directory() . '/functions/';
$includes_path = get_template_directory() . '/includes/';

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', 'lt384huxazx27rvdk1mmm5byw4h73jrol' );

// WooFramework
require_once ($functions_path . 'admin-init.php' );			// Framework Init

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-plugins.php', 			// Theme specific plugins integrated in a theme
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php'			// Theme widgets
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );
				
foreach ( $includes as $i ) {
	locate_template( $i, true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>