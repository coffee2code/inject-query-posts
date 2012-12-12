=== Inject Query Posts ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: wp_query, query, posts, loop, template tags, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 2.3
Tested up to: 3.5
Stable tag: 2.1
Version: 2.1

Inject an array of posts into a WP query object as if queried, particularly useful to allow use of standard template tags.


== Description ==

Inject an array of posts into a WP query object as if queried, particularly useful to allow use of standard template tags.

WordPress's template tags are intended to be used within 'the loop'.  The loop is managed by a WP_Query object which sets up various global variables and its own object variables for use by the various template tags.  The primary purpose of a WP_Query object is to actually query the database for the posts that match the currently specified criteria.  However, if you don't need to query for posts since you already have them by some other means, you can still take advantage of the template tags by injecting those posts into the WP_Query via this plugin.

Depending on the template tags you are looking to use, or the logic you are hoping to employ within a loop, you may need to manually configure some of the query object's variables.

Example:

`
<?php // Say we're in the sidebar

// We've gotten some post object on our own.
$posts = c2c_get_random_posts( 5, '' );

// Inject the posts
c2c_inject_query_posts( $posts );

// Now let's display them via template tags:
if (have_posts()) :
    while (have_posts()) : the_post(); ?>

        <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile;?>
<?php endif; ?>

`

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/inject-query-posts/) | [Plugin Directory Page](http://wordpress.org/extend/plugins/inject-query-posts/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `inject-query-posts.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use the `c2c_inject_query_posts()` function to inject an array of posts into a WP query object.  Specify the posts array as the first argument.  Configure the query object by passing an array as the second argument.  If specifying a WP query object, pass it as the third object; if not specified then the global wp_query object will be assumed.


== Template Tags ==

The plugin provides one template tag for use in your theme templates, functions.php, or plugins.

= Functions =

* `<?php function c2c_inject_query_posts( $posts, $config = array(), $query_obj = null, $preserve_query_obj = true, $cache_posts = true ) ?>`
Injects an array of posts into a query object as if that query object had obtained those posts via a query.

= Arguments =

* `$posts` (array)
Array of posts to inject into the query object.

* `$config` (array)
Optional.  Associative array of query object variables to directly set, and their values.

* `$query_obj` (WP_Query|null)
Optional.  The query object to modify. If null, then the global wp_query object will be used. Pass a string or non-zero integer to have a new query object created and used.

* `$preserve_query_obj` (bool)
Optional.  Should the query object be kept as-is prior to injecting posts? Default is true. If false, then the object is re-initialized/reset.

* `$cache_posts` (bool)
Optional.  Update the posts in cache? Default is true.

= Examples =

* (See Description section for an additional example.)

* Similar to previous example, for WP 3.0+

`
<?php
$posts = c2c_get_random_posts( 5, '' ); // Obtain posts via your favorite related posts, find posts, etc plugin, or via custom query
do_action( 'c2c_inject_query_posts', $posts ); // Better than direct call to c2c_inject_query_posts( $posts );
get_template_part('loop');
?>
`

== Filters ==

The plugin is further customizable via two hooks. Typically, these customizations would be put into your active theme's functions.php file, or used by another plugin.

= inject_query_posts_preserve_query_obj (filter) =

The 'inject_query_posts_preserve_query_obj' filter allows you override the value of the `$preserve_query_obj` argument passed to the function.  This is not typical usage for most users.

Arguments:

* $preserve_query_obj (bool) : Boolean indicating if the query object was set to be preserved or not
* $query_obj (WP_Query object) : The WP_Query object passed to the `c2c_inject_query_posts()`
* $posts (array) : The posts being injected into the WP_Query object
* $config (array) : Query object variables to directly set, and their values.

Example:

`
// Never preserve the condition of the WP_Query object
add_filter( 'inject_query_posts_preserve_query_obj', 'my_preserve_query_obj', 10, 4 );
function my_preserve_query_obj( $preserve_query_obj, $query_obj, $posts, $config ) {
	return false;
}
`

= c2c_inject_query_posts (action) =

The 'c2c_inject_query_posts' filter allows you to use an alternative approach to safely invoke `c2c_inject_query_posts()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.  This only applies if you use the function directly, which is not typical usage for most users.

Arguments:

* The same arguments as `c2c_inject_query_posts()`

Example:

Instead of:

`<?php echo c2c_inject_query_posts( $posts, array( 'is_search' => true ) ); ?>`

Do:

`<?php echo do_action( 'c2c_inject_query_posts', $posts, array( 'is_search' => true ) ); ?>`


== Changelog ==

= 2.1 =
* Rename `inject_query_posts()` to `c2c_inject_query_posts()` (but maintain a deprecated version for backwards compatibility)
* Add filter 'c2c_inject_query_posts' so that users can use the do_action('c2c_inject_query_posts') notation for invoking the function
* Add optional $cache_posts argument to function and use it determine if `update_post_caches()` should be called
* Add better control for specifying arguments to `update_post_caches()`
* Send $posts and $config as additional args to 'inject_query_posts_preserve_query_obj' filter
* Add check to prevent execution of code if file is directly accessed
* Update documentation
* Note compatibility through WP 3.5+
* Update copyright date (2013)

= 2.0.5 =
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

= 2.0.4 =
* Note compatibility through WP 3.2+
* Add link to plugin directory page to readme.txt
* Update copyright date (2012)

= 2.0.3 =
* Remove unnecessary reset of meta_query and tax_query query object variables

= 2.0.2 =
* Note compatibility through WP 3.2+
* Minor code formatting changes (spacing)
* Fix plugin homepage and author links in description in readme.txt

= 2.0.1 =
* Note compatibility through WP 3.1+
* Update copyright date (2011)

= 2.0 =
* If no query_obj is sent, use the global $wp_query object (previously used to create a new query object)
* If a not-false, non-object value is sent as $wp_query object (namely any non-empty string or non-zero integer), then create a new WP_Query object for use
* Add ability to preserve the state of the existing query_obj
    * Add $preserve_query_obj arg (optional) to inject_query_posts(), default to true
    * Add filter 'inject_query_posts_preserve_query_obj' that gets passed value of $preserve_query_obj
* Reset more query_obj settings
* Wrap function in if(!function_exists()) check
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Minor code reformatting (spacing)
* Add PHPDoc documentation
* Note compatibility with WP 2.8+, 2.9+, 3.0+
* Update copyright date
* Add package info to top of plugin file
* Add Changelog, Filters, Template Tags, and Upgrade Notice sections to readme.txt
* Remove trailing whitespace
* Add to plugin repo

= 1.0 =
* Initial release


== Upgrade Notice ==

= 2.1 =
Recommended major update: added argument and better handling for post caching; added filter; added arguments to existing filter; renamed and deprecated all existing functions; noted compatibility through WP 3.5+; and more.

= 2.0.5 =
Trivial update: noted compatibility through WP 3.4+; explicitly stated license

= 2.0.4 =
Trivial update: noted compatibility through WP 3.3+ and minor readme.txt tweaks

= 2.0.3 =
Trivial update: removed unnecessary resetting of query object variables

= 2.0.2 =
Trivial update: noted compatibility through WP 3.2+

= 2.0.1 =
Trivial update: noted compatibility with WP 3.1+ and updated copyright date.

= 2.0 =
Recommended major update! Highlights: now use global WP_Query object by default; added ability to preserve state of existing query object; misc non-functionality changes; expanded readme.txt text; verified WP 3.0 compatibility.
