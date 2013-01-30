<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Add custom styling to HEAD
- Add custom typograhpy to HEAD
- Add layout to body_class output
- woo_feedburner_link

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Theme Setup */
/*-----------------------------------------------------------------------------------*/
/**
 * Theme Setup
 *
 * This is the general theme setup, where we add_theme_support(), create global variables
 * and setup default generic filters and actions to be used across our theme.
 *
 * @package WooFramework
 * @subpackage Logic
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */

if ( ! isset( $content_width ) ) $content_width = 640;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support for post thumbnails.
 *
 * To override woothemes_setup() in a child theme, add your own woothemes_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for automatic feed links.
 * @uses add_editor_style() To style the visual editor.
 */

add_action( 'after_setup_theme', 'woothemes_setup' );

if ( ! function_exists( 'woothemes_setup' ) ) {
	function woothemes_setup () {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		if ( is_child_theme() ) {
			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );

			define( 'CHILD_THEME_URL', $theme_data['URI'] );
			define( 'CHILD_THEME_NAME', $theme_data['Name'] );
		}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Add Custom Styling to HEAD */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_head', 'woo_custom_styling', 10 ); // Add custom styling to HEAD

if ( ! function_exists( 'woo_custom_styling' ) ) {
	function woo_custom_styling() {

		$output = '';
		// Get options
		$settings = array(
						'body_color' => '', 
						'body_img' => '', 
						'body_repeat' => '', 
						'body_pos' => '', 
						'link_color' => '', 
						'link_hover_color' => '', 
						'button_color' => ''
						);
		$settings = woo_get_dynamic_values( $settings );
		
			
		// Add CSS to output
		if ( $settings['body_color'] != '' ) {
			$output .= 'body { background: ' . $settings['body_color'] . ' !important; }' . "\n";
		}
			
		if ( $settings['body_img'] != '' ) {
			$output .= 'body { background-image: url( ' . $settings['body_img'] . ' ) !important; }' . "\n";
		}

		if ( ( $settings['body_img'] != '' ) && ( $settings['body_repeat'] != '' ) && ( $settings['body_pos'] != '' ) ) {
			$output .= 'body { background-repeat: ' . $settings['body_repeat'] . ' !important; }' . "\n";
		}
		
		if ( ( $settings['body_img'] != '' ) && ( $settings['body_pos'] != '' ) ) {
			$output .= 'body { background-position: ' . $settings['body_pos'] . ' !important; }' . "\n";
		}
		
		if ( $settings['link_color'] != '' ) {
			$output .= '#wrapper a { color: ' . $settings['link_color'] . ' !important; }' . "\n";
		}
		
		if ( $settings['link_hover_color'] != '' ) {
			$output .= '#wrapper a:hover, .post-more a:hover, .post-meta a:hover, .post p.tags a:hover { color: ' . $settings['link_hover_color'] . ' !important; }' . "\n";
		}
		
		if ( $settings['button_color'] != '' ) {
			$output .= 'button.button, a.button, #wrapper a.comment-reply-link, #commentform #submit, #contact-page .submit { background: ' . $settings['button_color'] . ' !important; border-color: ' . $settings['button_color'] . ' !important; color: #FFFFFF !important; }' . "\n";
			$output .= 'a.button:hover, a.button.hover, a.button.active, a.comment-reply-link:hover, #commentform #submit:hover, #contact-page .submit:hover { background: ' . $settings['button_color'] . ' !important; opacity: 0.9; }' . "\n";
		}
		
		// Output styles
		if ( isset( $output ) && $output != '' ) {
			$output = strip_tags( $output );
			$output = "<!-- Woo Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
			
	} // End woo_custom_styling()
}

/*-----------------------------------------------------------------------------------*/
/* Add custom typograhpy to HEAD */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_head','woo_custom_typography', 10 ); // Add custom typography to HEAD

if ( ! function_exists( 'woo_custom_typography' ) ) {
	function woo_custom_typography() {
	
		// Get options
		global $woo_options;
				
		// Reset	
		$output = '';
		
		// Add Text title and tagline if text title option is enabled
		if ( isset( $woo_options['woo_texttitle'] ) && $woo_options['woo_texttitle'] == 'true' ) {		
			
			if ( $woo_options['woo_font_site_title'] )
				$output .= '#header .site-title a {'.woo_generate_font_css($woo_options['woo_font_site_title']).'}' . "\n";	
			if ( $woo_options['woo_tagline'] == "true" AND $woo_options['woo_font_tagline'] ) 
				$output .= '#header .site-description {'.woo_generate_font_css($woo_options[ 'woo_font_tagline']).'}' . "\n";	
		}

		if ( isset( $woo_options['woo_typography'] ) && $woo_options['woo_typography'] == 'true' ) {
			
			if ( isset( $woo_options['woo_font_body'] ) && $woo_options['woo_font_body'] )
				$output .= 'body, .ac_results { '.woo_generate_font_css($woo_options['woo_font_body'], '1.5').' }' . "\n";	

			if ( isset( $woo_options['woo_font_nav'] ) && $woo_options['woo_font_nav'] )
				$output .= '#navigation, #navigation .nav a { '.woo_generate_font_css($woo_options['woo_font_nav'], '1.4').' }' . "\n";	

			if ( isset( $woo_options['woo_font_page_title'] ) && $woo_options['woo_font_page_title'] )
				$output .= '.page header h1 { '.woo_generate_font_css($woo_options[ 'woo_font_page_title' ]).' }' . "\n";

			if ( isset( $woo_options['woo_font_post_title'] ) && $woo_options['woo_font_post_title'] )
				$output .= '.post header h1, .post header h1 a:link, .post header h1 a:visited { '.woo_generate_font_css($woo_options[ 'woo_font_post_title' ]).' }' . "\n";	
		
			if ( isset( $woo_options['woo_font_post_meta'] ) && $woo_options['woo_font_post_meta'] )
				$output .= '.post-meta { '.woo_generate_font_css($woo_options[ 'woo_font_post_meta' ]).' }' . "\n";	

			if ( isset( $woo_options['woo_font_post_entry'] ) && $woo_options['woo_font_post_entry'] )
				$output .= '.entry, .entry p { '.woo_generate_font_css($woo_options[ 'woo_font_post_entry' ], '1.5').' } h1, h2, h3, h4, h5, h6 { font-family: '.stripslashes($woo_options[ 'woo_font_post_entry' ]['face']).', arial, sans-serif; }'  . "\n";	

			if ( isset( $woo_options['woo_font_widget_titles'] ) && $woo_options['woo_font_widget_titles'] )
				$output .= '.widget h3, #advanced-search-form h1 { '.woo_generate_font_css($woo_options[ 'woo_font_widget_titles' ]).' }'  . "\n";
				
			// Theme-specific Settings
			if ( isset( $woo_options['woo_font_body'] ) && $woo_options['woo_font_body'] )
				$output .= '#footer p, #comments .reply a { '.woo_generate_font_css($woo_options['woo_font_body'], '1.5').' }' . "\n";

		// Add default typography Google Font
		} else {
		
			// Set default font face
			// $woo_options['woo_default_face'] = array('face' => 'Droid Serif');
			
			// Output default font face
			if ( isset( $woo_options['woo_default_face'] ) && !empty( $woo_options['woo_default_face'] ) )
				$output .= 'h1, h2, h3, h4, h5, h6, .post .title, .page .title, .archive_header { '.woo_generate_font_css($woo_options['woo_default_face']).' }' . "\n";			
		
		} 
		
		// Output styles
		if (isset($output) && $output != '') {
		
			// Enable Google Fonts stylesheet in HEAD
			if (function_exists( 'woo_google_webfonts')) woo_google_webfonts();
			
			$output = "<!-- Woo Custom Typography -->\n<style type=\"text/css\">\n" . $output . "</style>\n\n";
			echo $output;
			
		}
			
	} // End woo_custom_typography()
}

// Returns proper font css output
if (!function_exists( 'woo_generate_font_css')) {
	function woo_generate_font_css($option, $em = '1') {

		// Test if font-face is a Google font
		global $google_fonts;
		foreach ( $google_fonts as $google_font ) {

			// Add single quotation marks to font name and default arial sans-serif ending
			if ( $option[ 'face' ] == $google_font[ 'name' ] )
				$option[ 'face' ] = "'" . $option[ 'face' ] . "', arial, sans-serif";

		} // END foreach

		if ( !@$option["style"] && !@$option["size"] && !@$option["unit"] && !@$option["color"] )
			return 'font-family: '.stripslashes($option["face"]).';';
		else
			return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].';';
	}
}

// Output stylesheet and custom.css after custom styling
remove_action( 'wp_head', 'woothemes_wp_head' );
add_action( 'woo_head', 'woothemes_wp_head' );


/*-----------------------------------------------------------------------------------*/
/* Add layout to body_class output */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class','woo_layout_body_class', 10 );		// Add layout to body_class output

if ( ! function_exists( 'woo_layout_body_class' ) ) {
	function woo_layout_body_class( $classes ) {
		
		global $woo_options;
		
		$layout = 'layout-right-content';
		
		if ( isset( $woo_options['woo_site_layout'] ) && ( $woo_options['woo_site_layout'] != '' ) ) {
			$layout = $woo_options['woo_site_layout'];
		}

		// Set main layout on post or page
		if ( is_singular() ) {
			global $post;
			$single = get_post_meta($post->ID, '_layout', true);
			if ( $single != "" AND $single != "layout-default" ) 
				$layout = $single;
		}
		
		// Add layout to $woo_options array for use in theme
		$woo_options['woo_layout'] = $layout;
		
		// Add body class for if the optional sidebar is in effect.
		if ( woo_active_sidebar( 'secondary' ) ) {
			$classes[] = 'has-secondary-sidebar';
		}

		// Add classes to body_class() output 
		$classes[] = $layout;
		return $classes;						
					
	} // End woo_layout_body_class()
}


/*-----------------------------------------------------------------------------------*/
/* woo_feedburner_link() */
/*-----------------------------------------------------------------------------------*/
/**
 * woo_feedburner_link()
 *
 * Replace the default RSS feed link with the Feedburner URL, if one
 * has been provided by the user.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_filter( 'feed_link', 'woo_feedburner_link', 10 );

function woo_feedburner_link ( $output, $feed = null ) {

	global $woo_options;

	$default = get_default_feed();

	if ( ! $feed ) $feed = $default;

	if ( $woo_options[ 'woo_feed_url' ] && ( $feed == $default ) && ( ! stristr( $output, 'comments' ) ) ) $output = esc_url( $woo_options[ 'woo_feed_url' ] );

	return $output;

} // End woo_feedburner_link()


/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>