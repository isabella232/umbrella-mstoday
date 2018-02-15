<?php
/**
 * Compatibility functions for the Developer-Driven Custom Post Classes plugin
 *
 * @link https://github.com/INN/developer-driven-custom-post-classes/tree/master/docs/
 */

/**
 * Add homepage layout options for mstoday
 *
 * See implementation details at https://github.com/INN/developer-driven-custom-post-classes/tree/30141a2951c1c815db4957d7bb8d91ffbecbae82/docs#how-to-add-theme-compatibility-for-this-plugin
 *
 * @param Array $options DDCPC-set options
 * @return Array $options DDCPC-set options
 * @link https://github.com/INN/umbrella-mstoday/issues/5
 * @link https://github.com/INN/umbrella-mstoday/issues/6
 * @link https://github.com/INN/developer-driven-custom-post-classes/tree/30141a2951c1c815db4957d7bb8d91ffbecbae82/docs#how-to-add-theme-compatibility-for-this-plugin
 */
function mstoday_ddcpc_options( $options ) {
	$options = array_merge( $options, array(
		array(
			'description' => 'Homepage mobile display options',
			'name' => 'home-thumbnail',
			'options' => array(
				// class string as output in HTML => display text
				'home-thumbnail-hide' => 'Hide thumbnail',
				'home-thumbnail-left' => 'Left align thumbnail',
				'home-thumbnail-right' => 'Right align thumbnail',
			),
		),
	) );
	return $options;
}
add_filter( 'developer_driven_custom_post_classes_options', 'mstoday_ddcpc_options', 10, 1 );
