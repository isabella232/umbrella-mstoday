<?php
/**
 * A file for functions affecting largo's post-social functions.
 */
/**
 * Add the Linkedin button to the post social buttons directly, not within the "more" menu
 *
 * The 'brute' in this function name is because it uses string splicing and addition functions to add a button.
 *
 * @link http://jira.inn.org/browse/NQ-127A
 * @param string $input the post social links HTML.
 */
function rr_post_social_brute_add_linkedin( $input ) {
	// The start of the "More social links" menu.
	$before = '<span class="more-social-links';
	// Get the postition of the end of $before.
	$position = stripos( $input, $before );
	// Generate the link.
	$link = sprintf(
		'https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s&summary=%s&source=%s',
		rawurlencode( get_permalink() ),
		rawurlencode( get_the_title() ),
		rawurlencode( wp_strip_all_tags( wp_kses_decode_entities( largo_excerpt( null, 5, false, '', false ) ), true ) ),
		rawurlencode( get_bloginfo( 'name' ) )
	);
	// Insert the link into the Linkedin button.
	$string = sprintf(
		'<span class="linkedin"><a href="%s" target="_blank"><i class="icon-linkedin"></i> <span class="hidden-phone">Share <span class="visuallyhidden">on LinkedIn</span></span></a></span>',
		$link
	);
	// If you link to #disqus_thread, the obvious thing to do, Disqus will replace the inner html of the first instance of that on the page with a comment count.
	// @see rr_fake_comments_link
	$string .= '<span class="comments"><a href="#comments_link"><i class="icon-comment"></i> <span class="hidden-phone">Comments </span></a></span>';
	// Add the button to the largo_post_social_links HTML.
	$output = substr_replace( $input, $string, $position, 0 );
	return $output;
}
add_filter( 'largo_post_social_links', 'rr_post_social_brute_add_linkedin' );
