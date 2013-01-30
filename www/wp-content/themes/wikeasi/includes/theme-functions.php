<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Exclude categories from displaying on the "Blog" page template.
- Exclude categories from displaying on the homepage.
- Register WP Menus
- Page navigation
- Post Meta
- Subscribe & Connect
- Comment Form Fields
- Comment Form Settings
- Archive Description
- Get Theme Options For Use Below
- Custom Read More Text
- Table of Contents - Shortcode
- Table of Contents - Content Filter
- Table of Contents - Automation
- Table of Contents - Generator
- Revisions Display - Instantiate The WooThemes_Content_Revisions Class
- User Profiles - Load User Profiles Functionality
- References - Instantiate the WooThemes_References Class
- Modify woo_breadcrumbs() Arguments Specific to this Theme
- Search Auto-Complete - Search Term Suggestions Query
- Filter Bar - Modify Post Count If Applicable
- Filter Bar - Modify Order Direction For "Alphabetical"
- Filter Bar - Get Query Totals
- Search - Automatically Redirect To Result If Only One
- Search - AJAX Search Query
- Search - Add CSS Classes To The <body> Tag For Search-related Toggles
- Load custom "Theme Options" CSS on the "Theme Options" screen
- Add "Comment Status" CSS classes to each post_class
- Change Woo Pagination Prev/Next Elements

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the "Blog" page template.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the "Blog" page template.
add_filter( 'woo_blog_template_query_args', 'woo_exclude_categories_blogtemplate' );

function woo_exclude_categories_blogtemplate ( $args ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $args; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_blog' );

	// Homepage logic.
	if ( count( $excluded_cats ) > 0 ) {

		// Setup the categories as a string, because "category__not_in" doesn't seem to work
		// when using query_posts().

		foreach ( $excluded_cats as $k => $v ) { $excluded_cats[$k] = '-' . $v; }
		$cats = join( ',', $excluded_cats );

		$args['cat'] = $cats;
	}

	return $args;

} // End woo_exclude_categories_blogtemplate()

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the homepage.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the homepage.
add_filter( 'pre_get_posts', 'woo_exclude_categories_homepage' );

function woo_exclude_categories_homepage ( $query ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $query; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_home' );

	// Homepage logic.
	if ( is_home() && ( count( $excluded_cats ) > 0 ) ) {
		$query->set( 'category__not_in', $excluded_cats );
	}

	$query->parse_query();

	return $query;

} // End woo_exclude_categories_homepage()

/*-----------------------------------------------------------------------------------*/
/* Register WP Menus */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu', 'woothemes' ) ) );
	register_nav_menus( array( 'top-menu' => __( 'Top Menu', 'woothemes' ) ) );
}


/*-----------------------------------------------------------------------------------*/
/* Page navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_pagenav')) {
	function woo_pagenav() {

		global $woo_options;

		// If the user has set the option to use simple paging links, display those. By default, display the pagination.
		if ( array_key_exists( 'woo_pagination_type', $woo_options ) && $woo_options[ 'woo_pagination_type' ] == 'simple' ) {
			if ( get_next_posts_link() || get_previous_posts_link() ) {
		?>
            <nav class="nav-entries fix">
                <?php next_posts_link( '<span class="nav-prev fl">'. __( '<span class="meta-nav">&larr;</span> Older posts', 'woothemes' ) . '</span>' ); ?>
                <?php previous_posts_link( '<span class="nav-next fr">'. __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'woothemes' ) . '</span>' ); ?>
            </nav>
		<?php
			}
		} else {
			woo_pagination();

		} // End IF Statement

	} // End woo_pagenav()
} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_tabs_popular')) {
	function woo_tabs_popular( $posts = 5, $size = 45 ) {
		global $post;
		$popular = get_posts( 'ignore_sticky_posts=1&orderby=comment_count&showposts='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li class="fix">
		<?php if ($size <> 0) woo_image( 'height='.$size.'&width='.$size.'&class=thumbnail&single=true' ); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
	</li>
	<?php endforeach;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Post Meta */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_post_meta' ) ) {
	function woo_post_meta() {
		do_action( 'woo_post_meta' );
		
		$meta = get_post_custom( get_the_ID() );
		
		$date_label  = __( 'Last updated on', 'woothemes' );
		$date_value = get_post_modified_time( get_option( 'date_format' ), '', '', true );
		
		if ( ! is_singular() ) {
			$date_label = __( 'Posted', 'woothemes' );
			$date_value = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'woothemes' );
		}
