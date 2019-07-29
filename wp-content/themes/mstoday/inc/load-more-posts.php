<?php
/**
 * Functions modifying the mstoday child theme's Load More Posts behavior.
 * 
 * @link https://github.com/INN/umbrella-voiceofoc/blob/b90f7fa9b80cabc1f914140bb314a06f5d51836e/wp-content/themes/voiceofoc/inc/homepage.php a reference for this implementation
 * @link https://secure.helpscout.net/conversation/904103699/3904/?folderId=1219602 an issue we had with it
 */

/**
 * Generate the tax_query args for thoe homepage fiver LMP
 *
 * @return Array
 * @link https://github.com/INN/umbrella-voiceofoc/blob/b90f7fa9b80cabc1f914140bb314a06f5d51836e/wp-content/themes/voiceofoc/inc/homepage.php#L24-L57
 */
function mstoday_homepage_tax_query() {
	return array(
		array(
			'taxonomy' => 'prominence',
			'field' => 'slug',
			'terms' => 'homepage-exclude',
			'operator' => 'NOT IN',
		),
	);
}
