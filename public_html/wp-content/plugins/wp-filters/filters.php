<?php
/*
Plugin Name: wp-filters
Plugin URI: http://gobanana.ca/wp-filters
Description: A live post filtering plugin. Choose which categories to show in the filter and watch the post show/hide without reloading.
Version: 1.0
Author: Anna Klibanov
Author URI: http://gobanana.ca
License: A "Slug" license name e.g. GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


//add admin setting menu
function filters_menu() {
  	add_submenu_page('options-general.php', 'WP-Filters', 'WP-Filters', 'manage_options', 'wp-filters', 'filters_page' );

	//call register settings function
	add_action( 'admin_init', 'register_filters_settings' );
}
add_action('admin_menu', 'filters_menu');

function register_filters_settings() {
	//register settings
	register_setting( 'filters-group', 'jquery' );
	register_setting( 'filters-group', 'postsContainer' );
	
	$cats = get_categories("get=all");
	 foreach($cats as $c) {
		 register_setting( 'filters-group', $c->term_id );
	 }
		 
	
}

add_action('wp_head', 'filter_posts_jQuery_header');



function filter_posts_jQuery_header() {
	
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
	
		if(get_option('jquery')) echo ("<script type='text/javascript' src='" . $plugin_url . "/js/jquery.js'></script>\n");
		echo("<script type='text/javascript'>\n");
		echo("postsContainer = '" .  get_option('postsContainer') . "';\n");
		echo("</script>");
		echo ("<script type='text/javascript' src='" . $plugin_url . "/js/framework.js'></script>\n");
		
	
}



function filters_page() { ?>
		<div class="wrap"><h2>WP-Filters Settings</h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'filters-group' ); ?>
        		<table class="form-table">					
		<tr>
		<td width="100px" valign="middle"><strong>Include jQuery</strong></td>
		<td><input type="checkbox" name="jquery" id="jquery" value="true" <?php checked('true', get_option('jquery')); ?> /> Uncheck if jQuery is already included in the header.</td>
		</tr>
		<tr>
		<td width="100px" valign="middle"><strong>Posts Container</strong></td>
		<td><input type="text" name="postsContainer" id="postsContainer" value="<?php echo (get_option('postsContainer')); ?>" /> Remember to indicate if it is a class (.) or an id (#). <em>(e.g. #postsWrapper)</em></td>
		</tr>
        </table>
<br />
	   	   <?php $cats = get_categories("get=all");
        if(!empty($cats)) {
			echo "<table class=\"widefat post fixed\" id=\"category_filters\"><thead><tr><th scope=\"col\" id=\"cb\" class=\"manage-column column-cb check-column\"><input type=\"checkbox\" /></th><th scope=\"col\" id=\"cb\" class=\"manage-column column-title\">Category Title</th></tr></thead><tbody>";
           foreach($cats as $c) { 
               echo "<tr><th scope=\"row\" class=\"check-column\">"; ?>
                       <input name="<?php echo ($c->term_id); ?>" type="checkbox" id="<?php echo ($c->term_id); ?>" value="<?php echo ($c->term_id); ?>" <?php checked($c->term_id, get_option($c->term_id)); ?> />

             <?php  echo "</th><td><label for=\"$c->term_id\">$c->category_nicename</label></td></tr>\n";
          }
		  echo "</tbody></table>"; ?>
          
          <?php

        }
		
		
		?>

    
		<p class="submit">
	    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	    </p>	
		</form></div>
<?php }



function filters_generate() {
		$cats = get_categories("get=all");


		 foreach($cats as $c) {
				$options .= get_option($c->term_id);
				if (get_option($c->term_id) != "") $options .= ",";
		 }
	 			$options = substr($options,0,-1);
		if ($options == "") echo "$postWrapper";
		else {
		echo '<ul id="filters">' . "\n";
		echo '<li><a href="#">All</a></li>' . "\n";
		$optionsArray = explode(',',$options);
		foreach ($optionsArray as $category) {
		$slug = get_category($category);
		echo '<li><a href="category-';
		echo $slug->slug;
		echo '">';
		echo get_the_category_by_id($category);
		echo '</a>';
		}
	
			 
		echo '</ul>';

		}


} 

function getAllOptions() {
			$cats = get_categories("get=all");

		 foreach($cats as $c) {

		$options .= get_option($c->term_id);
	 }

	return $options;
	
} ?>