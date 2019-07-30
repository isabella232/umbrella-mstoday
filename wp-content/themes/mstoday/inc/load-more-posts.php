<?php
/**
 * Functions modifying the mstoday child theme's Load More Posts behavior.
 * 
 * @link https://github.com/INN/umbrella-voiceofoc/blob/b90f7fa9b80cabc1f914140bb314a06f5d51836e/wp-content/themes/voiceofoc/inc/homepage.php a reference for this implementation
 * @link https://secure.helpscout.net/conversation/904103699/3904/?folderId=1219602 an issue we had with it
 */

/**
 * Generate the tax_query args for the homepage river LMP
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

/**
 * filter LMP query on homepage by changing the config that is output for the LMP button
 *
 * This is the easiest way to affect what is loaded by the LMP button on the homepage.
 * The other option would be filtering largo_lmp_args, on the WP_Query that LMP runs, but that lacks
 * context that we have here.
 *
 * @uses mstoday_homepage_tax_query
 * @param Array $config the LMP config
 * @return Array the modified config
 * @since Largo 0.5.5.3
 * @since July 2019
 * @filter largo_load_more_posts_json
 * @see largo_load_more_posts_data
 * @see partials/home-post-list.php
 * @link https://secure.helpscout.net/conversation/904103699/3904/?folderId=1219602
 */
function mstoday_homepage_largo_load_more_posts_json( $config ) {
	if ( is_home() ) {
		$config['query']['tax_query'] = mstoday_homepage_tax_query();
	}
	return $config;
}
add_filter( 'largo_load_more_posts_json', 'mstoday_homepage_largo_load_more_posts_json' );
