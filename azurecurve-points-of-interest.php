<?php 
/*
Plugin Name: azurecurve Points of Interest
Plugin URI: http://wordpress.azurecurve.co.uk/plugins/points-of-interest

Description: Create Points of Interest using custom post type
Version: 1.0.0

Author: azurecurve
Author URI: http://wordpress.azurecurve.co.uk

Text Domain: azc_poi
Domain Path: /languages

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt
*/

function azc_poi_load_plugin_textdomain(){
	$loaded = load_plugin_textdomain( 'azc_poi', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	//if ($loaded){ echo 'true'; }else{ echo 'false'; }
}
add_action('plugins_loaded', 'azc_poi_load_plugin_textdomain');

function azc_poi_load_css(){
	wp_enqueue_style( 'azc_poi', plugins_url( 'style.css', __FILE__ ) );
}
add_action('admin_enqueue_scripts', 'azc_poi_load_css');
 
function azc_poi_set_default_options($networkwide) {
	
	$new_options = array(
				'default_type' => '',
				'default_continent' => '',
				'default_country' => '',
				'default_region' => '',
			);
	
	// set defaults for multi-site
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			global $wpdb;

			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );

				if ( get_option( 'azc_poi_options' ) === false ) {
					add_option( 'azc_poi_options', $new_options );
				}
			}

			switch_to_blog( $original_blog_id );
		}else{
			if ( get_option( 'azc_poi_options' ) === false ) {
				add_option( 'azc_poi_options', $new_options );
			}
		}
		if ( get_site_option( 'azc_poi_options' ) === false ) {
			add_site_option( 'azc_poi_options', $new_options );
		}
	}
	//set defaults for single site
	else{
		if ( get_option( 'azc_poi_options' ) === false ) {
			add_option( 'azc_poi_options', $new_options );
		}
	}
}
register_activation_hook( __FILE__, 'azc_poi_set_default_options' );

