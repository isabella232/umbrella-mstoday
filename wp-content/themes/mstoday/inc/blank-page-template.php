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
