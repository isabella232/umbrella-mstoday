<?php

include_once get_template_directory() . '/homepages/homepage-class.php';
include_once get_stylesheet_directory() . '/homepages/zones/mstoday-zones.php';

class MSTodayHomepage extends Homepage {

	function __construct( $options=array() ) {
		$suffix = (LARGO_DEBUG) ? '' : '.min';
		$defaults = array(
			'name' => __( 'MS Today Homepage Layout', 'mstoday' ),
			'description' => __( 'A homepage with top posts and widget area', 'mstoday' ),
			'template' => get_stylesheet_directory() . '/homepages/templates/mstoday_template.php',
			'rightRail' => true,
			'assets' => array(
				array( 'mstoday-homepage', get_stylesheet_directory_uri() . '/homepages/assets/css/homepage' . $suffix . '.css' )
			),
			'prominenceTerms' => array(
				array(
					'name' 			=> __( 'Exclude From Homepage', 'largo' ),
					'description' 	=> __( 'Stories that should be excluded from the main river of stories on the homepage', 'largo' ),
					'slug' 			=> 'homepage-exclude'
				),
				array(
					'name' 			=> __( 'Homepage Featured Projects', 'largo' ),
					'description' 	=> __( 'Stories to show in the featured projects box at the top of the homepage', 'largo' ),
					'slug' 			=> 'homepage-featured-projects'
				)
			)
		);
		$options = array_merge( $defaults, $options );
		$this->init();
		$this->load( $options );
	}
	
	function homepage_top_story() {
		return zone_homepage_top_story();
	}

	function homepage_second_story() {
		return zone_homepage_second_story();
	}
	
	function homepage_featured_projects_widget() {
		return zone_homepage_featured_projects_widget();
	}
	
	function homepage_river() {
		return zone_homepage_river();
	}

}

function mstoday_custom_homepage_layouts() {
	$unregister = array(
		'HomepageBlog',
		'HomepageSingle',
		'HomepageSingleWithFeatured',
		'HomepageSingleWithSeriesStories',
		'TopStories',
		'LegacyThreeColumn'
	);

	foreach ( $unregister as $layout )
		unregister_homepage_layout( $layout );

	register_homepage_layout( 'MSTodayHomepage' );
}
add_action( 'init', 'mstoday_custom_homepage_layouts', 10 );