function azc_poi_config_page() {
	if (!current_user_can('manage_options')) {
		$error = new WP_Error( 'not_found', __('You do not have sufficient permissions to access this page.' , 'azc_poi'), array( 'response' => '200' ) );
		if( is_wp_error($error) ){
			wp_die( $error, '', $error->get_error_data() );
		}
    }
	
	// Retrieve plugin configuration options from database
	$options = get_option( 'azc_poi_options' );
	?>
	<div id="azc-a-general" class="wrap">
		<fieldset>
			<h2>azurecurve Points of Interest <?php _e(' Options', 'azc_poi'); ?></h2>
			<?php if( isset($_GET['options-updated']) ) { ?>
				<div id="message" class="updated">
					<p><strong><?php _e('Options have been saved.') ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="save_azc_poi_options" />
				<input name="page_options" type="hidden" value="default_type, default continent, default_country, default_region" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field( 'azc_poi_nonce', 'azc_poi_nonce' ); ?>
				<table class="form-table">
				<tr><th scope="row"><label for="default_type"><?php _e('Default Type', 'azc_poi'); ?></label></th><td>
					<select name="default_type" style="width: 200px">
					<?php
						$point_of_interest_types = get_terms( 'point-of-interest-type', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_types ) {
							foreach ( $point_of_interest_types as $point_of_interest_type ) {
								echo "<option value='" . $point_of_interest_type->term_id . "' ";
								echo selected( $options["default_type"], $point_of_interest_type->term_id ) . ">";
								echo esc_html( $point_of_interest_type->name );
								echo "</option>";
							}        
						}
					?>
					</select>
				</td></tr>
				<tr><th scope="row"><label for="default_continent"><?php _e('Default Continent', 'azc_poi'); ?></label></th><td>
					<select name="default_continent" style="width: 200px;">
					<?php
						$point_of_interest_continents = get_terms( 'point-of-interest-continent', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_continents ) {
							foreach ( $point_of_interest_continents as $point_of_interest_continent ) {
								echo "<option value='" . $point_of_interest_continent->term_id . "' ";
								echo selected( $options["default_continent"], $point_of_interest_continent->term_id ) . ">";
								echo esc_html( $point_of_interest_continent->name );
								echo '</option>';
							}        
						}
					?>
					</select>
				</td></tr>
				<tr><th scope="row"><label for="default_country"><?php _e('Default Country', 'azc_poi'); ?></label></th><td>
					<select name="default_country" style="width: 200px;">
					<?php
						$point_of_interest_countries = get_terms( 'point-of-interest-country', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_countries ) {
							foreach ( $point_of_interest_countries as $point_of_interest_country ) {
								echo "<option value='" . $point_of_interest_country->term_id . "' ";
								echo selected( $options["default_country"], $point_of_interest_country->term_id ) . ">";
								echo esc_html( $point_of_interest_country->name );
								echo "</option>";
							}        
						}
					?>
					</select>
				</td></tr>
				<tr><th scope="row"><label for="default_region"><?php _e('Default Region', 'azc_poi'); ?></label></th><td>
					<select name="default_region" style="width: 200px;">
					<?php
						$point_of_interest_regions = get_terms( 'point-of-interest-region', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_regions ) {
							foreach ( $point_of_interest_regions as $point_of_interest_region ) {
								echo "<option value='" . $point_of_interest_region->term_id . "' ";
								echo selected( $options["default_region"], $point_of_interest_region->term_id ) . ">";
								echo esc_html( $point_of_interest_region->name );
								echo "</option>";
							}        
						}
					?>
					</select>
				</td></tr>
				</table>
				<input type="submit" value="<?php _e('Submit', 'azc_poi'); ?>" class="button-primary"/>
			</form>
		</fieldset>
	</div>
<?php }


function azc_poi_admin_init_options() {
	add_action( 'admin_post_save_azc_poi_options', 'process_azc_poi_options' );
}
add_action( 'admin_init', 'azc_poi_admin_init_options' );

function process_azc_poi_options() {
	// Check that user has proper security level
	if ( !current_user_can( 'manage_options' ) ){
		$error = new WP_Error( 'not_found', __('You do not have sufficient permissions to perform this action.' , 'azc_poi'), array( 'response' => '200' ) );
		if( is_wp_error($error) ){
			wp_die( $error, '', $error->get_error_data() );
		}
	}
	// Check that nonce field created in configuration form is present
	if ( ! empty( $_POST ) && check_admin_referer( 'azc_poi_nonce', 'azc_poi_nonce' ) ) {
	
		// Retrieve original plugin options array
		$options = get_option( 'azc_poi_options' );
		
		$option_name = 'default_type';
		if ( isset( $_POST[$option_name] ) ) {
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'default_continent';
		if ( isset( $_POST[$option_name] ) ) {
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'default_country';
		if ( isset( $_POST[$option_name] ) ) {
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'default_region';
		if ( isset( $_POST[$option_name] ) ) {
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		// Store updated options array to database
		update_option( 'azc_poi_options', $options );
		
		// Redirect the page to the configuration form that was processed
		wp_redirect( add_query_arg( 'page', 'points_of_interest_options&post_type=points-of-interest&options-updated', admin_url( 'edit.php' ) ) );
		exit;
	}
}

function azc_poi_create_post_type() {
	register_post_type( 'points-of-interest',
		array(
				'labels' => array(
				'name' => __('Points of Interest', 'azc_poi'),
				'singular_name' => __('Point of Interest', 'azc_poi'),
				'add_new' => __('Add New', 'azc_poi'),
				'add_new_item' => __('Add New Point of Interest', 'azc_poi'),
				'edit' => __('Edit', 'azc_poi'),
				'edit_item' => __('Edit Point of Interest', 'azc_poi'),
				'new_item' => __('New Point of Interest', 'azc_poi'),
				'view' => __('View', 'azc_poi'),
				'view_item' => __('View Point of Interest', 'azc_poi'),
				'search_items' => __('Search Points of Interest', 'azc_poi'),
				'not_found' => __('No Points of Interest found', 'azc_poi'),
				'not_found_in_trash' => __('No Points of Interest found in Trash', 'azc_poi'),
				'parent' => __('Parent Point of Interest', 'azc_poi')
			),
		'public' => true,
		'menu_position' => 20,
		'supports' => array( 'title', 'comments', 'trackbacks', 'revisions', 'excerpt', 'editor' ),
		'taxonomies' => array( '' ),
		'menu_icon' => plugins_url( 'images/poi-16x16.png', __FILE__ ),
		'has_archive' => true
		)
	);
}
add_action( 'init', 'azc_poi_create_post_type' );

function azc_poi_add_submenu(){
	add_submenu_page('edit.php?post_type=points-of-interest', 'options', __('Options', 'azc_poi'), 'manage_options', 'points_of_interest_options', 'azc_poi_config_page');
}
add_action("admin_menu", 'azc_poi_add_submenu');

// Function to register new meta box for book review post editor
function azc_poi_admin_init() {
	add_meta_box( 'azc_poi_point_of_interest_details_meta_box', __('Point of Interest Details', 'azc_poi'), 'azc_poi_display_point_of_interest_details_meta_box', 'points-of-interest', 'normal', 'high' );
}
add_action( 'admin_init', 'azc_poi_admin_init' );

// Function to display meta box contents
function azc_poi_display_point_of_interest_details_meta_box( $point_of_interest ) { 
	$options = get_option('azc_poi_options');
	// Retrieve point_of_interest details based on point_of_interest ID
	$point_of_interest_type = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-type', true ) );
	if (strlen($point_of_interest_type) == 0){
		$point_of_interest_type = $options['default_type'];
	}
	$point_of_interest_continent = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-continent', true ) );
	if (strlen($point_of_interest_continent) == 0){
		$point_of_interest_continent = $options['default_continent'];
	}
	$point_of_interest_owner = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-owner', true ) );
	$point_of_interest_country = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-country', true ) );
	if (strlen($point_of_interest_country) == 0){
		$point_of_interest_country = $options['default_country'];
	}
	$point_of_interest_region = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-region', true ) );
	if (strlen($point_of_interest_region) == 0){
		$point_of_interest_region = $options['default_region'];
	}
	$point_of_interest_location = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-location', true ) );
	$point_of_interest_grid_reference = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-grid-reference', true ) );
	$point_of_interest_telephone = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-telephone', true ) );
	$point_of_interest_email = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-email', true ) );
	$point_of_interest_website = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-website', true ) );
	$point_of_interest_twitter = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-twitter', true ) );
	$point_of_interest_facebook = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-facebook', true ) );
	$point_of_interest_linkedin = esc_html( get_post_meta( $point_of_interest->ID, 'point-of-interest-linkedin', true ) );
	?>
	<table>
		<tr>
			<td style="width: 100%"><?php _e('Type', 'azc_poi'); ?></td>
			<td>
				<select name="point-of-interest-type" style="width: 100%">
					<?php
						$assigned_types = wp_get_post_terms( $point_of_interest->ID, 'point-of-interest-type' );
						$point_of_interest_types = get_terms( 'point-of-interest-type', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_types ) {
							foreach ( $point_of_interest_types as $point_of_interest_type ) {
								echo "<option value='" . $point_of_interest_type->term_id;
								echo "' " . selected( $assigned_types[0]->term_id, $point_of_interest_type->term_id ) . ">";
								echo esc_html( $point_of_interest_type->name );
								echo "</option>";
							}
						} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Owner', 'azc_poi'); ?></td>
			<td>
				<select name="point-of-interest-owner" style="width: 100%">
					<?php
						$assigned_owners = wp_get_post_terms( $point_of_interest->ID, 'point-of-interest-owner' );
						$point_of_interest_owners = get_terms( 'point-of-interest-owner', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_owners ) {
							foreach ( $point_of_interest_owners as $point_of_interest_owner ) {
								echo "<option value='" . $point_of_interest_owner->term_id . "' ";
								echo selected( $assigned_owners[0]->term_id, $point_of_interest_owner->term_id ) . ">";
								echo esc_html( $point_of_interest_owner->name );
								echo "</option>";
							}
						} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Continent', 'azc_poi'); ?></td>
			<td>
				<select name="point-of-interest-continent" style="width: 100%">
					<?php
						$assigned_continents = wp_get_post_terms( $point_of_interest->ID, 'point-of-interest-continent' );
						$point_of_interest_continents = get_terms( 'point-of-interest-continent', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_continents ) {
							foreach ( $point_of_interest_continents as $point_of_interest_continent ) {
								echo "<option value='" . $point_of_interest_continent->term_id . "' ";
								echo selected( $assigned_continents[0]->term_id, $point_of_interest_continent->term_id ) . ">";
								echo esc_html( $point_of_interest_continent->name );
								echo "</option>";
							}
						} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Country', 'azc_poi'); ?></td>
			<td>
				<select name="point-of-interest-country" style="width: 100%">
					<?php
						$assigned_countries = wp_get_post_terms( $point_of_interest->ID, 'point-of-interest-country' );
						$point_of_interest_countries = get_terms( 'point-of-interest-country', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_countries ) {
							foreach ( $point_of_interest_countries as $point_of_interest_country ) {
								echo "<option value='" . $point_of_interest_country->term_id . "' ";
								echo selected( $assigned_countries[0]->term_id, $point_of_interest_country->term_id ) . ">";
								echo esc_html( $point_of_interest_country->name );
								echo "</option>";
							}
						} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Region', 'azc_poi'); ?></td>
			<td>
				<select name="point-of-interest-region" style="width: 100%">
					<?php
						$assigned_regions = wp_get_post_terms( $point_of_interest->ID, 'point-of-interest-region' );
						$point_of_interest_regions = get_terms( 'point-of-interest-region', array( 'orderby' => 'name', 'hide_empty' => 0) );
						if ( $point_of_interest_regions ) {
							foreach ( $point_of_interest_regions as $point_of_interest_region ) {
								echo "<option value='" . $point_of_interest_region->term_id . "' ";
								echo selected( $assigned_regions[0]->term_id, $point_of_interest_region->term_id ) . ">";
								echo esc_html( $point_of_interest_region->name );
								echo "</option>";
							}
						} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Location', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-location' value='<?php echo $point_of_interest_location; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Grid Reference', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-grid-reference' value='<?php echo $point_of_interest_grid_reference; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Telephone', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-telephone' value='<?php echo $point_of_interest_telephone; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Email', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-email' value='<?php echo $point_of_interest_email; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Website', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-website' value='<?php echo $point_of_interest_website; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Twitter', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-twitter' value='<?php echo $point_of_interest_twitter; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('Facebook', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-facebook' value='<?php echo $point_of_interest_facebook; ?>' /></td>
		</tr>
		<tr>
			<td style="width: 100%"><?php _e('LinkedIn', 'azc_poi'); ?></td>
			<td><input type='text' size='80' name='point-of-interest-linkedin' value='<?php echo $point_of_interest_linkedin; ?>' /></td>
		</tr>
	</table>

<?php }

// Register function to be called when posts are saved
// The function will receive 2 arguments
add_action( 'save_post', 'azc_poi_add_point_of_interest_fields', 10, 2 );

function azc_poi_add_point_of_interest_fields( $post_id = false, $post = false ) {
	// Check post type for book reviews
	if ( $post->post_type == 'points-of-interest' ) {
		// Store data in post meta table if present in post data
		
		$value_name = 'point-of-interest-type';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			wp_set_post_terms( $post_id, sanitize_text_field($_POST['point-of-interest-type']), 'point-of-interest-type' );
		}
		
		$value_name = 'point-of-interest-owner';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			wp_set_post_terms( $post_id, sanitize_text_field($_POST['point-of-interest-owner']), 'point-of-interest-owner' );
		}
		
		$value_name = 'point-of-interest-continent';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			wp_set_post_terms( $post_id, sanitize_text_field($_POST['point-of-interest-continent']), 'point-of-interest-continent' );
		}
		
		$value_name = 'point-of-interest-country';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			wp_set_post_terms( $post_id, sanitize_text_field($_POST['point-of-interest-country']), 'point-of-interest-country' );
		}
		
		$value_name = 'point-of-interest-region';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			wp_set_post_terms( $post_id, sanitize_text_field($_POST['point-of-interest-region']), 'point-of-interest-region' );
		}
		
		$value_name = 'point-of-interest-location';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
		
		$value_name = 'point-of-interest-grid-reference';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
		
		$value_name = 'point-of-interest-telephone';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
		
		$value_name = 'point-of-interest-email';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
		
		$value_name = 'point-of-interest-website';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
		
		$value_name = 'point-of-interest-twitter';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
		
		$value_name = 'point-of-interest-facebook';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
		
		$value_name = 'point-of-interest-linkedin';
		if ( isset( $_POST[$value_name] ) && $_POST[$value_name] != '' ) {
			update_post_meta( $post_id, $value_name, sanitize_text_field($_POST[$value_name]) );
		}
	}
}

function azc_poi_template_include( $template_path ){
	
	if ( get_post_type() == 'points-of-interest' ) {
		if ( is_single() ) {
			if ( $theme_file = locate_template( array( 'single-point-of-interest.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . 'templates/single-point-of-interest.php';
			}
		} elseif ( is_archive() ) {
			if ( $theme_file = locate_template( array( 'archive-points-of-interest.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . 'templates/archive-points-of-interest.php';
			}
		}
	}	
	
	return $template_path;
}
add_filter( 'template_include', 'azc_poi_template_include', 1 );


function azc_poi_points_of_interest_list_shortcode($atts, $content = null) {
	$param_count = 0;
	extract(shortcode_atts(array(
		'type' => '',
		'owner' => '',
		'continent' => '',
		'country' => '',
		'region' => '',
		'location' => ''
	), $atts));
    // Preparation of query array to retrieve 20 points_of_interest
    $query_params = array( 'post_type' => 'points-of-interest',
							'post_status' => 'publish',
							'posts_per_page' => 3,
							);
	
	if (strlen($type) > 0){
		$meta_key = 'point-of-interest-type';
		$meta_value = $type;
		$meta_query_type = array(
								'key' => 'point-of-interest-type',
								'value' => $type,
								'compare' => 'LIKE'
								);
		$param_count++;
	}
	
	if (strlen($owner) > 0){
		$meta_key = 'point-of-interest-owner';
		$meta_value = $owner;
		$meta_query_owner = array(
								'key' => 'point-of-interest-owner',
								'value' => $owner,
								'compare' => 'LIKE'
								);
		$param_count++;
	}
	
	if (strlen($continent) > 0){
		$meta_key = 'point-of-interest-continent';
		$meta_value = $continent;
		$meta_query_continent = array(
									'key' => 'point-of-interest-continent',
									'value' => $continent,
									'compare' => 'LIKE'
									);
		$param_count++;
	}
	
	if (strlen($country) > 0){
		$meta_key = 'point-of-interest-country';
		$meta_value = $country;
		$meta_query_country = array(
									'key' => 'point-of-interest-country',
									'value' => $country,
									'compare' => 'LIKE'
									);
		$param_count++;
	}
	
	if (strlen($region) > 0){
		$meta_key = 'point-of-interest-region';
		$meta_value = $region;
		$meta_query_region = array(
									'key' => 'point-of-interest-region',
									'value' => $region,
									'compare' => 'LIKE'
									);
		$param_count++;
	}
	
	if (strlen($location) > 0){
		$meta_key = 'point-of-interest-location';
		$meta_value = $location;
		$meta_query_location = array(
									'key' => 'point-of-interest-location',
									'value' => $location,
									'compare' => 'LIKE'
									);
		$param_count++;
	}
	
	if ($param_count == 1){
		$query_params['meta_key'] = $meta_key;
		$query_params['meta_value'] = $meta_value;
	}elseif ($param_count > 0){
		$query_params['meta_query'] = array( 
											'relation' => 'AND'
											,$meta_query_type
											,$meta_query_owner
											,$meta_query_continent
											,$meta_query_country
											,$meta_query_region
											,$meta_query_location
											);
	}
	
	// Retrieve page query variable, if present
	$page_num = ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
	// If page number is higher than 1, add to query array
	if ( $page_num != 1 ) {
		$query_params['paged'] = $page_num;
	}

	// Execution of post query
    $points_of_interest_query = new WP_Query;
    $points_of_interest_query->query( $query_params );
	
	// Check if any posts were returned by the query
    if ( $points_of_interest_query->have_posts() ) {
        // Display posts in table layout
        $output = '<table>';
		$output .= '<tr><th style="width: 350px"><strong>' . __('Name', 'azc_poi') . '</strong></th>';
        $output .= '<th><strong>' . __('Location', 'azc_poi') . '</strong></th></tr>';
		// Cycle through all items retrieved
        while ( $points_of_interest_query->have_posts() ) {
            $points_of_interest_query->the_post();
			$output .= '<tr><td><a href="' . post_permalink() . '">' . get_the_title( get_the_ID() ) . '</a></td>';
			
			$assigned_type = wp_get_post_terms( get_the_ID(), 'point-of-interest-type' );
			$output .= '<td>' . esc_html( $assigned_type[0]->name ) . '</td></tr>';
            
		}
		$output .= '</table>';
		// Display page navigation links
		if ( $points_of_interest_query->max_num_pages > 1 ) {
			$output .= '<nav id="nav-below">';
			$output .= '<div class="nav-previous" style="display: inline;">';
			$output .= get_previous_posts_link ( '<span class="meta-nav">&larr;</span> '. __('Previous Points of Interest', 'azc_poi'), $points_of_interest_query->max_num_pages );
			$output .= '</div>';
			$output .= '<div class="nav-next" style="display: inline; float: right;">';
			$output .= get_next_posts_link ( __('Next Points of Interest', 'azc_poi') . ' <span class="meta-nav">&rarr;</span>', $points_of_interest_query->max_num_pages );
			$output .= '</div>';
			$output .= '</nav>';
		}
		
		// Reset post data query
		wp_reset_postdata();
    } 
	return $output; 
}
add_shortcode( 'poi-list', 'azc_poi_points_of_interest_list_shortcode' );

// type
function azc_poi_points_of_interest_create_type_taxonomy() {
$labels = array(
		'name'              => __( 'Point of Interest Types', 'azc_poi' ),
		'singular_name'     => __( 'Point of Interest Type', 'azc_poi' ),
		'search_items'      => __( 'Search Point of Interest Types', 'azc_poi' ),
		'all_items'         => __( 'All Point of Interest Types', 'azc_poi' ),
		'parent_item'       => __( 'Parent Point of Interest Type', 'azc_poi' ),
		'parent_item_colon' => __( 'Parent Point of Interest Type:', 'azc_poi' ),
		'edit_item'         => __( 'Edit Point of Interest Type', 'azc_poi' ),
		'update_item'       => __( 'Update Point of Interest Type', 'azc_poi' ),
		'add_new_item'      => __( 'Add New Point of Interest Type', 'azc_poi' ),
		'new_item_name'     => __( 'New Point of Interest Type Name', 'azc_poi' ),
		'menu_name'         => __( 'Point of Interest Type', 'azc_poi' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false, //setting to false hides editor from custom post editor
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'point-of-interest-type' ),
	);

	register_taxonomy( 'point-of-interest-type', array( 'points-of-interest' ), $args );

}
add_action( 'init', 'azc_poi_points_of_interest_create_type_taxonomy', 0 );

function azc_poi_add_point_of_interest_type_item() {
    global $submenu;
	
    $submenu['edit.php?post_type=points-of-interest'][501] = array(
															__('Point of Interest Types', 'azc_poi'),
															'manage_options',
															admin_url( '/edit-tags.php?taxonomy=point-of-interest-type' )
														);
} 
add_action( 'admin_menu', 'azc_poi_add_point_of_interest_type_item' );


// owner
function azc_poi_points_of_interest_create_owner_taxonomy() {
$labels = array(
		'name'              => __( 'Point of Interest Owners', 'azc_poi' ),
		'singular_name'     => __( 'Point of Interest Owner', 'azc_poi' ),
		'search_items'      => __( 'Search Point of Interest Owners', 'azc_poi' ),
		'all_items'         => __( 'All Point of Interest Owners', 'azc_poi' ),
		'parent_item'       => __( 'Parent Point of Interest Owner', 'azc_poi' ),
		'parent_item_colon' => __( 'Parent Point of Interest Owner:', 'azc_poi' ),
		'edit_item'         => __( 'Edit Point of Interest Owner', 'azc_poi' ),
		'update_item'       => __( 'Update Point of Interest Owner', 'azc_poi' ),
		'add_new_item'      => __( 'Add New Point of Interest Owner', 'azc_poi' ),
		'new_item_name'     => __( 'New Point of Interest Owner Name', 'azc_poi' ),
		'menu_name'         => __( 'Point of Interest Owner', 'azc_poi' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false, //setting to false hides editor from custom post editor
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'point-of-interest-owner' ),
	);

	register_taxonomy( 'point-of-interest-owner', array( 'points-of-interest' ), $args );

}
add_action( 'init', 'azc_poi_points_of_interest_create_owner_taxonomy', 0 );

function azc_poi_add_point_of_interest_owner_item() {
    global $submenu;
	
    $submenu['edit.php?post_type=points-of-interest'][502] = array(
															__('Point of Interest Owners', 'azc_poi'),
															'manage_options',
															admin_url( '/edit-tags.php?taxonomy=point-of-interest-owner' )
														);
} 
add_action( 'admin_menu', 'azc_poi_add_point_of_interest_owner_item' );


// continent
function azc_poi_points_of_interest_create_continent_taxonomy() {
$labels = array(
		'name'              => __( 'Point of Interest Continents', 'azc_poi' ),
		'singular_name'     => __( 'Point of Interest Continent', 'azc_poi' ),
		'search_items'      => __( 'Search Point of Interest Continents', 'azc_poi' ),
		'all_items'         => __( 'All Point of Interest Continents', 'azc_poi' ),
		'parent_item'       => __( 'Parent Point of Interest Continent', 'azc_poi' ),
		'parent_item_colon' => __( 'Parent Point of Interest Continent:', 'azc_poi' ),
		'edit_item'         => __( 'Edit Point of Interest Continent', 'azc_poi' ),
		'update_item'       => __( 'Update Point of Interest Continent', 'azc_poi' ),
		'add_new_item'      => __( 'Add New Point of Interest Continent', 'azc_poi' ),
		'new_item_name'     => __( 'New Point of Interest Continent Name', 'azc_poi' ),
		'menu_name'         => __( 'Point of Interest Continent', 'azc_poi' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false, //setting to false hides editor from custom post editor
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'point-of-interest-continent' ),
	);

	register_taxonomy( 'point-of-interest-continent', array( 'points-of-interest' ), $args );

}
add_action( 'init', 'azc_poi_points_of_interest_create_continent_taxonomy', 0 );

function azc_poi_add_point_of_interest_continent_item() {
    global $submenu;
	
    $submenu['edit.php?post_type=points-of-interest'][503] = array(
															__('Point of Interest Continents', 'azc_poi'),
															'manage_options',
															admin_url( '/edit-tags.php?taxonomy=point-of-interest-continent' )
														);
} 
add_action( 'admin_menu', 'azc_poi_add_point_of_interest_continent_item' );

// country
function azc_poi_points_of_interest_create_country_taxonomy() {
$labels = array(
		'name'              => __( 'Point of Interest Countries', 'azc_poi' ),
		'singular_name'     => __( 'Point of Interest Country', 'azc_poi' ),
		'search_items'      => __( 'Search Point of Interest Countries', 'azc_poi' ),
		'all_items'         => __( 'All Point of Interest Countries', 'azc_poi' ),
		'parent_item'       => __( 'Parent Point of Interest Country', 'azc_poi' ),
		'parent_item_colon' => __( 'Parent Point of Interest Country:', 'azc_poi' ),
		'edit_item'         => __( 'Edit Point of Interest Country', 'azc_poi' ),
		'update_item'       => __( 'Update Point of Interest Country', 'azc_poi' ),
		'add_new_item'      => __( 'Add New Point of Interest Country', 'azc_poi' ),
		'new_item_name'     => __( 'New Point of Interest Country Name', 'azc_poi' ),
		'menu_name'         => __( 'Point of Interest Country', 'azc_poi' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false, //setting to false hides editor from custom post editor
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'point-of-interest-country' ),
	);

	register_taxonomy( 'point-of-interest-country', array( 'points-of-interest' ), $args );

}
add_action( 'init', 'azc_poi_points_of_interest_create_country_taxonomy', 0 );

function azc_poi_add_point_of_interest_country_item() {
    global $submenu;
	
    $submenu['edit.php?post_type=points-of-interest'][504] = array(
															__('Point of Interest Countries', 'azc_poi'),
															'manage_options',
															admin_url( '/edit-tags.php?taxonomy=point-of-interest-country' )
														);
} 
add_action( 'admin_menu', 'azc_poi_add_point_of_interest_country_item' );


// region
function azc_poi_points_of_interest_create_region_taxonomy() {
$labels = array(
		'name'              => __( 'Point of Interest Regions', 'azc_poi' ),
		'singular_name'     => __( 'Point of Interest Region', 'azc_poi' ),
		'search_items'      => __( 'Search Point of Interest Regions', 'azc_poi' ),
		'all_items'         => __( 'All Point of Interest Regions', 'azc_poi' ),
		'parent_item'       => __( 'Parent Point of Interest Region', 'azc_poi' ),
		'parent_item_colon' => __( 'Parent Point of Interest Region:', 'azc_poi' ),
		'edit_item'         => __( 'Edit Point of Interest Region', 'azc_poi' ),
		'update_item'       => __( 'Update Point of Interest Region', 'azc_poi' ),
		'add_new_item'      => __( 'Add New Point of Interest Region', 'azc_poi' ),
		'new_item_name'     => __( 'New Point of Interest Region Name', 'azc_poi' ),
		'menu_name'         => __( 'Point of Interest Region', 'azc_poi' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false, //setting to false hides editor from custom post editor
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'point-of-interest-region' ),
	);

	register_taxonomy( 'point-of-interest-region', array( 'points-of-interest' ), $args );

}
add_action( 'init', 'azc_poi_points_of_interest_create_region_taxonomy', 0 );

function azc_poi_add_point_of_interest_region_item() {
    global $submenu;
	
    $submenu['edit.php?post_type=points-of-interest'][505] = array(
															__('Point of Interest Regions', 'azc_poi'),
															'manage_options',
															admin_url( '/edit-tags.php?taxonomy=point-of-interest-region' )
														);
} 
add_action( 'admin_menu', 'azc_poi_add_point_of_interest_region_item' );


function azc_poi_points_of_interest_custom_post_add_columns( $columns ) {
	$columns['point-of-interest-type'] = __('Type', 'azc_poi');
	$columns['point-of-interest-owner'] = __('Owner', 'azc_poi');
	$columns['point-of-interest-continent'] = __('Continent', 'azc_poi');
	$columns['point-of-interest-country'] = __('Country', 'azc_poi');
	$columns['point-of-interest-region'] = __('Region', 'azc_poi');
	unset( $columns['comments'] );

	return $columns;
}
add_filter( 'azc_poi_manage_edit-points_of_interest_columns', 'azc_poi_points_of_interest_custom_post_add_columns' );

function azc_poi_points_of_interest_custom_post_populate_columns( $column ) {
	// Check column name and send back appropriate data
	if ( 'point-of-interest-type' == $column ) {
		$point_of_interest_type = wp_get_post_terms( get_the_ID(), 'point-of-interest-type' );
		if ( $point_of_interest_type ){
			echo $point_of_interest_type[0]->name;
		}else{
			_e('None Assigned', 'azc_poi');
		}
	} elseif ( 'point-of-interest-owner' == $column ) {
		$point_of_interest_owner = wp_get_post_terms( get_the_ID(), 'point-of-interest-owner' );
		if ( $point_of_interest_owner ){
			echo $point_of_interest_owner[0]->name;
		}else{
			_e('None Assigned', 'azc_poi');
		}
	} elseif ( 'point-of-interest-continent' == $column ) {
		$point_of_interest_continent = wp_get_post_terms( get_the_ID(), 'point-of-interest-continent' );
		if ( $point_of_interest_continent ){
			echo $point_of_interest_continent[0]->name;
		}else{
			_e('None Assigned', 'azc_poi');
		}
	} elseif ( 'point-of-interest-country' == $column ) {
		$point_of_interest_country = wp_get_post_terms( get_the_ID(), 'point-of-interest-country' );
		if ( $point_of_interest_country ){
			echo $point_of_interest_country[0]->name;
		}else{
			_e('None Assigned', 'azc_poi');
		}
	} elseif ( 'point-of-interest-region' == $column ) {
		$point_of_interest_region = wp_get_post_terms( get_the_ID(), 'point-of-interest-region' );
		if ( $point_of_interest_region ){
			echo $point_of_interest_region[0]->name;
		}else{
			_e('None Assigned', 'azc_poi');
		}
	}
}
add_action( 'manage_posts_custom_column', 'azc_poi_points_of_interest_custom_post_populate_columns' );

function azc_poi_points_of_interest_column_sortable( $columns ) {
	$columns['point-of-interest-type'] = 'point-of-interest-type';
	$columns['point-of-interest-owner'] = 'point-of-interest-owner';
	$columns['point-of-interest-continent'] = 'point-of-interest-continent';
	$columns['point-of-interest-country'] = 'point-of-interest-country';
	$columns['point-of-interest-region'] = 'point-of-interest-region';

	return $columns;
}
add_filter( 'manage_edit-points_of_interest_sortable_columns', 'azc_poi_points_of_interest_column_sortable' );

function azc_poi_points_of_interest_column_ordering( $vars ) {
    if ( !is_admin() )
        return $vars;
    
	if ( isset( $vars['orderby'] ) && 'point-of-interest-type' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
				'meta_key' => 'point-of-interest-type',
				'orderby' => 'meta_value'
		) );
	} elseif ( isset( $vars['orderby'] ) && 'point-of-interest-owner' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
				'meta_key' => 'point-of-interest-owner',
				'orderby' => 'meta_value_num' ) );
	} elseif ( isset( $vars['orderby'] ) && 'point-of-interest-continent' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
				'meta_key' => 'point-of-interest-continent',
				'orderby' => 'meta_value_num' ) );
	} elseif ( isset( $vars['orderby'] ) && 'point-of-interest-country' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
				'meta_key' => 'point-of-interest-country',
				'orderby' => 'meta_value_num' ) );
	} elseif ( isset( $vars['orderby'] ) && 'point-of-interest-region' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
				'meta_key' => 'point-of-interest-region',
				'orderby' => 'meta_value_num' ) );
	}

	return $vars;
}
add_filter( 'request', 'azc_poi_points_of_interest_column_ordering' );

