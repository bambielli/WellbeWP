<?php
/**
 * Single Post Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a post ('post' post_type).
 * @link http://codex.wordpress.org/Post_Types#Post
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
					'thumb_single' => 'false', 
					'single_w' => 200, 
					'single_h' => 200, 
					'thumb_single_align' => 'alignright'
					);
					
	$settings = woo_get_dynamic_values( $settings );
	
	$embed_width = 760;
	if ( woo_active_sidebar( 'secondary' ) ) { $embed_width = '500'; }
?>
       
    <div id="content" class="col-full">
		<section id="main" class="col-right">
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>
        <?php
        	if ( have_posts() ) { $count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        ?>
			<article <?php post_class(); ?>>

				<header>
                
	                <h1><?php the_title(); ?></h1>
	                
                	<?php woo_post_meta(); ?>
                	
                </header>
                
                <div class="fix"></div>

				<?php echo woo_embed( 'width=' . $embed_width ); ?>
                <?php
                	if ( $settings['thumb_single'] == 'true' ) {
						$image = woo_image( 'return=true&width=' . $settings['single_w'] . '&height=' . $settings['single_h'] . '&link=img&class=thumbnail' );
						
						if ( $image != '' ) {
				?>

            <?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] != 'content' ) { ?>
            <div class="drop-shadow curved curved-hz-1 <?php echo $settings['thumb_single_align']; ?>">
				<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" style="height: <?php echo $settings['single_h']; ?>px;">
					<?php echo $image; ?>
            	</a>
            </div><!--/.drop-shadow-->
                            
            <?php
            			}
            		}
            	}
            ?>
                
                <section class="entry">
                	<?php the_content(); 

			$meta = get_post_custom( get_the_ID() );
			$source_name = "";
			if ( isset( $meta['_source_name'] ) && ( $meta['_source_name'][0] != '' ) ) {
				$source_name = $meta['_source_name'][0];

				$source_link = "#";
				if ( isset( $meta['_source_link'] ) && ( $meta['_source_link'][0] != '' ) ) {
					$source_link = $meta['_source_link'][0];
				}
				echo "Read more at <a href='" . $source_link . "' target=_blank>" . $source_name . ".</a>";
			}
			?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
				</section>
									
				<?php the_tags( '<p class="tags">'.__( 'Tags: ', 'woothemes' ), ', ', '</p>' ); ?>
                                
            </article><!-- .post -->

				<?php if ( isset( $woo_options['woo_post_author'] ) && $woo_options['woo_post_author'] == 'true' ) { ?>
				<aside id="post-author" class="fix">
					<div class="profile-image"><?php echo get_avatar( get_the_author_meta( 'ID' ), '70' ); ?></div>
					<div class="profile-content">
						<h3 class="title"><?php printf( esc_attr__( 'About %s', 'woothemes' ), get_the_author() ); ?></h3>
						<?php the_author_meta( 'description' ); ?>
						<div class="profile-link">
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
								<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'woothemes' ), get_the_author() ); ?>
							</a>
						</div><!-- #profile-link	-->
					</div><!-- .post-entries -->
				</aside><!-- #post-author -->
				<?php } ?>

				<?php woo_subscribe_connect(); ?>

<?php


//print_r($wp_query->query_vars);
//print_r(get_queried_object());
//echo (get_queried_object()->ID);

//Let's do the drilldown process
$connection_type = '';
$connection_title = '';
//main array
$connection_ids = array();

//fill these from the session vars as well
$condition_ids = array();
$symptom_ids = array();
$treatment_ids = array();

//grab data from the session
if (isset($_SESSION['condition_ids']))
	$condition_ids = $_SESSION['condition_ids'];

if (isset($_SESSION['symptom_ids']))
	$symptom_ids = $_SESSION['symptom_ids'];

if (isset($_SESSION['treatment_ids']))
	$treatment_ids = $_SESSION['treatment_ids'];


//get the post data and add it to the arrays
if (isset($_GET['add'])) {
	if (isset($_GET['condition']) && !in_array($_GET['condition'], $condition_ids))
		$condition_ids[] = $_GET['condition'];

	if (isset($_GET['symptom']) && !in_array($_GET['symptom'], $symptom_ids))
		$symptom_ids[] = $_GET['symptom'];

	if (isset($_GET['treatment']) && !in_array($_GET['treatment'], $treatment_ids))
		$treatment_ids[] = $_GET['treatment'];
}

if (isset($_GET['del'])) {
	if (isset($_GET['condition']))
		$condition_ids = array_diff($condition_ids, array($_GET['condition']));

	if (isset($_GET['symptom']))
		$symptom_ids= array_diff($symptom_ids, array($_GET['symptom']));

	if (isset($_GET['treatment']))
		$treatment_ids = array_diff($treatment_ids, array($_GET['treatment']));

	if (isset($_GET['all'])) {
		$condition_ids = array();
		$symptom_ids = array();
		$treatment_ids = array();
	}
}

//save the arrays to session
$_SESSION['condition_ids'] = $condition_ids;
$_SESSION['symptom_ids'] = $symptom_ids;
$_SESSION['treatment_ids'] = $treatment_ids;

if ( get_post_type() == 'post' ) {
	$connection_type = '';
	$connection_title = '';	
} elseif ( get_post_type() == 'conditions' ) {
	$connection_type = 'conditions_to_treatments';
	$connection_title = 'Related Treatments:';

	$condition_ids[] = get_queried_object()->ID;
	$connection_ids = $condition_ids;
} elseif ( get_post_type() == 'symptoms' ) {
	$connection_type = 'symptoms_to_conditions';
	$connection_title = 'Related Conditions:';	

	$symptom_ids[] = get_queried_object()->ID;
	$connection_ids = $symptom_ids;
} elseif ( get_post_type() == 'treatments' ) {
	$connection_type = 'posts_to_treatments';
	$connection_title = 'Related Articles:';	

	$treatment_ids[] = get_queried_object()->ID;
	$connection_ids = $treatment_ids;
} else {
	//defaults
	$connection_type = '';
	$connection_title = '';	
}

/*
//test data
echo ('Conditions: ');
print_r($condition_ids);
echo ('Symptoms: ');
print_r($symptom_ids);
echo ('Treatments: ');
print_r($treatment_ids);
*/

