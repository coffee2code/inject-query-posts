# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Cast first argument to `inject_query_posts_preserve_query_obj` filter as bool
* Rename `$preserve_query_obj` arg to `$reset_query_obj`. Leave default as true, which changes default behavior of the arg.
* Deprecate `inject_query_posts_preserve_query_obj` filter and introduce `c2c_inject_query_posts_reset_query_obj`
* Remove already deprecated `inject_query_posts()`

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/inject-query-posts/) or on [GitHub](https://github.com/coffee2code/inject-query-posts/) as an issue or PR).
