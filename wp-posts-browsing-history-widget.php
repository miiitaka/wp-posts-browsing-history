<?php
/**
 * Admin Widget Register
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Posts_Browsing_History_Widget extends WP_Widget {

	/**
	 * Constructor Define.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_options = array( 'description' => 'Posts Browsing History Widget' );
		parent::__construct( false, 'Posts Browsing History', $widget_options );
	}

	/**
	 * Widget Form Display.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $instance
	 * @return string Parent::Default return is 'noform'
	 */
	public function form( $instance ) {
		if ( !isset( $instance['title'] ) ) {
			$instance['title'] = "";
		}

		echo '<p>Title : ';
		printf(
			'<input type="text" id="%s" name="%s" value="%s">',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $instance['title'] )
		);
		echo '</p>';
	}

	/**
	 * Widget Form Update.
	 * @since  1.0.0
	 * @access public
	 * @param  array $new_instance
	 * @param  array $old_instance
	 * @return array Parent::Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	/**
	 * Widget Display.
	 * @since  1.0.0
	 * @access public
	 * @param  array $args
	 * @param  array $instance
	 *
	 */
	public function widget( $args, $instance ) {
		if ( isset( $_COOKIE['wp-posts-browsing-history'] ) ) {
			$query_args = array(
				"post__in" => explode( ',', esc_html( $_COOKIE['wp-posts-browsing-history'] ) ),
				"posts_per_page" => 10,
			);

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) {
				/** Widget header display. */
				echo $args['before_widget'];
				echo $args['before_title'];
				echo esc_html($instance['title']);
				echo $args['after_title'];
				echo $args['after_widget'];

				while ( $query->have_posts() ) {
					the_title();
					$query->the_post();
				}
				wp_reset_postdata();
			}
		}
	}
}