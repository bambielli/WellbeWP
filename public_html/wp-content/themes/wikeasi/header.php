<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 global $woo_options;
 
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php woo_title(); ?></title>

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<?php woo_meta(); ?>

<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php
	wp_head();
	woo_head();
?>
</head>

<body <?php body_class(); ?>>
<?php woo_top(); ?>

<div id="wrapper">

	<?php 
	if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }

	if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) { ?>
	<div id="top">
		<nav class="col-full" role="navigation">
			<div class="fl">
				<a href="<?php echo home_url( '/' ); ?>"><img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" height="40px" /></a>
			</div>
			<div class="fr">
			<?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav', 'theme_location' => 'top-menu' ) ); ?>
			
			<ul class="rss">
            <?php
            	$email = '';
            	$rss = get_bloginfo_rss( 'rss2_url' );
            	
            	if ( isset( $woo_options['woo_subscribe_email'] ) && ( $woo_options['woo_subscribe_email'] != '' ) ) {
            		$email = $woo_options['woo_subscribe_email'];
            	}
            	
            	if ( isset( $woo_options['woo_feed_url'] ) && ( $woo_options['woo_feed_url'] != '' ) ) {
            		$rss = $woo_options['woo_feed_url'];
            	}
            	
            	if ( $email != '' ) {
            ?>
            <li class="sub-email"><a href="<?php echo esc_url( $email ); ?>" target="_blank"><?php _e( 'Email', 'woothemes' ); ?></a> |</li>
            <?php } ?>
            <li class="sub-rss"><a href="<?php echo $rss; ?>"><?php _e( 'RSS', 'woothemes' ); ?></a></li>
        </ul>
        </div><!-- /.fr -->
		</nav>
	</div><!-- /#top -->

    <?php } ?>

	<header id="header" class="col-full">
		
		<?php
		    $logo = get_template_directory_uri() . '/images/logo.png';
		    if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
		?>
		<?php if ( ! isset( $woo_options['woo_texttitle'] ) || $woo_options['woo_texttitle'] != 'true' ) { ?>
		    <a id="logo" href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'description' ); ?>">
		    	<img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" />
		    </a>
	    <?php } ?>
	    
	    <hgroup>
	        
			<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		      	
		</hgroup>

		<?php if ( isset( $woo_options['woo_ad_top'] ) && $woo_options['woo_ad_top'] == 'true' ) { ?>
        <div id="topad">
			<?php
				if ( isset( $woo_options['woo_ad_top_adsense'] ) && $woo_options['woo_ad_top_adsense'] != '' ) {
					echo stripslashes( $woo_options['woo_ad_top_adsense'] );
				} else {
					if ( isset( $woo_options['woo_ad_top_url'] ) && isset( $woo_options['woo_ad_top_image'] ) )
			?>
				<a href="<?php echo $woo_options['woo_ad_top_url']; ?>"><img src="<?php echo $woo_options['woo_ad_top_image']; ?>" width="468" height="60" alt="advert" /></a>
			<?php } ?>
		</div><!-- /#topad -->
        <?php } ?>
        
		<?php if ( $woo_options['woo_ad_top'] == 'false' ) { ?>
	<nav id="navigation" class="center fr" role="navigation">
		<?php
		if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fr', 'theme_location' => 'primary-menu' ) );
		} else {
		?>
        <ul id="main-nav" class="nav">
			<?php
        	if ( isset($woo_options['woo_custom_nav_menu'] ) && $woo_options['woo_custom_nav_menu'] == 'true' ) {
        		if ( function_exists( 'woo_custom_navigation_output' ) )
					woo_custom_navigation_output();
			} else { ?>
	            <?php if ( is_page() ) $highlight = 'page_item'; else $highlight = 'page_item current_page_item'; ?>
	            <li class="<?php echo $highlight; ?>"><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
	            <?php
	    			wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' );
			}
			?>
        </ul><!-- /#nav -->
        <?php } ?>

	</nav><!-- /#navigation -->
		<?php } ?>
		
	</header><!-- /#header -->