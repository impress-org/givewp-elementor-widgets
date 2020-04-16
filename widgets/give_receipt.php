<?php 
/**
 * Donation Widgets 4 Elementor GiveWP Receipt Widget.
 *
 * Elementor widget that inserts the GiveWP Donation Receipt onto any page.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Receipt_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
  
		wp_register_style( 'dw4elementor-admin-styles', GiveWP_DW_4_Elementor_URL . '/assets/dw4elementor-admin.css', array(), mt_rand(1,999), 'all');
	 }
  
	public function get_style_depends() {
	   return [ 'dw4elementor-admin-styles' ];
	}
	
	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'GiveWP Receipt';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'GiveWP Receipt', 'dw4elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'dw4elementor-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'givewp-category' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'GiveWP Receipt Settings', 'dw4elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'info',
			[
				'label' => __( '<strong>GiveWP Receipt</strong>', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<p><br />' . __( 'The GiveWP [give_receipt] shortcode does not have any options or settings to configure at all.', 'dw4elementor' ) . '</p>',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$html = do_shortcode('[give_receipt]');

		echo '<div class="givewp-elementor-widget give_receipt">';

		echo $html;

		echo '</div>';

	}

}