<?php
/**
 * Template Name: Blog
 *
 * The blog page template displays the "blog-style" template on a sub-page. 
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
					'thumb_w' => 75, 
					'thumb_h' => 75, 
					'thumb_align' => 'alignleft'
					);
					
	$settings = woo_get_dynamic_values( $settings );
?>
    <!-- #content Starts -->
    <div id="content" class="col-full">
    
        <!-- #main Starts -->
        <section id="main" class="col-right">      
        <?php
        	// Load the filter bar.
			get_template_part( 'includes/filter-bar' );
        ?>      
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  

        <?php
        	if ( get_query_var( 'paged') ) { $paged = get_query_var( 'paged' ); } elseif ( get_query_var( 'page') ) { $paged = get_query_var( 'page' ); } else { $paged = 1; }
        	
        	$query_args = array(
        						'post_type' => 'post', 
        						'paged' => $paged
        					);
        	
        	$query_args = apply_filters( 'woo_blog_template_query_args', $query_args ); // Do not remove. Used to exclude categories from displaying here.
			remove_filter( 'pre_get_posts', 'woo_exclude_categories_homepage' );        	
        	query_posts( $query_args );
        	
        	if ( have_posts() ) {
        		$count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        ?>                                                            
        	<!-- Post Starts -->                                                          
            <article <?php post_class(); ?>>

                <?php
					$image = woo_image( 'return=true&width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&link=img&class=thumbnail' );
					
					if ( $image != '' ) {
				?>

            <?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] != 'content' ) { ?>
            <div class="drop-shadow curved curved-hz-1 <?php echo $settings['thumb_align']; ?>">
				<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" >
					<?php echo $image; ?>
            	</a>
            </div><!--/.drop-shadow-->
                            
            <?php }} ?>

                <div class="container">
                
                <header>
                
                	<h1 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                	
                	<?php woo_post_meta(); ?>
                	
                </header>
                
                <section class="entry">
                    <?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'content' ) { the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); } else { the_excerpt(); } ?>
                </section>
                
                <div class="comments">
                <?php comments_popup_link( __( '0', 'woothemes' ), __( '1', 'woothemes' ), __( '%', 'woothemes' ) ); ?>
                </div>
                
                </div><!-- /.container -->   
                
              <div class="fix"></div>
                                     
            </article><!-- /.post -->
                                                
        <?php
        		} // End WHILE Loop
        	
        	} else {
        ?>
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        <?php } // End IF Statement ?>  
    
            <?php if ( isset( $woo_options['woo_pagenav_show'] ) && $woo_options['woo_pagenav_show'] == 'true' ) {  woo_pagenav();  } ?>
			<?php wp_reset_query(); ?>                

        </section><!-- /#main -->
            
		<?php get_sidebar(); ?>

    </div><!-- /#content -->    
		
<?php get_footer(); ?>