=== Inject Query Posts ===
Contributors: Scott Reilly
Donate link: http://coffee2code.com
Tags: wp_query, query, posts, loop, template tags
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: trunk
Version: 1.0

Inject an array of posts into a WP query object as if queried, particularly useful to allow use of standard template tags.

== Description ==

Inject an array of posts into a WP query object as if queried, particularly useful to allow use of standard template tags.

WordPress's template tags are intended to be used within 'the loop'.  The loop is managed by a WP_Query object which sets up various global variables and its own object variables for use by the various template tags.  The primary purpose of a WP_Query object is to actually query the database for the posts that match the currently specified criteria.  However, if you don't need to query for posts since you already have them by some other means, you can still take advantage of the template tags by injecting those posts into the WP_Query via this plugin.

Depending on the template tags you are looking to use, or the logic you are hoping to employ within a loop, you may need to manually configure some of the query object's variables.

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


== Installation ==

1. Unzip `inject-query-posts-v1.0.zip` inside the `/wp-content/plugins/` directory for your site
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use the `inject_query_posts()` function to inject an array of posts into a WP query object.  Specify the posts array as the first argument.  Configure the query object by passing an array as the second argument.  If specifying a WP query object, pass it as the third object; if not specified then the global wp_query object will be assumed.

