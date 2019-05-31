<?php
/**
 * Single Post Template: Blank Page (MS Today special)
 * Template Name: Blank Page (MS Today special)
 * Description: A blank slate for your Gutenberg-powered interactives
 */

global $shown_ids;

add_filter( 'body_class', function( $classes ) {
	$classes[] = 'normal blank-page';
	return $classes;
} );

add_action( 'wp_head', 'mstoday_blank_page_options_filter_register', 1 );
add_action( 'wp_enqueue_scripts', 'mstoday_blank_page_largo_nav_js', 11 );

get_header();
?>

<div id="content" role="main">
	<?php
		while ( have_posts() ) : the_post();

			$shown_ids[] = get_the_ID();

			$partial = ( is_page() ) ? 'page' : 'single';

			get_template_part( 'partials/content', 'blank' );

			if ( $partial === 'single' ) {

				do_action( 'largo_before_post_bottom_widget_area' );

				do_action( 'largo_post_bottom_widget_area' );

			}

		endwhile;
	?>
</div>

<?php do_action( 'largo_after_content' ); ?>

<?php get_footer();
