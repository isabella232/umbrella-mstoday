<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?> itemscope itemtype="https://schema.org/Article">

	<?php do_action('largo_before_post_header'); ?>

	<?php largo_post_metadata( $post->ID ); ?>

	<?php
		do_action('largo_after_hero');
	?>

	<section class="entry-content clearfix" itemprop="articleBody">

		<?php largo_entry_content( $post ); ?>

	</section>

	<?php do_action('largo_after_post_content'); ?>

</article>
