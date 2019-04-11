<?php
/**
 * Functions related to the Blank Page template
 */

/**
 * Hide Largo's Floating Social Buttons on the Blank Page template
 *
 * @see single-blank.php
 */
function mstoday_blank_page_largo_floating_social_buttons() {
	remove_action( 'wp_footer', 'largo_floating_social_buttons', 10 );
	remove_action( 'wp_footer', 'largo_floating_social_button_js', 10 );
	remove_action( 'wp_footer', 'largo_floating_social_button_width_json', 10 );
}

/**
 * Override Largo's enqueue options for sticky nav
 *
 * @see single-blank.php
 */
function mstoday_blank_page_largo_enqueue_js() {
	wp_localize_script(
		'largoCore', 'Largo', array(
		'is_home' => is_front_page(),
		'is_single' => is_single() || is_singular(),
		'sticky_nav_options' => array(
			'sticky_nav_display' => true,
			'main_nav_hide_article' => true,
			'nav_overflow_label' => of_get_option( 'nav_overflow_label', 'More' )
		)
	));
}
