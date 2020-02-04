<?php
/**
 * Template Name: Full Width Page
 * Single Post Template: Full-width (no sidebar)
 * Description: Shows the post but does not load any sidebars, allowing content to span full container width.
 *
 * @package Largo
 * @since 0.1
 */
get_header();
?>

<div id="content" class="span12" role="main">
	<?php
		while ( have_posts() ) : the_post();

			$shown_ids[] = get_the_ID();

			$partial = ( is_page() ) ? 'page' : 'single';

			get_template_part( 'partials/content', $partial );

			if ( $partial === 'single' ) {
				comments_template( '', true );
			}

		endwhile;
	?>
</div>

<?php get_footer();
