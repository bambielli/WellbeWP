<?php
	$orderby_options = array( 'date' => __( 'Most Recent', 'woothemes' ), 'comment_count' => __( 'Most Commented', 'woothemes' ), 'modified' => __( 'Last Modified', 'woothemes' ), 'title' => __( 'Alphabetical', 'woothemes' ) );
	$postcount_options = array( '1' );
	for ( $i = 1; $i <= 50; $i++ ) {
		if ( $i % 5 == 0 ) { $postcount_options[] = $i; }
	}
	
	$orderby = get_query_var( 'orderby' );
	$postcount = get_query_var( 'posts_per_page' );
	if ( isset( $_GET['postcount'] ) && ( $_GET['postcount'] != '' ) ) {
		$postcount = $_GET['postcount'];
	}
?>
<section id="filter-bar">
<form name="filter-bar" method="get" class="alignleft">
	<label for="orderby"><?php echo __( 'Sort by', 'woothemes' ) . ':'; ?></label>
	<div>
	<select class="select sort" name="orderby" title="Select one">
		<?php
			$html = '';
			foreach ( $orderby_options as $k => $v ) {
				$html .= '<option value="' . $k . '"' . selected( $k, $orderby, false ) . '>' . $v . '</option>' . "\n";
			}
			echo $html;
		?>
	</select>
	<span class="select-arrow"></span>
	</div>
	<label for="postcount"><?php echo __( 'Show', 'woothemes' ) . ':'; ?></label>
	<div>
	<select class="select show" name="postcount" title="Select one">
		<?php
			$html = '';
			foreach ( $postcount_options as $k => $v ) {
				$html .= '<option value="' . $v . '"' . selected( $v, $postcount, false ) . '>' . $v . '</option>' . "\n";
			}
			echo $html;
		?>
	</select>
	<span class="select-arrow"></span>
	</div>
	<?php
		// Cater for the search form, as it also uses the GET querystring.
		if ( is_search() && ( get_query_var( 's' ) != '' ) ) {
			echo '<input type="hidden" name="s" value="' . esc_attr( get_query_var( 's' ) ) . '" />' . "\n";
		}
	?>
	<button type="submit" class="button"><?php _e( 'Filter', 'woothemes' ); ?></button>
</form>
<div class="totals alignright">
<?php
	echo woo_get_query_totals();
?>
</div><!--/.totals-->
<div class="fix"></div>
</section>