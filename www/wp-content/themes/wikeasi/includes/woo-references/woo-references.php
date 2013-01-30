<?php
/**
 * WooThemes References Manager.
 *
 * Controls the management and insertion of references into posts and pages.
 *
 * @category Modules
 * @package WordPress
 * @subpackage WooFramework
 * @author Matty at WooThemes
 * @date 2011-11-03.
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - var $token
 * - var $singular
 * - var $plural
 *
 * - var $template_url
 * - var $reference_counter
 * - var $count_reference
 * - var $total_references
 * - var $used_numbers
 * - var $current_reference
 * - var $references
 * - var $reference_keys

 * - Constructor Function
 * - function init()
 * - function filter_mce_buttons()
 * - function filter_mce_external_plugins()
 * - function create_reference_links()
 * - function create_reference_links_callback()
 * - function tag_unautop()
 * - function remove_empty_p()
 * - function filter_content()
 * - function toggle_filters()
 * - function register_post_type()
 * - function customise_wooframework_meta_box()
 * - function enqueue_styles()
 * - function add_sortable_columns()
 * - function add_column_headings()
 * - function add_column_data()
 * - function sort_on_custom_columns()
 * - function get_formatted_reference()
 */

class WooThemes_References {

	/**
	 * Variables
	 *
	 * @description Setup of variable placeholders, to be populated when the constructor runs.
	 * @since 1.0.0
	 */

	var $token;
	var $singular;
	var $plural;
	
	var $template_url;
	var $reference_counter;
	var $count_reference;
	var $total_references;
	var $used_numbers;
	var $current_reference;
	var $references;

	/**
	 * WooThemes_References function.
	 *
	 * @description Constructor function. Sets up the class and registers variable action hooks.
	 * @access public
	 * @return void
	 */
	function WooThemes_References () {
		$this->token = 'reference';
		$this->singular = __( 'Reference', 'woothemes' );
		$this->plural = __( 'References', 'woothemes' );
		
		$this->template_url = get_template_directory_uri();
		$this->reference_counter = 2;
		$this->count_reference = true;
		$this->total_references = 1;
		$this->current_reference = 2;
		$this->used_numbers = array();
		$this->references = array();
		$this->reference_keys = array();
	} // End WooThemes_References()
	
