<?php
	global $woo_options;
	/**
	 * The Variables
	 *
	 * Setup default variables, overriding them if the "Theme Options" have been saved.
	 */
	
	$settings = array(
					'thumb_w' => 75, 
					'thumb_h' => 75, 
					'thumb_align' => 'alignleft', 
					'enable_searchbox' => 'true', 
					'enable_filterbar' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );
	
	$ajax_search = false;
	
	// If we're doing an AJAX call, run the query.
	if ( isset( $_POST['search_term'] ) && wp_verify_nonce( $_POST['ajax_search_nonce'], 'ajax_search_nonce' ) ) {
		query_posts( array( 's' => esc_attr( $_POST['search_term'] ), 'post_status' => 'publish', 'post_type' => array ( 'conditions', 'treatments','symptoms') ) );
		$ajax_search = true;
	}
	
	if ( $ajax_search == true ) {
		$settings = array(
					'thumb_w' => 75, 
					'thumb_h' => 75, 
					'thumb_align' => 'alignleft', 
					'enable_searchbox' => 'true', 
					'enable_filterbar' => 'true'
					);
					
		$settings = woo_get_dynamic_values( $settings );
	
		get_template_part( 'includes/filter-bar' );
	}

// #entries DIV is used to find/replace the results when doing an AJAX search.
?>
<div id="entries">
<?php if ( have_posts() ) { $count = 0; ?>

<header class="archive_header"><?php echo __( 'Search results:', 'woothemes' ) . ' '; the_search_query(); ?></header>
    
<?php while ( have_posts() ) { the_post(); $count++; ?>

<!-- Post Starts -->                                                          
<article <?php post_class(); ?>>

    <?php
		$image = woo_image( 'return=true&width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&link=img&class=thumbnail' );
		
		if ( $image != '' ) {
	?>

<div class="drop-shadow curved curved-hz-1 <?php echo $settings['thumb_align']; ?>">
	<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" >
		<?php echo $image; ?>
	</a>
</div><!--/.drop-shadow-->
<?php } ?>
    <div class="container">
    
    <header>
    
    	<h1 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
    	
    	<?php woo_post_meta(); ?>
    	
    </header>
    
    <section class="entry">
        <?php the_excerpt(); ?>
    </section>
    
    <?php if ( get_post_type() == 'post' ) { ?>
    <div class="comments">
    <?php comments_popup_link( __( '0', 'woothemes' ), __( '1', 'woothemes' ), __( '%', 'woothemes' ) ); ?>
    </div>
    <?php } ?>
    
    </div><!-- /.container -->   
    
  <div class="fix"></div>
                         
</article><!-- /.post -->
                                        
<?php
		} // End WHILE Loop
		
		// Add Pagination
		woo_pagenav();

	} else {
?>

<article <?php post_class(); ?>>
    <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
</article><!-- /.post -->

<?php } // End IF Statement ?>
</div><!--/#entries-->