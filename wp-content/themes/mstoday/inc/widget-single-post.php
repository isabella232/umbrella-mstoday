<?php
/**
 * Single Post widget
 *
 * Displays a single post using the same template as Largo Recent Posts.
 *
 * @link https://github.com/INN/umbrella-mstoday/issues/72
 */

/**
 * The Mstoday Single Posts
 *
 * Based on the Largo Recent Posts cleanup in https://github.com/INN/umbrella-borderzine/blob/master/wp-content/themes/borderzine/inc/widgets/class-borderzine-3-col-widget.php
 * That cleanup work comes from https://github.com/INN/largo/issues/1807
 */
class MStoday_Single_Post extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname' => 'mstoday-single-post largo-recent-posts',
			'description' => __( 'Displays a single post.', 'mstoday' ),
		);
		parent::__construct(
			'mstoday-single-post', // Base ID
			__( 'MS Today Single Post', 'mstoday' ), // Name
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
		$post_id = isset( $instance['post_id'] ) ? $instance['post_id'] : null;

		$query_args = array (
			'post_status'    => 'publish',
			'p'              => $post_id,
		);

		/*
		 * here begins the widget output
		 */

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . wp_kses_post( $title ). $args['after_title'];
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

		if ( ! empty( $instance['linkurl'] ) && ! empty( $instance['linktext'] ) ) {
			echo '<p class="morelink"><a href="' . esc_url( $instance['linkurl'] ) . '">' . esc_html( $instance['linktext'] ) . '</a></p>';
		}

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

		$instance['thumbnail_display'] = sanitize_key( $new_instance['thumbnail_display'] );
		$instance['image_align'] = sanitize_key( $new_instance['image_align'] );
		$instance['excerpt_display'] = sanitize_key( $new_instance['excerpt_display'] );
		$instance['num_sentences'] = ( 0 > intval( $new_instance['num_sentences'] ) ) ? intval( $new_instance['num_sentences'] ) : 2 ;
		$instance['show_byline'] = ! empty( $new_instance['show_byline'] );
		$instance['hide_byline_date'] = ( ! empty( $new_instance['hide_byline_date'] ) ) ? $new_instance['hide_byline_date'] : false ;
		$instance['show_top_term'] = ! empty( $new_instance['show_top_term'] );

		$instance['linktext'] = sanitize_text_field( $new_instance['linktext'] );
		$instance['linkurl'] = esc_url_raw( $new_instance['linkurl'] );
		return $instance;
	}

	public function form( $instance ) {
		$defaults = array(
			'title' => __( 'Single Post' , 'largo' ),
			'post_id' => null,
			'thumbnail_display' => 'small',
			'image_align' => 'left',
			'excerpt_display' => 'num_sentences',
			'num_sentences' => 2,
			'show_byline' => '',
			'hide_byline_date' => false,
			'show_top_term' => '',
			'show_icon' => '',
			'linktext' => '',
			'linkurl' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$showbyline = $instance['show_byline'] ? 'checked="checked"' : '';
		$hidebylinedate = $instance['hide_byline_date'] ? 'checked="checked"' : '';
		$show_top_term = $instance['show_top_term'] ? 'checked="checked"' : '';
		$show_icon = $instance['show_icon'] ? 'checked="checked"' : '';

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'largo' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>"><?php esc_html_e( 'ID of post to show:', 'mstoday' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_id' ) ); ?>" value="<?php echo esc_attr( $instance['post_id'] ); ?>" style="clear: both;" class="widefat" type="number" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ); ?>">
				<?php echo wp_kses_post( __( 'The post ID is the <code>130248</code> in the <code>/wp-admin/post.php?post=130248&action=edit</code> part of the URL when you edit a post.', 'mstoday' ) ); ?>
			</label>
		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail_display' ) ); ?>"><?php esc_html_e( 'Thumbnail Image', 'largo' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_display' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_display' ) ); ?>" class="widefat" style="">
				<option <?php selected( $instance['thumbnail_display'], 'small' ); ?> value="small"><?php esc_html_e( 'Small (60x60)', 'largo' ); ?></option>
				<option <?php selected( $instance['thumbnail_display'], 'medium' ); ?> value="medium"><?php esc_html_e( 'Medium (140x140)', 'largo' ); ?></option>
				<option <?php selected( $instance['thumbnail_display'], 'large' ); ?> value="large"><?php esc_html_e( 'Large (Full width of the widget)', 'largo' ); ?></option>
				<option <?php selected( $instance['thumbnail_display'], 'none' ); ?> value="none"><?php esc_html_e( 'None', 'largo' ); ?></option>
			</select>
		</p>

		<!-- Image alignment -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_align' ) ); ?>"><?php esc_html_e( 'Image Alignment', 'largo' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'image_align' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_align' ) ); ?>" class="widefat" style="">
				<option <?php selected( $instance['image_align'], 'left' ); ?> value="left"><?php esc_html_e( 'Left align', 'largo' ); ?></option>
				<option <?php selected( $instance['image_align'], 'right' ); ?> value="right"><?php esc_html_e( 'Right align', 'largo' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_display' ) ); ?>"><?php esc_html_e( 'Excerpt Display', 'largo' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'excerpt_display' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_display' ) ); ?>" class="widefat" style="">
				<option <?php selected( $instance['excerpt_display'], 'num_sentences' ); ?> value="num_sentences"><?php esc_html_e( 'Use # of Sentences', 'largo' ); ?></option>
				<option <?php selected( $instance['excerpt_display'], 'custom_excerpt' ); ?> value="custom_excerpt"><?php esc_html_e( 'Use Custom Post Excerpt', 'largo' ); ?></option>
				<option <?php selected( $instance['excerpt_display'], 'none' ); ?> value="none"><?php esc_html_e( 'None', 'largo' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'num_sentences' ) ); ?>"><?php esc_html_e( 'Excerpt Length (# of Sentences):', 'largo' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'num_sentences' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_sentences' ) ); ?>" value="<?php echo (int) $instance['num_sentences']; ?>" style="" type="number" min="0"/>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $showbyline; ?> id="<?php echo esc_attr( $this->get_field_id( 'show_byline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_byline' ) ); ?>" /> <label for="<?php echo esc_attr( $this->get_field_id( 'show_byline' ) ); ?>"><?php esc_html_e( 'Show byline on posts?', 'largo' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $hidebylinedate; ?> id="<?php echo esc_attr( $this->get_field_id( 'hide_byline_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_byline_date' ) ); ?>" /> <label for="<?php echo esc_attr( $this->get_field_id( 'hide_byline_date' ) ); ?>"><?php esc_html_e( 'Hide the publish date in the byline?', 'largo' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php echo $show_top_term; ?> id="<?php echo esc_attr( $this->get_field_id( 'show_top_term' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_top_term' ) ); ?>" /> <label for="<?php echo esc_attr( $this->get_field_id( 'show_top_term' ) ); ?>"><?php esc_html_e( 'Show the top term on posts?', 'largo' ); ?></label>
		</p>

		<?php
			// only show this admin if the "Post Types" taxonomy is enabled.
			if ( taxonomy_exists( 'post-type' ) && of_get_option( 'post_types_enabled' ) ) {
				?>
					<p>
						<input class="checkbox" type="checkbox" <?php echo $show_icon; ?> id="<?php echo esc_attr( $this->get_field_id( 'show_icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_icon' ) ); ?>" /> <label for="<?php echo esc_attr( $this->get_field_id( 'show_icon' ) ); ?>"><?php esc_html_e( 'Show the post type icon?', 'largo' ); ?></label>
					</p>
				<?php
			}
		?>

		<p>
			<strong><?php esc_html_e( 'More Link', 'largo' ); ?></strong>
			<br />
			<small><?php esc_html_e( 'If you would like to add a more link at the bottom of the widget, add the link text and url here.', 'largo' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'linktext' ) ); ?>"><?php esc_html_e( 'Link text:', 'largo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linktext' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linktext' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['linktext'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'linkurl' ) ); ?>"><?php esc_html_e( 'URL:', 'largo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkurl' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkurl' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['linkurl'] ); ?>" />
		</p>

		<?php
	}
}

/**
 * Register the widget
 */
add_action( 'widgets_init', function() {
	register_widget( 'MStoday_Single_Post' );
});
