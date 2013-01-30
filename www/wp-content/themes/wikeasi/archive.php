<?php get_header(); 
	$settings = array(
					'thumb_w' => 75, 
					'thumb_h' => 75, 
					'thumb_align' => 'alignleft', 
					'enable_filterbar' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );
?>
    
    <div id="content" class="col-full">
		<section id="main" class="col-right">
        <?php
        	// Load the filter bar.
			if ( $settings['enable_filterbar'] == 'true' ) {
				get_template_part( 'includes/filter-bar' );
			}
        ?> 
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  

		<?php if (have_posts()) : $count = 0; ?>
        
            <?php if (is_category()) { ?>
        	<header class="archive_header">
        		<span class="fl cat"><?php _e( 'Archive', 'woothemes' ); ?> | <?php echo single_cat_title(); ?></span> 
        		<span class="fr catrss"><?php $cat_id = get_cat_ID( single_cat_title( '', false ) ); echo '<a href="' . get_category_feed_link( $cat_id, '' ) . '">' . __( "RSS feed for this section", "woothemes" ) . '</a>'; ?></span>
        	</header>        
        
            <?php } elseif (is_day()) { ?>
            <header class="archive_header fix"><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( get_option( 'date_format' ) ); ?></header>

            <?php } elseif (is_month()) { ?>
            <header class="archive_header fix"><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( 'F, Y' ); ?></header>

            <?php } elseif (is_year()) { ?>
            <header class="archive_header fix"><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( 'Y' ); ?></header>

            <?php } elseif (is_author()) { ?>
            <header class="archive_header fix"><?php _e( 'Archive by Author', 'woothemes' ); ?></header>

            <?php } elseif (is_tag()) { ?>
            <header class="archive_header fix"><?php _e( 'Tag Archives:', 'woothemes' ); ?> <?php echo single_tag_title( '', true ); ?></header>
            
            <?php } ?>

        <?php
        	// Display the description for this archive, if it's available.
        	woo_archive_description();
        ?>
        
        <div class="fix"></div>
        
        <?php
        	while (have_posts()) : the_post(); $count++;
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
                
                <div class="comments"><?php comments_popup_link( __( '0', 'woothemes' ), __( '1', 'woothemes' ), __( '%', 'woothemes' ) ); ?>
                </div>
                
                </div><!-- /.container -->   
                
              <div class="fix"></div>
                                     
            </article><!-- /.post -->
            
        <?php endwhile; else: ?>
        
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        
        <?php endif; ?>  
    
			<?php if ( isset( $woo_options['woo_pagenav_show'] ) && $woo_options['woo_pagenav_show'] == 'true' ) {  woo_pagenav();  } ?>
                
		</section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>