<?php 

/**
 * Includes
 */
$includes = array(
	'/homepages/homepage.php'
);
// Perform load
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}

/**
 * Include compiled style.css
 */
function child_stylesheet() {
	wp_dequeue_style( 'largo-child-styles' );

	$suffix = ( LARGO_DEBUG )? '' : '.min';
	wp_enqueue_style( 'mstoday', get_stylesheet_directory_uri() . '/css/child' . $suffix . '.css' );

}
add_action( 'wp_enqueue_scripts', 'child_stylesheet', 20 );


function mstoday_register_sidebars() {

	$sidebars = array();

	$sidebars[] = array(
		'name' => __( 'Homepage next to second post', 'mstoday' ),
		'id' => 'homepage-next-second-post',
		'description' => __( 'A widget area that appears next to the second post on the homepage.', 'mstoday' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> '</aside>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	);
	
	$sidebars[] = array(
		'name' => __( 'Signup Interstitial', 'mstoday' ),
		'id' => 'signup-interstitial',
		'description' => __( 'MailChimp signup form that appears on the homepage and archive pages in the main river of stories.', 'mstoday' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> '</aside>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	);
	
	$sidebars[] = array(
		'name' => __( 'Donate Interstitial', 'mstoday' ),
		'id' => 'donate-interstitial',
		'description' => __( 'Donation message that appears on the homepage and archive pages in the main river of stories.', 'mstoday' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> '</aside>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	);

	foreach ( $sidebars as $sidebar ) {
		register_sidebar( $sidebar );
	}
}
add_action( 'widgets_init', 'mstoday_register_sidebars', 11);

function mstoday_interstitial( $counter, $context ) {
	if ( $counter === 2 ) {
		echo '<div class="signup-interstitial">';
		dynamic_sidebar( 'signup-interstitial' );
		echo '</div>';
	}
	if ( $counter === 7 ) {
		echo '<div class="donate-interstitial">';
		dynamic_sidebar( 'donate-interstitial' );
		echo '</div>';
	}
}
add_action( 'largo_loop_after_post_x', 'mstoday_interstitial', 10, 2 );

/**
 * This removes the Largo action that separates out the posts that create the hierarchical header
 *
 * https://github.com/INN/Largo/blob/master/inc/taxonomies.php#L346 see largo_category_archive_posts, which is what we're removing.
 * If the priority of largo_category_archive_posts is changed from 15, this will cease to work
 *
 * @since 0.1
 * @since Largo 0.5.4
 * @uses remove_action (which in turn uses remove_filter, see https://developer.wordpress.org/reference/functions/remove_filter/)
 * @link https://github.com/INN/Largo/blob/master/inc/taxonomies.php#L346 see largo_category_archive_posts, which is what we're removing.
 */
function mstoday_remove_category_header_on_this_one_specific_category() {
	$qo = get_queried_object();
	if ( is_main_query() && is_category() && $qo->slug === 'around-the-state' ) {
		remove_action( 'pre_get_posts', 'largo_category_archive_posts', 15 );
	} else {
		// put this back if it's not any of the above; we might need this perhaps maybe
		add_action( 'pre_get_posts', 'largo_category_archive_posts', 15 );
	}
}
add_action( 'pre_get_posts', 'mstoday_remove_category_header_on_this_one_specific_category', 1 );
