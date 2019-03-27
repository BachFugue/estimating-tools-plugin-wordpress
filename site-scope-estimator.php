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



function display_post_types(){
	$publish_sum;
	$draft_sum;
	$trash_sum;
	$type_count = 1;
	
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
	   echo $type_count;
	   echo '</td>';
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
	   $type_count++;
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
	   echo $type_count;
	   echo '</td>';
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
	   $type_count++;
	}
	
	echo '<tr>';
	echo '<td colspan="2">';
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

function display_taxonomies(){

	// built-in taxonomy
	$builtin_args = array(
	  'public'   => true,
	  '_builtin' => true
	  
	); 
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies( $builtin_args, $output, $operator ); 
	if ( $taxonomies ) {
		
		foreach ( $taxonomies  as $taxonomy ) {
		    echo '<h2>' . ucfirst ($taxonomy) . '</h2>';		
			$terms_args = array(
			    'taxonomy' => $taxonomy,
			    'hide_empty' => true,
			    'orderby' => 'name',
			    'order' => 'ASC',
			);
			$terms = get_terms($terms_args);
			if( $terms ){
			    echo '<table id="scope">';
			    echo '<tr>';
			    echo '<th>';
			    echo 'Terms';
			    echo '</th>';
			    echo '<th>';
			    echo 'Count';
			    echo '</th>';
				echo '</tr>';			
			    //display all the top-level categories first
			    foreach ($terms as $term) {
			        if( !$term->parent ){
			            echo '<tr>';
			            echo '<td>';
			            echo '<a href="' . esc_url( get_term_link( $term->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $term->name ) ) . '" >' . $term->name.'</a>';
			            echo '</td>';
			            echo '<td>';
			            echo $term->count;
			            echo '</td>';
			            echo '<tr>';
			            
			            $child_args = array(
						    'taxonomy' => $taxonomy,
						    'child_of' => $term->term_id,
						    'hide_empty' => true,
						    'orderby' => 'name',
						    'order' => 'ASC',
						);
						$children = get_terms($child_args);
						if( $children ){
							foreach ($children as $child) {
					            echo '<tr>';
					            echo '<td style="padding-left:40px;">';
// 					            echo '<div style="height:50px; width:50px; background: salmon; display: inline-block;" ></div>';
					            echo '<a href="' . esc_url( get_term_link( $child->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $child->name ) ) . '" >' . $child->name.'</a>';
					            echo '</td>';
					            echo '<td>';
					            echo $child->count;
					            echo '</td>';
					            echo '<tr>';
							}
						}
						
			        }
			    }
			
			    echo '</table>';
			}
		}
	}
	
	//custom taxonomy
	$builtin_args = array(
	  'public'   => true,
	  '_builtin' => false
	  
	); 
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies( $builtin_args, $output, $operator ); 
	if ( $taxonomies ) {
		
		foreach ( $taxonomies  as $taxonomy ) {
		    echo '<h2>' . ucfirst ($taxonomy) . '</h2>';		
			$terms_args = array(
			    'taxonomy' => $taxonomy,
			    'hide_empty' => true,
			    'orderby' => 'name',
			    'order' => 'ASC',
			);
			$terms = get_terms($terms_args);
			if( $terms ){
			    echo '<table id="scope">';
			    echo '<tr>';
			    echo '<th>';
			    echo 'Name';
			    echo '</th>';
			    echo '<th>';
			    echo 'Count';
			    echo '</th>';
				echo '</tr>';
			
			    //display all the top-level categories first
			    foreach ($terms as $term) {
			        if( !$term->parent ){
			            echo '<tr>';
			            echo '<td>';
			            echo '<a href="' . esc_url( get_term_link( $term->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $term->name ) ) . '" >' . $term->name.'</a>';
			            echo '</td>';
			            echo '<td>';
			            echo $term->count;
			            echo '</td>';
			            echo '<tr>';
			         
			         	$child_args = array(
						    'taxonomy' => $taxonomy,
						    'child_of' => $term->term_id,
						    'hide_empty' => true,
						    'orderby' => 'name',
						    'order' => 'ASC',
						);
						$children = get_terms($child_args);
						if( $children ){
							foreach ($children as $child) {
					            echo '<tr>';
					            echo '<td style="padding-left:40px;">';
					            echo '<a href="' . esc_url( get_term_link( $child->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $child->name ) ) . '" >' . $child->name.'</a>';
					            echo '</td>';
					            echo '<td>';
					            echo $child->count;
					            echo '</td>';
					            echo '<tr>';
							}
						}   
			            
			        }
			    }
			
			    //now, display all the child categories
			    foreach ($terms as $term) {
			        if( $term->parent ){
			            $output .= '<a href="' . esc_url( get_term_link( $term->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $term->name ) ) . '" >' . $term->name.'</a>,';
			        }
			    }
			
			    echo '</table>';
			}
		}
	}
	
// 	$taxonomies = get_taxonomies( $args, $output, $operator );
	

	
/*
	echo '<pre>';
	var_dump($terms);
	echo '</pre>';
*/
}




function my_plugin_page(){
	?>
	<div class="wrap">
		<h2>Site Scope</h2>
		<table id="scope">
			<tr>
				<th colspan="2">Post Types</th>
				<th>Published</th>
				<th>Drafts</th>
				<th>Trash</th>
			</tr>
		<?php display_post_types() ?>
		</table>
		<table>
		<?php display_taxonomies() ?>
		</table>
	</div>
	<style>
		#scope {
		  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}
		
		#scope td, #scope th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}
		
		#scope tr:nth-child(even){background-color: #f2f2f2;}
		
		#scope tr:hover {background-color: #ddd;}
		
		#scope th {
		  padding-top: 12px;
		  padding-bottom: 12px;
		  text-align: left;
		  background-color: #0073AA;
		  color: white;
		}
	</style>
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