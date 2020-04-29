<?php

/**
 * Notice
 *
 * Notice related functionality goes in this file.
 *
 * @since   1.0.0
 * @package WP
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('dw4elementor_review_notice')) {
    // Add an admin notice.
    add_action('admin_notices', 'dw4elementor_review_notice');
    /**
     *  Admin Notice to Encourage a Review or Donation.
     *
     *  @author Matt Cromwell
     *  @version 1.0.0
     */
    function dw4elementor_review_notice()
    {

        // Get current user.
        global $current_user, $pagenow;
        $user_id = $current_user->ID;

        // Get today's timestamp.
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        // Get the trigger date
        $triggerdate = get_option('dw4elementor_activation_date', false);

        $installed = (!empty($triggerdate) ? $triggerdate : '999999999999999');

        // First check whether today's date is greater than the install date plus the delay
        // Then check whether the user is a Super Admin or Admin on a non-Multisite Network
        // For testing live, comment out `$installed <= $today &&` from this conditional
        //if ( dw4elementor_is_super_admin( $current_user = $current_user ) == true ) {
        if ($installed <= $today && dw4elementor_is_super_admin($current_user = $current_user) == true) {

            // Make sure we're on the plugins page.
            if ('plugins.php' == $pagenow) {

                // If the user hasn't already dismissed our alert,
                // Output the activation banner.
                $nag_admin_dismiss_url = 'plugins.php?dw4elementor_review_dismiss=0';
                $user_meta             = get_user_meta($user_id, 'dw4elementor_review_dismiss');

                if (empty($user_meta)) {

?>
                    <div class="notice notice-success dw4elementor-review">
                        <?php
                        // For testing purposes
                        //echo '<p>Today = ' . $today . '</p>';
                        //echo '<p>Installed = ' . $installed . '</p>';
                        //echo '<p>Trigger = ' . $triggerdate . '</p>';
                        ?>
                        <i class="dashicons dashicons-heart"></i>
                        <p class="dw4elementor-review">
                            <?php _e('Hey, you\'ve been using "GiveWP Donation Widgets for Elementor" for a while now. We have some other free things in the GiveWP Newsletter that we think you\'ll love.', 'dw4elementor');
                            ?>
                        </p>
                        <?php include_once GiveWP_DW_4_Elementor_DIR . 'includes/admin/subscribe.php'; ?>

                        <a href="<?php echo admin_url($nag_admin_dismiss_url); ?>" class="dismiss"><span class="dashicons dashicons-dismiss"></span></a>

                    </div>

<?php }
            }
        }
    }
}


if (!function_exists('dw4elementor_ignore_review_notice')) {
    // Function to force the Review Admin Notice to stay dismissed correctly.
    add_action('admin_init', 'dw4elementor_ignore_review_notice');

    /**
     * Ignore review notice.
     *
     * @since  1.0.0
     */
    function dw4elementor_ignore_review_notice()
    {
        if (isset($_GET['dw4elementor_review_dismiss']) && '0' == $_GET['dw4elementor_review_dismiss']) {

            // Get the global user.
            global $current_user;
            $user_id = $current_user->ID;

            add_user_meta($user_id, 'dw4elementor_review_dismiss', 'true', true);
        }
    }
}

if (!function_exists('dw4elementor_is_super_admin')) {

    // Helper function to determine whether the current
    // user is a Super Admin or Admin on a non-Network environment
    function dw4elementor_is_super_admin($current_user)
    {
        global $current_user;

        $shownotice = false;

        if (is_multisite() && current_user_can('create_sites')) {
            $shownotice = true;
        } elseif (is_multisite() == false && current_user_can('install_plugins')) {
            $shownotice = true;
        } else {
            $shownotice = false;
        }

        return $shownotice;
    }
}
