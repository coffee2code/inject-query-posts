# Developer Documentation

This plugin provides [hooks](#hooks) and a [template tag](#template-tag).

## Template Tag

The plugin provides one template tag for use in your theme templates, functions.php, or plugins.

### Functions

* `<?php function c2c_inject_query_posts( $posts, $args = array() ) ?>`
Injects an array of posts into a query object as if that query object had obtained those posts via a query.

### Arguments

* `$posts` _(array)_
Required. Array of posts to inject into the query object.

* `$args` _(array)_
Optional. Associative array of configuration options. Available options:
    * `$config` _(array)_
    Associative array of query object variables to directly set, and their values.

    * `$query_obj` _(WP_Query|null)_
    The query object to modify. If null, then the global wp_query object will be used. Pass a string or non-zero integer to have a new query object created and used.

    * `$preserve_query_obj` _(bool)_
    Should the query object be kept as-is prior to injecting posts? Default is false. If false, then the object is re-initialized/reset before post injection.

    * `$cache_posts` _(bool)_
    Update the posts in cache? Default is true.

### Examples

* (See Description section in [readme.txt](readme.txt)for an additional example.)

* Similar to previous example, for WP 3.0+

```php
$posts = c2c_get_random_posts( 5, '' ); // Obtain posts via your favorite related posts, find posts, etc plugin, or via custom query
do_action( 'c2c_inject_query_posts', $posts ); // Better than direct call to c2c_inject_query_posts( $posts );
get_template_part( 'loop' );
```

## Hooks

The plugin is further customizable via two hooks. Such code should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

### `inject_query_posts_preserve_query_obj` _(filter)_

The `inject_query_posts_preserve_query_obj` filter allows you override the value of the `$preserve_query_obj` argument passed to the function. This is not typical usage for most users.

#### Arguments

* **$preserve_query_obj** _(bool)_ : Boolean indicating if the query object was set to be preserved or not
* **$query_obj** _(WP_Query _object) : The WP_Query object passed to the `c2c_inject_query_posts()`
* **$posts** _(array)_ : The posts being injected into the WP_Query object
* **$config** _(array)_ : Query object variables to directly set, and their values.

#### Example

```php
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
```

### `c2c_inject_query_posts` _(filter)_

The `c2c_inject_query_posts` filter allows you to use an alternative approach to safely invoke `c2c_inject_query_posts()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.

#### Arguments:

* The same arguments as `c2c_inject_query_posts()`

#### Example:

Instead of:

`<?php echo c2c_inject_query_posts( $posts, array( 'is_search' => true ) ); ?>`

Do:

`<?php echo apply_filters( 'c2c_inject_query_posts', $posts, array( 'is_search' => true ) ); ?>`
