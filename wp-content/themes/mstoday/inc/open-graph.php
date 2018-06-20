<?php
/**
 * Replacements of Largo/inc/open-graph.php functions
 */

/**
 * Zero out the OpenGraph tags produced by Largo if Yoast is active
 *
 * @link https://github.com/INN/largo/issues/1437
 * @link https://wordpress.org/plugins/wordpress-seo/
 * @since Largo 0.5.5.4
 * @since Yoast/wordpress-seo 7.6.1
 * @since WordPress 4.9.6
 */
function mstoday_opengraph() {
	// this is defined in wordpress-seo/wp-seo-main.php.
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		largo_opengraph();
	}
}

/**
 * Dequeue largo_opengraph after it gets enqueued
 */
function mstoday_dequeue_largo_opengraph() {
	remove_action( 'wp_head', 'largo_opengraph', 10 );
	add_action( 'wp_head', 'mstoday_opengraph', 10 );
}
add_action( 'wp_head', 'mstoday_dequeue_largo_opengraph', 1 );
