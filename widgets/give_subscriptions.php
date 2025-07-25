<?php

/**
 * Elementor GiveWP Subscriptions Widget.
 *
 * Elementor widget that inserts the GiveWP [give_subscriptions] shrotcode to output a donor's full donation history table.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Subscriptions_Widget extends \Elementor\Widget_Base
{

	public function __construct($data = [], $args = null)
	{
		parent::__construct($data, $args);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve GiveWP Subscriptions widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'GiveWP Subscriptions';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve GiveWP Subscriptions widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('GiveWP Subscriptions', 'dw4elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve GiveWP Subscriptions widget icon.
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
	 * Retrieve the list of categories the GiveWP Subscriptions widget belongs to.
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
	 * Widget inner wrapper.
	 *
	 * Use optimized DOM structure, without the inner wrapper.
	 *
	 * @since 2.0.3
	 * @access public
	 */
	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	/**
	 * Register GiveWP Subscriptions widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{

		$this->start_controls_section(
			'give_subscriptions_settings',
			[
				'label' => __('GiveWP Subscriptions Widget', 'dw4elementor'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_status',
			[
				'label' => __('Status', 'dw4elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __('Show or hide the subscription status column.', 'dw4elementor'),
				'label_on' => __('Show', 'dw4elementor'),
				'label_off' => __('Hide', 'dw4elementor'),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_renewal_date',
			[
				'label' => __('Renewal Date', 'dw4elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __('Show or hide a column with the subscription renewal date.', 'dw4elementor'),
				'label_on' => __('Show', 'dw4elementor'),
				'label_off' => __('Hide', 'dw4elementor'),
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_progress',
			[
				'label' => __('Progress', 'dw4elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __('Show or hide a column with progress of the subscription.', 'dw4elementor'),
				'label_on' => __('Show', 'dw4elementor'),
				'label_off' => __('Hide', 'dw4elementor'),
				'default' => 'no'
			]
		);

		$this->add_control(
			'show_start_date',
			[
				'label' => __('Start Date', 'dw4elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __('Show or hide a column with the subscription start date.', 'dw4elementor'),
				'label_on' => __('Show', 'dw4elementor'),
				'label_off' => __('Hide', 'dw4elementor'),
				'default' => 'no'
			]
		);

		$this->add_control(
			'show_end_date',
			[
				'label' => __('End Date', 'dw4elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __('Show or hide a column with the subscription end date.', 'dw4elementor'),
				'label_on' => __('Show', 'dw4elementor'),
				'label_off' => __('Hide', 'dw4elementor'),
				'default' => 'no'
			]
		);

		$this->add_control(
			'subscriptions_per_page',
			[
				'label' => __('Subscriptions per Page', 'dw4elementor'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __('The number of subscriptions to show before pagination appears.', 'dw4elementor'),
				'input_type' => 'number',
				'default' => '30'
			]
		);

		$this->add_control(
			'give_subscriptions_info',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'dw4e-info',
				'raw' => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP SUBSCRIPTION HISTORY WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is the GiveWP Subscriptions widget. Choose which columns you want to have appear for your donors subscription history.', 'dw4elementor') . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/add-ons/recurring-donations/managing-subscriptions/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __('Visit the GiveWP Docs for more info on the GiveWP Subscriptions table.', 'dw4elementor') . '</a>
						</p>
				</div>'
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the [give_subscriptions] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{

		$settings = $this->get_settings_for_display();

		$show_status = ('yes' === $settings['show_status'] ? '' : 'show_status="false"');
		$show_renewal_date = ('yes' === $settings['show_renewal_date'] ? 'show_renewal_date="true"' : '');
		$show_progress = ('yes' === $settings['show_progress'] ? '' : 'show_progress="false"');
		$show_start_date = ('yes' === $settings['show_start_date'] ? '' : 'show_start_date="false"');
		$show_end_date = ('yes' === $settings['show_end_date'] ? 'show_end_date="true"' : '');
		$subscriptions_per_page = ('yes' === $settings['subscriptions_per_page'] ? 'subscriptions_per_page="true"' : '');

		if (!\Elementor\Plugin::$instance->editor->is_edit_mode()) {

			$html = do_shortcode(
				'
			[give_subscriptions '
					. $show_status . ' '
					. $show_renewal_date . ' '
					. $show_progress . ' '
					. $show_start_date . ' '
					. $show_end_date . ' '
					. $subscriptions_per_page .
					']'
			);
		} else {
			ob_start(); ?>
			<table id="give_user_history" class="give-table">
				<thead>
					<tr class="give_purchase_row">
							<th><?php _e('Subscription', 'dw4elementor'); ?></th>
						<?php if ('yes' === $settings['show_status']) : ?>
							<th><?php _e('Status', 'dw4elementor'); ?></th>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_renewal_date']) : ?>
							<th><?php _e('Renewal Date', 'dw4elementor'); ?></th>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_progress']) : ?>
							<th><?php _e('Progress', 'dw4elementor'); ?></th>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_start_date']) : ?>
							<th><?php _e('Start Date', 'dw4elementor'); ?></th>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_end_date']) : ?>
							<th><?php _e('End Date', 'dw4elementor'); ?></th>
						<?php endif; ?>
							<th><?php _e('Actions', 'dw4elementor'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="give-subscription-name"><?php _e('Form with a Goal', 'dw4elementor'); ?></span><br>
							<span class="give-subscription-billing-cycle">
								$25.00 / <?php _e('Monthly', 'dw4elementor'); ?> </span>
						</td>
						<?php if ('yes' === $settings['show_status']) : ?>
						<td>
							<span class="give-subscription-status"><span class="give-donation-status status-active"><span class="give-donation-status-icon"></span> <?php _e('Active', 'dw4elementor'); ?></span></span>
						</td>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_renewal_date']) : ?>
						<td>
							<span class="give-subscription-renewal-date">
								<?php _e('Auto renew on June 4, 2020', 'dw4elementor'); ?> </span>
						</td>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_progress']) : ?>
						<td>
							<span class="give-subscription-times-billed">1 / <?php _e('Ongoing', 'dw4elementor'); ?></span>
						</td>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_start_date']) : ?>
						<td>
							<?php _e('May 4, 2020', 'dw4elementor'); ?>
						</td>
						<?php endif; ?>
						<?php if ('yes' === $settings['show_end_date']) : ?>
						<td>
						<?php _e('Ongoing', 'dw4elementor'); ?>
						</td>
						<?php endif; ?>
						<td>
							<a href="#"><?php _e('View Receipt', 'dw4elementor'); ?></a>
							&nbsp;|&nbsp;
							<a href="#" class="give-cancel-subscription"><?php _e('Cancel', 'dw4elementor'); ?></a>
						</td>
					</tr>
				</tbody>
			</table>

<?php
			ob_get_contents();
			$html = ob_get_clean();
		}

		echo '<div class="givewp-elementor-widget donation-history">';

		echo $html;

		echo '</div>';
	}
}
