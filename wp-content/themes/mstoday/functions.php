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
	'/inc/widget-single-post.php',
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
function mstoday_remove_category_header_on_this_one_specific_category( $query ) {
	$qo = get_queried_object();
	if ( $query->is_main_query() && $query->is_category() &&
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
 * A shim to make captions in articles show as actual captions instead of as body content.
 * 
 * Waiting for this to be fixed in the actual ANP plugin. 
 * https://github.com/INN/umbrella-mstoday/issues/57#issuecomment-580844062
 * 
 * The general idea here is to find all images attached to the post, find their
 * captions and media credits, then loop through $json and if any body component matches
 * an image caption/credit, change that component to be `role = caption` instead of `role = body`.
 * 
 * @param arr $json An array of the post content in JSON format
 * @param int $post_id The post id
 * 
 * @return arr @json The modified post content JSON
 */
function mstoday_apple_news_article_update_captions_shim( $json, $post_id ){

	// grab all attachments tied to this post
	$attachments = get_attached_media( 'image', $post->ID );

	// init empty arr
	$captions_and_credits = array();

	// loop through all returned attachments and add their captions and 
	// credits to our $captions_and_credits arr
	foreach( $attachments as $attachment ){

		$attachment_caption = wp_get_attachment_caption( $attachment->ID );

		if ( function_exists( 'navis_get_media_credit' ) ) {
			$attachment_credit = navis_get_media_credit( $attachment->ID );
		}

		if( !empty( $attachment_caption ) ) {
			array_push( $captions_and_credits, preg_replace("/[^a-z0-9.]+/i", "", $attachment_caption ) );
		}

		if( isset( $attachment_credit ) && !empty( $attachment_credit ) ) {
			if( !empty( $attachment_credit->credit ) ) {
				array_push( $captions_and_credits, preg_replace("/[^a-z0-9.]+/i", "", $attachment_credit->credit ) );
			}

			if( !empty( $attachment_credit->org ) ) {
				array_push( $captions_and_credits, preg_replace("/[^a-z0-9.]+/i", "", $attachment_credit->org ) );
			}
		}

	}

	// woohoo set an index
	$index = -1;
	$dropcap_set = false;

	// this is where the fun begins
	// loop through all $json body components and if one matches a caption/credit, update its role
	foreach( $json['components'] as $component ) {

		$index++;

		// we have to make it only alphanumeric because this plugin is annoying and adds way too much whitespace
		// that doesn't want to be stripped out with trim, str_replace, etc.
		$component_text = preg_replace("/[^a-z0-9.]+/i", "", $component['text']);
		$component_text = trim( $component_text, 'p' );
	
		// if the component text actually matches a credit or caption, update it
		if( in_array( $component_text, $captions_and_credits ) ) {
			
			// that's better (hopefully)
			$json['components'][$index]['role'] = 'caption';
			$json['components'][$index]['textStyle'] = array(
				"textAlignment" => "left",
				"fontName" => "Helvetica-Bold",
				"fontSize" => 14,
			);

			// who needs a layout if you're a caption
			unset($json['components'][$index]['layout']);

		}

		// if a dropcap component is found, make sure we don't update any others to use a dropcap
		if( false === $dropcap_set && 'dropcapBodyStyle' === $json['components'][$index]['textStyle'] ) {

			$dropcap_set = true;

		}

		// terrible way to fix the dropcap, but it's the only way that works
		// if this component has role = body and the dropcap has not yet been set, 
		// we can go ahead and safely add the dropcap to this component
		if( false === $dropcap_set && 'body' === $json['components'][$index]['role'] ) {
			
			$json['components'][$index]['textStyle'] = 'dropcapBodyStyle';
			$dropcap_set = true;

		}

	}

	// uncomment if you're interested in seeing what the $json looks like
	// print("<pre>".print_r($json,true)."</pre>");
	// die();

	return $json;

}
add_filter('apple_news_generate_json', 'mstoday_apple_news_article_update_captions_shim', 10, 2);

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

/**
 * Display a subscribe button in the navbars
 * 
 * @param str $location The location that this button is placed
 * 
 * @return str The formatted subscribe button
 */
 function mstoday_subscribe_button( $location = null ) {

    if( 'sticky' === $location ) {

        printf( '<a class="subscribe-link" href="%1$s"><span>%2$s</span></a>',
            esc_url( '\/subscribe\/' ),
            esc_html( 'Subscribe' )
        );

    } else {

        printf( '<div class="subscribe-btn"><a href="%1$s">%2$s</a></div>',
            esc_url( '\/subscribe\/' ),
            esc_html( 'Subscribe' )
        );

    }
} 

/**
 * Add relevant data attribute to scripts that should be ignored by 
 * CloudFlare's Rocket Loader functionality
 * 
 * @link Rocket Loader docs: https://support.cloudflare.com/hc/en-us/articles/200169436-How-can-I-have-Rocket-Loader-ignore-specific-JavaScripts-
 * @link GitHub issue: https://github.com/INN/umbrella-mstoday/issues/81
 * 
 * @param str $tag The <script> tag for the enqueued script.
 * @param str $handle The script's registered handle.
 * @param str $src The script's source URL.
 * 
 * @return str $tag The <script> tag for the enqueued script.
 */
function mstoday_rocket_load_ignored_scripts( $tag, $handle, $src ) {

	// array full of script handles that CF RL should ignore
	$scripts_to_ignore = array(
		'jquery-core',
		'load-more-posts'
	);

	foreach( $scripts_to_ignore as $script ) {

		// if the current script matches a $handle, add the specific attribute to allow CF to ignore it
		if ( $script === $handle ) {
			$tag = '<script type="text/javascript" data-cfasync="false" src="' . esc_url( $src ) . '"></script>';
		}

	}
 
	return $tag;
	
}
add_filter( 'script_loader_tag', 'mstoday_rocket_load_ignored_scripts', 10, 3 );

/**
 * Conditionally enqueue all of the required JS and CSS for the Inline Google Spreadsheet Viewer plugin
 * in order to help with pagespeed and performance. Now these assets should only be loaded on posts or pages
 * that contain the [gdoc] shortcode.
 * 
 * @see https://github.com/INN/umbrella-mstoday/issues/95
 * @see https://wordpress.org/support/topic/page-load-performance-issues-solution-suggestion/
 */
function mstoday_dequeue_unused_google_inline_sheets_assets() {

	global $post;
	
	$maybe_load_assets = false;

	$scripts = array(
		'jquery-datatables',
		'datatables-buttons',
		'datatables-buttons-colvis',
		'datatables-buttons-print',
		'datatables-buttons-html5',
		'datatables-fixedheader',
		'datatables-fixedcolumns',
		'datatables-responsive',
		'datatables-select',
		'igsv-datatables',
		'google-ajax-api',
		'igsv-gvizcharts',
		'pdfmake',
		'pdfmake-fonts',
		'vfs-fonts',
		'jszip'
	);
	
	$styles = array(
		'jquery-datatables',
		'datatables-buttons',
		'datatables-select',
		'datatables-fixedheader',
		'datatables-fixedcolumns',
		'datatables-responsive'
	);

	if( ( is_single() || is_page() ) && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'gdoc' ) ) {
		$maybe_load_assets = true;
		return;
	}

	if( false == $maybe_load_assets ) {
		foreach( $scripts as $script ) {
			wp_dequeue_script( $script );
		}

		foreach( $styles as $style ) {
			wp_dequeue_style( $style );
		}
	}

}
add_filter( 'wp_enqueue_scripts', 'mstoday_dequeue_unused_google_inline_sheets_assets', 9999 );

/**
 * Conditionally enqueue all of the required JS and CSS for the SiteOrigin page builder plugin + its addons
 * in order to help with pagespeed and performance. Now these assets should only be loaded on posts or pages
 * where `siteorigin_panels_render( $post->ID )` isn't empty.
 * 
 * @see https://github.com/INN/umbrella-mstoday/issues/95
 * @see https://siteorigin.com/docs/widgets-bundle/form-building/builder-field/
 * @see https://siteorigin.com/thread/check-if-page-is-using-the-page-builder/
 */
function mstoday_dequeue_unused_site_origin_assets() {

	global $post;

	$maybe_load_assets = false;

	if ( ( is_single() || is_page() ) && is_a( $post, 'WP_Post' ) && siteorigin_panels_render( $post->ID ) ){
		$maybe_load_assets = true;
		return;
	}

	$scripts = array(
		'siteorigin-premium-web-font-importer',
		'sow-slider-slider-cycle2',
		'sow-slider-slider-cycle2-swipe',
		'sow-google-map',
		'sow-slider-slider',
		'sowb-fittext',
		'sow-cta-main'
	);

	$styles = array(
		'sow-slider-slider-cycle2',
		'sow-slider-slider-cycle2-swipe',
		'sow-google-map',
		'sow-slider-slider',
		'sow-button-base',
		'sow-cta-main'
	);

	if( false === $maybe_load_assets ) {
		// loop through /uploads/siteorigin-widgets/* and remove all of those css files
		// because for some reason the SO widgets bundle plugin creates these files based off of widget settigns
		$uploads_dir = wp_upload_dir();
		$siteorigin_uploads = $uploads_dir['basedir'] . '/siteorigin-widgets';
		$uploads_items = list_files( $siteorigin_uploads, 1 );
		if( ! empty( $uploads_items ) ) {
			foreach ( $uploads_items as $item ) {
				$styles[] = str_replace( '.css', '', basename( $item ) );
			}
		}

		foreach( $scripts as $script ) {
			wp_dequeue_script( $script );
		}

		foreach( $styles as $style ) {
			wp_dequeue_style( $style );
		}
	}

}
add_filter( 'wp_enqueue_scripts', 'mstoday_dequeue_unused_site_origin_assets', 9999999 );