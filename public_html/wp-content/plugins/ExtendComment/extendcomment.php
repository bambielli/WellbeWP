<?php
/*
Plugin Name: Extend Comment
Version: 1.0
Plugin URI: http://smartwebworker.com
Description: A plug-in to add additional fields in the comment form.
Author: Specky Geek
Author URI: http://www.speckygeek.com
*/

// Modified significantly for Wellbe custom fields and rating functionality
// JMH

// Add custom meta (ratings) fields to the default comment form
// Default comment form includes name, email and URL
// Default comment form elements are hidden when user is logged in

add_filter('comment_form_default_fields','custom_fields');
function custom_fields($fields) {

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields[ 'author' ] = '<p class="comment-form-author">'.
			'<label for="author">' . __( 'Name' ) . '</label>'.
			( $req ? '<span class="required">*</span>' : '' ).
			'<input id="author" name="author" type="text" value="'. esc_attr( $commenter['comment_author'] ) . 
			'" size="30" tabindex="1"' . $aria_req . ' /></p>';
		
		$fields[ 'email' ] = '<p class="comment-form-email">'.
			'<label for="email">' . __( 'Email' ) . '</label>'.
			( $req ? '<span class="required">*</span>' : '' ).
			'<input id="email" name="email" type="text" value="'. esc_attr( $commenter['comment_author_email'] ) . 
			'" size="30"  tabindex="2"' . $aria_req . ' /></p>';
					
		$fields[ 'url' ] = '<p class="comment-form-url">'.
			'<label for="url">' . __( 'Website' ) . '</label>'.
			'<input id="url" name="url" type="text" value="'. esc_attr( $commenter['comment_author_url'] ) . 
			'" size="30"  tabindex="3" /></p>';

		$fields[ 'phone' ] = '<p class="comment-form-phone">'.
			'<label for="phone">' . __( 'Phone' ) . '</label>'.
			'<input id="phone" name="phone" type="text" size="30"  tabindex="4" /></p>';

	return $fields;
}

// Add fields after default fields above the comment box, always visible

add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );

function additional_fields () {
	echo '<br>';
	echo '<p class="comment-form-title" style="display: none;">'.
	'<label for="title">' . __( 'Review Title' ) . ': </label>'.
	'<input id="title" name="title" type="text" size="30" value="Treatment Review"/></p>';

	echo '<p class="comment-form-rating">'.
	'<label for="rating_helpful">'. __('Was this treatment helpful?') . '<span class="required">*</span></label>
	<span class="commentratingbox"><select name="rating_helpful" id="rating_helpful">';
	echo "<option value=0>Please Select</option>";
	echo "<option value=0>Harmful</option>";
	echo "<option value=1>Not Helpful</option>";
	echo "<option value=2>I Don't Know, Can't Tell</option>";
	echo "<option value=3>Slight Improvement, Somewhat Helpful</option>";
	echo "<option value=4>Complete / Amazing Improvement</option>";
	echo'</select></span></p>';

	echo '<p class="comment-form-rating">'.
	'<label for="rating_properly">'. __('Did you complete this treatment properly?') . '<span class="required">*</span></label>
	<span class="commentratingbox"><select name="rating_properly" id="rating_properly">';
	echo "<option value=0>Please Select</option>";
	echo "<option value=0>Not At All / Didn't Take It</option>";
	echo "<option value=1>Very Sporadic / Infrequently</option>";
	echo "<option value=2>I Don't Know / I Can't Remember</option>";
	echo "<option value=3>Enough To Make Some Difference</option>";
	echo "<option value=4>Followed The Treatment Perfectly</option>";
	echo'</select></span></p>';

	echo '<p class="comment-form-rating">'.
	'<label for="rating_spent">'. __('How much have you spent on this treatment?') . '<span class="required">*</span></label>
	<span class="commentratingbox"><select name="rating_spent" id="rating_spent">';
	echo "<option value=0>Please Select</option>";
	echo "<option value=4>\$0 - \$99</option>";
	echo "<option value=3>\$100 - \$499</option>";
	echo "<option value=2>\$500 - \$999</option>";
	echo "<option value=1>\$1,000 - \$4,999</option>";
	echo "<option value=0>\$5,000+</option>";
	echo'</select></span></p>';

	echo '<p class="comment-form-rating">'.
	'<label for="rating_pay">'. __('How did you pay for this treatment?') . '<span class="required">*</span></label>
	<span class="commentratingbox"><select name="rating_pay" id="rating_pay">';
	echo "<option value=0>Please Select</option>";
	echo "<option value=0>Totally Out Of Pocket</option>";
	echo "<option value=1>Out Of Pocket From FSA Account</option>";
	echo "<option value=2>Out Of Pocket + Insurance + FSA Combo</option>";
	echo "<option value=3>Partial Insurance + FSA</option>";
	echo "<option value=4>Insurance Covered / No Problems</option>";
	echo'</select></span></p>';

	//for( $i=1; $i <= 5; $i++ )
	//echo '<option value="'. $i .'"/>'. $i .'</option>';

	echo '<p class="comment-form-title">'.
	'<label for="insurance">' . __( 'Please enter your insurance company (optional)' ) . ': </label>'.
	'<input id="insurance" name="insurance" type="text" size="30"/></p>';


}

// Save the comment meta data along with comment

