<?php
if ( !function_exists( 'woo_options' ) ) {
	function woo_options() {

		// THEME VARIABLES
		$themename = "Wikeasi";
		$themeslug = "wikeasi";

		// STANDARD VARIABLES. DO NOT TOUCH!
		$shortname = "woo";
		$manualurl = 'http://www.woothemes.com/support/theme-documentation/' . $themeslug . '/';

		//Access the WordPress Categories via an Array
		$woo_categories = array();
		$woo_categories_obj = get_categories( 'hide_empty=0' );
		foreach ( $woo_categories_obj as $woo_cat ) {
			$woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;}
		$categories_tmp = array_unshift( $woo_categories, "Select a category:" );

		//Access the WordPress Pages via an Array
		$woo_pages = array();
		$woo_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
		foreach ( $woo_pages_obj as $woo_page ) {
			$woo_pages[$woo_page->ID] = $woo_page->post_name; }
		$woo_pages_tmp = array_unshift( $woo_pages, "Select a page:" );

		//Stylesheets Reader
		$alt_stylesheet_path = get_template_directory() . '/styles/';
		$alt_stylesheets = array();
		if ( is_dir( $alt_stylesheet_path ) ) {
			if ( $alt_stylesheet_dir = opendir( $alt_stylesheet_path ) ) {
				while ( ( $alt_stylesheet_file = readdir( $alt_stylesheet_dir ) ) !== false ) {
					if( stristr( $alt_stylesheet_file, ".css" ) !== false ) {
						$alt_stylesheets[] = $alt_stylesheet_file;
					}
				}
			}
		}

		//More Options
		$other_entries = array( "Select a number:", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19" );

		// THIS IS THE DIFFERENT FIELDS
		$options = array();

		// General

		$options[] = array( 'name' => __( 'General Settings', 'woothemes' ),
			'type' => "heading",
			'icon' => "general" );

		$options[] = array( 'name' => __( 'Theme Stylesheet', 'woothemes' ),
			'desc' => __( 'Select your themes alternative color scheme.', 'woothemes' ),
			'id' => $shortname."_alt_stylesheet",
			'std' => "default.css",
			'type' => "select",
			'options' => $alt_stylesheets );

		$options[] = array( 'name' => __( 'Custom Logo', 'woothemes' ),
			'desc' => __( 'Upload a logo for your theme, or specify an image URL directly.', 'woothemes' ),
			'id' => $shortname."_logo",
			'std' => "",
			'type' => "upload" );

		$options[] = array( 'name' => __( 'Text Title', 'woothemes' ),
			'desc' => sprintf( __( 'Enable text-based Site Title and Tagline. Setup title & tagline in %1$s.', 'woothemes' ), '<a href="' . home_url() . '/wp-admin/options-general.php">' . __( 'General Settings', 'woothemes' ) . '</a>' ),
			'id' => $shortname."_texttitle",
			'std' => "false",
			"class" => "collapsed",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Site Title', 'woothemes' ),
			'desc' => __( 'Change the site title typography.', 'woothemes' ),
			'id' => $shortname."_font_site_title",
			'std' => array( 'size' => '70', 'unit' => 'px', 'face' => 'StMarie-Thin', 'style' => 'normal', 'color' => '#3E3E3E' ),
			"class" => "hidden",
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Site Description', 'woothemes' ),
			'desc' => __( 'Enable the site description/tagline under site title.', 'woothemes' ),
			'id' => $shortname."_tagline",
			"class" => "hidden",
			'std' => "false",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Site Description', 'woothemes' ),
			'desc' => __( 'Change the site description typography.', 'woothemes' ),
			'id' => $shortname."_font_tagline",
			'std' => array( 'size' => '26', 'unit' => 'px', 'face' => 'BergamoStd-Italic', 'style' => 'italic', 'color' => '#3E3E3E' ),
			"class" => "hidden last",
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Custom Favicon', 'woothemes' ),
			'desc' => __( 'Upload a 16px x 16px <a href="http://www.faviconr.com/">ico image</a> that will represent your website\'s favicon.', 'woothemes' ),
			'id' => $shortname."_custom_favicon",
			'std' => "",
			'type' => "upload" );

		$options[] = array( 'name' => __( 'Tracking Code', 'woothemes' ),
			'desc' => __( 'Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'woothemes' ),
			'id' => $shortname."_google_analytics",
			'std' => "",
			'type' => "textarea" );

		$options[] = array( 'name' => __( 'RSS URL', 'woothemes' ),
			'desc' => __( 'Enter your preferred RSS URL. (Feedburner or other)', 'woothemes' ),
			'id' => $shortname."_feed_url",
			'std' => "",
			'type' => "text" );

		$options[] = array( 'name' => __( 'E-Mail Subscription URL', 'woothemes' ),
			'desc' => __( 'Enter your preferred E-mail subscription URL. (Feedburner or other)', 'woothemes' ),
			'id' => $shortname."_subscribe_email",
			'std' => "",
			'type' => "text" );

		$options[] = array( 'name' => __( 'Contact Form E-Mail', 'woothemes' ),
			'desc' => __( 'Enter your E-mail address to use on the Contact Form Page Template. Add the contact form by adding a new page and selecting "Contact Form" as page template.', 'woothemes' ),
			'id' => $shortname."_contactform_email",
			'std' => "",
			'type' => "text" );

		$options[] = array( 'name' => __( 'Custom CSS', 'woothemes' ),
			'desc' => __( 'Quickly add some CSS to your theme by adding it to this block.', 'woothemes' ),
			'id' => $shortname."_custom_css",
			'std' => "",
			'type' => "textarea" );

		$options[] = array( 'name' => __( 'Post/Page Comments', 'woothemes' ),
			'desc' => __( 'Select if you want to enable/disable comments on posts and/or pages.', 'woothemes' ),
			'id' => $shortname."_comments",
			'std' => "both",
			'type' => "select2",
			'options' => array( "post" => __( 'Posts Only', 'woothemes' ), "page" => __( 'Pages Only', 'woothemes' ), "both" => __( 'Pages / Posts', 'woothemes' ), "none" => __( 'None', 'woothemes' ) ) );

		$options[] = array( 'name' => __( 'Post Content', 'woothemes' ),
			'desc' => __( 'Select if you want to show the full content or the excerpt on posts.', 'woothemes' ),
			'id' => $shortname."_post_content",
			'type' => "select2",
			'options' => array( "excerpt" => __( 'The Excerpt', 'woothemes' ), "content" => __( 'Full Content', 'woothemes' ) ) );

		$options[] = array( 'name' => __( 'Post Author Box', 'woothemes' ),
			'desc' => sprintf( __( 'This will enable the post author box on the single posts page. Edit description in %1$s.', 'woothemes' ), '<a href="' . home_url() . '/wp-admin/profile.php">' . __( 'Profile', 'woothemes' ) . '</a>' ),
			'id' => $shortname."_post_author",
			'std' => "true",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Display Breadcrumbs', 'woothemes' ),
			'desc' => __( 'Display dynamic breadcrumbs on each page of your website.', 'woothemes' ),
			'id' => $shortname."_breadcrumbs_show",
			'std' => "false",
			'type' => "checkbox" );
			
		$options[] = array( 'name' => __( 'Display Pagination', 'woothemes' ),
			'desc' => __( 'Display pagination on the blog.', 'woothemes' ),
			'id' => $shortname."_pagenav_show",
			'std' => "true",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Pagination Style', 'woothemes' ),
			'desc' => __( 'Select the style of pagination you would like to use on the blog.', 'woothemes' ),
			'id' => $shortname."_pagination_type",
			'type' => "select2",
			'options' => array( "paginated_links" => __( 'Numbers', 'woothemes' ), "simple" => __( 'Next/Previous', 'woothemes' ) ) );

		// Styling
		$options[] = array( 'name' => __( 'Styling Options', 'woothemes' ),
			'type' => "heading",
			'icon' => "styling" );

		$options[] = array( 'name' => __( 'Body Background Color', 'woothemes' ),
			'desc' => __( 'Pick a custom color for background color of the theme e.g. #697e09', 'woothemes' ),
			'id' => "woo_body_color",
			'std' => "",
			'type' => "color" );

		$options[] = array( 'name' => __( 'Body background image', 'woothemes' ),
			'desc' => __( 'Upload an image for the theme\'s background', 'woothemes' ),
			'id' => $shortname."_body_img",
			'std' => "",
			'type' => "upload" );

		$options[] = array( 'name' => __( 'Background image repeat', 'woothemes' ),
			'desc' => __( 'Select how you would like to repeat the background-image', 'woothemes' ),
			'id' => $shortname."_body_repeat",
			'std' => "no-repeat",
			'type' => "select",
			'options' => array( "no-repeat", "repeat-x", "repeat-y", "repeat" ) );

		$options[] = array( 'name' => __( 'Background image position', 'woothemes' ),
			'desc' => __( 'Select how you would like to position the background', 'woothemes' ),
			'id' => $shortname."_body_pos",
			'std' => "top",
			'type' => "select",
			'options' => array( "top left", "top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right" ) );

		$options[] = array( 'name' => __( 'Link Color', 'woothemes' ),
			'desc' => __( 'Pick a custom color for links or add a hex color code e.g. #697e09', 'woothemes' ),
			'id' => "woo_link_color",
			'std' => "",
			'type' => "color" );

		$options[] = array( 'name' =>  __( 'Link Hover Color', 'woothemes' ),
			'desc' => __( 'Pick a custom color for links hover or add a hex color code e.g. #697e09', 'woothemes' ),
			'id' => "woo_link_hover_color",
			'std' => "",
			'type' => "color" );

		$options[] = array( 'name' =>  __( 'Button Color', 'woothemes' ),
			'desc' => __( 'Pick a custom color for buttons or add a hex color code e.g. #697e09', 'woothemes' ),
			'id' => "woo_button_color",
			'std' => "",
			'type' => "color" );

		/* Typography */

		$options[] = array( 'name' => __( 'Typography', 'woothemes' ),
			'type' => "heading",
			'icon' => "typography" );

		$options[] = array( 'name' => __( 'Enable Custom Typography', 'woothemes' ) ,
			'desc' => __( 'Enable the use of custom typography for your site. Custom styling will be output in your sites HEAD.', 'woothemes' ) ,
			'id' => $shortname."_typography",
			'std' => "false",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'General Typography', 'woothemes' ) ,
			'desc' => __( 'Change the general font.', 'woothemes' ) ,
			'id' => $shortname."_font_body",
			'std' => array( 'size' => '12', 'unit' => 'px', 'face' => 'FontSiteSans-Roman', 'style' => '', 'color' => '#3E3E3E' ),
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Navigation', 'woothemes' ) ,
			'desc' => __( 'Change the navigation font.', 'woothemes' ),
			'id' => $shortname."_font_nav",
			'std' => array( 'size' => '18', 'unit' => 'px', 'face' => 'FontSiteSans-Cond', 'style' => '', 'color' => '#3E3E3E' ),
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Page Title', 'woothemes' ) ,
			'desc' => __( 'Change the page title.', 'woothemes' ) ,
			'id' => $shortname."_font_page_title",
			'std' => array( 'size' => '21', 'unit' => 'px', 'face' => 'BergamoStd', 'style' => 'bold', 'color' => '#3E3E3E' ),
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Post Title', 'woothemes' ) ,
			'desc' => __( 'Change the post title.', 'woothemes' ) ,
			'id' => $shortname."_font_post_title",
			'std' => array( 'size' => '21', 'unit' => 'px', 'face' => 'BergamoStd', 'style' => 'bold', 'color' => '#3E3E3E' ),
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Post Meta', 'woothemes' ),
			'desc' => __( 'Change the post meta.', 'woothemes' ) ,
			'id' => $shortname."_font_post_meta",
			'std' => array( 'size' => '16', 'unit' => 'px', 'face' => 'BergamoStd', 'style' => '', 'color' => '#3E3E3E' ),
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Post Entry', 'woothemes' ) ,
			'desc' => __( 'Change the post entry.', 'woothemes' ) ,
			'id' => $shortname."_font_post_entry",
			'std' => array( 'size' => '18', 'unit' => 'px', 'face' => 'BergamoStd', 'style' => '', 'color' => '#3E3E3E' ),
			'type' => "typography" );

		$options[] = array( 'name' => __( 'Widget Titles', 'woothemes' ) ,
			'desc' => __( 'Change the widget titles.', 'woothemes' ) ,
			'id' => $shortname."_font_widget_titles",
			'std' => array( 'size' => '18', 'unit' => 'px', 'face' => 'FontSiteSans-Cond', 'style' => 'bold', 'color' => '#3E3E3E' ),
			'type' => "typography" );

		/* Layout */

		$options[] = array( 'name' => __( 'Layout Options', 'woothemes' ),
			'type' => "heading",
			'icon' => "layout" );

		$url =  get_template_directory_uri() . '/functions/images/';
		$options[] = array( 'name' => __( 'Main Layout', 'woothemes' ),
			'desc' => __( 'Select which layout you want for your site.', 'woothemes' ),
			'id' => $shortname."_site_layout",
			'std' => "layout-right-content",
			'type' => "images",
			'options' => array(
				'layout-left-content' => $url . '2cl.png',
				'layout-right-content' => $url . '2cr.png' )
		);

		$options[] = array( 'name' => __( 'Category Exclude - Homepage', 'woothemes' ),
			'desc' => __( 'Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your homepage (eg: uncategorized).', 'woothemes' ),
			'id' => $shortname."_exclude_cats_home",
			'std' => "",
			'type' => "text" );

		$options[] = array( 'name' => __( 'Category Exclude - Blog Page Template', 'woothemes' ),
			'desc' => __( 'Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your \'Blog\' page template (eg: uncategorized).', 'woothemes' ),
			'id' => $shortname."_exclude_cats_blog",
			'std' => "",
			'type' => "text" );

		/* Dynamic Images */
		$options[] = array( 'name' => __( 'Dynamic Images', 'woothemes' ),
			'type' => "heading",
			'icon' => "image" );

		$options[] = array( 'name' => __( 'Dynamic Image Resizing', 'woothemes' ),
			'desc' => "",
			'id' => $shortname."_wpthumb_notice",
			'std' => __( 'There are two alternative methods of dynamically resizing the thumbnails in the theme, <strong>WP Post Thumbnail</strong> or <strong>TimThumb - Custom Settings panel</strong>. We recommend using WP Post Thumbnail option.', 'woothemes' ),
			'type' => "info" );

		$options[] = array( 'name' => __( 'WP Post Thumbnail', 'woothemes' ),
			'desc' => __( 'Use WordPress post thumbnail to assign a post thumbnail. Will enable the <strong>Featured Image panel</strong> in your post sidebar where you can assign a post thumbnail.', 'woothemes' ),
			'id' => $shortname."_post_image_support",
			'std' => "true",
			"class" => "collapsed",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'WP Post Thumbnail - Dynamic Image Resizing', 'woothemes' ),
			'desc' => __( 'The post thumbnail will be dynamically resized using native WP resize functionality. <em>(Requires PHP 5.2+)</em>', 'woothemes' ),
			'id' => $shortname."_pis_resize",
			'std' => "true",
			"class" => "hidden",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'WP Post Thumbnail - Hard Crop', 'woothemes' ),
			'desc' => __( 'The post thumbnail will be cropped to match the target aspect ratio (only used if "Dynamic Image Resizing" is enabled).', 'woothemes' ),
			'id' => $shortname."_pis_hard_crop",
			'std' => "true",
			"class" => "hidden last",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'TimThumb - Custom Settings Panel', 'woothemes' ),
			'desc' => sprintf( __( 'This will enable the %1$s (thumb.php) script which dynamically resizes images added through the <strong>custom settings panel below the post</strong>. Make sure your themes <em>cache</em> folder is writable. %2$s', 'woothemes' ), '<a href="http://code.google.com/p/timthumb/">TimThumb</a>', '<a href="http://www.woothemes.com/2008/10/troubleshooting-image-resizer-thumbphp/">Need help?</a>' ),
			'id' => $shortname."_resize",
			'std' => "true",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Automatic Image Thumbnail', 'woothemes' ),
			'desc' => __( 'If no thumbnail is specifified then the first uploaded image in the post is used.', 'woothemes' ),
			'id' => $shortname."_auto_img",
			'std' => "false",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Thumbnail Image Dimensions', 'woothemes' ),
			'desc' => __( 'Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.', 'woothemes' ),
			'id' => $shortname."_image_dimensions",
			'std' => "",
			'type' => array(
				array(  'id' => $shortname. '_thumb_w',
					'type' => 'text',
					'std' => 100,
					'meta' => __( 'Width', 'woothemes' ) ),
				array(  'id' => $shortname. '_thumb_h',
					'type' => 'text',
					'std' => 100,
					'meta' => __( 'Height', 'woothemes' ) )
			) );

		$options[] = array( 'name' => __( 'Thumbnail Image alignment', 'woothemes' ),
			'desc' => __( 'Select how to align your thumbnails with posts.', 'woothemes' ),
			'id' => $shortname."_thumb_align",
			'std' => "alignright",
			'type' => "radio",
			'options' => array( "alignleft" => __( 'Left', 'woothemes' ), "alignright" => __( 'Right', 'woothemes' ), "aligncenter" => __( 'Center', 'woothemes' ) ) );

		$options[] = array( 'name' => __( 'Show thumbnail in Single Posts', 'woothemes' ),
			'desc' => __( 'Show the attached image in the single post page.', 'woothemes' ),
			'id' => $shortname."_thumb_single",
			"class" => "collapsed",
			'std' => "false",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Single Image Dimensions', 'woothemes' ),
			'desc' => __( '"Enter an integer value i.e. 250 for the image size. Max width is 576.', 'woothemes' ),
			'id' => $shortname."_image_dimensions",
			'std' => "",
			"class" => "hidden last",
			'type' => array(
				array(  'id' => $shortname. '_single_w',
					'type' => 'text',
					'std' => 200,
					'meta' => __( 'Width', 'woothemes' ) ),
				array(  'id' => $shortname. '_single_h',
					'type' => 'text',
					'std' => 200,
					'meta' => __( 'Height', 'woothemes' ) )
			) );

		$options[] = array( 'name' => __( 'Single Post Image alignment', 'woothemes' ),
			'desc' => __( 'Select how to align your thumbnail with single posts.', 'woothemes' ),
			'id' => $shortname."_thumb_single_align",
			'std' => "alignright",
			'type' => "radio",
			"class" => "hidden",
			'options' => array( "alignleft" => __( 'Left', 'woothemes' ), "alignright" => __( 'Right', 'woothemes' ), "aligncenter" => __( 'Center', 'woothemes' ) ) );

		$options[] = array( 'name' => __( 'Add thumbnail to RSS feed', 'woothemes' ),
			'desc' => __( 'Add the the image uploaded via your Custom Settings to your RSS feed', 'woothemes' ),
			'id' => $shortname."_rss_thumb",
			'std' => "false",
			'type' => "checkbox" );

		/* Footer */
		$options[] = array( 'name' => __( 'Footer Customization', 'woothemes' ),
			'type' => 'heading',
			'icon' => 'footer' );

		$url =  get_template_directory_uri() . '/functions/images/';
		$options[] = array( 'name' => __( 'Footer Widget Areas', 'woothemes' ),
			'desc' => __( 'Select how many footer widget areas you want to display.', 'woothemes' ),
			'id' => $shortname."_footer_sidebars",
			'std' => "4",
			'type' => "images",
			'options' => array(
				'0' => $url . 'layout-off.png',
				'1' => $url . 'footer-widgets-1.png',
				'2' => $url . 'footer-widgets-2.png',
				'3' => $url . 'footer-widgets-3.png',
				'4' => $url . 'footer-widgets-4.png' )
		);

		$options[] = array( 'name' => __( 'Custom Affiliate Link', 'woothemes' ),
			'desc' => __( 'Add an affiliate link to the WooThemes logo in the footer of the theme.', 'woothemes' ),
			'id' => $shortname."_footer_aff_link",
			'std' => "",
			'type' => "text" );

		$options[] = array( 'name' => __( 'Enable Custom Footer (Left)', 'woothemes' ),
			'desc' => __( 'Activate to add the custom text below to the theme footer.', 'woothemes' ),
			'id' => $shortname."_footer_left",
			'std' => "false",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Custom Text (Left)', 'woothemes' ),
			'desc' => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'woothemes' ),
			'id' => $shortname."_footer_left_text",
			'std' => "",
			'type' => "textarea" );

		$options[] = array( 'name' => __( 'Enable Custom Footer (Right)', 'woothemes' ),
			'desc' => __( 'Activate to add the custom text below to the theme footer.', 'woothemes' ),
			'id' => $shortname."_footer_right",
			'std' => "false",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Custom Text (Right)', 'woothemes' ),
			'desc' => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'woothemes' ),
			'id' => $shortname."_footer_right_text",
			'std' => "",
			'type' => "textarea" );

		/* Subscribe & Connect */
		$options[] = array( 'name' => __( 'Subscribe & Connect', 'woothemes' ),
			'type' => "heading",
			'icon' => "connect" );

		$options[] = array( 'name' => __( 'Enable Subscribe & Connect - Single Post', 'woothemes' ),
			'desc' => sprintf( __( 'Enable the subscribe & connect area on single posts. You can also add this as a %1$s in your sidebar.', 'woothemes' ), '<a href="' . home_url() . '/wp-admin/widgets.php">widget</a>' ),
			'id' => $shortname."_connect",
			'std' => 'false',
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Subscribe Title', 'woothemes' ),
			'desc' => __( 'Enter the title to show in your subscribe & connect area.', 'woothemes' ),
			'id' => $shortname."_connect_title",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Text', 'woothemes' ),
			'desc' => __( 'Change the default text in this area.', 'woothemes' ),
			'id' => $shortname."_connect_content",
			'std' => '',
			'type' => "textarea" );

		$options[] = array( 'name' => __( 'Subscribe By E-mail ID (Feedburner)', 'woothemes' ),
			'desc' => __( 'Enter your <a href="http://www.google.com/support/feedburner/bin/answer.py?hl=en&answer=78982">Feedburner ID</a> for the e-mail subscription form.', 'woothemes' ),
			'id' => $shortname."_connect_newsletter_id",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Subscribe By E-mail to MailChimp', 'woothemes', 'woothemes' ),
			'desc' => __( 'If you have a MailChimp account you can enter the <a href="http://woochimp.heroku.com" target="_blank">MailChimp List Subscribe URL</a> to allow your users to subscribe to a MailChimp List.', 'woothemes' ),
			'id' => $shortname."_connect_mailchimp_list_url",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Enable RSS', 'woothemes' ),
			'desc' => __( 'Enable the subscribe and RSS icon.', 'woothemes' ),
			'id' => $shortname."_connect_rss",
			'std' => 'true',
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Twitter URL', 'woothemes' ),
			'desc' => __( 'Enter your  <a href="http://www.twitter.com/">Twitter</a> URL e.g. http://www.twitter.com/woothemes', 'woothemes' ),
			'id' => $shortname."_connect_twitter",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Facebook URL', 'woothemes' ),
			'desc' => __( 'Enter your  <a href="http://www.facebook.com/">Facebook</a> URL e.g. http://www.facebook.com/woothemes', 'woothemes' ),
			'id' => $shortname."_connect_facebook",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'YouTube URL', 'woothemes' ),
			'desc' => __( 'Enter your  <a href="http://www.youtube.com/">YouTube</a> URL e.g. http://www.youtube.com/woothemes', 'woothemes' ),
			'id' => $shortname."_connect_youtube",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Flickr URL', 'woothemes' ),
			'desc' => __( 'Enter your  <a href="http://www.flickr.com/">Flickr</a> URL e.g. http://www.flickr.com/woothemes', 'woothemes' ),
			'id' => $shortname."_connect_flickr",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'LinkedIn URL', 'woothemes' ),
			'desc' => __( 'Enter your  <a href="http://www.www.linkedin.com.com/">LinkedIn</a> URL e.g. http://www.linkedin.com/in/woothemes', 'woothemes' ),
			'id' => $shortname."_connect_linkedin",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Delicious URL', 'woothemes' ),
			'desc' => __( 'Enter your <a href="http://www.delicious.com/">Delicious</a> URL e.g. http://www.delicious.com/woothemes', 'woothemes' ),
			'id' => $shortname."_connect_delicious",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Google+ URL', 'woothemes' ),
			'desc' => __( 'Enter your <a href="http://plus.google.com/">Google+</a> URL e.g. https://plus.google.com/104560124403688998123/', 'woothemes' ),
			'id' => $shortname."_connect_googleplus",
			'std' => '',
			'type' => "text" );

		$options[] = array( 'name' => __( 'Enable Related Posts', 'woothemes' ),
			'desc' => __( 'Enable related posts in the subscribe area. Uses posts with the same <strong>tags</strong> to find related posts. Note: Will not show in the Subscribe widget.', 'woothemes' ),
			'id' => $shortname."_connect_related",
			'std' => 'true',
			'type' => "checkbox" );

		/* Advertising */
		$options[] = array( 'name' => __( 'Top Ad (468x60px)', 'woothemes' ),
			'type' => "heading",
			'icon' => "ads" );

		$options[] = array( 'name' => __( 'Enable Ad', 'woothemes' ),
			'desc' => __( 'Enable the ad space', 'woothemes' ),
			'id' => $shortname."_ad_top",
			'std' => "false",
			'type' => "checkbox" );

		$options[] = array( 'name' => __( 'Adsense code', 'woothemes' ),
			'desc' => __( 'Enter your adsense code (or other ad network code) here.', 'woothemes' ),
			'id' => $shortname."_ad_top_adsense",
			'std' => "",
			'type' => "textarea" );

		$options[] = array( 'name' => __( 'Image Location', 'woothemes' ),
			'desc' => __( 'Enter the URL to the banner ad image location.', 'woothemes' ),
			'id' => $shortname."_ad_top_image",
			'std' => "http://www.woothemes.com/ads/468x60b.jpg",
			'type' => "upload" );

		$options[] = array( 'name' => __( 'Destination URL', 'woothemes' ),
			'desc' => __( 'Enter the URL where this banner ad points to.', 'woothemes' ),
			'id' => $shortname."_ad_top_url",
			'std' => "http://www.woothemes.com",
			'type' => "text" );

		/* Table of Contents */
		$options[] = array( 'name' => __( 'Table of Contents', 'woothemes' ),
			'type' => 'heading',
			'icon' => 'main' );
			
		$options[] = array( 'name' => __( 'Enable Automatic Table Of Contents', 'woothemes' ),
			'desc' => __( 'Automatically add a table of contents, based on headings in the content, to each post and page.', 'woothemes' ),
			'id' => $shortname . '_auto_tableofcontents',
			'std' => 'true',
			'type' => 'checkbox' );
			
		$options[] = array( 'name' => __( 'Using The Table Of Contents', 'woothemes' ),
			'id' => $shortname . '_tableofcontents_info',
			'std' => sprintf( __( 'If disabled, the table of contents can still be added to a page or post\'s content using the %s shortcode.', 'woothemes' ), '<code>[table_of_contents]</code>' ),
			'type' => 'info' );
			
		/* Revisions */
		$options[] = array( 'name' => __( 'Content Revisions', 'woothemes' ),
			'type' => 'heading',
			'icon' => 'post' );
			
		$options[] = array( 'name' => __( 'Enable Revisions List', 'woothemes' ),
			'desc' => __( 'Automatically add a list of revisions to each post and page.', 'woothemes' ),
			'id' => $shortname . '_auto_revisions_list',
			'std' => 'true',
			'type' => 'checkbox' );
			
		$options[] = array( 'name' => __( 'Enable Revisions Differences', 'woothemes' ),
			'desc' => __( 'Automatically add a table of revision differences to each post and page when viewing a revision of it.', 'woothemes' ),
			'id' => $shortname . '_auto_revisions_diff',
			'std' => 'true',
			'type' => 'checkbox' );
			
		/* References */
		$options[] = array( 'name' => __( 'References', 'woothemes' ),
			'type' => 'heading',
			'icon' => 'references' );
			
		$options[] = array( 'name' => __( 'Enable The References Manager', 'woothemes' ),
			'desc' => __( 'Enable the ability to add content references within your posts and pages.', 'woothemes' ),
			'id' => $shortname . '_enable_references',
			'std' => 'true',
			'type' => 'checkbox' );
			
		/* Author Archives */
		$options[] = array( 'name' => __( 'Author Archives', 'woothemes' ),
			'type' => 'heading',
			'icon' => 'profile' );
			
		$options[] = array( 'name' => __( 'Enable Author Biography Box', 'woothemes' ),
			'desc' => __( 'Enable the author biography box when on an author\'s archive screen.', 'woothemes' ),
			'id' => $shortname . '_enable_author_biobox',
			'std' => 'true',
			'type' => 'checkbox' );
			
		$options[] = array( 'name' => __( 'Enable Author Contributions Box', 'woothemes' ),
			'desc' => __( 'Enable the author contributions box when on an author\'s archive screen.', 'woothemes' ),
			'id' => $shortname . '_enable_author_contributionsbox',
			'std' => 'true',
			'type' => 'checkbox' );
			
		/* Search */
		$options[] = array( 'name' => __( 'Search', 'woothemes' ),
			'type' => 'heading',
			'icon' => 'nav' );
			
		$options[] = array( 'name' => __( 'Enable Advanced Search Box', 'woothemes' ),
			'desc' => __( 'Enable the advanced search box on the homepage and "search" screens.', 'woothemes' ),
			'id' => $shortname . '_enable_searchbox',
			'std' => 'true',
			'type' => 'checkbox' );
			
		$options[] = array( 'name' => __( 'Enable Filter Bar', 'woothemes' ),
			'desc' => __( 'Enable the filter bar on the home and archive screens.', 'woothemes' ),
			'id' => $shortname . '_enable_filterbar',
			'std' => 'true',
			'type' => 'checkbox' );
			
		$options[] = array( 'name' => __( 'Enable AJAX Search', 'woothemes' ),
			'desc' => __( 'Enable the AJAX search on the advanced search box.', 'woothemes' ),
			'id' => $shortname . '_enable_ajaxsearch',
			'std' => 'true',
			'type' => 'checkbox' );
			
		$options[] = array( 'name' => __( 'Enable Search Suggest', 'woothemes' ),
			'desc' => __( 'Enable the search suggestions in the advanced search box.', 'woothemes' ),
			'id' => $shortname . '_enable_searchsuggest',
			'std' => 'true',
			'type' => 'checkbox' );

		// Add extra options through function
		if ( function_exists( "woo_options_add" ) )
			$options = woo_options_add( $options );

		if ( get_option( 'woo_template' ) != $options ) update_option( 'woo_template', $options );
		if ( get_option( 'woo_themename' ) != $themename ) update_option( 'woo_themename', $themename );
		if ( get_option( 'woo_shortname' ) != $shortname ) update_option( 'woo_shortname', $shortname );
		if ( get_option( 'woo_manual' ) != $manualurl ) update_option( 'woo_manual', $manualurl );

		// Woo Metabox Options
		// Start name with underscore to hide custom key from the user
		$woo_metaboxes = array();

		global $post;

		// Post Custom Fields
		if ( ( get_post_type() == 'post' ) || ( get_post_type() == 'conditions' ) || ( get_post_type() == 'treatments' ) || ( get_post_type() == 'symptoms' ) || ( !get_post_type() ) ) {

			// Source Name
			$woo_metaboxes[] = array ( 'name' => '_source_name',
				'std' => '',
				'label' => __( 'Source Name', 'woothemes' ),
				'type' => 'text',
				'desc' => __( 'The name of your article\'s source (if applicable).', 'woothemes' )
			);
			// Source Link
			$woo_metaboxes[] = array ( 'name' => '_source_link',
				'std' => '',
				'label' => __( 'Source Link', 'woothemes' ),
				'type' => 'text',
				'desc' => __( 'The link to your article\'s source (if applicable).', 'woothemes' )
			);
		}

		// References Custom Fields
		if ( ( get_post_type() == 'reference' ) || ( !get_post_type() ) ) {

			// ISBN Number
			$woo_metaboxes[] = array ( 'name' => '_isbn',
				'std' => '',
				'label' => __( 'ISBN Number', 'woothemes' ),
				'type' => 'text',
				'desc' => __( 'The ISBN number of your reference work (if applicable).', 'woothemes' )
			);

			// Publisher
			$woo_metaboxes[] = array ( 'name' => '_publisher',
				'std' => '',
				'label' => __( 'Publisher', 'woothemes' ),
				'type' => 'text',
				'desc' => __( 'The company who published your reference work (if applicable). This could be the name of a website as well.', 'woothemes' )
			);

			// Publisher URL
			$woo_metaboxes[] = array ( 'name' => '_publisher_url',
				'std' => '',
				'label' => __( 'Publisher URL', 'woothemes' ),
				'type' => 'text',
				'desc' => __( 'The URL to the company who published your reference work (if applicable). This could be the URL to a reference website as well.', 'woothemes' )
			);

			// Pages
			$woo_metaboxes[] = array ( 'name' => '_pages',
				'std' => '',
				'label' => __( 'Pages Referenced', 'woothemes' ),
				'type' => 'text',
				'desc' => __( 'The pages referenced in your work (this would apply to a printed reference) (eg: 22-23).', 'woothemes' )
			);
		}

		// Default Custom Fields

		/*
		if ( ( get_post_type() == 'post' ) || ( get_post_type() == 'conditions' ) || ( get_post_type() == 'treatments' ) || ( get_post_type() == 'symptoms' ) || ( !get_post_type() ) ) {

			$woo_metaboxes[] = array ( 'name' => "image",
				"label" => "Image",
				'type' => "upload",
				'desc' => "Upload an image or enter an URL." );

			if ( get_option( 'woo_resize' ) == "true" ) {
				$woo_metaboxes[] = array ( 'name' => "_image_alignment",
					'std' => "Center",
					"label" => "Image Crop Alignment",
					'type' => "select2",
					'desc' => "Select crop alignment for resized image",
					'options' => array( "c" => "Center",
						"t" => "Top",
						"b" => "Bottom",
						"l" => "Left",
						"r" => "Right" ) );
			}

			$woo_metaboxes[] = array (  'name'  => "embed",
				'std'  => "",
				"label" => "Embed Code",
				'type' => "textarea",
				'desc' => "Enter the video embed code for your video (YouTube, Vimeo or similar)" );

		} // End post
		*/

		/*
		if ( ( get_post_type() == 'post' ) || ( get_post_type() == 'page' ) || ( !get_post_type() ) ) {

			$woo_metaboxes[] = array ( 'name' => "_layout",
				'std' => "normal",
				"label" => "Layout",
				'type' => "images",
				'desc' => "Select the layout you want on this specific post/page.",
				'options' => array(
					'layout-default' => $url . 'layout-off.png',
					'layout-full' => get_template_directory_uri() . '/functions/images/' . '1c.png',
					'layout-left-content' => get_template_directory_uri() . '/functions/images/' . '2cl.png',
					'layout-right-content' => get_template_directory_uri() . '/functions/images/' . '2cr.png' ) );

		}
		*/

		// Add extra metaboxes through function
		if ( function_exists( "woo_metaboxes_add" ) )
			$woo_metaboxes = woo_metaboxes_add( $woo_metaboxes );

		if ( get_option( 'woo_custom_template' ) != $woo_metaboxes ) update_option( 'woo_custom_template', $woo_metaboxes );

	} // END woo_options()
} // END function_exists()

// Add options to admin_head
add_action( 'admin_head', 'woo_options' );

//Enable WooSEO on these Post types
$seo_post_types = array( 'post', 'page', 'conditions', 'treatments', 'symptoms' );
define( "SEOPOSTTYPES", serialize( $seo_post_types ) );

//Global options setup
add_action( 'init', 'woo_global_options' );
function woo_global_options(){
	// Populate WooThemes option in array for use in theme
	global $woo_options;
	$woo_options = get_option( 'woo_options' );
}

?>