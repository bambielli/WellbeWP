<?php
/**
 * Search Template
 *
 * The search template is used to display search results from the native WordPress search.
 *
 * If no search results are found, the user is assisted in refining their search query in
 * an attempt to produce an appropriate search results set for the user's search query.
 *
 * @package WooFramework
 * @subpackage Template
 */

 get_header();
 global $woo_options;
 
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
$settings = array(
				'enable_searchbox' => 'true', 
				'enable_filterbar' => 'true'
				);
				
$settings = woo_get_dynamic_values( $settings );
?>     
    <div id="content" class="col-full">
		<section id="main" class="col-right">
        <?php
        	// Load the front page search form.
			if ( $settings['enable_searchbox'] == 'true' ) {
				get_template_part( 'includes/advanced-search-form' );
			}
			
			// Load the filter bar.
			if ( $settings['enable_filterbar'] == 'true' ) {
				get_template_part( 'includes/filter-bar' );
			}
        ?>  
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  	
		<?php
			// Load the search results template part (also used on the AJAX search)
			get_template_part( 'search', 'results' );
		?>      
        </section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>