?>
<aside class="post-meta">
	<ul>
		<?php
			if ( isset( $meta['_source_name'] ) && ( $meta['_source_name'][0] != '' ) ) {

			$source_name = $meta['_source_name'][0];
			$source_link = "#";
			if ( isset( $meta['_source_link'] ) && ( $meta['_source_link'][0] != '' ) ) {
				$source_link = $meta['_source_link'][0];
			}
		?>
		<li class="post-source">
			<span class="small"><?php _e( 'From', 'woothemes' ); ?></span>
			<?php echo ' ' . '<a href="' . $source_link . '" target=_blank>' . $source_name . '</a> &bull; '; ?>
		</li>
		<?php
			}
		?>
		<li class="post-date">
			<span class="small"><?php echo $date_label; ?></span>
			<?php echo $date_value; ?>
		</li>
		<!--
		<li class="post-category">
			<span class="small"><?php _e( 'under', 'woothemes' ); ?></span>
			<?php the_category( ', ' ); ?>
		</li>
		-->
		<?php edit_post_link( __( 'Edit', 'woothemes' ), '<li class="edit">', '</li>' ); ?>
	</ul>
</aside>
<?php
	} // End woo_post_meta()
}


/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_subscribe_connect')) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		global $woo_options;

		// Setup title
		if ( $widget != 'true' )
			$title = $woo_options[ 'woo_connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $woo_options[ 'woo_connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $woo_options[ 'woo_connect' ] == "true" OR $widget == 'true' ) : ?>
	<aside id="connect" class="fix">
		<h3><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else _e('Subscribe','woothemes'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ($woo_options[ 'woo_connect_content' ] != '') echo stripslashes($woo_options[ 'woo_connect_content' ]); else _e( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ); ?></p>

			<?php if ( $woo_options[ 'woo_connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'woothemes' ); ?>';}" />
				<input type="hidden" value="<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit" type="submit" name="submit" value="<?php _e( 'Submit', 'woothemes' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $woo_options['woo_connect_mailchimp_list_url'] != "" AND $form != 'on' AND $woo_options['woo_connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $woo_options[ 'woo_connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $woo_options[ 'woo_connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $woo_options['woo_feed_url'] ) { echo esc_url( $woo_options['woo_feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe" title="RSS"></a>

		   		<?php } if ( $woo_options[ 'woo_connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_twitter'] ); ?>" class="twitter" title="Twitter"></a>

		   		<?php } if ( $woo_options[ 'woo_connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_facebook'] ); ?>" class="facebook" title="Facebook"></a>

		   		<?php } if ( $woo_options[ 'woo_connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_youtube'] ); ?>" class="youtube" title="YouTube"></a>

		   		<?php } if ( $woo_options[ 'woo_connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_flickr'] ); ?>" class="flickr" title="Flickr"></a>

		   		<?php } if ( $woo_options[ 'woo_connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_linkedin'] ); ?>" class="linkedin" title="LinkedIn"></a>

		   		<?php } if ( $woo_options[ 'woo_connect_delicious' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_delicious'] ); ?>" class="delicious" title="Delicious"></a>

		   		<?php } if ( $woo_options[ 'woo_connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_googleplus'] ); ?>" class="googleplus" title="Google+"></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $woo_options[ 'woo_connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Posts:', 'woothemes' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

	</aside>
	<?php endif; ?>
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Fields */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_default_fields', 'woo_comment_form_fields' );

	if ( ! function_exists( 'woo_comment_form_fields' ) ) {
		function woo_comment_form_fields ( $fields ) {

			$commenter = wp_get_current_commenter();

			$required_text = ' <span class="required">(' . __( 'Required', 'woothemes' ) . ')</span>';

			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$fields =  array(
				'author' => '<p class="comment-form-author">' .
							'<input id="author" class="txt" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'<label for="author">' . __( 'Name', 'woothemes' ) . ( $req ? $required_text : '' ) . '</label> ' .
							'</p>',
				'email'  => '<p class="comment-form-email">' .
				            '<input id="email" class="txt" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
				            '<label for="email">' . __( 'Email', 'woothemes' ) . ( $req ? $required_text : '' ) . '</label> ' .
				            '</p>',
				'url'    => '<p class="comment-form-url">' .
				            '<input id="url" class="txt" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />' .
				            '<label for="url">' . __( 'Website', 'woothemes' ) . '</label>' .
				            '</p>',
			);

			return $fields;

		} // End woo_comment_form_fields()
	}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Settings */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_defaults', 'woo_comment_form_settings' );

	if ( ! function_exists( 'woo_comment_form_settings' ) ) {
		function woo_comment_form_settings ( $settings ) {

			$settings['comment_notes_before'] = '';
			$settings['comment_notes_after'] = '';
			$settings['label_submit'] = __( 'Submit Review', 'woothemes' );
			$settings['title_reply'] = __('Treatment review:', 'woothemes');
			$settings['cancel_reply_link'] = __( 'Click here to cancel review.', 'woothemes' );
			$settings['comment_field'] = __('<p class="comment-form-comment"><label for="comment">' . _x( 'Additional Comments:', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>');
			$settings['must_log_in'] = __('<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to to rate this treatment or post a testimonial.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>');

			return $settings;

		} // End woo_comment_form_settings()
	}

	/*-----------------------------------------------------------------------------------*/
	/* Misc back compat */
	/*-----------------------------------------------------------------------------------*/

	// array_fill_keys doesn't exist in PHP < 5.2
	// Can remove this after PHP <  5.2 support is dropped
	if ( !function_exists( 'array_fill_keys' ) ) {
		function array_fill_keys( $keys, $value ) {
			return array_combine( $keys, array_fill( 0, count( $keys ), $value ) );
		}
	}

/*-----------------------------------------------------------------------------------*/
/**
 * woo_archive_description()
 *
 * Display a description, if available, for the archive being viewed (category, tag, other taxonomy).
 *
 * @since V1.0.0
 * @uses do_atomic(), get_queried_object(), term_description()
 * @echo string
 * @filter woo_archive_description
 */

if ( ! function_exists( 'woo_archive_description' ) ) {
	function woo_archive_description ( $echo = true ) {
		do_action( 'woo_archive_description' );
		
		// Archive Description, if one is available.
		$term_obj = get_queried_object();
		$description = term_description( $term_obj->term_id, $term_obj->taxonomy );
		
		if ( $description != '' ) {
			// Allow child themes/plugins to filter here ( 1: text in DIV and paragraph, 2: term object )
			$description = apply_filters( 'woo_archive_description', '<div class="archive-description">' . $description . '</div><!--/.archive-description-->', $term_obj );
		}
		
		if ( $echo != true ) { return $description; }
		
		echo $description;
	} // End woo_archive_description()
}

/*-----------------------------------------------------------------------------------*/
/* Get Theme Options For Use Below */
/*-----------------------------------------------------------------------------------*/

$woo_options = get_option( 'woo_options' );

/*-----------------------------------------------------------------------------------*/
/* Custom Read More Text */
/*-----------------------------------------------------------------------------------*/

add_filter( 'excerpt_more', 'woo_excerpt_more', 10 );

if ( ! function_exists( 'woo_excerpt_more' ) ) {
	function woo_excerpt_more( $more ) {
	       global $post;
		return '<a class="moretag" href="'. get_permalink( $post->ID ) . '">&nbsp;&nbsp;' . __( 'Read on', 'woothemes' ) . '&hellip;</a>' . "\n";
	} // End woo_excerpt_more()
}

/*-----------------------------------------------------------------------------------*/
/* Table of Contents - Shortcode */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_shortcode_table_of_contents' ) ) {
	function woo_shortcode_table_of_contents ( $atts, $content = null ) {
		global $post;
		$defaults = array();
	
		$atts = shortcode_atts( $defaults, $atts );
	
		extract( $atts );
		
		$table_of_contents = woo_get_table_of_contents( $post->post_content );
		
		$html = '';
		if ( isset( $table_of_contents['list'] ) && $table_of_contents['list'] != '<ol></ol>' ) {
			$html = '<div class="table_of_contents fl">' . '<h4>' . __( 'Contents', 'woothemes' ) . '</h4>' . $table_of_contents['list'] . '</div>' . "\n";
		}
		
		return apply_filters( 'woo_shortcode_table_of_contents', $html, $atts );
	} // End woo_shortcode_table_of_contents()
}

add_shortcode( 'table_of_contents', 'woo_shortcode_table_of_contents' );

/*-----------------------------------------------------------------------------------*/
/* Table of Contents - Content Filter */
/*-----------------------------------------------------------------------------------*/

add_filter( 'the_content', 'woo_table_of_contents_section_anchors', 10 );
	
if ( ! function_exists( 'woo_table_of_contents_section_anchors' ) ) {
	function woo_table_of_contents_section_anchors ( $content ) {
		$data = woo_get_table_of_contents( $content );
		
		foreach ( $data['sections_with_ids'] as $k => $v ) {
	  		$content = str_replace( $data['sections'][$k], $v, $content );
	 	}
		
		return $content;
	} // End woo_table_of_contents_section_anchors()
}

/*-----------------------------------------------------------------------------------*/
/* Table of Contents - Automation */
/*-----------------------------------------------------------------------------------*/

if ( isset( $woo_options['woo_auto_tableofcontents'] ) && ( apply_filters( 'woo_auto_tableofcontents', $woo_options['woo_auto_tableofcontents'] ) != 'false' ) ) {
	add_filter( 'the_content', 'woo_table_of_contents_automation', 10 );
}
	
if ( ! function_exists( 'woo_table_of_contents_automation' ) ) {
	function woo_table_of_contents_automation ( $content ) {
		if ( is_singular() && in_the_loop() ) {
			$content = '[table_of_contents] ' . $content;
		}
		
		return $content;
	} // End woo_table_of_contents_automation()
}

/*-----------------------------------------------------------------------------------*/
/* Table of Contents - Generator */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_table_of_contents' ) ) {
	function woo_get_table_of_contents ( $content ) {
	  preg_match_all( "/(<h([0-6]{1})[^<>]*>)([^<>]+)(<\/h[0-6]{1}>)/", $content, $matches, PREG_SET_ORDER );
	  $count = 0; // List Item Count
	  $level = 1; // Heading Level
	  $list = array();
	  $sections = array();
	  $sections_with_ids = array();
	  
	  foreach ( $matches as $val ) {
		$count++;
		
		if ( $val[2] == $level ) { // If the heading level didn’t change.
			$list[$count] = '<li><a href="#section-' . $count . '">'. $val[3] . '</a>';
			
		} else if ( $val[2] > $level ) { // If bigger then last heading level, create a nested list.
			$list[$count] = '<ol><li><a href="#section-' . $count . '">'. $val[3] . '</a>';
			
		} else if ( $val[2] < $level ) { // If less then last Heading Level, end nested list.
			$list[$count] = '</ol></li><li><a href="#section-' . $count . '">'. $val[3] . '</a>';
		}
		
		$sections[$count] = $val[1] . $val[3] . $val[4]; // Original heading to be Replaced.
		$sections_with_ids[$count] = '<h' . $val[2] . ' id="section-' . $count . '">' . $val[3] . $val[4]; // This is the new Heading.
	
		$level = $val[2];
	  }
	  
	  switch ( $level ) { // Final markup fix, used if the list ended on a subheading, such as h3, h4. Etc.
	    case 2:
	     $list[$count] .= '</li>';
	    break;
	    case 3:
	     $list[$count] .= '</ol></li>';
	    break;
	    case 4:
	     $list[$count] .= '</ol></li></ol></li>';
	    break;
	    case 5:
	     $list[$count] .= '</ol></li></ol></li></ol></li>';
	    break;
	    case 6:
	     $list[$count] .= '</ol></li></ol></li></ol></li></ol></li></ol></li>';
	    break;
	  }
	  
	  // Setup container to store rendered HTML.
	  $html = '';
	  
	  foreach ( $list as $k => $v ) { // Puts together the list.
	    $html .= $v;
	  }
	  
	  $html = stripslashes( $html );
	  
	  // Add opening and closing <ol> tags only when necessary.
	  if ( substr( $html, 0, 4 ) != '<ol>' ) {
	  	$html = '<ol>' . $html;
	  }
	  
	  if ( substr( $html, -5 ) != '</ol>' ) {
	  	$html .= '</ol>';
	  }
	  
	  return array( 'list' => $html, 'sections' => $sections, 'sections_with_ids' => $sections_with_ids ); // Returns the content
	} // End woo_get_table_of_contents()
}

/*-----------------------------------------------------------------------------------*/
/* Revisions Display - Instantiate The WooThemes_Content_Revisions Class */
/*-----------------------------------------------------------------------------------*/

locate_template( 'includes/theme-content-revisions.php', true, true );

if ( class_exists( 'WooThemes_Content_Revisions' ) ) {
	$woothemes_content_revisions = new WooThemes_Content_Revisions;
	$woothemes_content_revisions->init();
}

/*-----------------------------------------------------------------------------------*/
/* User Profiles - Load User Profiles Functionality */
/*-----------------------------------------------------------------------------------*/

locate_template( 'includes/theme-user-profiles.php', true, true );

/*-----------------------------------------------------------------------------------*/
/* References - Instantiate the WooThemes_References Class */
/*-----------------------------------------------------------------------------------*/

if ( isset( $woo_options['woo_enable_references'] ) && ( apply_filters( 'woo_enable_references', $woo_options['woo_enable_references'] ) != 'false' ) ) {
	locate_template( 'includes/woo-references/woo-references.php', true, true );
	
	if ( class_exists( 'WooThemes_References' ) ) {
		$woothemes_references = new WooThemes_References;
		$woothemes_references->init();
	}
}

/*-----------------------------------------------------------------------------------*/
/* Modify woo_breadcrumbs() Arguments Specific to this Theme */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_breadcrumbs_args', 'woo_filter_breadcrumbs_args', 10 );

if ( ! function_exists( 'woo_filter_breadcrumbs_args' ) ) {
	function woo_filter_breadcrumbs_args( $args ) {
	
		$args['separator'] = '&raquo;';
	
		return $args;
	
	} // End woo_filter_breadcrumbs_args()
}

/*-----------------------------------------------------------------------------------*/
/* Search Auto-Complete - Search Term Suggestions Query */
/*-----------------------------------------------------------------------------------*/

add_action( 'template_redirect', 'woo_search_terms_suggest', 0 );

/**
 * woo_search_terms_suggest function.
 * 
 * @access public
 * @return void
 */
if ( ! function_exists( 'woo_search_terms_suggest' ) ) {
	function woo_search_terms_suggest () {
		if ( isset( $_GET['search-suggest'] ) && ( $_GET['search-suggest'] == 'suggest' ) ) {
			$q = strtolower( $_REQUEST['q'] );
			
			if ( ! $q ) { exit; }
			
			$args = array(
							's' => esc_attr( strip_tags( $q ) ), 
							'posts_per_page' => -1, 
							'post_type' => array ( 'conditions', 'treatments','symptoms')
						);
			
			$entries = get_posts( $args );
			
			if ( is_array( $entries ) &&  ( count( $entries ) > 0 ) ) {
				foreach ( $entries as $k => $v ) {
					echo $v->post_title . "\n";
				}
			} else {
				echo __( 'No Results Found', 'woothemes' );
			}
			
			exit;
		}
	} // End woo_search_terms_suggest()
}

/*-----------------------------------------------------------------------------------*/
/* Filter Bar - Modify Post Count If Applicable */
/*-----------------------------------------------------------------------------------*/

if ( ! is_admin() ) { add_filter( 'pre_get_posts', 'woo_filter_bar_post_count', 10 ); }

/**
 * woo_filter_bar_post_count function.
 * 
 * @access public
 * @param object $query
 * @return object $query
 */
function woo_filter_bar_post_count ( $query) {
	if ( isset( $_GET['postcount'] ) && $_GET['postcount'] != '' ) {
		$query->set( 'posts_per_page', intval( $_GET['postcount'] ) );
		
		$query->parse_query();
	}
	return $query;
} // End woo_filter_bar_post_count()

/*-----------------------------------------------------------------------------------*/
/* Filter Bar - Modify Order Direction For "Alphabetical" */
/*-----------------------------------------------------------------------------------*/

if ( ! is_admin() ) { add_filter( 'pre_get_posts', 'woo_filter_bar_alphabetical_direction', 10 ); }

/**
 * woo_filter_bar_alphabetical_direction function.
 * 
 * @access public
 * @param object $query
 * @return object $query
 */
function woo_filter_bar_alphabetical_direction ( $query) {
	if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'title' ) {
		$query->set( 'order', 'ASC' );
		
		$query->parse_query();
	}
	return $query;
} // End woo_filter_bar_alphabetical_direction()

