<?php
/**
 * Single Post widget
 *
 * Displays a single post using the same template as Largo Recent Posts.
 *
 * @link https://github.com/INN/umbrella-mstoday/issues/72
 */

/**
 * The Borderzine 3-Column recent posts widget
 *
 * Copied from Largo Recent Posts
 */
class Borderzine_3_Col_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname' => 'mstoday-single-post',
			'description' => __( 'Displays a single post.', 'mstoday' ),
		);
		parent::__construct(
			'mstoday-single-post', // Base ID
			__( 'Mississippi Today Single Post', 'mstoday' ), // Name
			$widget_ops // Args
		);

	}

	/**
	 * Outputs the content of the recent posts widget.
	 *
	 * @param array $args widget arguments.
	 * @param array $instance saved values from databse.
	 * @global $post
	 * @global $shown_ids An array of post IDs already on the page, to avoid duplicating posts
	 * @global $wp_query Used to get posts on the page not in $shown_ids, to avoid duplicating posts
	 */
	public function widget( $args, $instance ) {

		global $post,
			$wp_query, // grab this to copy posts in the main column
			$shown_ids; // an array of post IDs already on a page so we can avoid duplicating posts;

		// Preserve global $post
		$preserve = $post;


		// Add the link to the title.
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$thumb = isset( $instance['thumbnail_display'] ) ? $instance['thumbnail_display'] : 'small';
		$excerpt = isset( $instance['excerpt_display'] ) ? $instance['excerpt_display'] : 'num_sentences';

		$query_args = array (
			'posts_per_page' => isset( $instance['num_posts'] ) ? $instance['num_posts'] : 3,
			'post_status'    => 'publish',
			'tax_query'      => array(),
		);

		if ( isset( $instance['avoid_duplicates'] ) && 1 === $instance['avoid_duplicates'] ) {
			$query_args['post__not_in'] = $shown_ids;
		}
		if ( ! empty( $instance['cat'] ) ) {
			$query_args['cat'] = $instance['cat'];
		}
		if ( ! empty( $instance['tag'] ) ) {
			$query_args['tag'] = $instance['tag'];
		}
		if ( ! empty( $instance['author'] ) ) {
			$query_args['author'] = $instance['author'];
		}
		if ( ! empty( $instance['prominence'] ) ) {
			$query_args['tax_query'] = array_merge(
				$query_args['tax_query'],
				array(
					array(
						'taxonomy' => 'prominence',
						'field'    => 'term_id',
						'terms'    => $instance['prominence'],
					),
				)
			);
		}

		/*
		 * here begins the widget output
		 */

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . wp_kses_post( $title ). $args['after_title'];
		}

		if ( ! empty( $instance['linkurl'] ) && ! empty( $instance['linktext'] ) ) {
			echo '<p class="morelink btn btn-primary"><a href="' . esc_url( $instance['linkurl'] ) . '">' . esc_html( $instance['linktext'] ) . '</a></p>';
		}

		echo '<ul>';

		$my_query = new WP_Query( $query_args );

		if ( $my_query->have_posts() ) {

			$output = '';

			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$shown_ids[] = get_the_ID();

				// wrap the items in li's.
				$output .= sprintf(
					'<li class="%1$s" >',
					implode( ' ', get_post_class( '', get_the_id() ) )
				);

				$context = array(
					'instance' => $instance,
					'thumb' => $thumb,
					'excerpt' => $excerpt,
				);

				ob_start();
				largo_render_template( 'partials/widget', 'content', $context );
				$output .= ob_get_clean();

				// close the item
				$output .= '</li>';

			} // endwhile.

			// print all of the items
			echo $output;

		} else {
			printf(
				'<p class="error"><strong>%1$s</strong></p>',
				sprintf(
					// translators: %s is the word this site uses for "posts", like "articles" or "stories". It's a plural noun.
					esc_html__( 'You don\'t have any recent %s', 'largo' ),
					of_get_option( 'posts_term_plural', 'Posts' )
				)
			);
		} // end more featured posts

		// close the ul
		echo '</ul>';

		// close the widget
		echo wp_kses_post( $args['after_widget'] );

		// Restore global $post
		wp_reset_postdata();
		$post = $preserve;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['post_id'] = (int) sanitize_text_field( $new_instance['post_id'] ); // a number
		return $instance;
	}

	public function form( $instance ) {
		$defaults = array(
			'title' => sprintf(
				// translators: %s is the word this site uses for "posts", like "articles" or "stories". It's a plural noun.
				__( 'Recent %1$s' , 'largo' ),
				of_get_option( 'posts_term_plural', 'Posts' )
			),
			'post_id' => null,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'largo' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:90%;" type="text" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>"><?php esc_html_e( 'ID of post to show:', 'mstoday' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_id' ) ); ?>" value="<?php echo esc_attr( $instance['post_id'] ); ?>" style="width:90%;" type="number" />
		</p>

		<?php
	}
}

/**
 * Register the widget
 */
add_action( 'widgets_init', function() {
	register_widget( 'Borderzine_3_Col_Widget' );
});
