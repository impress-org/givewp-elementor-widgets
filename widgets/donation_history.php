<?php 
/**
 * Elementor Donation History Widget.
 *
 * Elementor widget that inserts the GiveWP [donation_history] shrotcode to output a donor's full donation history table.
 *
 * @since 1.0.0
 */

class DW4Elementor_Donation_History_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}
	
	/**
	 * Get widget name.
	 *
	 * Retrieve Donation History widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Donation History';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Donation History widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Donation History', 'dw4elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Donation History widget icon.
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
	 * Retrieve the list of categories the Donation History widget belongs to.
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
	 * Register Donation History widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'donation_history_settings',
			[
				'label' => __( 'Donation History Widget', 'dw4elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => __( 'ID', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide the "ID" column.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'date',
			[
				'label' => __( 'Date', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a column with the date of the donation.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'donor',
			[
				'label' => __( 'Donor', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a column with the donors full name.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'no'
			]
		);

		$this->add_control(
			'amount',
			[
				'label' => __( 'Amount', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a column with the amount of the donation.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'status',
			[
				'label' => __( 'Status', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a column with the status of the payment.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'no'
			]
		);

		$this->add_control(
			'method',
			[
				'label' => __( 'Payment Method', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a column with the name of the payment method.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'no'
			]
		);

		$this->add_control(
			'give_history_info',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'dw4e-info',
				'raw' => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP DONATION HISTORY WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is the GiveWP Donation History widget. Choose which columns you want to have appear for your donors history.', 'dw4elementor') . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/core/shortcodes/donation_history/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __('Visit the GiveWP Docs for more info on the GiveWP Donation History.', 'dw4elementor') . '</a>
						</p>
				</div>'
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the [donation_history] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$id = ('yes' === $settings['form_id'] ? '' : 'id="false"');
		$donor = ('yes' === $settings['donor'] ? 'donor="true"' : '' );
		$date = ('yes' === $settings['date'] ? '' : 'date="false"' );
		$amount = ('yes' === $settings['amount'] ? '' : 'amount="false"' );
		$status = ('yes' === $settings['status'] ? 'status="true"' : '' );
		$method = ('yes' === $settings['method'] ? 'payment_method="true"' : '' );

		$html = do_shortcode('
			[donation_history ' 
				. $id . ' ' 
				. $donor . ' '
				. $date . ' '
				. $amount . ' '
				. $status . ' '
				. $method . 
				']'
			);

		echo '<div class="givewp-elementor-widget donation-history">';

		echo $html;

		echo '</div>';

	}

}