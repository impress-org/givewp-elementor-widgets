<?php 
/**
 * Elementor Donation Receipt Widget.
 *
 * Elementor widget that inserts the GiveWP [give_receipt] shrotcode to output a donor's full Donation Receipt table.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Receipt_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}
	
	/**
	 * Get widget name.
	 *
	 * Retrieve Donation Receipt widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Donation Receipt';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Donation Receipt widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Donation Receipt', 'dw4elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Donation Receipt widget icon.
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
	 * Retrieve the list of categories the Donation Receipt widget belongs to.
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
	 * Register Donation Receipt widget controls.
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
				'label' => __( 'GiveWP Donation Receipt Widget', 'dw4elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'error',
			[
				'label' => __( 'Error Message', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description' => __( 'Custom text to show if there is an error showing the receipt table.', 'dw4elementor' ),
				'default' => __( 'You are missing the donation id to view this donation receipt.', 'give' ),
				'placeholder' => __( 'You are missing the donation id to view this donation receipt.', 'give' ),
			]
		);

		$this->add_control(
			'success',
			[
				'label' => __( 'Success Message', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description' => __( 'Custom text to show if the donation was successful.', 'dw4elementor' ),
				'default' => __( 'Thank you for your donation.', 'give' ),
				'placeholder' => __( 'Thank you for your donation.', 'give' ),
			]
		);

		$this->add_control(
			'price',
			[
				'label' => __( 'Donation Total', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a row with the total donation amount.', 'dw4elementor' ),
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
				'description' => __( 'Show or hide a row with the donors full name.', 'dw4elementor' ),
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
				'description' => __( 'Show or hide a row with the date of the donation.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'method',
			[
				'label' => __( 'Payment Method', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a row with the name of the payment type (credit card, offline, etc).', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'payment_id',
			[
				'label' => __( 'Payment ID', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a row with the ID of the donation.', 'dw4elementor' ),
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
				'description' => __( 'Show or hide a row with the status of the payment.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'no'
			]
		);

		$this->add_control(
			'company',
			[
				'label' => __( 'Company Name', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide a row with the company name the donor provided.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'no'
			]
		);

		$this->add_control(
			'status_notice',
			[
				'label' => __( 'Payment Status Notice', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show or hide an alert above the receipt table showing the status of the donation payment.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'give_receipt_info',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'dw4e-info',
				'raw' => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP RECEIPT WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is the GiveWP Receipt widget. Choose the columns you want to have appear for your donor receipts.', 'dw4elementor') . '</p>
						<p class="info-message"><strong>' . __('NOTE:', 'dw4elementor') . '</strong> ' . __('This is a sample receipt with all fields exposed. The alerts and info will show correctly for your donors. This receipt is just for preview/editing purposes.', 'dw4elementor') . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/core/shortcodes/give_receipt/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __('Visit the GiveWP Docs for more info on the GiveWP Donation Receipt.', 'dw4elementor') . '</a>
						</p>
				</div>'
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the [give_receipt] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		global $give_receipt_args, $donation;

		$settings = $this->get_settings_for_display();

		$error = esc_html( $settings['error'] );
		$success = esc_html( $settings['success'] );
		$price = ('yes' === $settings['price'] ? '' : 'price="false"' );
		$donor = ('yes' === $settings['donor'] ? '' : 'donor="false"' );
		$date = ('yes' === $settings['date'] ? '' : 'date="false"' );
		$method = ('yes' === $settings['method'] ? '' : 'payment_method="false"' );
		$id = ('yes' === $settings['payment_id'] ? '' : 'payment_id="false"');
		$status = ('yes' === $settings['status'] ? 'payment_status="true"' : '' );
		$company = ('yes' === $settings['company'] ? 'company_name="true"' : '' );
		$notice = ('yes' === $settings['status_notice'] ? '' : 'status_notice="false"');
		
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {

		$html = do_shortcode('
			[give_receipt ' 
				. $error . ' '
				. $price . ' '
				. $donor . ' '
				. $date . ' '
				. $method . ' '
				. $id . ' ' 
				. $status . ' '
				. $company . ' '
				. $notice .
				']'
			);

		echo '<div class="givewp-elementor-widget give_receipt">';

		echo $html;

		echo '</div>';

		} else { 
			do_action( 'give_payment_receipt_header_before', $donation, $give_receipt_args );
			?>
			<div id="give-receipt">
				<div class="give_notices give_errors" id="give_error_fail">
					<p class="give_notice give_error">
						<?php echo (!empty($error) ? $error : __('You are missing the donation ID to view this donation receipt.', 'dw4elementor')); ?>
					</p>		
				</div>
				<?php if ( 'yes' == $settings['status_notice'] ) : ?>
				<div class="give_notices give_errors" id="give_error_success">
					<p class="give_notice give_success">
						<?php echo (!empty($success) ? $success : __('Thank you for your donation.', 'dw4elementor')); ?>
					</p>		
				</div>
				<?php endif; ?>
				<?php do_action( 'give_payment_receipt_before_table', $donation, $give_receipt_args ); ?>
			<table id="give_donation_receipt" class="give-table">
				<thead>
					<tr>
						<th scope="colgroup" colspan="2">
							<span class="give-receipt-thead-text">Donation Receipt</span>
						</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					if ( 'yes' == $settings['donor'] ) : ?>
					<tr>
						<td scope="row"><strong>Donor</strong></td>
						<td>Test Donor</td>
					</tr>
					<?php endif; 
					if ( 'yes' == $settings['company'] ) : ?>
					<tr>
						<td scope="row"><strong>Company Name</strong></td>
						<td>Impress.org</td>
					</tr>
					<?php endif; 
					if ( 'yes' == $settings['date'] ) : ?>
					<tr>
						<td scope="row"><strong>Date</strong></td>
						<td>April 18, 2020</td>
					</tr>
					<?php endif; 
					if ( 'yes' == $settings['price'] ) : ?>
					<tr>
						<td scope="row"><strong>Total Donation</strong></td>
						<td>$25.00</td>
					</tr>
					<?php endif; ?>
					<tr>
						<td scope="row"><strong>Donation</strong></td>
						<td>First Form<span class="donation-level-text-wrap"></span></td>
					</tr>
					<?php 
					if ( 'yes' == $settings['status'] ) : ?>
					<tr>
						<td scope="row"><strong>Donation Status</strong></td>
						<td>Complete</td>
					</tr>
					<?php endif; 
					if ( 'yes' == $settings['id'] ) : ?>
					<tr>
						<td scope="row"><strong>Donation ID</strong></td>
						<td>3</td>
					</tr>
					<?php endif; 
					if ( 'yes' == $settings['method'] ) : ?>
					<tr>
						<td scope="row"><strong>Payment Method</strong></td>
						<td>Test Donation</td>
					</tr>	
					<?php endif; ?>
				</tbody>
			</table>
			<?php do_action( 'give_payment_receipt_after_table', $donation, $give_receipt_args ); ?>
		</div>
		<? }
	}
}