if ($connection_type != '') {
	//Main Connection Check
	// Find connected pages
	$connected = new WP_Query( array(
	  'connected_type' => $connection_type,
	  'connected_items' => $connection_ids,
	  'nopaging' => true,
	) );

	//echo ("Test1: ");
	//print_r($connected);

	//do the nested checks now, based on the type of page we're on
	if (get_post_type() == 'treatments') {
		if (count($condition_ids) > 0) {

			$current_ids = array();
			$current_ids[] = 0;
			while ( $connected->have_posts() ) :
				$connected->the_post();
				$current_ids[] = get_the_ID();
			endwhile;
	
			// Prevent weirdness
			wp_reset_query();
			wp_reset_postdata();
	
			//nested WP_Query search to do a deep filter
			$connected = new WP_Query( array(
			  'connected_type' => 'posts_to_conditions',
			  'connected_items' => $condition_ids,
			  'nopaging' => true,
			  'post__in' => $current_ids,
			  'suppress_filters' => false
			) );

			//echo ("Test2: ");
			//print_r($connected);
	
		}

		if (count($symptom_ids) > 0) {

			$current_ids = array();
			$current_ids[] = 0;
			while ( $connected->have_posts() ) :
				$connected->the_post();
				$current_ids[] = get_the_ID();
			endwhile;
	
			// Prevent weirdness
			wp_reset_query();
			wp_reset_postdata();
	
			//nested WP_Query search to do a deep filter
			$connected = new WP_Query( array(
			  'connected_type' => 'posts_to_symptoms',
			  'connected_items' => $symptom_ids,
			  'nopaging' => true,
			  'post__in' => $current_ids,
			  'suppress_filters' => false
			) );

			//echo ("Test3: ");
			//print_r($connected);
	
		}

	}
	
	// Display connected pages
	if ( $connected->have_posts() ) :
	?>
	<section id="footer-widgets" class="col-full col-4 fix">
		<div class="block footer-widget-<?php echo '2'; ?>">
	
		<div id="text-3" class="widget widget_text">
		<div class="textwidget"><strong><?php echo $connection_title ?></strong></div>
		</div>

	<div class="widget widget_p2p"><ul id="related_list">
	<ul>
	<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
		<?php 
		$meta = get_post_custom( get_the_ID() );
		$source_name = "";
		if ( isset( $meta['_source_name'] ) && ( $meta['_source_name'][0] != '' ) )
			$source_name = "(" . $meta['_source_name'][0] . ")";
		?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><div class="fr"><?php echo $source_name ?></div></li>
	<?php endwhile; ?>
	</ul>
	</div>

		</div>
	</section>
	
	<?php 
	// Prevent weirdness
	wp_reset_query();
	wp_reset_postdata();
	
	endif;
}

//Look for connected Testimonials now
	$connection_title = 'Related Testimonials:';

if ( get_post_type() == 'post' ) {
	$connection_type = '';
} elseif ( get_post_type() == 'conditions' ) {
	$connection_type = 'testimonials_to_conditions';
} elseif ( get_post_type() == 'symptoms' ) {
	$connection_type = 'testimonials_to_symptoms';
} elseif ( get_post_type() == 'treatments' ) {
	$connection_type = 'testimonials_to_treatments';
} else {
	//defaults
	$connection_type = '';
	$connection_title = '';	
}

if ($connection_type != '') {
	//Main Connection Check
	// Find connected testimonials
	$connected = new WP_Query( array(
	  'connected_type' => $connection_type,
	  'connected_items' => $connection_ids,
	  'nopaging' => true,
	) );
	
	// Display connected pages
	if ( $connected->have_posts() ) :
	?>
	<section id="footer-widgets" class="col-full col-4 fix">
		<div class="block footer-widget-<?php echo '2'; ?>">
	
		<div id="text-3" class="widget widget_text">
		<div class="textwidget"><strong><?php echo $connection_title ?></strong></div>
		</div>

	<div class="widget widget_p2p"><ul id="related_list">
	<ul>
	<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><div class="fr">(<?php the_author_meta('display_name'); ?>)</div></li>
	<?php endwhile; ?>
	</ul>
	</div>

		</div>
	</section>
	
	<?php 
	// Prevent weirdness
	wp_reset_query();
	wp_reset_postdata();
	
	endif;
}
?>

	        <nav id="post-entries" class="fix">
	            <div class="nav-prev fl"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> %title' ); ?></div>
	            <div class="nav-next fr"><?php next_post_link( '%link', '%title <span class="meta-nav">&rarr;</span>' ); ?></div>
	        </nav><!-- #post-entries -->
            <?php
            	// Determine wether or not to display comments here, based on "Theme Options".
            	if ( isset( $woo_options['woo_comments'] ) && in_array( $woo_options['woo_comments'], array( 'post', 'both' ) ) ) {
            		
			if ( get_post_type() == 'post' ) {
				comments_template();
			}
            	}

				} // End WHILE Loop
			} else {
		?>
			<article <?php post_class(); ?>>
            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
			</article><!-- .post -->             
       	<?php } ?>  
        
		</section><!-- #main -->

        <?php get_sidebar(); ?>

    </div><!-- #content -->
		
<?php get_footer(); ?>