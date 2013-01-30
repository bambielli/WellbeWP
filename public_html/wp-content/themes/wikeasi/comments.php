<?php
/**
 * Comments Template
 *
 * This template file handles the display of comments, pingbacks and trackbacks.
 *
 * External functions are used to display the various types of comments.
 *
 * @package WooFramework
 * @subpackage Template
 */

// Do not delete these lines
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'Please do not load this page directly. Thanks!' );
}

if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'woothemes' ); ?></p>
<?php return; } ?>

<?php $comments_by_type = &separate_comments( $comments ); ?>    

<!-- You can start editing here. -->

<?php if ( have_comments() ) { ?>

<div id="comments">

	<?php if ( ! empty( $comments_by_type['comment'] ) ) { ?>
		<h3><?php comments_number( __( 'Treatment Review:', 'woothemes' ), __( 'Treatment Review:', 'woothemes' ), __( 'Treatment Reviews: (%)', 'woothemes' ) ); ?><!-- <?php _e( 'for', 'woothemes' ); ?> &#8220;<?php the_title(); ?>&#8221;--></h3>

		<ol class="commentlist">
	
			<?php 
			
			$comment_count = get_comment_count($post->ID);
			$total_reviews = $comment_count['approved'];
			//echo $comment_count['approved'] . " reviews.";
			$total_rating = 0.00;

			 foreach( $comments as $comment ) :
				if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
					$total_rating = $total_rating + $commentrating;
				}
			endforeach;
			$final_rating = ceil(($total_rating/$total_reviews));
			echo '<b>Average score: ' . $final_rating . '%</b>';
			?>
			<br><br>
			<script type="text/javascript"> 
			function toggle(layerShow, layerHide) {
				var d;
				d = document.getElementById(layerShow);
				//d.style.visibility = (d.style.visibility == 'hidden') ? 'visible' : 'hidden';
				d.style.display = (d.style.display== 'none') ? 'block' : 'none';

				d = document.getElementById(layerHide);
				//d.style.visibility = (d.style.visibility == 'hidden') ? 'visible' : 'hidden';
				d.style.display = (d.style.display == 'none') ? 'block' : 'none';
			}
			</script>
			<div id="hideComments"><a href="javascript:toggle('showComments', 'hideComments')" >Show Reviews</a></div>

			<div id="showComments" style="display: none;"><a href="javascript:toggle('hideComments', 'showComments')" >Hide Reviews</a>
			<?php
			wp_list_comments( 'avatar_size=40&callback=custom_comment&type=comment' );
			?>
			</div>
		</ol>    

		<nav class="navigation fix">
			<div class="fl"><?php previous_comments_link(); ?></div>
			<div class="fr"><?php next_comments_link(); ?></div>
		</nav><!-- /.navigation -->
	<?php } ?>
		    
	<?php if ( false && ! empty( $comments_by_type['pings'] ) ) { ?>
    		
        <h3 id="pings"><?php _e( 'Trackbacks/Pingbacks', 'woothemes' ); ?></h3>
    
        <ol class="pinglist">
            <?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
        </ol>
    	
	<?php }; ?>
    	
</div> <!-- /#comments_wrap -->

<?php } else { // this is displayed if there are no comments so far ?>

<div id="comments">

	<?php 
		// If there are no comments and comments are closed, let's leave a little note, shall we?
		if ( ! comments_open() && is_singular() ) { ?><h3 class="nocomments"><?php _e( 'Reviews are closed.', 'woothemes' ); ?></h3><?php }
		else { ?><!--<h3 class="nocomments"><?php _e( 'No reviews yet.', 'woothemes' ); ?></h3>--><?php }
	?>

</div> <!-- /#comments_wrap -->

<?php
	} // End IF Statement
	
	/* The Respond Form. Uses filters in the theme-functions.php file to customise the form HTML. */
	comment_form();
?>
<br>