/*-----------------------------------------------------------------------------------*/
/* Filter Bar - Get Query Totals */
/*-----------------------------------------------------------------------------------*/

/**
 * woo_get_query_totals function.
 * 
 * @access public
 * @return string $html
 */
if ( ! function_exists( 'woo_get_query_totals' ) ) {
	function woo_get_query_totals () {
		global $wp_query;
		
		// Retrieve the values we need to work out which items we're displaying.
		$per_page = $wp_query->query_vars['posts_per_page'];
		$paged = $wp_query->query_vars['paged'];
		$past_pages = 0;
		if ( $paged > 0 ) { $past_pages = $paged - 1; }
		$post_count = $wp_query->post_count;
		$total = $wp_query->found_posts;
		
		if ( $total == 0 ) { return; }
		
		// Setup the start and end values.
		$processed_entries = $per_page * $past_pages;
		$start = $processed_entries + 1;
		$end = $processed_entries + $post_count;
		
		// Setup our information for display as HTML.
		$html = '';
			$html .= __( 'Viewing', 'woothemes' ) . ' ';
		
		if ( $total == 1 ) {
		
			$html .= '<strong>' . $total . '</strong> ' . __( 'item', 'woothemes' );
		
		} else {
		
			$html .= sprintf( __( '%1$s to %2$s of %3$s items', 'woothemes' ), '<strong>' . $start . '</strong>', '<strong>' . $end . '</strong>', '<strong>' . $total . '</strong>' );
		
		}
		
		// Return the finished HTML.
		return $html;
	} // End woo_get_query_totals()
}

