<?php
/**
 * Replacements of Largo/inc/open-graph.php functions
 */

/**
 * Zero out the OpenGraph tags produced by Largo if Yoast is active,
 *
 * but don't hide the meta description tag, because that's apparently something
 * that Yoast SEO does not output. Why, I don't know. Anyhow, copy a bunch of
 * logic from largo_opengraph(); and run it if Yoast is running, just for the
 * meta name="description" tag.
 *
 * @link https://github.com/INN/largo/issues/1437
 * @link https://wordpress.org/plugins/wordpress-seo/
 * @since Largo 0.5.5.4
 * @since Yoast/wordpress-seo 7.6.1
 * @since WordPress 4.9.6
 */
function mstoday_opengraph() {
	// this is defined in wordpress-seo/wp-seo-main.php.
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		largo_opengraph();
	} else {
		// Yoast does not take care of these tags. :frown:
		if ( is_single() ) {
			if ( have_posts() ) {
				the_post(); // we need to queue up the post to get the post specific info
				?>
					<meta name="description" content="<?php echo strip_tags( esc_html( get_the_excerpt() ) ); ?>" />
				<?php
			}
			rewind_posts();
		} elseif ( is_home() ) {
			?>
				<meta name="description" content="<?php bloginfo( 'description' ); ?>" />
			<?php
		} else {
			// let's try to get a better description when available.
			if ( is_category() && category_description() ) {
				$description = category_description();
			} elseif ( is_author() ) {
				if ( have_posts() ) {
					the_post(); // we need to queue up the post to get the post specific info.
					if ( get_the_author_meta( 'description' ) ) {
						$description = get_the_author_meta( 'description' );
					}
				}
				rewind_posts();
			} else {
				$description = get_bloginfo( 'description' );
			}
			if ( $description ) {
				echo '<meta name="description" content="' . strip_tags( esc_html( $description ) ) . '" />';
			}
		}
	}
}

/**
 * Dequeue largo_opengraph after it gets enqueued
 */
function mstoday_dequeue_largo_opengraph() {
	remove_action( 'wp_head', 'largo_opengraph', 10 );
	add_action( 'wp_head', 'mstoday_opengraph', 10 );
}
add_action( 'wp_head', 'mstoday_dequeue_largo_opengraph', 1 );
