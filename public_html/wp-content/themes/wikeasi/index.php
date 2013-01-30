<?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
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
					'thumb_align' => 'alignleft', 
					'enable_searchbox' => 'true', 
					'enable_filterbar' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );
?>

    <div id="content" class="col-full">
		<section id="main" class="fullwidth">      
        <?php
			// Load the front page search form.
			if ( $settings['enable_searchbox'] == 'true' ) {
				get_template_part( 'includes/advanced-search-form' );
			}

			// Load the filter bar.
			if ( FALSE && $settings['enable_filterbar'] == 'true' ) {
				get_template_part( 'includes/filter-bar' );
			}
		?>            

		<section id="second-main-row">
		<div class="alignleft" style="min-height:480px">
		<?php 
		the_widget('Woo_Widget_WooTabs', 'tags=off&comments=off');
		 ?>
		</div>
		<div class="alignright" style="min-height:480px">
		<?php 
		the_widget('Woo_Subscribe', 'title=Our%20Story');

		echo "<strong>Sign up for the Wellbe newsletter!</strong><br>";
		mailchimpSF_signup_form();
		 ?>
		</div>
		</section>
		
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php //woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>
		<?php
			// To customise the query used on this template, please uncomment the code below.
			/*
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; query_posts( array( 'post_type' => 'post', 'paged' => $paged ) );
			*/
		?>
		<?php /* #entries DIV used in the AJAX search. */ ?>
		<div id="entries">
        <?php
        	if ( FALSE && have_posts() ) { $count = 0;
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
                	
                	<?php /*woo_post_meta();*/ ?>
                	
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
			//do nothing
		}
	/*
        ?>
        
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        
        <?php }*/ ?>
        <?php if ( isset( $woo_options['woo_pagenav_show'] ) && $woo_options['woo_pagenav_show'] == 'true' ) {  woo_pagenav();  } ?>
        	</div><!--/#entries-->
		</section><!-- /#main -->

        <?php /*get_sidebar();*/ ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>

<!-- Woo Tabs Widget -->
<script type="text/javascript">jQuery(document).ready(function(){var a="#tagcloud";var b=jQuery("#tagcloud").height();jQuery(".inside ul li:last-child").css("border-bottom","0px");jQuery(".wooTabs").each(function(){jQuery(this).children("li").children("a:first").addClass("selected")});jQuery(".inside > *").hide();jQuery(".inside > *:first-child").show();jQuery(".wooTabs li a").click(function(a){var b=jQuery(this).attr("href");jQuery(this).parent().parent().children("li").children("a").removeClass("selected");jQuery(this).addClass("selected");jQuery(this).parent().parent().parent().children(".inside").children("*").hide();jQuery(".inside "+b).fadeIn(500);a.preventDefault()})})</script>