	/**
	 * init function.
	 *
	 * @description This guy runs the show. Rocket boosters... engage!
	 * @access public
	 * @return void
	 */
	function init() {
		add_action( 'init', array( &$this, 'register_post_type' ), 10 );
		
		add_action( 'admin_print_styles-edit.php', array( &$this, 'enqueue_styles' ), 10 );
		add_action( 'admin_print_styles-post.php', array( &$this, 'enqueue_styles' ), 10 );
		add_action( 'admin_print_styles-post-new.php', array( &$this, 'enqueue_styles' ), 10 );
		
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) == 'true' )  {

			// Add the tinyMCE buttons and plugins.
			add_filter( 'mce_buttons', array( &$this, 'filter_mce_buttons' ) );
			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );

		} // End IF Statement

		if ( is_admin() ) {
			global $pagenow;

			if ( ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) ) {
				add_filter( 'woothemes_metabox_settings', array( &$this, 'customise_wooframework_meta_box' ), 10, 3 );
			}
			
			if ( $pagenow == 'edit.php' ) {
				// add_filter( 'manage_edit-' . $this->token . '_sortable_columns', array( &$this, 'add_sortable_columns' ), 10, 1 );
				add_filter( 'manage_edit-' . $this->token . '_columns', array( &$this, 'add_column_headings' ), 10, 1 );
				add_action( 'manage_posts_custom_column', array( &$this, 'add_column_data' ), 10, 2 );
				// add_filter( 'pre_get_posts', array( &$this, 'sort_on_custom_columns' ), 10, 1 );
			}
		}

		$this->toggle_filters();
	} // End init()

	/**
	 * filter_mce_buttons function.
	 *
	 * @description Add our new button to the tinyMCE editor.
	 * @access public
	 * @param array $buttons
	 * @return array $buttons
	 */
	function filter_mce_buttons( $buttons ) {
		array_push( $buttons, '|', 'WooThemes_References' );

		return $buttons;
	} // End filter_mce_buttons()

	/**
	 * filter_mce_external_plugins function.
	 *
	 * @description Add functionality to the tinyMCE editor as an external plugin.
	 * @access public
	 * @param mixed $plugins
	 * @return array $plugins
	 */
	function filter_mce_external_plugins( $plugins ) {
		$plugins['WooThemes_References'] = $this->template_url . '/includes/woo-references/js/editor_plugin.js';

		return $plugins;
	} // End filter_mce_external_plugins()
	
	/**
	 * create_reference_links function.
	 *
	 * @description Creates reference links in the content by replacing <!--reference--> tags.
	 * @access public
	 * @param string $content
	 * @return string $content
	 */
	function create_reference_links ( $content ) {
		global $post;

		$pattern = '/<!--reference(.*?)?-->/';

		preg_match_all( $pattern, $content, $matches );

		if ( is_array( $matches ) && is_array( $matches[0] ) ) {
			$this->total_references = count( $matches[0] );
		}

		if ( preg_match( $pattern, $content, $matches ) ) {
			$content = preg_replace_callback( $pattern, array( &$this, 'create_reference_links_callback' ), $content );
		}

		return $content;
	} // End create_reference_links()
	
	/**
	 * create_reference_links_callback function.
	 *
	 * @description Creates references in the content by replacing <!--reference--> tags.
	 * @access public
	 * @param array $matches
	 * @return string $content
	 */
	function create_reference_links_callback ( $matches ) {
		$current = $this->current_reference;
		
		if ( ! in_array( $matches[1], $this->reference_keys ) ) {
			$this->reference_keys[] = $matches[1];
		}
		
		// Get current reference number.
		$reference_number = '';
		foreach( $this->reference_keys as $k => $v ) {
			if ( $v == $matches[1] ) {
				$reference_number = $k + 1;
				break;
			}
		}
		
		$content = ' <a href="#reference-' . $matches[1] . '" title="' . __( 'View Reference', 'woothemes' ) . '" class="reference-link-in-content"><sup>' . $reference_number . '</sup></a>';
		
		if ( ! isset( $this->references["'" . $matches[1] . "'"] ) ) { $this->references[$matches[1]] = get_post( intval( $matches[1] ) ); }
		
		$this->current_reference++;		

		return $content;
	} // End create_reference_links_callback()

	/**
	 * tag_unautop function.
	 *
	 * @description Ensures that <!--reference--> tags are not wrapped in <<p>>...<</p>>.
	 * @since 1.0.0
	 * @param string $content The content.
	 * @return string The filtered content.
	 */
	function tag_unautop ( $content ) {
		global $shortcode_tags;

		if ( !empty( $shortcode_tags ) && is_array( $shortcode_tags ) ) {
			$tagnames = array_keys( $shortcode_tags );
			$tagregexp = join( '|', array_map( 'preg_quote', $tagnames ) );
			$content = preg_replace( '/<p>\\s*?(<!--reference-->)\\s*<\\/p>/s', '$1', $content );
		}

		return $content;
	} // End tag_unautop()

	/**
	 * remove_empty_p function.
	 * 
	 * @access public
	 * @description Attempts to remove empty <p></p> tags.
	 * @param string $content
	 * @return string $content
	 */
	function remove_empty_p ( $content ) {
		$content = str_replace( '<p></p>', '', $content );

		return $content;
	} // End remove_empty_p()

	/**
	 * filter_content function.
	 * 
	 * @access public
	 * @param string $content
	 * @return string $content
	 */
	function filter_content ( $content ) {
		if ( count( $this->references ) > 0 ) {
			$references = '';
			global $post;
			
			$original_post = $post;
		
			$count = 0;
			$half_way = round( count( $this->references ) / 2 );
			
			$references = '<div id="references" class="references">' . "\n" . '<h3>' . __( 'References', 'woothemes' ) . '</h3>' . "\n" . '<ol>' . "\n";
				foreach ( $this->references as $k => $post ) {
					$count++;
					setup_postdata( $post );
					$references .= '<li id="reference-' . get_the_ID() . '">' . '<span class="number">' . $count . '.</span> ' . $this->get_formatted_reference( get_the_ID() ) . '</li>' . "\n";
					if ( $count == $half_way ) {
						$references .= '</ol><ol>';
					}
				}
			$references .= '</ol>' . "\n" . '</div>' . "\n";
			
			$content .= $references;
			
			$post = $original_post;
		}
		return $content;
	} // End filter_content()

	/**
	 * toggle_filters function.
	 *
	 * @description Toggle content filters.
	 * @access public
	 * @param bool $off (default: false)
	 * @return void
	 */
	function toggle_filters ( $off = false ) {
		if ( $off == true ) {
			// Create columns in the content.
			remove_filter( 'the_content', array( &$this, 'tag_unautop' ), 10 );
			remove_filter( 'the_content', array( &$this, 'create_reference_links' ), 11 );
			remove_filter( 'the_content', array( &$this, 'remove_empty_p' ), 12 );
			remove_filter( 'the_content', array( &$this, 'filter_content' ), 13 );
		} else {
			// Create columns in the content.
			add_filter( 'the_content', array( &$this, 'tag_unautop' ), 10 );
			add_filter( 'the_content', array( &$this, 'create_reference_links' ), 11 );
			add_filter( 'the_content', array( &$this, 'remove_empty_p' ), 12 );
			add_filter( 'the_content', array( &$this, 'filter_content' ), 13 );
		}
	} // End toggle_filters()
	
	/**
	 * register_post_type function.
	 * 
	 * @access public
	 * @return void
	 */
	function register_post_type () {
		$labels = array(
	    'name' => $this->plural,
	    'singular_name' => $this->singular,
	    'add_new' => _x( 'Add New', $this->token ),
	    'add_new_item' => sprintf( __( 'Add New %s', 'woothemes' ), $this->singular ),
	    'edit_item' => sprintf( __( 'Edit %s', 'woothemes' ), $this->singular ),
	    'new_item' => sprintf( __( 'New %s', 'woothemes' ), $this->singular ),
	    'all_items' => sprintf( __( 'All %s', 'woothemes' ), $this->plural ),
	    'view_item' => sprintf( __( 'View %s', 'woothemes' ), $this->singular ),
	    'search_items' => sprintf( __( 'Search %s', 'woothemes' ), $this->plural ),
	    'not_found' =>  sprintf( __( 'No %s Found', 'woothemes' ), $this->plural ),
	    'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'woothemes' ), $this->plural ), 
	    'parent_item_colon' => '',
	    'menu_name' => $this->plural
	
	  );
	  $args = array(
	    'labels' => $labels,
	    'public' => true,
	    'exclude_from_search' => true, 
	    'publicly_queryable' => false,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'query_var' => true,
	    'rewrite' => true,
	    'capability_type' => 'post',
	    'has_archive' => true, 
	    'hierarchical' => false,
	    'menu_position' => 5, 
	    'menu_icon' => $this->template_url . '/includes/woo-references/js/img/icon_16.png', 
	    'supports' => array( 'title', 'excerpt' )
	  );
	
		register_post_type( $this->token, $args );
	} // End register_post_type()
	
	/**
	 * customise_wooframework_meta_box function.
	 * 
	 * @access public
	 * @param array $settings
	 * @param string $type
	 * @param string $handle
	 * @return array $settings
	 */
	function customise_wooframework_meta_box( $settings, $type, $handle ) {
		if ( $type == 'reference' ) {
			$settings['title'] = __( 'Reference Details', 'woothemes' );
		}
		
		return $settings;
	} // End customise_wooframework_meta_box()
	
	/**
	 * enqueue_styles function.
	 * 
	 * @access public
	 * @return void
	 */
	function enqueue_styles () {
		global $pagenow;
		
		wp_register_style( 'woo-references-dialog', $this->template_url . '/includes/woo-references/js/css/dialog.css', '', '1.0.0' );
		wp_register_style( 'woo-references-admininterface', $this->template_url . '/includes/woo-references/css/admin.css', '', '1.0.0' );
		
		// Only on the "add" and "edit" screens.
		if ( $pagenow != 'edit.php' ) {
			wp_enqueue_style( 'woo-references-dialog' );
		}
		
		if ( ( get_query_var( 'post_type' ) == $this->token ) || ( get_post_type() == $this->token ) ) {
			wp_enqueue_style( 'woo-references-admininterface' );
		}
	} // End enqueue_styles()
	
	/**
	 * add_sortable_columns function.
	 * 
	 * @access public
	 * @param array $columns
	 * @return array $columns
	 */
	function add_sortable_columns ( $columns ) {
		$columns['isbn'] = 'isbn';
		$columns['publisher'] = 'publisher';
		return $columns;
	} // End add_sortable_columns()
	
	/**
	 * add_column_headings function.
	 * 
	 * @access public
	 * @param array $defaults
	 * @return array $new_columns
	 */
	function add_column_headings ( $defaults ) {
		
		$new_columns['cb'] = '<input type="checkbox" />';
		// $new_columns['id'] = __( 'ID' );
		$new_columns['title'] = _x( 'Reference Title', 'column name', 'woothemes' );
		$new_columns['isbn'] = __( 'ISBN Number', 'woothemes' );
		$new_columns['publisher'] = __( 'Publisher', 'woothemes' );
		$new_columns['author'] = __( 'Added By', 'woothemes' );
 		$new_columns['date'] = _x( 'Added On', 'column name', 'woothemes' );
 
		return $new_columns;
		
	} // End add_column_headings()
	
	/**
	 * add_custom_column_data function.
	 * 
	 * @access public
	 * @param string $column_name
	 * @param int $id
	 * @return void
	 */
	function add_column_data ( $column_name, $id ) {
		global $wpdb, $post;
		
		$meta = get_post_custom( $id );
		
		switch ( $column_name ) {
		
			case 'id':
				echo $id;
			break;
			
			case 'isbn':
				$value = __( 'No ISBN Number Specified', 'woothemes' );
				if ( isset( $meta['_isbn'] ) && ( $meta['_isbn'][0] != '' ) ) {
					$value = $meta['_isbn'][0];
				}
				echo $value;
			break;
			
			case 'publisher':
				$value = __( 'No Publisher Specified', 'woothemes' );
				if ( isset( $meta['_publisher'] ) && ( $meta['_publisher'][0] != '' ) ) {
					$value = $meta['_publisher'][0];
				}
				echo $value;
			break;
			
			default:
			break;
		
		}
	} // End add_column_data()
	
	/**
	 * sort_on_custom_columns function.
	 * 
	 * @access public
	 * @param object $query
	 * @return object $query
	 */
	function sort_on_custom_columns ( $query ) {
		global $post_type;
		
		if ( ( $post_type == $this->token ) ) {
			$orderby = get_query_var( 'orderby' );
			$order = get_query_var( 'order' );
			
			if (
				in_array( $orderby, array( 'isbn', 'publisher' ) ) && 
				in_array( $order, array( 'asc', 'desc' ) )
				) {
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', $order );
				$query->set( 'meta_key', '_' . $orderby );
				$query->parse_query();
			}
		}
		
		return $query;
	} // End sort_on_custom_columns()
	
	/**
	 * get_formatted_reference function.
	 * 
	 * @access public
	 * @param int $id
	 * @return string $html
	 */
	function get_formatted_reference ( $id ) {
		$html = '';
		
		$meta = get_post_custom( $id );
		
		$post_data = get_post( $id );

		$html .= get_the_title( $id );
		
		// Publisher display logic.
		$publisher_title = '';
		$publisher_text = '';
		
		if ( isset( $meta['_publisher'] ) && ( $meta['_publisher'][0] != '' ) ) {
			$publisher_title = $meta['_publisher'][0];
			$publisher_text = $publisher_title;
		}
		
		if ( isset( $meta['_publisher_url'] ) && ( $meta['_publisher_url'][0] != '' ) ) {
			if ( $publisher_title == '' ) { $publisher_title = $meta['_publisher_url'][0]; }
			$publisher_text = '<a href="' . $meta['_publisher_url'][0] . '" title="' . esc_attr( $publisher_title ) . '" class="url">' . $publisher_title . '</a>';
		}
		
		$html .= ' ' . $publisher_text;
		
		// Pages display logic.
		if ( isset( $meta['_pages'] ) && ( $meta['_pages'][0] != '' ) ) {
			$html .= ' pp. ' . $meta['_pages'][0];
		}
		
		// ISBN display logic.
		$publisher_title = '';
		$publisher_text = '';
		
		if ( isset( $meta['_isbn'] ) && ( $meta['_isbn'][0] != '' ) ) {
			$publisher_title = $meta['_isbn'][0];
			$publisher_text = $publisher_title;
		}
		
		if ( isset( $meta['_publisher_url'] ) && ( $meta['_publisher_url'][0] != '' ) ) {
			if ( $publisher_title == '' ) { $publisher_title = $meta['_publisher_url'][0]; }
			$publisher_text = '<a href="' . $meta['_publisher_url'][0] . '" title="' . esc_attr( $publisher_title ) . '" class="url">' . $publisher_title . '</a>';
		}
		
		$html .= ' ' . $publisher_text;
		
		// The excerpt display logic.
		$html .= '<p>' . $post_data->post_excerpt . '</p>';
		
		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'woo_get_formatted_reference', $html, $id, $meta );
		
		return $html;
	} // End get_formatted_reference()

} // End Class
?>