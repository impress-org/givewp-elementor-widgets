<?php
/**
 * Elementor Give Register Widget.
 *
 * Elementor widget that inserts the GiveWP [give_register] shrotcode to output a form total with options.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Register_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve Give Register widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Give Register';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Give Register widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Give Register', 'dw4elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Give Register widget icon.
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
	 * Retrieve the list of categories the Give Register widget belongs to.
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
	 * Register Give Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'give_register_settings',
			[
				'label' => __( 'GiveWP Register Widget', 'dw4elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'description' => __( 'The url you want to redirect users to after they submit the registration form.', 'dw4elementor' ),
				'show_external' => false,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->add_control(
			'give_register_info',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'dw4e-info',
				'raw' => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP REGISTER WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is the GiveWP Register widget. Allow your visitors to register on your website.', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is a sample register form with all fields exposed. This is only to help position and style the form with Elementor. If you want to see the live form, go to this page while logged out or in an Icognito browser.', 'dw4elementor') . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/core/shortcodes/give_register/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __('Visit the GiveWP Docs for more info on the GiveWP Register feature.', 'dw4elementor') . '</a>
						</p>
				</div>'
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the [give_register] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$link = esc_url( $settings['link']['url'] );


		$html = do_shortcode('
			[give_register redirect="' . $link . '"]'
		);

		ob_start(); ?>
		<form id="give-register-form" class="give-form">

		<fieldset>
			<legend>Register a New Account</legend>


			<div class="form-row form-row-first form-row-responsive">
				<label for="give-user-login">Username</label>
				<input id="give-user-login" class="required give-input" type="text" name="give_user_login" required="" aria-required="true">
			</div>

			<div class="form-row form-row-last form-row-responsive">
				<label for="give-user-email">Email</label>
				<input id="give-user-email" class="required give-input" type="email" name="give_user_email" required="" aria-required="true">
			</div>

			<div class="form-row form-row-first form-row-responsive">
				<label for="give-user-pass">Password</label>
				<input id="give-user-pass" class="password required give-input" type="password" name="give_user_pass" required="" aria-required="true">
			</div>

			<div class="form-row form-row-last form-row-responsive">
				<label for="give-user-pass2">Confirm PW</label>
				<input id="give-user-pass2" class="password required give-input" type="password" name="give_user_pass2" required="" aria-required="true">
			</div>


			<div class="give-hidden">
				<input type="hidden" name="give_honeypot" value="">
				<input type="hidden" name="give_action" value="user_register">
				<input type="hidden" name="give_redirect" value="http://example.org">
			</div>

			<div class="form-row">
				<input class="button" name="give_register_submit" type="submit" value="Register">
			</div>


		</fieldset>

		</form>
		<?php

		ob_get_contents();

		$preview = ob_get_clean();

		echo '<div class="givewp-elementor-widget give-register-shortcode-wrap">';

		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			echo $html;
		} else {
			echo $preview;
		}

		echo '</div>';
	}
}