/*-----------------------------------------------------------------------------------*/
/* Search - Automatically Redirect To Result If Only One */
/*-----------------------------------------------------------------------------------*/

add_action( 'template_redirect', 'woo_search_results_redirect', 10 );
	
/**
 * woo_search_results_redirect function.
 * 
 * @access public
 * @return void
 */
if ( ! function_exists( 'woo_search_results_redirect' ) ) {
	function woo_search_results_redirect () {
		if ( is_search() ) {
			global $wp_query;
			
			if ( TRUE || $wp_query->post_count == 1 ) {
				wp_redirect( get_permalink( $wp_query->post->ID ) );
				exit;
			}
		}
	} // End woo_search_results_redirect()
}

/*-----------------------------------------------------------------------------------*/
/* Search - AJAX Search Query */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_ajax_ajax_search', 'woo_ajax_search_response' );
add_action( 'wp_ajax_nopriv_ajax_search', 'woo_ajax_search_response' ); 

/**
 * woo_ajax_search_response function.
 * 
 * @access public
 * @return void
 */
function woo_ajax_search_response () {
  
  $nonce = $_POST['ajax_search_nonce'];
  
  //Add nonce security to the request
  if ( ! wp_verify_nonce( $nonce, 'ajax_search_nonce' ) ) {
        die();
    }
    
  //Just get the template part, a check is made there for the query
  get_template_part( 'search', 'results' ); 
  
  die(); // WordPress may print out a spurious zero without this can be particularly bad if using JSON
  
}// End woo_ajax_search_response()

