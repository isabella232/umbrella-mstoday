<?php
/**
 * Template for around the state category page.
 * This removes the featured area for just this category,
 * in the future we may want a more elegant way of doing this....
 *
 * This depends upon mstoday_remove_category_header_on_thi_one_specific_category in functions.php
 * If the priority largo_category_archive_posts is registered with changes from 15 in largo/inc/taxonomies.php, then this template will no longer work.
 *
 * @package Largo
 * @since 0.4
 * @filter largo_partial_by_post_type
 */
get_header();

global $tags, $paged, $post, $shown_ids;

$title = single_cat_title( '', false );
$description = category_description();
$rss_link = get_category_feed_link( get_queried_object_id() );
$posts_term = of_get_option( 'posts_term_plural', 'Stories' );
$queried_object = get_queried_object();
?>

<div class="clearfix">
	<header class="archive-background clearfix">
		<a class="rss-link rss-subscribe-link" href="<?php echo $rss_link; ?>"><?php echo __( 'Subscribe', 'largo' ); ?> <i class="icon-rss"></i></a>
		<h1 class="page-title"><?php echo $title; ?></h1>
		<div class="archive-description"><?php echo $description; ?></div>
	</header>
</div>

<div class="row-fluid clearfix">
	<div class="stories span8" role="main" id="content">
		
	<?php 
		do_action( 'largo_before_category_river' );
		if ( have_posts() ) {
			$counter = 1;
			while ( have_posts() ) {
				the_post();
				$post_type = get_post_type();
				$partial = largo_get_partial_by_post_type( 'archive', $post_type, 'archive' );
				get_template_part( 'partials/content', 'archive' );
				do_action( 'largo_loop_after_post_x', $counter, $context = 'archive' );
				$counter++;
			}
			largo_content_nav( 'nav-below' );
		}
		do_action( 'largo_after_category_river' ); 
	?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer();
