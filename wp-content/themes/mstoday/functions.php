<?php

/**
 * Includes
 */
$includes = array(
	'/homepages/homepage.php',
	'/inc/DDCPC.php',
	'/inc/post-tags.php',
	'/inc/open-graph.php',
	'/inc/blank-page-template.php',
	'/inc/load-more-posts.php',
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
	wp_enqueue_style(
		'mstoday',
		get_stylesheet_directory_uri() . '/css/child' . $suffix . '.css',
		array(),
		filemtime( get_stylesheet_directory() . '/css/child' . $suffix . '.css' )
	);

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
	if ( is_main_query() && is_category() &&
		( 'around-the-state' === $qo->slug || 'mississippi-roundup' === $qo->slug )
	) {
		remove_action( 'pre_get_posts', 'largo_category_archive_posts', 15 );
	} else {
		// if the case is not any of the above, make sure that this is enabled
		// because otherwise all other category pages will be messed up
		//
		// this may cause an issue if they ever check the box for hide_category_featured
		add_action( 'pre_get_posts', 'largo_category_archive_posts', 15 );
	}
}
add_action( 'pre_get_posts', 'mstoday_remove_category_header_on_this_one_specific_category', 1 );

/**
 * Remove Largo's default shortcut icons so that we may add our own specific ones
 */
function mstoday_largo_shortcut_icons() {
	remove_action( 'wp_head', 'largo_shortcut_icons', 10 );
	add_action( 'wp_head', 'mstoday_shortcut_icons', 10 );
}
add_action( 'wp_head', 'mstoday_largo_shortcut_icons', 9 );

/**
 * Add new specific touch icons
 */
function mstoday_shortcut_icons() {
	if ( of_get_option( 'favicon' ) ) {
		echo '<link rel="shortcut icon" href="' . esc_url( of_get_option( 'favicon' ) ) . '"/>';
	}

	?>
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_72.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_114.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_152.png" />
		<link rel="apple-touch-icon" sizes="167x167" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_167.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_180.png" />
		<link rel="apple-touch-icon" sizes="1024x1024" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_1024.png" />
		<meta name="apple-mobile-web-app-title" content="Mississippi Today">

		<link rel="icon" sizes="1024x1024" href="<?php echo get_stylesheet_directory_uri(); ?>/img/mstoday_new_1024.png" />
		<meta name="mobile-web-app-capable" content="yes">
	<?php
}


/**
 * Register support for Gutenberg wide images in your theme
 */
function mstoday_add_wide_image_support() {
  add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'mstoday_add_wide_image_support' );

/**
 * Copied from largo_layout_meta_box_dsiplay()
 * https://github.com/INN/largo/blob/512da701664b329f2f92244bbe54880a6e146431/inc/post-metaboxes.php#L169-L198
 * 
 * The only modification that's made here is it only fires if the post type is page
 * and switches all `post` to `page` in titles/descriptions
 */
function mstoday_layout_meta_box_display () {
	global $post;

	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );

	$current_template = (of_get_option('single_template') == 'normal')? __('One Column (Standard)', 'largo'):__('Two Column (Classic)', 'largo');

	if ( $post->post_type == 'page' ) {
		echo '<p><strong>' . __('Template', 'largo' ) . '</strong></p>';
		echo '<p>' . __('Select the page template you wish this page to use.', 'largo') . '</p>';
		echo '<label class="hidden" for="post_template">' . __("Page Template", 'largo' ) . '</label>';
		echo '<select name="_wp_post_template" id="post_template" class="dropdown">';
		echo '<option value="">' . sprintf(__( 'Default: %s', 'largo' ), $current_template) . '</option>';
		post_templates_dropdown(); //get the options
		echo '</select>';
	}

	echo '<p><strong>' . __('Custom Sidebar', 'largo' ) . '</strong><br />';
	echo __('Select a custom sidebar to display.', 'largo' ) . '</p>';
	echo '<label class="hidden" for="custom_sidebar">' . __("Custom Sidebar", 'largo' ) . '</label>';
	echo '<select name="custom_sidebar" id="custom_sidebar" class="dropdown">';
	largo_custom_sidebars_dropdown(); //get the options
	echo '</select>';
}

/**
 * Actually adds the layout metabox to the page editor
 */
function mstoday_add_layout_meta_box_to_pages() {
	largo_add_meta_box(
		'mstoday_layout_meta',
		__( 'Layout Options', 'mstoday' ),
		'mstoday_layout_meta_box_display',
		array('page'),
		'side',
		'core'
	);
}
add_filter( 'init', 'mstoday_add_layout_meta_box_to_pages' );

/**
 * Expanding Largo get_post_template() to also fire on pages
 * https://github.com/INN/largo/blob/512da701664b329f2f92244bbe54880a6e146431/inc/post-templates.php#L64-L84
 * 
 * Filters the page template value, and replaces it with
 * the template chosen by the user, if they chose one.
 */
function mstoday_get_page_template() {
	if( function_exists( 'get_post_template' ) ) {
		add_filter( 'page_template', 'get_post_template' );
	}
}
add_filter( 'init', 'mstoday_get_page_template' );