/*-----------------------------------------------------------------------------------*/
/* Search - Add CSS Classes To The <body> Tag For Search-related Toggles */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class', 'woo_ajax_search_bodyclass', 10 );

/**
 * woo_ajax_search_bodyclass function.
 * 
 * @access public
 * @param array $classes
 * @return array $classes
 */
if ( ! function_exists( 'woo_ajax_search_bodyclass' ) ) {
  function woo_ajax_search_bodyclass ( $classes ) {
    global $woo_options;
    
    // AJAX Search Toggle
    $css_class = 'ajax-search-disabled';
    if ( isset( $woo_options['woo_enable_ajaxsearch'] ) && ( $woo_options['woo_enable_ajaxsearch'] == 'true' ) ) {
      $css_class = 'ajax-search-enabled';
    }
    
    $classes[] = $css_class;
    
    // Search Suggest Toggle
    $css_class = 'search-suggest-disabled';
    if ( isset( $woo_options['woo_enable_searchsuggest'] ) && ( $woo_options['woo_enable_searchsuggest'] == 'true' ) ) {
      $css_class = 'search-suggest-enabled';
    }
    
    $classes[] = $css_class;
    
    // Search Box Toggle
    $css_class = 'advanced-search-box-disabled';
    if ( isset( $woo_options['woo_enable_searchbox'] ) && ( $woo_options['woo_enable_searchbox'] == 'true' ) ) {
      $css_class = 'advanced-search-box-enabled';
    }
    
    // Filter Bar Toggle
	if ( isset( $woo_options['woo_enable_filterbar'] ) && ( $woo_options['woo_enable_filterbar'] != 'false' ) ) {
		$classes[] = 'filter-bar-enabled';
	} else {
		$classes[] = 'filter-bar-disabled';
	}
    
    $classes[] = $css_class;
    
    return $classes;
  } // End woo_ajax_search_bodyclass()
}

