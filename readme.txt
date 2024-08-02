=== Inject Query Posts ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: wp_query, query, posts, loop, template tags
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.6
Tested up to: 6.6
Stable tag: 3.0.4

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

1. Install via the built-in WordPress plugin installer. Or install the plugin code inside the plugins directory for your site (typically `/wp-content/plugins/`).
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use the `c2c_inject_query_posts()` function to inject an array of posts into a WP query object. Specify the posts array as the first argument. Configure the query object by passing an array as the second argument. If specifying a WP query object, pass it as the third object; if not specified then the global wp_query object will be assumed.


== Developer Documentation ==

Developer documentation can be found in [DEVELOPER-DOCS.md](https://github.com/coffee2code/inject-query-posts/blob/master/DEVELOPER-DOCS.md). That documentation covers the template tag and hooks provided by the plugin.

As an overview, this is the template tag provided by the plugin:

* `c2c_inject_query_posts()` : Template tag to inject an array of posts into a query object as if that query object had obtained those posts via a query.

Theses are the hooks provided by the plugin:

* `inject_query_posts_preserve_query_obj` : Overrides the value of the `$preserve_query_obj` argument passed to the function. This is not typical usage for most users.
* `c2c_inject_query_posts` : Allows use of an alternative approach to safely invoke `c2c_inject_query_posts()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.


== Changelog ==

= 3.0.4 (2024-08-02) =
* Change: Note compatibility through WP 6.6+
* Change: Update copyright date (2024)
* New: Add `.gitignore` file
* Change: Reduce number of 'Tags' from `readme.txt`
* Change: Remove development and testing-related files from release packaging
* Unit tests:
    * Hardening: Prevent direct web access to `bootstrap.php`
    * Allow tests to run against current versions of WordPress
    * New: Add `composer.json` for PHPUnit Polyfill dependency
    * Change: In bootstrap, store path to plugin directory in a constant

= 3.0.3 (2023-05-18) =
* Change: Add link to DEVELOPER-DOCS.md to README.md
* Change: Tweak installation instruction
* Change: Tweak some documentation text spacing and fix a typo
* Change: Note compatibility through WP 6.3+
* Change: Update copyright date (2023)
* New: Add a potential TODO feature

= 3.0.2 (2021-10-01) =
* New: Add DEVELOPER-DOCS.md and move template tag and hooks documentation into it
* Change: Note compatibility through WP 5.8+
* Unit tests:
    * Change: Restructure unit test directories
        * Change: Move `phpunit/` into `tests/phpunit/`
        * Change: Move `phpunit/bin/` into `tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/inject-query-posts/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 3.0.4 =
Trivial update: noted compatibility through WP 6.6+, removed unit tests from release packaging, and updated copyright date (2024)

= 3.0.3 =
Trivial update: noted compatibility through WP 6.3+, made minor documentation tweaks, and updated copyright date (2023)

= 3.0.2 =
Trivial update: added DEVELOPER-DOCS.md, noted compatibility through WP 5.8+, and minor reorganization and tweaks to unit tests

= 3.0.1 =
Trivial update: noted compatibility through WP 5.7+ and updated copyright date (2021)

= 3.0 =
Notable update: Changed argument handling, removed long-deprecated `inject_query_posts()`, changed unit test file structure, improved inline documentation, added TODO.md file, and noted compatibility through WP 5.5+.

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
