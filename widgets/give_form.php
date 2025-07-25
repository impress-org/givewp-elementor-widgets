<?php

use Give\DonationForms\Models\DonationForm;
use Give\Framework\Database\DB;
use Give\Helpers\Form\Utils;

/**
 * Elementor Give Form Widget.
 *
 * Elementor widget that inserts the GiveWP [give_form] shrotcode to output a form total with options.
 *
 * @since 1.0.0
 */
class DW4Elementor_GiveWP_Form_Widget extends \Elementor\Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    /**
     * Get widget name.
     *
     * Retrieve Give Form widget name.
     *
     * @since  1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'Give Form';
    }

    /**
     * Get widget title.
     *
     * Retrieve Give Form widget title.
     *
     * @since  1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Give Form', 'dw4elementor');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Give Form widget icon.
     *
     * @since  1.0.0
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
     * Retrieve the list of categories the Give Form widget belongs to.
     *
     * @since  1.0.0
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
     * Register Give Form widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $forms        = $this->get_donation_forms_options();
        $legacyForms  = $this->get_legacy_forms($forms);
        $classicForms = $this->get_classic_forms($forms);
        $v3Forms      = $this->get_v3_forms($forms);

        $this->start_controls_section(
            'give_form_settings',
            [
                'label' => __('GiveWP Form Widget', 'dw4elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_id',
            [
                'label'       => __('Form ID', 'dw4elementor'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'description' => __('Choose the GiveWP Form you want to embed.', 'dw4elementor'),
                'default'     => '',
                'options'     => $forms,
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'       => __('Show Form Title', 'dw4elementor'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Show/hide the GiveWP form title.', 'dw4elementor'),
                'label_on'    => __('Show', 'dw4elementor'),
                'label_off'   => __('Hide', 'dw4elementor'),
                'default'     => 'yes',
                'conditions'  => [
                    'terms' => [
                        [
                            'name'     => 'form_id',
                            'operator' => '!in',
                            'value'    => $classicForms,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'show_goal',
            [
                'label'        => __('Show Goal', 'dw4elementor'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'description'  => __('Show/hide the progress bar and goal for this form.', 'dw4elementor'),
                'label_on'     => __('Show', 'dw4elementor'),
                'label_off'    => __('Hide', 'dw4elementor'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );


        $this->add_control(
            'show_content',
            [
                'label'        => __('Show Form Content', 'dw4elementor'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'description'  => __('Show/hide the content of this form.', 'dw4elementor'),
                'label_on'     => __('Show', 'dw4elementor'),
                'label_off'    => __('Hide', 'dw4elementor'),
                'return_value' => 'yes',
                'default'      => 'no',
                'conditions'   => [
                    'terms' => [
                        [
                            'name'     => 'form_id',
                            'operator' => 'in',
                            'value'    => $legacyForms,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'display_style',
            [
                'label'       => __('Form Display Style', 'dw4elementor'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'description' => __('Choose which display to use for this GiveWP form.', 'dw4elementor'),
                'options'     => [
                    'onpage' => __('Full Form', 'dw4elementor'),
                    'button' => __('Button Only', 'dw4elementor'),
                    'modal'  => __('Modal Reveal', 'dw4elementor'),
                    'reveal' => __('Reveal', 'dw4elementor'),
                ],
                'default'     => 'onpage',
            ]
        );

        $this->add_control(
            'continue_button_title',
            [
                'label'       => __('Reveal Button Text', 'dw4elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'description' => __('Text on the button that reveals the form.', 'dw4elementor'),
                'default'     => __('Continue to Donate', 'dw4elementor'),
                'condition'   => [
                    'display_style!' => 'onpage',
                ],
            ]
        );

        $this->add_control(
            'v3_notice',
            [
                'label'           => __('Important Note', 'dw4elementor'),
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => esc_html__(
                    'Form Display Style changes will not be visible for Donation forms created using the Visual Form Builder. Save the page and view it on the front end.',
                    'dw4elementor'
                ),
                'content_classes' => 'give-elementor-notice',
                'conditions'      => [
                    'terms' => [
                        [
                            'name'     => 'form_id',
                            'operator' => 'in',
                            'value'    => $v3Forms,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'give_form_info',
            [
                'label'           => '',
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'dw4e-info',
                'raw'             => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP FORM WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __(
                        'This is the GiveWP Form widget. Choose which form you want to embed on this page with it\'s form "ID".',
                        'dw4elementor'
                    ) . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/core/shortcodes/give_form/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __(
                                         'Visit the GiveWP Docs for more info on the GiveWP Form.',
                                         'dw4elementor'
                                     ) . '</a>
						</p>
				</div>',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the [give_form] output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_data('settings');

        $form_id               = (int)$this->get_settings('form_id');
        $show_title            = isset($settings['show_title']) ? $settings['show_title'] : 'true';
        $show_goal             = isset($settings['show_goal']) ? $settings['show_goal'] : 'true';
        $show_content          = isset($settings['show_content']) ? $settings['show_content'] : 'true';
        $display_style         = isset($settings['display_style']) ? $settings['display_style'] : 'onpage';
        $continue_button_title = isset($settings['continue_button_title']) ? $settings['continue_button_title'] : __('Continue to Donate', 'dw4elementor');

        if (isset($_POST['action']) && $_POST['action'] === 'elementor_ajax') {
            // is this v3 form?
            if (Utils::isV3Form($form_id)) {
                if ($donationForm = DonationForm::find($form_id)) {
                    $donationForm->settings->showHeading        = boolval($show_title);
                    $donationForm->settings->enableDonationGoal = boolval($show_goal);
                    $donationForm->save();
                }
            } else {
                // For some strange reason, passing show_goal attr to give_form shortcode doesn't work, so in order for this to work we have to enable/disable goal by updating meta
                give_update_meta($form_id, '_give_goal_option', $show_goal ? 'enabled' : 'disabled');
            }
        }

        $shortcode = sprintf(
            '[give_form id="%s" show_title="%s" show_goal="%s" show_content="%s" display_style="%s" continue_button_title="%s"]',
            $form_id,
            $show_title,
            $show_goal,
            $show_content,
            $display_style,
            $continue_button_title
        );

        echo '<div class="givewp-elementor-widget give-form-shortcode-wrap">';

        echo do_shortcode($shortcode);

        echo '</div>';
    }

    /**
     * @return array
     */
    private function get_donation_forms_options()
    {
        $options = [];

        $forms = DB::table('posts')
                   ->select('ID', 'post_title')
                   ->where('post_type', 'give_forms')
                   ->where('post_status', 'publish')
                   ->getAll();

        foreach ($forms as $form) {
            $options[$form->ID] = $form->post_title;
        }

        return $options;
    }

    /**
     * Get forms using legacy template from list of forms returned by DW4Elementor_GiveWP_Form_Widget::get_donation_forms_options
     *
     * @unlreased
     *
     * @param array $forms
     *
     * @return array
     */
    private function get_legacy_forms($forms)
    {
        $data = [];

        foreach (array_keys($forms) as $formId) {
            if ('legacy' === $this->get_form_template($formId)) {
                $data[] = (string)$formId;
            }
        }

        return $data;
    }

    /**
     * Get forms using classic template from list of forms returned by DW4Elementor_GiveWP_Form_Widget::get_donation_forms_options
     *
     * @unlreased
     *
     * @param array $forms
     *
     * @return array
     */
    private function get_classic_forms($forms)
    {
        $data = [];

        foreach (array_keys($forms) as $formId) {
            if ('classic' === $this->get_form_template($formId)) {
                $data[] = (string)$formId;
            }
        }

        return $data;
    }

    /**
     * Get v3 forms from list of forms returned by DW4Elementor_GiveWP_Form_Widget::get_donation_forms_options
     *
     * @unlreased
     *
     * @param array $forms
     *
     * @return array
     */
    private function get_v3_forms($forms)
    {
        $data = [];

        foreach (array_keys($forms) as $formId) {
            if (Utils::isV3Form((int)$formId)) {
                $data[] = (string)$formId;
            }
        }

        return $data;
    }

    /**
     * Get form template
     *
     * @since 2.0.0
     *
     * @param $formId
     *
     * @return string
     */
    private function get_form_template($formId)
    {
        return Give()->form_meta->get_meta($formId, '_give_form_template', true);
    }
}
