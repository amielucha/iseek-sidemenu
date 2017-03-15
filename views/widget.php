<?php
// Front-facing content
if ( $title ) {
	echo $args['before_title'] . $title . $args['after_title'];
}
?>
<?php
	$taxonomy = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';

	$terms = get_terms( array(
	    'taxonomy' => $taxonomy,
	    'hide_empty' => false,
	) );

?>
<div class="list-group">
	<?php wp_list_categories( array(
		taxonomy => $taxonomy,
		'hide_empty' => false,
		'hide_title_if_empty' => true,
		'title_li'            => $title ? $title : '',
		'style'               => 'nav',
		'depth'               => 3,
		'walker' => new ISeek_Bootstrap_Taxonomy_Walker
		//'show_count' => 1
	) ); ?>
</div>

<?php // http://wordpress.stackexchange.com/questions/67791/how-can-i-make-wp-list-categories-output-li-with-category-slug-as-class-for-its
// should I add it? @TODO ?>
