<?php
// Register widgetized areas

if ( ! function_exists( 'the_widgets_init' ) ) {
	function the_widgets_init() {
		if ( ! function_exists( 'register_sidebar' ) )
			return;

		register_sidebar( array( 'name' => __( 'Primary', 'woothemes' ), 'id' => 'primary', 'description' => __( 'Primary sidebar', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );

		register_sidebar( array( 'name' => __( 'Secondary', 'woothemes' ), 'id' => 'secondary', 'description' => __( 'Optional secondary sidebar', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );

		register_sidebar( array( 'name' => __( 'Footer 1', 'woothemes' ), 'id' => 'footer-1', 'description' => __( 'Widgetized footer region 01', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );
		register_sidebar( array( 'name' => __( 'Footer 2', 'woothemes' ), 'id' => 'footer-2', 'description' => __( 'Widgetized footer region 02', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );
		register_sidebar( array( 'name' => __( 'Footer 3', 'woothemes' ), 'id' => 'footer-3', 'description' => __( 'Widgetized footer region 03', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );
		register_sidebar( array( 'name' => __( 'Footer 4', 'woothemes' ), 'id' => 'footer-4', 'description' => __( 'Widgetized footer region 04', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );
	}
}

add_action( 'init', 'the_widgets_init', 10 );
?>