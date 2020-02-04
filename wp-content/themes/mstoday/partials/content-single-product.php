<?php
/**
 * The template for displaying content in the single.php template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?> itemscope itemtype="https://schema.org/Article">

	<?php do_action('largo_before_post_header'); ?>

	<header>

		<?php largo_maybe_top_term(); ?>

		<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
		<?php if ( $subtitle = get_post_meta( $post->ID, 'subtitle', true ) ) : ?>
			<h2 class="subtitle"><?php echo $subtitle ?></h2>
		<?php endif; ?>

<?php largo_post_metadata( $post->ID ); ?>

	</header><!-- / entry header -->

    <?php 
    
        if( function_exists( 'woocommerce_get_product_thumbnail' ) ) {
            echo woocommerce_get_product_thumbnail( 'large' );
        }
    
    ?>

	<section class="entry-content clearfix" itemprop="articleBody">
		
		<?php largo_entry_content( $post ); ?>
		
	</section>

</article>
