<?php
/*
Plugin Name: Inject Query Posts
Version: 1.0
Plugin URI: http://coffee2code.com/wp-plugins/inject-query-posts
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Inject an array of posts into a WP query object as if queried, particularly useful to allow use of standard template tags.

WordPress's template tags are intended to be used within 'the loop'.  The loop is managed by a WP_Query object which sets up various
global variables and its own object variables for use by the various template tags.  The primary purpose of a WP_Query object is to
actually query the database for the posts that match the currently specified criteria.  However, if you don't need to query for posts
since you already have them by some other means, you can still take advantage of the template tags by injecting those posts into the
WP_Query via this plugin.

Depending on the template tags you are looking to use, or the logic you are hoping to employ within a loop, you may need to manually
configure some of the query object's variables.

Example:

<?php
// Say we're in the sidebar

// We've gotten some post object on our own.
$posts = c2c_get_random_posts(5, '');
// Inject the posts
inject_query_posts($posts);
// Now let's display them via template tags:
if (have_posts()) :
    while (have_posts()) : the_post(); ?>

        <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile;?>
<?php endif; ?>

Compatible with WordPress 2.3+, 2.5+, 2.6+, 2.7+

NOTE: Injecting posts into a query object will cause that object to forget about previous posts it may have retrieved.  You probably
only want to do this outside of any existing loops, and create your own custom loop after the injection.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/inject-query-posts.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use the inject_query_posts() function to inject an array of posts into a WP query object.  Specify the posts array as the
first argument.  Configure the query object by passing an array as the second argument.  If specifying a WP query object, pass
it as the third object; if not specified then the global wp_query object will be assumed.

*/

/*
Copyright (c) 2008-2009 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

function inject_query_posts( $posts, $config = array(), $query_obj = null ) {
	$posts = (array) $posts;
	if ( !$query_obj ) {
		global $wp_query;
		$query_obj = new WP_Query();
	}
	// Initialize the query object
	if ( isset($config['query']) )
		$query_obj->parse_query($config['query']); // This calls init() itself, so no need to do it here
	else
		$query_obj->init();
	foreach ( $config as $key => $value ) {
		if ( 'query' == $key ) continue;
		$query_obj->$key = $value;
	}
	// Load the posts into the query object
	$query_obj->posts = $posts;
	update_post_caches($posts);
	$query_obj->post_count = count($posts);
	if ( $query_obj->post_count > 0 ) {
		$query_obj->post = $posts[0];
		$query_obj->found_posts = $query_obj->post_count;
	}
	if ( !isset($config['is_404']) ) // Unless explicitly told to be a 404, don't be a 404
		$query_obj->is_404 = false;
	$wp_query = $query_obj; // This only has any effect if wp_query was previously declared as global
	return $posts;
}

?>