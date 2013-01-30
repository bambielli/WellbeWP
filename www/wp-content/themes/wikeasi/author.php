<?php get_header(); 
	$settings = array(
					'thumb_w' => 100, 
					'thumb_h' => 100, 
					'thumb_align' => 'alignleft', 
					'enable_author_biobox' => 'true', 
					'enable_author_contributionsbox' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );
	
	/* Setup Author Data. */
	$author = get_query_var( 'author' );
	$user_data = woo_get_user_data( $author );
?>
    
    <div id="content" class="col-full">
		<section id="main" class="col-right">
            
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  
		<header class="archive_header">
         <?php
         	// Display author data.
         	echo $user_data['avatar'];
         ?>
         	<div class="container">
         <?php
         	echo '<h1>' . $user_data['display_name'] . '</h1>' . "\n";
         	if ( isset( $user_data['byline'] ) && ( $user_data['byline'] != '' ) ) { echo '<span class="byline">' . $user_data['byline'] . '</span>' . "\n"; }
         ?>
         	</div>
         <?php
         	// Display social links.
         	echo woo_get_author_social_links( $author, $user_data );
         ?>
        </header>
        
        <div class="fix"></div>
        
        <?php
        // Display the author information box.
        if ( $settings['enable_author_biobox'] == 'true' ) {
        	echo woo_get_author_info_box( $author );
        }
        
        // Display the description for this archive, if it's available.
        if ( isset( $user_data['user_description'] ) && ( $user_data['user_description'] != '' ) ) {
         echo '<div class="profile-description">' . $user_data['user_description'] . '</div>' . "\n"; 
        }
        
        if ( $settings['enable_author_contributionsbox'] == 'true' ) {
        ?>
        <div id="contributions" class="contributions">
        	<h3><?php _e( 'Contributions', 'woothemes' ); ?></h3>
        	<ul>
		<?php
			if ( have_posts() ) : $count = 0;
				$contributions = '';
				
				while ( have_posts() ) : the_post(); $count++;
					$title = '<a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a>';
 					$contributions .= '<li class="' . join( ' ', get_post_class( '', get_the_ID() ) ) . '">' . woo_get_contribution_label( get_post_type(), get_post_status(), $title ) . '</li>' . "\n";
        		endwhile;
        		
        		echo $contributions;
        		
        	else:
     			echo '<li class="noposts">' . __( 'Sorry, no posts matched your criteria.', 'woothemes' ) . '</li>' . "\n";
     		endif;
     	?>
       		</ul>
       		<div class="fix"></div>
       		<?php if ( isset( $woo_options['woo_pagenav_show'] ) && $woo_options['woo_pagenav_show'] == 'true' ) {  woo_pagenav();  } ?>
    	</div><!--/#contributions-->
        <?php } // End "enable_author_contributionsbox" Check ?>      
		</section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>