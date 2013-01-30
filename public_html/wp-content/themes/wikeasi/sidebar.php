<?php 
/**
 * Sidebar Template
 *
 * If a `primary` widget area is active and has widgets, display the sidebar.
 *
 * @package WooFramework
 * @subpackage Template
 */
	global $woo_options;
	
	if ( isset( $woo_options['woo_layout'] ) && ( $woo_options['woo_layout'] != 'layout-full' ) ) {
?>	
<aside id="sidebar" class="col-left">

	<?php if ( woo_active_sidebar( 'primary' ) ) { ?>
    <div class="primary">
		<?php woo_sidebar( 'primary' ); ?>		           
	</div>        
	<?php } ?>    
	
</aside><!-- /#sidebar -->
<?php if ( woo_active_sidebar( 'secondary' ) ) { ?>
<aside id="sidebar-secondary" class="col-right">
    <div class="secondary">
		<?php woo_sidebar( 'secondary' ); ?>		           
	</div>   
</aside><!-- /#sidebar-secondary -->
<?php } ?> 
<?php } // End IF Statement ?>