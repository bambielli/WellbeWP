<?php
// Get the path to the root.
$full_path = __FILE__;

$path_bits = explode( 'wp-content', $full_path );

$url = $path_bits[0];

// Require WordPress bootstrap.
require_once( $url . '/wp-load.php' );

$woo_references_url = get_template_directory_uri() . '/includes/woo-references/';

// Load the WooThemes_References class.
require_once( get_template_directory() . '/includes/woo-references/woo-references.php' );

// Setup the references.
$token = 'reference';

$args = array( 'numberposts' => -1, 'post_type' => $token );

$references = get_posts( $args );
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<div id="woo-dialog">

<div id="woo-options-buttons" class="clear">
	<div class="alignleft">
	    <input type="button" id="woo-btn-cancel" class="button" name="cancel" value="Cancel" accesskey="C" />
	</div>
	<div class="alignright">
	    <input type="button" id="woo-btn-insert" class="button-primary" name="insert" value="Insert" accesskey="I" />
	</div>
	<div class="clear"></div><!--/.clear-->
</div><!--/#woo-options-buttons .clear-->

<div id="woo-options" class="alignleft">
    <h3><?php _e( 'Insert Reference', 'woothemes' ); ?></h3>
    <form name="reference-select" method="post">
    	<ul>
		<?php		
			$html = '';
			if ( is_array( $references ) && ( count( $references ) > 0 ) ) {
				foreach ( $references as $k => $post ) {
					setup_postdata( $post );
					
					$css_class = 'even';
					if ( $k % 2 == 0 ) {
						$css_class = 'odd';
					}
					
					$preview_data = '<div class="reference-preview-data hidden">' . "\n" . "\n";
						$preview_data .= WooThemes_References::get_formatted_reference( get_the_ID() );
					$preview_data .= "\n" . '</div><!--/.reference-preview-data hidden-->' . "\n";
					
					$html .= '<li class="' . $css_class . '">' . '<input type="radio" name="reference-id[]" value="' . get_the_ID() . '" class="reference-selector" />' . ' <label for="reference-id[]">' . get_the_title() . '</label>' . $preview_data . '</li>' . "\n";
				}
			} else {
				$html = '<li>' . __( 'No References Currently Available.', 'woothemes' ) . '</li>' . "\n";
			}
			
			echo $html;
		?>
		</ul>
	</form>

</div>
<div id="woo-preview" class="alignleft">
	<h3><?php _e( 'Reference Preview', 'woothemes' ); ?></h3>
	<div class="preview-data">
	<?php _e( 'Select a reference to view it\'s data.', 'woothemes' ); ?>
	</div><!--/.preview-data-->
</div><!--/#woo-preview-->
<div class="clear"></div>

<script type="text/javascript" src="<?php echo $woo_references_url; ?>js/dialog-js.php"></script>
</div>
</body>
</html>