/*-----------------------------------------------------------------------------------*/
/* Load custom "Theme Options" CSS on the "Theme Options" screen */
/*-----------------------------------------------------------------------------------*/

add_action( 'admin_print_styles', 'woo_load_custom_theme_options_css', 10 );

/**
 * woo_load_custom_theme_options_css function.
 * 
 * @access public
 * @return void
 */
if ( ! function_exists( 'woo_load_custom_theme_options_css' ) ) {
	function woo_load_custom_theme_options_css () {
		global $pagenow;
		
		wp_register_style( 'woo-custom-theme-options', get_template_directory_uri() . '/includes/css/woo-custom-theme-options.css' );
		
		if ( ( $pagenow == 'admin.php' ) && ( isset( $_GET['page'] ) ) && ( $_GET['page'] == 'woothemes' ) ) {
			wp_enqueue_style( 'woo-custom-theme-options' );
		}
	} // End woo_load_custom_theme_options_css()
}

/*-----------------------------------------------------------------------------------*/
/* Add "Comment Status" CSS classes to each post_class */
/*-----------------------------------------------------------------------------------*/

add_filter( 'post_class', 'woo_add_comment_status_post_class', 10 );

function woo_add_comment_status_post_class ( $classes ) {
	$css_class = 'closed';
	if ( comments_open() ) {
		$css_class = 'open';
	}
	
	$classes[] = 'comment-status-' . $css_class;
	return $classes;
} // End woo_add_comment_status_post_class()

/*-----------------------------------------------------------------------------------*/
/* Change Woo Pagination Prev/Next Elements */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_pagination_args', 'woo_filter_pagination_args', 10 );

if ( ! function_exists( 'woo_filter_pagination_args' ) ) {
	function woo_filter_pagination_args( $args ) {
	
		$args['prev_text'] = '#';
		$args['next_text'] = '#';
		
		return $args;
	
	} // End woo_filter_breadcrumbs_args()
}

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>