<?php 

function add_givewp_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'givewp-category',
		[
			'title' => __( 'GiveWP', 'dw4elementor' ),
			'icon' => 'dashicons dashicons-give',
		]
	);

}
add_action( 'elementor/elements/categories_registered', 'add_givewp_widget_categories' );