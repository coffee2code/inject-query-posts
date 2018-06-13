<?php

defined( 'ABSPATH' ) or die();

class Inject_Query_Posts_Test extends WP_UnitTestCase {

	public function tearDown() {
		parent::tearDown();
		// Ensure main WP_Query gets reset
		if ( isset ( $GLOBALS['wp_query'] ) ) {
			$GLOBALS['wp_query']->init();
		}
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	protected function create_posts() {
		$post_id1 = $this->factory->post->create( array( 'post_content' => 'a third go-round', 'post_title' => 'just to have more' ) );
		$post_id2 = $this->factory->post->create( array( 'post_content' => 'more content', 'post_title' => 'another title' ) );
		$post_id3 = $this->factory->post->create( array( 'post_content' => 'here is the content', 'post_title' => 'example title' ) );
		$post1 = get_post( $post_id1 );
		$post2 = get_post( $post_id2 );
		$post3 = get_post( $post_id3 );

		return array( $post3, $post2, $post1 );
	}

	/**
	 * Check each of the WP_Query is_* functions/properties against expected boolean value.
	 *
	 * Any properties that are listed by name as parameters will be expected to be true; any others are
	 * expected to be false. For example, assertQueryTrue('is_single', 'is_feed') means is_single()
	 * and is_feed() must be true and everything else must be false to pass.
	 *
	 * @since 1.6.1
	 * @link https://unit-tests.svn.wordpress.org/trunk/tests/query/conditionals.php
	 *
	 * @param string $prop,... Any number of WP_Query properties that are expected to be true for the current request.
	 */
	public function assertQueryTrue(/* ... */) {
		global $wp_query;
		$all = array(
			'is_single', 'is_preview', 'is_page', 'is_archive', 'is_date', 'is_year', 'is_month', 'is_day', 'is_time',
			'is_author', 'is_category', 'is_tag', 'is_tax', 'is_search', 'is_feed', 'is_comment_feed', 'is_trackback',
			'is_home', 'is_404', 'is_comments_popup', 'is_paged', 'is_admin', 'is_attachment', 'is_singular', 'is_robots',
			'is_posts_page', 'is_post_type_archive',
		);
		$true = func_get_args();

		$passed = true;
		$not_false = $not_true = array(); // properties that were not set to expected values

		foreach ( $all as $query_thing ) {
			$result = is_callable( $query_thing ) ? call_user_func( $query_thing ) : $wp_query->$query_thing;

			if ( in_array( $query_thing, $true ) ) {
				if ( ! $result ) {
					array_push( $not_true, $query_thing );
					$passed = false;
				}
			} else if ( $result ) {
				array_push( $not_false, $query_thing );
				$passed = false;
			}
		}

		$message = '';
		if ( count($not_true) )
			$message .= implode( $not_true, ', ' ) . ' should be true. ';
		if ( count($not_false) )
			$message .= implode( $not_false, ', ' ) . ' should be false.';
		$this->assertTrue( $passed, $message );
	}


	//
	//
	// TESTS
	//
	//


	public function test_return_value() {
		$posts = $this->create_posts();

		$injected_posts = c2c_inject_query_posts( $posts );

		$this->assertEquals( $posts, $injected_posts );
	}

	public function test_template_tags_work_after_post_injection() {
		list( $post1, $post2, $post3 ) = $this->create_posts();

		c2c_inject_query_posts( array( $post1, $post2 ) );

		$this->assertTrue( have_posts() );
		$this->assertEquals( 2, $GLOBALS['wp_query']->post_count );
		the_post();
		$this->assertEquals( 'here is the content', get_the_content() );
	}

	public function test_template_tags_work_after_post_injection_via_hook() {
		list( $post1, $post2, $post3 ) = $this->create_posts();

		apply_filters( 'c2c_inject_query_posts', array( $post1, $post2 ) );

		$this->assertTrue( have_posts() );
		$this->assertEquals( 2, $GLOBALS['wp_query']->post_count );
		the_post();
		$this->assertEquals( 'here is the content', get_the_content() );
	}

	public function test_sending_post_as_first_arg() {
		list( $post1, $post2, $post3 ) = $this->create_posts();

		c2c_inject_query_posts( $post1 );

		$this->assertTrue( have_posts() );
		$this->assertEquals( 1, $GLOBALS['wp_query']->post_count );
		the_post();
		$this->assertEquals( 'here is the content', get_the_content() );
	}

	public function test_filter_invocation() {
		$posts = $this->create_posts();

		$injected_posts = apply_filters( 'c2c_inject_query_posts', $posts );

		$this->assertEquals( $posts, $injected_posts );
	}

	public function test_works_on_custom_wp_query() {
		$posts = $this->create_posts();
		$posts = array_slice( $posts, 0, 2 ); // Only grab first 2 for this test
		$query = new WP_Query;

		$injected_posts = c2c_inject_query_posts( $posts, array(), $query );

		$this->assertTrue( $query->have_posts() );
		$this->assertEquals( 2, $query->post_count );
		$this->assertEquals( $posts, $query->posts );
		$this->assertEquals( $posts, $injected_posts );
		$this->assertNotEquals( $GLOBALS['wp_query'], $query );
	}

	public function test_configuration_of_wp_query() {
		$posts = $this->create_posts();
		$config = array(
			'is_search' => true,
			's' => 'dog',
		);

		$injected_posts = c2c_inject_query_posts( $posts, $config );

		$this->assertTrue( is_search() );
		$this->assertQueryTrue( 'is_search' );
		$this->assertTrue( $GLOBALS['wp_query']->is_search );
		$this->assertEquals( 'dog', $GLOBALS['wp_query']->s );
	}

	public function test_configuration_of_custom_query() {
		$posts = $this->create_posts();
		$query = new WP_Query;
		$config = array(
			'is_search' => true,
			'query_vars' => array( 's' => 'cat' ),
		);

		$injected_posts = c2c_inject_query_posts( $posts[0], $config, $query, false );

		$this->assertFalse( is_search() );
		$this->assertTrue( $query->is_search );
		$this->assertFalse( $GLOBALS['wp_query']->is_search );
		$this->assertEquals( 'cat', $query->get( 's' ) );
		$this->assertEmpty( get_query_var( 's' ) );
	}

	/**
	 * @expectedDeprecated inject_query_posts
	 */
	public function test_deprecated_function() {
		$posts = $this->create_posts();

		$injected_posts = inject_query_posts( $posts );

		$this->assertEquals( $posts, $injected_posts );
	}

	public function test_preserving_query_obj() {
		$posts = $this->create_posts();

		/* Prime the global wp_query with a search request */

		$this->go_to( '?s=third' );

		global $wp_query;
		$this->assertTrue( is_search() );
		$this->assertQueryTrue( 'is_search' );
		$this->assertEquals( 'third', get_query_var( 's' ) );
		$this->assertEquals( array ( $posts[2] ), $wp_query->posts );
		$this->assertEquals( 1, $wp_query->post_count );

		/* Now inject a pair of posts as if they were the found posts, but
		   otherwise preserve the state of the query object */

		$injected = array( $posts[1], $posts[0] );
		$injected_posts = c2c_inject_query_posts( $injected, array(), null, true );

		$this->assertEquals( $injected, $injected_posts );
		$this->assertEquals( $injected, $wp_query->posts );
		$this->assertTrue( is_search() );
		$this->assertQueryTrue( 'is_search' );

		/* Now reinject the same posts, this time not preserving the original
		   state of the query object */

		$injected_posts = c2c_inject_query_posts( $injected, array(), false );

		$this->assertEquals( $injected, $injected_posts );
		$this->assertEquals( $injected, $wp_query->posts );
		$this->assertFalse( is_search() );
	}

}