function azc_poi_point_of_interest_type_filter_list() {
	$screen = get_current_screen();
	global $wp_query;
	if ( $screen->post_type == 'points-of-interest' ) {
		wp_dropdown_categories( array(
			'show_option_all'	=>  'Show All Point of Interest Types',
			'taxonomy'			=>  'point-of-interest-type',
			'name'				=>  'point-of-interest-type',
			'orderby'			=>  'name',
			'selected'			=>  ( isset($wp_query->query['point-of-interest-type']) ?
$wp_query->query['point-of-interest-type'] : ''),
			'hierarchical'		=>  false,
			'depth'				=>  3,
			'show_count'		=>  false,
			'hide_empty'		=>  true,
		) );
	}
}
add_action( 'restrict_manage_posts', 'azc_poi_point_of_interest_type_filter_list' );

function azc_poi_point_of_interest_type_filtering( $query ) {
	$qv = &$query->query_vars;

	if ( !empty( $qv['point-of-interest-type'] ) && is_numeric( $qv['point-of-interest-type'] ) ) {
			$term = get_term_by( 'id', $qv['point-of-interest-type'], 'point-of-interest-type' );
			$qv['point-of-interest-type'] = $term->slug;
    }
}
add_filter( 'parse_query', 'azc_poi_point_of_interest_type_filtering' );

?>