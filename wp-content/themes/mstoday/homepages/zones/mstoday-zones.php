<?php
function zone_homepage_top_story() {
	global $shown_ids;

	$topstory = largo_home_single_top();

	$shown_ids[] = $topstory->ID;
	$thumbnail = get_the_post_thumbnail( $topstory->ID, 'full' );
	$excerpt = largo_excerpt( $topstory, 2, false, '', false );

	ob_start(); ?>
	
	<article id="post-<?php echo $topstory->ID; ?>" <?php post_class( 'clearfix', $topstory->ID ); ?>>

		<header>
			<div class="hero span12 <?php largo_hero_class( $topstory->ID ); ?>">
			<?php
				if ( $thumbnail ) {
					echo( '<a href="' . get_permalink( $topstory->ID ) . '" rel="bookmark">');
					echo $thumbnail;
					echo('</a>');
				}
			?>
			</div>
		</header>
		
		<div class="entry-content span10 with-hero">
		
			<?php
				if ( largo_has_categories_or_tags() ) {
				 	echo '<h5 class="top-tag">' . largo_top_term( $args = array( 'post' => $topstory->ID, 'echo' => FALSE ) ) . '</h5>';
				}
			?>
	
		 	<h2 class="entry-title">
		 		<a href="<?php echo get_permalink( $topstory->ID ); ?>" rel="bookmark"><?php echo $topstory->post_title; ?></a>
		 	</h2>
	
		 	<h5 class="byline"><?php largo_byline( true, false, $topstory ); ?></h5>
	
			<?php echo $excerpt; ?>

		</div><!-- .entry-content -->

	</article><!-- #post-<?php the_ID(); ?> -->
	
<?php
	wp_reset_postdata();
	return ob_get_clean();
}

function zone_homepage_second_story() {
		global $shown_ids;

	$max = 3;

	/**
	 * Filter the maximum number of posts to show in the featured stories list
	 * on the HomepageSingleWithFeatured homepage template.
	 *
	 * This is used in the query for the series list of posts in the same series
	 * as the main feature. This is the maximum number of posts that will display
	 * in the list.
	 *
	 * Default value is 3.
	 *
	 * @since 0.5.1
	 *
	 * @param int  $var minimum number of posts that can show.
	 */
	$max = apply_filters( 'largo_homepage_feature_stories_list_maximum', $max );

	ob_start();
	$featured_stories = largo_home_featured_stories( $max );
	if ( count( $featured_stories ) < 3) {
		$recent_stories = wp_get_recent_posts( array(
			'numberposts' => 3,
			'post__not_in' => $shown_ids,
			'post_type' => 'post',
			'post_status' => 'publish',
		), 'OBJECT');

		$featured_stories = array_merge( $featured_stories, $recent_stories );
	}
	foreach ( $featured_stories as $featured ) {
		$shown_ids[] = $featured->ID;
		$thumbnail = get_the_post_thumbnail( $featured->ID, 'rect_thumb' ); 
		$excerpt = largo_excerpt( $featured, 2, false, '', false );
	?>
		<div class="span4">
			<article id="post-<?php echo $featured->ID; ?>" <?php post_class( 'clearfix', $featured->ID ); ?>>
				<header>
					<div class="hero span12 <?php largo_hero_class( $featured->ID ); ?>">
					<?php
						if ( $thumbnail ) {
							echo( '<a href="' . get_permalink( $featured->ID ) . '" rel="bookmark">');
							echo $thumbnail;
							echo('</a>');
						}
					?>
					</div>
				</header>
				
				<div class="entry-content">
					<h5 class="top-tag"><?php largo_top_term( array( 'post'=> $featured->ID ) ); ?></h5>
					<h2 class="entry-title"><a href="<?php echo esc_url( get_permalink( $featured->ID ) ); ?>"><?php echo $featured->post_title; ?></a></h3>
					<h5 class="byline"><?php largo_byline( true, true, $featured ); ?></h5>
				</div>
			</article>
		</div>
	<?php
	}
	wp_reset_postdata();
	return ob_get_clean();
}

function zone_homepage_featured_projects_widget() {
	ob_start();
	$widget = "<div class='widget-area bold-widget-title'>";

	if ( !dynamic_sidebar( 'Homepage next to second post' ) ) {
		$widget .= "<div style=''> Add a widget to the 'Homepage next to second post' sidebar</div>";
	}

	$widget .= "</div>";
	return ob_get_clean();
}

function zone_homepage_river() {
	global $shown_ids;
	
	$args = array(
		'paged' => $paged,
		'post_status' => 'publish',
		'posts_per_page' => 10,
		'post__not_in' => $shown_ids,
		'ignore_sticky_posts' => true,
		'tax_query' => array(
			array(
				'taxonomy' => 'prominence',
				'field'    => 'slug',
				'terms'    => 'homepage-exclude',
				'operator' => 'NOT IN',
			),
		),
	);
	
	if (of_get_option('num_posts_home'))
		$args['posts_per_page'] = of_get_option('num_posts_home');
	if (of_get_option('cats_home'))
		$args['cat'] = of_get_option('cats_home');
	
	$query = new WP_Query($args);
	
	ob_start();
	if ( $query->have_posts() ) {
		$counter = 1;
		while ( $query->have_posts() ) {
			$query->the_post();
	
			//if the post is in the array of post IDs already on this page, skip it. Just a double-check
			if ( in_array( get_the_ID(), $shown_ids ) ) {
				continue;
			} else {
				$shown_ids[] = get_the_ID();
				do_action( 'largo_before_home_list_post', $post, $query );
				get_template_part( 'partials/content', 'home' );
				do_action( 'largo_after_home_list_post', $post, $query );
				do_action( 'largo_loop_after_post_x', $counter, $context = 'home' );
				$counter++;
			}
		}
		largo_content_nav( 'nav-below' );
	} else {
		get_template_part( 'partials/content', 'not-found' );
	}
	return ob_get_clean();
}
