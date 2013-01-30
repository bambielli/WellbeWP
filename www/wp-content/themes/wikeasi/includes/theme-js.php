<?php
/*-----------------------------------------------------------------------------------*/
/* Theme Frontend JavaScript */
/*-----------------------------------------------------------------------------------*/

if ( ! is_admin() ) { add_action( 'wp_print_scripts', 'woothemes_add_javascript' ); }

if ( ! function_exists( 'woothemes_add_javascript' ) ) {
	function woothemes_add_javascript() {
		wp_enqueue_script( 'html5', get_template_directory_uri() . '/includes/js/html5.js', array( 'jquery' ) );
		wp_enqueue_script( 'tiptip', get_template_directory_uri() . '/includes/js/jquery.tipTip.minified.js', array( 'jquery' ) );
		wp_enqueue_script( 'general', get_template_directory_uri() . '/includes/js/general.js', array( 'jquery', 'suggest', 'tiptip' ) );

		/* Setup strings to be translatable and sent through to the general.js file. */
		$translation_strings = array(
									'txt_hide' => __( 'Hide', 'woothemes' ), 
									'txt_show' => __( 'Show', 'woothemes' ), 
									'txt_revisions' => __( 'Revisions', 'woothemes' ), 
									'txt_no_results' => __( 'No Results Found', 'woothemes' ), 
									'txt_toggle' => __( 'Toggle', 'woothemes' )
									);

		$ajax_vars = array( 'ajax_url' => admin_url( 'admin-ajax.php' ) , 'ajax_search_nonce' => wp_create_nonce( 'ajax_search_nonce' ) );

		$data = array_merge( $translation_strings, $ajax_vars );

		/* Specify variables to be made available to the general.js file. */
		wp_localize_script( 'general', 'woo_localized_data', $data );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Theme Frontend CSS */
/*-----------------------------------------------------------------------------------*/

if ( ! is_admin() ) { add_action( 'wp_print_styles', 'woothemes_add_css' ); }

if ( ! function_exists( 'woothemes_add_css' ) ) {
	function woothemes_add_css() {  
		wp_register_style( 'tiptip', get_template_directory_uri() . '/includes/css/tipTip.css' );
		
		wp_enqueue_style( 'tiptip' );
		
		do_action( 'woothemes_add_css' );
	} // End woothemes_add_css()
}
?>