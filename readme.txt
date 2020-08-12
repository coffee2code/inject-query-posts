=== Inject Query Posts ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: wp_query, query, posts, loop, template tags, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.6
Tested up to: 5.5
Stable tag: 2.2.9

Facilitates injecting an array of posts into a WP query object as if queried. Particularly useful to allow use of standard template tags.


== Description ==

This plugin provides a function for use by developers who have their own code for fetching posts according to a given criteria and now want to make use of loop-aware template tags to display those posts.

WordPress's template tags are intended to be used within 'the loop'. The loop is managed by a WP_Query object which sets up various global variables and its own object variables for use by the various template tags. The primary purpose of a WP_Query object is to actually query the database for the posts that match the currently specified criteria. However, if you don't need to query for posts since you already have them by some other means, you can still take advantage of the template tags by injecting those posts into the WP_Query via this plugin.

Depending on the template tags you are looking to use, or the logic you are hoping to employ within a loop, you may need to manually configure some of the query object's variables.

Example:

`
<?php // Say we're in the sidebar

// We've gotten some post objects on our own.
$posts = c2c_get_random_posts( 5, '' );

// Inject the posts
c2c_inject_query_posts( $posts );

// Now let's display them via template tags:
if ( have_posts() ) :
    while ( have_posts() ) : the_post(); ?>

        <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>

    <?php endwhile;?>
<?php endif; ?>

`

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/inject-query-posts/) | [Plugin Directory Page](https://wordpress.org/plugins/inject-query-posts/) | [GitHub](https://github.com/coffee2code/inject-query-posts/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `inject-query-posts.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use the `c2c_inject_query_posts()` function to inject an array of posts into a WP query object.  Specify the posts array as the first argument.  Configure the query object by passing an array as the second argument.  If specifying a WP query object, pass it as the third object; if not specified then the global wp_query object will be assumed.


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
Optional.  Should the query object be kept as-is prior to injecting posts? Default is false. If false, then the object is re-initialized/reset before post injection.

* `$cache_posts` (bool)
Optional.  Update the posts in cache? Default is true.

= Examples =

* (See Description section for an additional example.)

* Similar to previous example, for WP 3.0+

`
<?php
$posts = c2c_get_random_posts( 5, '' ); // Obtain posts via your favorite related posts, find posts, etc plugin, or via custom query
do_action( 'c2c_inject_query_posts', $posts ); // Better than direct call to c2c_inject_query_posts( $posts );
get_template_part( 'loop' );
?>
`

== Hooks ==

The plugin is further customizable via two hooks. Such code should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**inject_query_posts_preserve_query_obj (filter)**

The 'inject_query_posts_preserve_query_obj' filter allows you override the value of the `$preserve_query_obj` argument passed to the function. This is not typical usage for most users.

Arguments:

* $preserve_query_obj (bool) : Boolean indicating if the query object was set to be preserved or not
* $query_obj (WP_Query object) : The WP_Query object passed to the `c2c_inject_query_posts()`
* $posts (array) : The posts being injected into the WP_Query object
* $config (array) : Query object variables to directly set, and their values.

Example:

`
/**
 * Always preserve the condition of the WP_Query object passed ot Inject Query Posts.
 *
 * @param bool     $preserve_query_obj The default preservation value as passed to the function.
 * @param WP_Query $query_obj          The query object.
 * @param array    $posts              The posts being injected.
 * @param array    $config             Associative array of query object variables to directly set, and their values.
 * @return bool
 */
function my_preserve_query_obj( $preserve_query_obj, $query_obj, $posts, $config ) {
	return true;
}
add_filter( 'inject_query_posts_preserve_query_obj', 'my_preserve_query_obj', 10, 4 );
`

**c2c_inject_query_posts (filter)**

The 'c2c_inject_query_posts' filter allows you to use an alternative approach to safely invoke `c2c_inject_query_posts()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.

Arguments:

* The same arguments as `c2c_inject_query_posts()`

Example:

Instead of:

`<?php echo c2c_inject_query_posts( $posts, array( 'is_search' => true ) ); ?>`

Do:

`<?php echo apply_filters( 'c2c_inject_query_posts', $posts, array( 'is_search' => true ) ); ?>`


== Changelog ==

= 2.2.9 (2020-05-01) =
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS

= 2.2.8 (2019-11-22) =
* Change: Note compatibility through WP 5.3+
* Change: Use full URL for readme.txt link to full changelog
* Change: Unit tests: Change method signature of `assertQueryTrue()` to match parent's update to use the spread operator
* Change: Update copyright date (2020)

= 2.2.7 (2019-02-13) =
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Change: Cast return value of `inject_query_posts_preserve_query_obj` as boolean
* Change: Note compatibility through WP 5.1+
* Unit tests:
    * Change: Update unit test install script and bootstrap to use latest WP unit test repo
    * Change: Stop testing `is_comments_popup()` due to its deprecation
* Fix: Correct inline documentation for function parameter defaults
* Change: Reformat function docblocks for better line-wrapping
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Split paragraph in README.md's "Support" section into two

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/inject-query-posts/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.2.9 =
Trivial update: Updated a few URLs to be HTTPS and noted compatibility through WP 5.4+.

= 2.2.8 =
Trivial update: noted compatibility through WP 5.3+ and updated copyright date (2020).

= 2.2.7 =
Trivial update: created CHANGELOG.md to store historical changelog outside of readme.txt, updated unit test initialization, noted compatibility through WP 5.1+, and updated copyright date (2019)

= 2.2.6 =
Trivial update: fixed broken unit test, added README.md, noted compatibility through WP 4.9+, and updated copyright date (2018)

= 2.2.5 =
Trivial update: noted compatibility through WP 4.7+, updated unit test bootstrap, minor documentation tweaks, updated copyright date

= 2.2.4 =
Trivial update: minor unit test tweaks; verified compatibility through WP 4.4+; and updated copyright date (2016)

= 2.2.3 =
Trivial update: bugfix for very rare usage technique; noted compatibility through WP 4.3+

= 2.2.2 =
Trivial update: noted compatibility through WP 4.1+ and updated copyright date

= 2.2.1 =
Trivial update: noted compatibility through WP 4.0+; added plugin icon.

= 2.2 =
Moderate update: changed default value of $preserve_query_obj arg to false; added unit tests; noted compatibility through WP 3.8+; dropped compatibility with WP older than 3.6

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
