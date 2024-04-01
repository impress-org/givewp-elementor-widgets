<?php
/**
 * Elementor Give Form Grid Widget.
 *
 * Elementor widget that inserts the GiveWP [give_form_grid] shrotcode to output a form total with options.
 *
 * @since 1.0.0
 */

class DW4Elementor_GiveWP_Form_Grid_Widget extends \Elementor\Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve Give Form Grid widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'GiveWP_Form_Grid';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Give Form Grid widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'GiveWP Form Grid', 'dw4elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Give Form Grid widget icon.
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
	 * Retrieve the list of categories the Give Form Grid widget belongs to.
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
	 * Register Give Form Grid widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 2.0.1 Changes 'donate_button_text_color' default value and removes 'show_bar' and 'donate_button_background_color' options as those don't exist in the form grid shortcode.
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'give_form_grid_settings',
			[
				'label' => __( 'GiveWP Form Grid Widget', 'dw4elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'forms_cats_tags',
			[
				'label' => __( 'Collection Method', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __('Would you like to show forms by ID, by their Categories, or by their Tags?', 'dw4elementor'),
				'options' => [
					'forms' => __( 'Forms', 'plugin-domain' ),
					'cats' => __( 'Categories', 'plugin-domain' ),
					'tags' => __( 'Tags', 'plugin-domain' ),
				],
				'default' => 'forms',
			]
		);

		$this->add_control(
			'all_forms',
			[
				'label' => __( 'Show All Forms?', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'description' => __( 'Do you want to show all forms in your grid?', 'dw4elementor' ),
				'options' => [
					'yes' => [
						'title' => __( 'Yes', 'dw4elementor' ),
						'icon' => 'fa fa-check',
					],
					'no' => [
						'title' => __( 'No', 'dw4elementor' ),
						'icon' => 'fa fa-times-circle',
					],
				],
				'default' => 'yes',
				'toggle' => true,
				'condition' => [
					'forms_cats_tags' => 'forms'
				]
			]
		);

		$this->add_control(
			'form_ids',
			[
				'label' => __( 'Show by Form IDs', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Place the form IDs you\'d like to show in your grid here, separated by commas.', 'dw4elementor' ),
				'condition' => [
					'all_forms' => 'no',
					'forms_cats_tags' => 'forms'
				]
			]
		);

		$this->add_control(
			'cats',
			[
				'label' => __( 'Show by Form Categories', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Place the form category IDs you\'d like to show in your grid here, separated by commas.', 'dw4elementor' ),
				'condition' => [
					'forms_cats_tags' => 'cats'
				]
			]
		);

		$this->add_control(
			'tags',
			[
				'label' => __( 'Show by Form Tags', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Place the form category IDs you\'d like to show in your grid here, separated by commas.', 'dw4elementor' ),
				'condition' => [
					'forms_cats_tags' => 'tags'
				]
			]
		);

		$this->add_control(
			'exclude',
			[
				'label' => __( 'Exclude Forms', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'List the form IDs that you want excluded from this Form Grid.', 'dw4elementor' ),
			]
		);

		$this->add_control(
			'forms_per_page',
			[
				'label' => __( 'Forms Per Page', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The number of forms to show in the grid before the "Load More" button appears.', 'dw4elementor' ),
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12',
				],
				'default' => '12'
			]
		);

        $this->add_control(
            'paged',
            [
                'label' => __( 'Show Pagination', 'dw4elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'dw4elementor' ),
                'label_off' => __( 'Hide', 'dw4elementor' ),
                'default' => 'yes',
            ]
        );

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The element which the order will be determined by.', 'dw4elementor' ),
				'options' => [
					'date' => __('Date Created', 'dw4elementor'),
					'title' => __('Form Name', 'dw4elementor'),
					'amount_donated' => __('Amount Donated', 'dw4elementor'),
					'number_donations' => __('Number of Donations', 'dw4elementor'),
					'menu_order' => __('Menu Order', 'dw4elementor'),
					'post__in' => __('Form ID', 'dw4elementor'),
					'closest_to_goal' => __('Closest to Goal', 'dw4elementor'),
				],
				'default' => 'date'
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order By', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The order the donors will be displayed in according to the "Order By" field chosen.', 'dw4elementor' ),
				'options' => [
					'desc' => __('Descending', 'dw4elementor'),
					'asc' => __('Ascending', 'dw4elementor')
				],
				'default' => 'desc'
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Number of Columns', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The number of columns to display. Note that "Best Fit" will always stretch to fill the available width that the donor wall is placed within.', 'dw4elementor' ),
				'options' => [
					'best-fit' => __('Best Fit', 'dw4elementor'),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'default' => 'best-fit'
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Form Title', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show/Hide the form title.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Form Excerpt', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show/Hide the form excerpt.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes',
			]
		);

        $this->add_control(
            'excerpt_length',
            [
                'label' => __( 'Excerpt Length', 'dw4elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'Excerpt Length.', 'dw4elementor' ),
                'condition' => [
                    'show_excerpt' => 'yes'
                ]
            ]
        );

        $this->add_control(
			'show_goal',
			[
				'label' => __( 'Show Form Goal', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show/Hide the form goal.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_featured_image',
			[
				'label' => __( 'Show Form Featured Image', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Show/Hide the form featured image.', 'dw4elementor' ),
				'label_on' => __( 'Show', 'dw4elementor' ),
				'label_off' => __( 'Hide', 'dw4elementor' ),
				'default' => 'yes',
			]
		);

        $this->add_control(
            'image_size',
            [
                'label' => __( 'Image Size', 'dw4elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'Featured image size. Default "medium". Accepts WordPress image sizes.', 'dw4elementor' ),
                'condition' => [
                    'show_featured_image' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'image_height',
            [
                'label' => __( 'Image Height', 'dw4elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'Featured image height. Default "auto". Accepts valid CSS heights', 'dw4elementor' ),
                'condition' => [
                    'show_featured_image' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_donate_button',
            [
                'label' => __( 'Show Donate Button', 'dw4elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'dw4elementor' ),
                'label_off' => __( 'Hide', 'dw4elementor' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'donate_button_text_color',
            [
                'label' => __( 'Donate Button Text Color', 'dw4elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#69B86B',
                'condition' => [
                    'show_donate_button' => 'yes'
                ],
            ]
        );

		$this->add_control(
			'display_style',
			[
				'label' => __( 'Display Type', 'dw4elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Choose the behavior when a form is clicked on within the grid.', 'dw4elementor' ),
				'options' => [
					'redirect' => __('Redirect to the single Form Page.', 'dw4elementor'),
					'modal_reveal' => __('Open the form in a modal window.', 'dw4elementor'),
				],
				'default' => 'redirect'
			]
		);

		$this->add_control(
			'give_form_grid_info',
			[
				'label' => '',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'dw4e-info',
				'raw' => '
					<div class="dw4e">
						<p class="info-head">
							' . __('GIVEWP FORM GRID WIDGET', 'dw4elementor') . '</p>
						<p class="info-message">' . __('This is the GiveWP Form Grid widget. Choose the elements you want to see appear in the form grid.', 'dw4elementor') . '</p>
						<p class="dw4e-docs-links">
							<a href="https://givewp.com/documentation/core/shortcodes/give_form_grid/?utm_source=plugin_settings&utm_medium=referral&utm_campaign=Free_Addons&utm_content=dw4elementor" rel="noopener noreferrer" target="_blank"><i class="fa fa-book" aria-hidden="true"></i>' . __('Visit the GiveWP Docs for more info on the GiveWP Form Grid.', 'dw4elementor') . '</a>
						</p>
				</div>'
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the [give_form_grid] output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
     *
     * @since 2.0.1 Changes 'donate_button_text_color' default value and removes 'show_bar' and 'donate_button_background_color' options as those don't exist in the form grid shortcode.
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		global $give_receipt_args, $donation;

		$settings = $this->get_settings_for_display();

		$forms = ( 'yes' === $settings['all_forms'] ? '' : $settings['form_ids']);
		$forms_per_page = esc_html( $settings['forms_per_page'] );
		$paged = esc_html( $settings['paged'] );
		$columns = esc_html( $settings['columns'] );
		$orderby = esc_html( $settings['orderby'] );
		$order = esc_html( $settings['order'] );
		$exclude = esc_html( $settings['exclude'] );
		$cats = esc_html( $settings['cats'] );
		$tags = esc_html( $settings['tags'] );
		$show_title = esc_html( $settings['show_title'] );
		$show_goal = esc_html( $settings['show_goal'] );
		$show_excerpt = esc_html( $settings['show_excerpt'] );
        $excerpt_length = esc_html( $settings['excerpt_length'] );
		$show_featured_image = esc_html( $settings['show_featured_image'] );
		$display_style = esc_html( $settings['display_style'] );
		$image_size = esc_html( $settings['image_size'] );
		$image_height = esc_html( $settings['image_height'] );
        $show_donate_button = esc_html($settings['show_donate_button']);
		$button_text_color = esc_html( $settings['donate_button_text_color'] );

		$html = do_shortcode('
			[give_form_grid 
				forms_per_page="' . $forms_per_page . '" 
				paged="' . $paged . '" 
				ids="' . $forms . '" 
				columns="' . $columns . '" 
				order="' . $order . '" 
				exclude="' . $exclude . '" 
				cats="' . $cats . '" 
				tags="' . $tags . '" 
				show_title="' . $show_title . '" 
				show_goal="' . $show_goal . '" 				 
				show_excerpt="' . $show_excerpt . '" 
				excerpt_length="' . $excerpt_length . '" 
				show_featured_image="' . $show_featured_image . '" 
				image_size="' . $image_size . '" 
				image_height="' . $image_height . '" 
				show_donate_button="' . $show_donate_button . '" 				 
				donate_button_text_color="' . $button_text_color . '" 
				display_style="' . $display_style . '" 
				orderby="' . $orderby .
				'"]'
		);

		echo '<div class="givewp-elementor-widget give-form-grid-shortcode-wrap">';

		echo $html;

		echo '</div>';
	}
}
