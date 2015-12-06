<?php
/**
 * Admin ShortCode Settings
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Posted_Display_ShortCode {

	/**
	 * Variable definition.
	 *
	 * @since 1.0.0
	 */
	private $text_domain = 'wp-posted-display';

	/**
	 * ShortCode Display.
	 *
	 * @since   1.0.0
	 * @version 1.0.4
	 * @access  public
	 * @param   array  $args
	 * @return  string $html
	 */
	public function short_code_display ( $args ) {
		extract( shortcode_atts( array (
			'id'    => "",
			'posts' => "5",
			'sort'  => 0
		), $args ) );

		$instance = array(
			'id'    => $id,
			'posts' => $posts,
			'sort'  => $sort
		);

		/** DB Connect */
		$db      = new Posted_Display_Admin_Db();
		$results = $db->get_options( esc_html( (int) $id ) );
		$html    = '';

		if ( $results ) {
			$cookie_name = $this->text_domain . '-' . esc_html( (int) $id );
			$query_args  = $db->set_query( $results, $instance, $cookie_name );
			$query       = new WP_Query( $query_args );

			if ( $query->have_posts() ) {
				/** Display ShortCode body. */
				$html = '<ul>' . PHP_EOL;

				while ( $query->have_posts() ) {
					$query->the_post();

					if ( has_post_thumbnail( get_the_ID() ) ) {
						$images = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
					} else {
						$images[0] = isset( $results['template_no_image'] ) ? $results['template_no_image'] : '';
					}
					$html .= '<li>' . PHP_EOL;
					$html .= $db->set_template(
							$results['template'],
							get_the_title(),
							get_the_excerpt(),
							$images[0],
							get_the_time( get_option( 'date_format' ) ),
							get_the_permalink()
					);
					$html .= '</li>' . PHP_EOL;
				}
				wp_reset_postdata();

				$html .= '</ul>';
			}
		}
		return (string) $html;
	}
}