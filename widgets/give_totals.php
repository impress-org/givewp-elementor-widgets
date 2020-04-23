<?php 
/**
 * Elementor Give Totals Widget.
 *
 * Elementor widget that inserts the GiveWP [give_totals] shrotcode to output a form total with options.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Totals_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}
	
	/**
	 * Get widget name.
	 *
	 * Retrieve Give Totals widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Give Totals';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Give Totals widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Give Totals', 'dw4elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Give Totals widget icon.
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
	 * Retrieve the list of categories the Give Totals widget belongs to.
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
	 * Register Give Totals widget controls.
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
				'label' => __( 'Give Totals Arguments', 'dw4elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'forms',
			[
				'label' => __( 'Forms', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'description' => __( 'Choose the forms you want to combine in this total.', 'dw4elementor' ),
				'separator' => 'after',
				'options' => [
					'10' => "First Form"
				],
				'multiple' => true,
				'default' => array('10'),
			]
		);

		$this->add_control(
			'total_goal',
			[
				'label' => __( 'Goal Amount', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Designate a goal amount.', 'dw4elementor' ),
				'default' => '10000'
			]
		);


		$this->add_control(
			'message',
			[
				'label' => __( 'Message:', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description' => __( '(Optional) The total goal you want to reflect in your message.', 'dw4elementor' ),
				'default' => __( 'Hey! We\'ve raised {total} of the {total_goal} we are trying to raise for this campaign!', 'give' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'description' => __( 'The url of where you want to link donors to encourage them to donate toward the goal.', 'dw4elementor' ),
				'show_external' => false,
				'default' => [
					'url' => 'https://example.org',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => __( 'Link Text', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description' => __( 'The text you want to hyperlink with your link.', 'dw4elementor' ),
				'default' => __('Donate Now', 'dw4elementor'),
			]
		);

		$this->add_control(
			'show_progress',
			[
				'label' => __( 'Show Progress', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a the progress bar.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'cats',
			[
				'label' => __( 'Categories', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Comma separated list of GiveWP category IDs you want to combine into this total.', 'dw4elementor' ),
			]
		);

		$this->add_control(
			'tags',
			[
				'label' => __( 'Tags', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Comma separated list of GiveWP tag IDs you want to combine into this total.', 'dw4elementor' ),
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the [give_totals] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		global $give_receipt_args, $donation;

		$settings = $this->get_settings_for_display();

		$forms = $settings['forms'][0];
		$goal = esc_html( $settings['total_goal'] );
		$message = esc_html( $settings['message'] );
		$link = esc_url( $settings['link']['url'] );
		$link_text = esc_html( $settings['link_text'] );$show_progress = ('yes' === $settings['show_progress'] ? 'progress_bar="true"' : 'progress_bar="false"' );
		$cats = esc_html( $settings['cats'] );
		$tags = esc_html( $settings['tags'] );

		$html = do_shortcode('
			[give_totals 
				ids="' . $forms . '" 
				total_goal="' . $goal . '" 
				message="' . $message . '"
				link_text="' . $link_text . '"
				link="' . $link . '" '
				. $show_progress . ' 
				cats="' . $cats . '"
				tags="' . $tags . '"]'
			);

		echo '<div class="givewp-elementor-widget give-totals-shortcode-wrap">';

		echo $html;

		echo '</div>';
	}
}