add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {
	if ( ( isset( $_POST['phone'] ) ) && ( $_POST['phone'] != '') )
	$phone = wp_filter_nohtml_kses($_POST['phone']);
	add_comment_meta( $comment_id, 'phone', $phone );

	if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') )
	$title = wp_filter_nohtml_kses($_POST['title']);
	add_comment_meta( $comment_id, 'title', $title );

	if ( ( isset( $_POST['rating_helpful'] ) ) && ( $_POST['rating_helpful'] != '') )
	$rating_helpful = wp_filter_nohtml_kses($_POST['rating_helpful']);
	add_comment_meta( $comment_id, 'rating_helpful', $rating_helpful );

	if ( ( isset( $_POST['rating_properly'] ) ) && ( $_POST['rating_properly'] != '') )
	$rating_properly = wp_filter_nohtml_kses($_POST['rating_properly']);
	add_comment_meta( $comment_id, 'rating_properly', $rating_properly);

	if ( ( isset( $_POST['rating_spent'] ) ) && ( $_POST['rating_spent'] != '') )
	$rating_spent = wp_filter_nohtml_kses($_POST['rating_spent']);
	add_comment_meta( $comment_id, 'rating_spent', $rating_spent);

	if ( ( isset( $_POST['rating_pay'] ) ) && ( $_POST['rating_pay'] != '') )
	$rating_pay = wp_filter_nohtml_kses($_POST['rating_pay']);
	add_comment_meta( $comment_id, 'rating_pay', $rating_pay);

	$total_rating = $rating_helpful + $rating_properly + $rating_spent + $rating_pay;
	$total_max = 16;

	$final_rating = ceil(($total_rating/$total_max) * 100);
	add_comment_meta( $comment_id, 'rating', $final_rating);

	if ( ( isset( $_POST['insurance'] ) ) && ( $_POST['insurance'] != '') )
	$title = wp_filter_nohtml_kses($_POST['insurance']);
	add_comment_meta( $comment_id, 'insurance', $insurance );

}


// Add the filter to check if the comment meta data has been filled or not

add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
	/*if ( ! isset( $_POST['rating'] ) )
	wp_die( __( 'Error: You did not add your rating. Hit the BACK button of your Web browser and resubmit your comment with rating.' ) );
	*/
	return $commentdata;
}

//Add an edit option in comment edit screen  

add_action( 'add_meta_boxes_comment', 'extend_comment_add_meta_box' );
function extend_comment_add_meta_box() {
    add_meta_box( 'title', __( 'Comment Metadata - Extend Comment' ), 'extend_comment_meta_box', 'comment', 'normal', 'high' );
}
 
function extend_comment_meta_box ( $comment ) {
    $phone = get_comment_meta( $comment->comment_ID, 'phone', true );
    $title = get_comment_meta( $comment->comment_ID, 'title', true );
    //$rating = get_comment_meta( $comment->comment_ID, 'rating', true );

    $insurance = get_comment_meta( $comment->comment_ID, 'insurance', true );

    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>
    <p>
        <label for="phone"><?php _e( 'Phone' ); ?></label>
        <input type="text" name="phone" value="<?php echo esc_attr( $phone ); ?>" class="widefat" />
    </p>
    <p>
        <label for="title"><?php _e( 'Review Title' ); ?></label>
        <input type="text" name="title" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
    </p>
<!--	
    <p>
        <label for="rating"><?php _e( 'Rating: ' ); ?></label>
			<span class="commentratingbox">
			<?php for( $i=1; $i <= 5; $i++ ) {
				echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
				if ( $rating == $i ) echo ' checked="checked"';
				echo ' />'. $i .' </span>'; 
				}
			?>
			</span>
    </p>
-->
    <?php
}

// Update comment meta data from comment edit screen 

add_action( 'edit_comment', 'extend_comment_edit_metafields' );
function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

	if ( ( isset( $_POST['phone'] ) ) && ( $_POST['phone'] != '') ) : 
	$phone = wp_filter_nohtml_kses($_POST['phone']);
	update_comment_meta( $comment_id, 'phone', $phone );
	else :
	delete_comment_meta( $comment_id, 'phone');
	endif;
		
	if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') ):
	$title = wp_filter_nohtml_kses($_POST['title']);
	update_comment_meta( $comment_id, 'title', $title );
	else :
	delete_comment_meta( $comment_id, 'title');
	endif;

	/*
	if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ):
	$rating = wp_filter_nohtml_kses($_POST['rating']);
	update_comment_meta( $comment_id, 'rating', $rating );
	else :
	delete_comment_meta( $comment_id, 'rating');
	endif;
	*/

	if ( ( isset( $_POST['insurance'] ) ) && ( $_POST['insurance'] != '') ):
	$insurance = wp_filter_nohtml_kses($_POST['insurance']);
	update_comment_meta( $comment_id, 'insurance', $insurance );
	else :
	delete_comment_meta( $comment_id, 'insurance');
	endif;

}

// Add the comment meta (saved earlier) to the comment text 
// You can also output the comment meta values directly in comments template  

add_filter( 'comment_text', 'modify_comment');
function modify_comment( $text ){

	$plugin_url_path = WP_PLUGIN_URL;

	if( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
		$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
		$text = $commenttitle . $text;
	} 

	if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
		//$commentrating = '<p class="comment-rating">	<img src="'. $plugin_url_path .
		'/ExtendComment/images/'. $commentrating . 'star.gif"/><br/>Rating: <strong>'. $commentrating .' / 5</strong></p>';
		$commentrating = '<p class="comment-rating">Rating: <strong>'. $commentrating .'%</strong></p>';

		$text = $text . $commentrating;
		return $text;		
	} else {
		return $text;		
	}	 
}