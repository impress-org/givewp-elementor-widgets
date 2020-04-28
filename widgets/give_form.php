<?php 
/**
 * Elementor Give Form Widget.
 *
 * Elementor widget that inserts the GiveWP [give_form] shrotcode to output a form total with options.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Form_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}
	
	/**
	 * Get widget name.
	 *
	 * Retrieve Give Form widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Give Form';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Give Form widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Give Form', 'dw4elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Give Form widget icon.
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
	 * Retrieve the list of categories the Give Form widget belongs to.
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
	 * Register Give Form widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'give_form_settings',
			[
				'label' => __( 'GiveWP Form Widget', 'dw4elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'give_form_info',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'dw4e-info',
				'raw' => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP FORM WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is the GiveWP Form widget. Choose which form you want to embed on this page with it\'s form "ID".', 'dw4elementor') . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/core/shortcodes/give_form/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __('Visit the GiveWP Docs for more info on the GiveWP Form.', 'dw4elementor') . '</a>
						</p>
				</div>'
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => __( 'Form ID', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Choose the GiveWP Form ID you want to embed.', 'dw4elementor' ),
				'input_type' => 'number',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Form Title', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show/hide the GiveWP form title.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_goal',
			[
				'label' => __( 'Show Goal', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show/hide the progress bar and goal for this form.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


		$this->add_control(
			'show_content',
			[
				'label' => __( 'Show Form Content', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show/hide the content of this form.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'display_style',
			[
				'label' => __( 'Form Display Style', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Choose which display to use for this GiveWP form.', 'dw4elementor' ),
				'options' => [
					'onpage' => __('Full Form','dw4elementor'),
					'button' => __('Button Only', 'dw4elementor'),
					'modal' => __('Modal Reveal', 'dw4elementor'),
					'reveal' => __('Reveal', 'dw4elementor')
				],
				'default' => 'onpage'
			]
		);

		$this->add_control(
			'continue_button_title',
			[
				'label' => __( 'Reveal Button Text', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Text on the button that reveals the form.', 'dw4elementor' ),
				'default' => __('Continue to Donate', 'dw4elementor'),
				'condition' => [
					'display_style!' => 'onpage',
				]
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the [give_form] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		global $give_receipt_args, $donation;

		$settings = $this->get_settings_for_display();

		$form_id = ( 'yes' === $settings['form_id'] ? '' : esc_attr($settings['form_id']));
		$show_title = ( 'yes' === $settings['show_title'] ? 'true' :  'false');
		$show_goal = ( 'yes' === $settings['show_goal'] ? 'true' :  'false');
		$show_content = ( 'yes' === $settings['show_content'] ? 'true' :  'false');
		$display_style = esc_attr( $settings['display_style'] );
		$continue_button_title = esc_attr( $settings['continue_button_title'] );

		$html = do_shortcode('
			[give_form 
				id="' . $form_id . '" 
				show_title="' . $show_title . '" 
				show_goal="' . $show_goal . '" 
				show_content="' . $show_content . '" 
				display_style="' . $display_style . '" 
				continue_button_title="' . $continue_button_title . '" 
				]'
		);

		echo '<div class="givewp-elementor-widget give-form-shortcode-wrap">';

		echo $html;

		echo '</div>';
	}

	private function get_donation_forms() {

		// Define variables.
		$result         = array();
		$post_data      = give_clean( $_POST );
		$search_keyword = ! empty( $post_data['search'] ) ? $post_data['search'] : '';

		// Setup the arguments to fetch the donation forms.
		$forms_query = new Give_Forms_Query(
			array(
				's'           => $search_keyword,
				'number'      => 30,
				'post_status' => 'publish',
			)
		);

		// Fetch the donation forms.
		$forms = $forms_query->get_forms();

		// Loop through each donation form.
		foreach ( $forms as $form ) {
			$result[] = array(
				'id'   => $form->ID,
				'name' => $form->post_title,
			);
		}

		echo wp_json_encode( $result );
		give_die();
	}
}