<?php
	$s = '';
	if ( get_query_var( 's' ) != '' ) { $s = get_query_var( 's' ); }

 	global $woo_options;
	if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
?>
<section id="advanced-search-form">
<br>&nbsp;<br>
<img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" height="120px" />
<h2><?php _e( 'Your Health. More Choices.', 'woothemes' ); ?></h2>
<br>&nbsp;<br>&nbsp;<br>
<form name="advanced-search-form" method="get" action="<?php echo home_url( '/' ); ?>" class="auto-complete" autocomplete="off">
	<input type="text" class="input-text input-txt" name="s" id="s" value="<?php echo esc_attr( $s ); ?>" />
	<button type="submit" class="adv-button"><?php _e( 'Search', 'woothemes' ); ?></button>
</form>
<div class="fl">&nbsp;&nbsp;<a href="<?php echo home_url( '/directory/' ); ?>" style="color: white; font-weight: bold; text-decoration: none;">Browse All</a></div>
</section>