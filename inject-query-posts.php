<?php
/**
 * @package Inject_Query_Posts
 * @author Scott Reilly
 * @version 2.0.5
 */
/*
Plugin Name: Inject Query Posts
Version: 2.0.5
Plugin URI: http://coffee2code.com/wp-plugins/inject-query-posts/
Author: Scott Reilly
Author URI: http://coffee2code.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Description: Inject an array of posts into a WP query object as if queried, particularly useful to allow use of standard template tags.

Compatible with WordPress 2.3 through 3.4+.

NOTE: Injecting posts into a query object will cause that object to forget about previous posts it may have retrieved.  You probably
only want to do this outside of any existing loops, and create your own custom loop after the injection.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/inject-query-posts/

*/

/*
	Copyright (c) 2008-2012 by Scott Reilly (aka coffee2code)

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

if ( ! function_exists( 'inject_query_posts' ) ) :
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
 * @param array $posts Array of posts to inject into the query object.
 * @param array $config (optional) Associative array of query object variables to directly set, and their values.
 * @param WP_Query|null $query_obj (optional) The query object to modify. If null, then the global wp_query object will be used. Pass a string or non-zero integer to have a new query object created and used.
 * @param bool $preserve_query_obj (optional) Should the query object be re-initialized (i.e. reset) prior to injecting posts?
 * @return array The originally passed in array of posts.
 */
function inject_query_posts( $posts, $config = array(), $query_obj = null, $preserve_query_obj = true ) {
	$posts = (array) $posts;
	$preserve_query_obj = apply_filters( 'inject_query_posts_preserve_query_obj', $preserve_query_obj, $query_obj );

	if ( ! $query_obj ) {
		global $wp_query;
		$query_obj = $wp_query;
	}

	if ( ! is_object( $query_obj ) )
		$query_obj = new WP_Query();

	// Initialize the query object
	if ( ! $preserve_query_obj ) {
		// From WP 2.9.1 - 3.1, these object variables are not resettable except directly
		$query_obj->post = '';
		$query_obj->request = '';
		$query_obj->found_posts = 0;
		$query_obj->max_num_pages = 0;
		$query_obj->comments = '';
		$query_obj->comment_count = 0;
		$query_obj->current_comment = -1;
		$query_obj->comment = '';
		$query_obj->max_num_pages = 0;
		$query_obj->max_num_comment_pages = 0;
		$query_obj->is_preview = false;
		$query_obj->is_comments_popup = false;

		if ( isset( $config['query'] ) )
			$query_obj->parse_query( $config['query'] ); // This calls init() itself, so no need to do it here
		else
			$query_obj->init();
	}

	foreach ( (array) $config as $key => $value ) {
		if ( 'query' == $key )
			continue;
		$query_obj->$key = $value;
	}

	// Load the posts into the query object
	$query_obj->posts = $posts;
	update_post_caches( $posts );
	$query_obj->post_count = count( $posts );
	if ( $query_obj->post_count > 0 ) {
		$query_obj->post = $posts[0];
		$query_obj->found_posts = $query_obj->post_count;
	}

	if ( ! isset( $config['is_404'] ) ) // Unless explicitly told to be a 404, don't be a 404
		$query_obj->is_404 = false;

	$wp_query = $query_obj; // This only has effect if $wp_query was previously declared global
	return $posts;
}
endif;
