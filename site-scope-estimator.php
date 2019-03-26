<?php
/**
 * Plugin Name: Site Scope Estimator
 * Plugin URI:  https://example.com/plugins/the-basics/
 * Description: Quickly see the scope of work
 * Version:     1.0
 * Author:      Peter Lanier
 * Author URI:  https://author.example.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wporg
 * Domain Path: /languages
 */
 /*
	{Plugin Name} is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.
	 
	{Plugin Name} is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	 
	You should have received a copy of the GNU General Public License
	along with {Plugin Name}. If not, see {License URI}.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$publish_sum;
$draft_sum;
$trash_sum;

function display_post_types(){
	// CUSTOM POST TYPES
	$cust_args = array(
	   'public'   => true,
	   '_builtin' => false
	);
	
	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	
	
	$post_types = get_post_types( $cust_args, $output, $operator );
	
	foreach ( $post_types  as $post_type ) {
	   echo '<tr>';
	   echo '<td>';
	   echo $post_type;
	   echo '</td>';
	   echo '<td>';
	   echo wp_count_posts( $post_type )->publish;
	   $publish_sum += wp_count_posts( $post_type )->publish;
	   echo '</td>';
	   echo '<td>';
	   echo wp_count_posts( $post_type )->draft;
	   $draft_sum += wp_count_posts( $post_type )->draft;
	   echo '</td>';
	   echo '<td>';
	   echo wp_count_posts( $post_type )->trash;
	   $trash_sum += wp_count_posts( $post_type )->trash;
	   echo '</td>';
	   echo '</tr>';
	}
	
	//BUILT-INS
	$builtin_args = array(
	   'public'   => true,
	   '_builtin' => true
	);
	
	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	
	
	$post_types = get_post_types( $builtin_args, $output, $operator );
	
	foreach ( $post_types  as $post_type ) {
	   echo '<tr>';
	   echo '<td>';
	   echo $post_type;
	   echo '</td>';
	   echo '<td>';
	   echo wp_count_posts( $post_type )->publish;
	   $publish_sum += wp_count_posts( $post_type )->publish;
	   echo '</td>';
	   echo '<td>';
	   echo wp_count_posts( $post_type )->draft;
	   $draft_sum += wp_count_posts( $post_type )->draft;
	   echo '</td>';
	   echo '<td>';
	   echo wp_count_posts( $post_type )->trash;
	   $trash_sum += wp_count_posts( $post_type )->trash;
	   echo '</td>';
	   echo '</tr>';
	}
	
	echo '<tr>';
	echo '<td>';
	echo 'TOTALS';
	echo '</td>';
	echo '<td>';
	echo $publish_sum;
	echo '</td>';
	echo '<td>';
	echo $draft_sum;
	echo '</td>';
	echo '<td>';
	echo $trash_sum;
	echo '</td>';
	echo '</tr>';
}


// Admin
$parent_slug = 'fb_scope.php';
$page_title = 'Site Scope';
$menu_title = 'Site Scope';
$capability = 'site_scope';
$menu_slug = 'site_scope';

function wpdocs_my_plugin_menu() {
    add_options_page( 
        __( 'Site Scope', 'textdomain' ),
        __( 'Site Scope', 'textdomain' ),
        'manage_options',
        'my-plugin.php',
        'my_plugin_page'
    );
}
add_action('admin_menu', 'wpdocs_my_plugin_menu');

function my_plugin_page(){
	?>
	<div class="wrap">
		<h2>Site Scope</h2>
		<table>
			<tr>
				<th>Post Types</th>
				<th>Published</th>
				<th>Drafts</th>
				<th>Trash</th>
			</tr>
		<?php display_post_types() ?>
		</table>
		<table></table>
	</div>
	<?php
}

/*
// ACTIVATION HOOKS
function pluginprefix_setup_post_type() {
    // register the "book" custom post type
    register_post_type( 'book', ['public' => 'true'] );
}
add_action( 'init', 'pluginprefix_setup_post_type' );
 
function pluginprefix_install() {
    // trigger our function that registers the custom post type
    pluginprefix_setup_post_type();
 
    // clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pluginprefix_install' );

// DEACTIVATION HOOKS
function pluginprefix_deactivation() {
    // unregister the post type, so the rules are no longer in memory
    unregister_post_type( 'book' );
    // clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivation' );
*/