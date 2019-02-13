# Changelog

## _(in-progress)_
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Split paragraph in README.md's "Support" section into two

## 2.2.6 _(2018-06-12)_
* New: Add README.md
* Unit tests:
    * Change: Make local copy of `assertQueryTrue()`; apparently it's (now?) a test-specific assertion and not a globally aware assertion
    * Change: Minor whitespace tweaks to bootstrap
* Change: Tweak plugin descrition
* Change: Add GitHub link to readme
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Note compatibility through WP 4.9+
* Change: Update copyright date (2018)

## 2.2.5 _(2017-02-03)_
* Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable.
* Change: Enable more error output for unit tests.
* Change: Minor unit test improvements.
* Change: Note compatibility through WP 4.7+.
* Change: Minor readme.txt improvements.
* New: Add LICENSE file.
* Change: Update copyright date (2017).

## 2.2.4 _(2016-01-25)_
* New: Create empty index.php to prevent files from being listed if web server has enabled directory listings.
* New: Add 'Text Domain' header attribute.
* Change: Note compatibility through WP 4.4+.
* Change: Explicitly declare methods in unit tests as public.
* Change: Update copyright date (2016).

## 2.2.3 _(2015-08-14)_
* Bugfix: `c2c_inject_query_posts` hook should be a filter and not an action
* Update: Correct documentation regarding `c2c_inject_query_posts` hook
* Update: Minor inline document tweaks (spacing)
* Update: Add full inline documentation to provided example
* Update: Note compatibility through WP 4.3+

## 2.2.2 _(2015-02-11)_
* Note compatibility through WP 4.1+
* Update copyright date (2015)

## 2.2.1 _(2014-08-25)_
* Minor plugin header reformatting
* Minor code reformatting (spacing, bracing)
* Change documentation links to wp.org to be https
* Note compatibility through WP 4.0+
* Add plugin icon

## 2.2 _(2013-12-17)_
* Change default of `$preserve_query_obj` argument to false, meaning that the query object getting injected will be reset before doing so
* Remove manual resetting of `WP_Query` variables since the class's `init()` does it all
* Support passing a `WP_Post` object as the first argument
* Add unit tests
* Note compatibility through WP 3.8+
* Drop compatibility with versions of WP older than 3.6
* Update copyright date (2014)
* Add banner
* Change donate link
* Minor code formatting changes (bracing)
* Minor formatting changes (spacing) and code example changes in readme.txt

## 2.1
* Rename `inject_query_posts()` to `c2c_inject_query_posts()` (but maintain a deprecated version for backwards compatibility)
* Add filter `c2c_inject_query_posts` so that users can use the `do_action('c2c_inject_query_posts')` notation for invoking the function
* Add optional `$cache_posts` argument to function and use it determine if `update_post_caches()` should be called
* Add better control for specifying arguments to `update_post_caches()`
* Send $posts and $config as additional args to 'inject_query_posts_preserve_query_obj' filter
* Add check to prevent execution of code if file is directly accessed
* Update documentation
* Note compatibility through WP 3.5+
* Update copyright date (2013)

## 2.0.5
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

## 2.0.4
* Note compatibility through WP 3.2+
* Add link to plugin directory page to readme.txt
* Update copyright date (2012)

## 2.0.3
* Remove unnecessary reset of `meta_query` and `tax_query` query object variables

## 2.0.2
* Note compatibility through WP 3.2+
* Minor code formatting changes (spacing)
* Fix plugin homepage and author links in description in readme.txt

## 2.0.1
* Note compatibility through WP 3.1+
* Update copyright date (2011)

## 2.0
* If no query object is sent, use the global `$wp_query` object (previously used to create a new query object)
* If a not-false, non-object value is sent as `$wp_query` object (namely any non-empty string or non-zero integer), then create a new `WP_Query` object for use
* Add ability to preserve the state of the existing query_obj
    * Add `$preserve_query_obj` arg (optional) to `inject_query_posts()`, default to true
    * Add filter `inject_query_posts_preserve_query_obj` that gets passed value of `$preserve_query_obj`
* Reset more query object settings
* Wrap function in `if(!function_exists())` check
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Minor code reformatting (spacing)
* Add PHPDoc documentation
* Note compatibility with WP 2.8+, 2.9+, 3.0+
* Update copyright date
* Add package info to top of plugin file
* Add Changelog, Filters, Template Tags, and Upgrade Notice sections to readme.txt
* Remove trailing whitespace
* Add to plugin repo

## 1.0
* Initial release
