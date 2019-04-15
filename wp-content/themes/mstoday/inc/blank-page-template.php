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
 * This is a filter on the "option_{$option}" filter, which allows
 * us to change the value of a `get_option( $option )` call, before
 * that value is returned.
 *
 * Using this filter, we modify the result of get_option calls when
 * this filter is active.
 *
 * This should only be enqueued on single-blank.php page loads.
 *
 * @see single-blank.php
 * @since Largo 0.6.1
 * @param Mixed $value The value returned
 * @param String $option the option name
 */
function mstoday_blank_page_options_filter( $value, $option ) {
	if ( is_array( $value ) ) {
		$value['main_nav_hide_article'] = '1';
		$value['sticky_nav_display'] = '1';
	}
	return $value;
}

/**
 * Enqueue mstoday_blank_page_options_filter using the correct ID.
 *
 * This is necessary because we must make sure that the
 * "option_{$option}" filter has the correct $option.
 *
 * It uses the same logic as the function of_get_option, the return
 * value for which we are attempting to modify.
 *
 * @link https://github.com/INN/largo/blob/099fd2c9361b7a4e9e6e729d35adde40685cb0fb/lib/options-framework/options-framework.php#L410
 * @see mstoday_blank_page_options_filter
 * @since Largo 0.6.1
 */
function mstoday_blank_page_options_filter_register() {
	$config = get_option( 'optionsframework' );
	if ( ! isset( $config['id'] ) ) {
		return false;
	}
	$filter = 'option_' . $config['id'];

	return add_filter( $filter, 'mstoday_blank_page_options_filter', 10, 2 );
}

/**
 * Replace Largo's sticky nav js with a copy that changes the sticky nav behavior
 *
 * @see single-blank.php
 * @link https://github.com/INN/umbrella-mstoday/issues/32
 */
function mstoday_blank_page_largo_nav_js() {
	wp_dequeue_script( 'largo-navigation' );
	wp_deregister_script( 'largo-navigation' );

	wp_enqueue_script(
		'largo-navigation',
		get_stylesheet_directory_uri() . '/js/navigation.js',
		array( 'largoCore' ),
		filemtime( get_stylesheet_directory() . '/js/navigation.js' ),
		true
	);
}
