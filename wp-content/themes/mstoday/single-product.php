<?php
/**
 * Woocommerce Single Product
 * Template Name: Woocommerce Single Product
 * Description: Shows Woocommerce products without any article widgets
 */

global $shown_ids;

add_filter( 'body_class', function( $classes ) {
	$classes[] = 'normal';
	return $classes;
} );

get_header();
?>

<div id="content" role="main">
	<?php
		while ( have_posts() ) : the_post();

            $partial = 'single-product';
			get_template_part( 'partials/content', $partial );

		endwhile;
	?>
</div>

<?php get_footer();
