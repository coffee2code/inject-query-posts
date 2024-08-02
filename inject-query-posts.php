<?php
/**
 * Plugin Name: Inject Query Posts
 * Version:     3.0.4
 * Plugin URI:  https://coffee2code.com/wp-plugins/inject-query-posts/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: inject-query-posts
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Facilitates injecting an array of posts into a WP query object as if queried. Particularly useful to allow use of standard template tags.
 *
 * Compatible with WordPress 3.6 through 6.6+.
 *
 * NOTE: Injecting posts into a query object will cause that object to forget about previous posts it may have retrieved. You probably
 * only want to do this outside of any existing loops, and create your own custom loop after the injection.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/inject-query-posts/
 *
 * @package Inject_Query_Posts
 * @author  Scott Reilly
 * @version 3.0.4
 */

/*
	Copyright (c) 2008-2024 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


defined( 'ABSPATH' ) or die();


if ( ! function_exists( 'c2c_inject_query_posts' ) ) :
/**
 * Injects an array of posts into a query object as if that query object had
 * obtained those posts via a query.
 *
 * NOTE: If you wish to fully simulate a query despite the fact that the query
 * object is not performing the actual database query, you must specify the
 * a query via $config (i.e. array('query'=>'cat=5')) and/or directly set
 * query object variables via $config.
 *
 * NOTE: By default, the function assumes the posts being injected will
 * invalidate any existing state in the query object, so that query object will
 * be reset prior to post inject.  Set $preserve_query_obj to true to retain
 * the state of the query object.
 *
 * NOTE: In order for 'query' to be configured via $config, $preserve_query_obj
 * must be false.
 *
 * @param array $posts Array of posts to inject into the
 *                     query object.
 * @param array $args  {
 *     Associative array of configuration options.
 *
 *     @type array         $config             Optional. Associative array of query
 *                                             object variables to directly set, and
 *                                             their values. Default [].
 *     @type WP_Query|null $query_obj          Optional. The query object to modify.
 *                                             If null, then the global wp_query
 *                                             object will be used. Pass a string or
 *                                             non-zero integer to have a new query
 *                                             object created and used. Default null.
 *     @type bool          $preserve_query_obj Optional. Should the query object be
 *                                             kept as-is prior to injecting posts?
 *                                             If false, then the object is
 *                                             re-initialized/reset. Default false.
 *     @type bool          $cache_posts        Optional. Update the posts in cache?
 *                                             Default false.
 * }
 * @return array        The originally passed in array of posts.
 */
function c2c_inject_query_posts( $posts, $args = array() ) {
	// Default config array options. Order matters for first 4 items.
	$defaults = array(
		'config'             => array(),
		'query_obj'          => null,
		'preserve_query_obj' => false,
		'cache_posts'        => true,
	);

	// Convert legacy argument invocation into args array.
	if (
		// More than 2 args.
		func_num_args() > 2
	||
		// Exactly 2 args but second arg doesn't set any configurable values.
		( $args && ! array_intersect_key( $args, $defaults ) )
	) {
		$func_args = func_get_args();
		$args = array();
		foreach ( array_keys( $defaults ) as $i => $key ) {
			// Offset by 1 since first arg is $posts.
			$j = $i + 1;
			if ( ! empty( $func_args[ $j ] ) ) {
				$args[ $key ] = $func_args[ $j ];
			}
		}
	}

	$args = wp_parse_args( $args, $defaults );

	// Safer version of `extract()`.
	foreach ( array_keys( $defaults ) as $key ) {
		$$key = $args[ $key ];
	}

	$posts = is_array( $posts ) ? $posts : array( $posts );

	if ( ! $query_obj ) {
		global $wp_query;
		$query_obj = $wp_query;
	}

	if ( ! is_object( $query_obj ) ) {
		$query_obj = new WP_Query();
	}

	$query_obj->current_post = -1;

	/**
	 * Filters if the query object should be kept as-is prior to injecting posts.
	 *
	 * If the query object is not preserved, it will be reinitialized.
	 *
	 * @since 2.0
	 *
	 * @param bool     $preserve  Preserve the current state of the query object?
	 *                            Default false.
	 * @param WP_Query $query_obj The query object to modify.
	 * @param array    $posts     Posts to be injected into the query object.
	 * @param array    $config    Associative array of query object variables to
	 *                            directly set, and their values.
	 */
	$preserve_query_obj = (bool) apply_filters( 'inject_query_posts_preserve_query_obj', (bool) $preserve_query_obj, $query_obj, $posts, $config );

	// Initialize the query object
	if ( ! $preserve_query_obj ) {
		if ( isset( $config['query'] ) ) {
			$query_obj->parse_query( $config['query'] ); // This calls init() itself, so no need to do it here
		} else {
			$query_obj->init();
		}
	}

	// Prevent the override of certain WP_Query object variables via $config.
	foreach ( (array) $config as $key => $value ) {
		if ( in_array( $key, array( 'query', 'update_post_meta_cache', 'update_post_term_cache' ) ) ) {
			continue;
		}
		$query_obj->$key = $value;
	}

	// Load the posts into the query object
	$query_obj->posts = array_map( 'get_post', $posts );

	// Handle post cache updates.
	if ( $cache_posts ) {
		update_post_caches(
			$query_obj->posts,
			( isset( $config['post_type'] ) ? $config['post_type'] : 'post' ),
			( isset( $config['update_post_term_cache'] ) ? $config['update_post_term_cache'] : true ),
			( isset( $config['update_post_meta_cache'] ) ? $config['update_post_meta_cache'] : true )
		);
	}

	// Set post counts.
	$query_obj->post_count = count( $query_obj->posts );
	if ( $query_obj->post_count > 0 ) {
		$query_obj->post = $query_obj->posts[0];
		$query_obj->found_posts = $query_obj->post_count;
	}

	// Unless explicitly told to be a 404, don't be a 404.
	if ( ! isset( $config['is_404'] ) ) {
		$query_obj->is_404 = false;
	}

	// This only has effect if $wp_query was previously declared global.
	$wp_query = $query_obj;

	return $posts;
}
add_filter( 'c2c_inject_query_posts', 'c2c_inject_query_posts', 10, 5 );
endif;
