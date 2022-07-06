<?php

/**
 * Elementor Give Goal Widget.
 *
 * Elementor widget that inserts the GiveWP [give_goal] shrotcode to output a login form.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Goal_Widget extends \Elementor\Widget_Base
{

	public function __construct($data = [], $args = null)
	{
		parent::__construct($data, $args);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve Give Goal widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'Give Goal';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Give Goal widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('Give Goal', 'dw4elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Give Goal widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'dw4elementor-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Give Goal widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['givewp-category'];
	}

	/**
	 * Goal Give Goal widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{

		$this->start_controls_section(
			'give_login_settings',
			[
				'label' => __('GiveWP Goal Widget', 'dw4elementor'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => __('Form ID', 'dw4elementor'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number'
			]
		);

		$this->add_control(
			'show_text',
			[
				'label' => __( 'Show Text', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide the goal text.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_bar',
			[
				'label' => __( 'Show Progress Bar', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide progress bar.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'give_goal_info',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'dw4e-info',
				'raw' => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP GOAL WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is the GiveWP Goal widget. Choose how you want your form goal to be displayed. Note that this widget only supports forms that have a goal enabled. If you want to show progress of any form or multiple forms, use the "GiveWP Totals" widget instead.', 'dw4elementor') . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/core/shortcodes/give_goal/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __('Visit the GiveWP Docs for more info on the GiveWP Goal.', 'dw4elementor') . '</a>
						</p>
				</div>'
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the [give_goal] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$id = esc_html( $settings['form_id'] );
		$show_text = esc_html( $settings['show_text'] );
		$show_bar = esc_html( $settings['show_bar'] );

		$html = do_shortcode(
			'[give_goal 
				id="' . $id . '" 
				show_text="' . $show_text . '" 
				show_bar="' . $show_bar . '"
				]'
		);

		echo '<div class="givewp-elementor-widget give-login-shortcode-wrap">' . $html . '</div>';

	}
}
