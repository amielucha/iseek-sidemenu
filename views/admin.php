<?php
	$taxonomy = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
	$taxonomies = get_taxonomies();
?>
<!-- This file is used to markup the administration form of the widget. -->
<?php
	$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
	$title = sanitize_text_field( $instance['title'] );
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

<p>
	<label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php _e( 'Select Taxonomy:' ); ?></label>
	<select id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">

		<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
		<?php foreach ( $taxonomies as $tax ) : ?>
			<option value="<?php echo esc_attr( $tax ); ?>" <?php selected( $taxonomy, esc_attr( $tax ) ); ?>>
				<?php echo esc_html( $tax ); ?>
			</option>
		<?php endforeach; ?>
	</select>
</p>
