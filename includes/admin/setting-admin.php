<?php

// Exit, if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generates custom menu section and setting page
 *
 * @return void
 */
function lkn_autoconnect_wp_child_add_config_section() {
    add_menu_page(
        __('Autoconnect WP Child', 'lkn-autoconnect-wp-child'),
        __('Autoconnect WP Child', 'lkn-autoconnect-wp-child'),
        'manage_options',
        'lkn-autoconnect-wp-child-config',
        false,
        'dashicons-privacy',
        50
    );

    $hookname = add_submenu_page(
        'lkn-autoconnect-wp-child-config',
        __('Settings', 'lkn-autoconnect-wp-child'),
        __('Settings', 'lkn-autoconnect-wp-child'),
        'manage_options',
        'lkn-autoconnect-wp-child-config',
        'lkn_autoconnect_wp_child_render_config_page',
        1
    );

    add_action('load-' . $hookname, 'lkn_autoconnect_wp_child_configuration_form_handle');
}

add_action('admin_menu', 'lkn_autoconnect_wp_child_add_config_section');

function lkn_autoconnect_wp_child_render_config_page() {
    $status = get_option('lkn_autoconnect_wp_child_status', 'Disconnected');
    $userId = get_option('lkn_autoconnect_wp_child_login_user', '');
    $website = get_option('lkn_autoconnect_wp_child_website', '');

    if (!empty($userId)) {
        $user = get_user_by('id', $userId)->user_email;
    } ?>

    <div class="wrap">
        <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
        <?php settings_errors(); ?>
        <form action="<?php menu_page_url('lkn-autoconnect-wp-child-config') ?>" method="post" class="lkn-admin-form-wrap">
        <?php wp_nonce_field('lkn_client_save_config'); ?>
        <div class="lkn-autoconnect-wp-child-config-data">
            <div class="lkn-autoconnect-wp-child-row-wrap">
                <div class="lkn-autoconnect-wp-child-column-wrap">
                    <div class="input-row-wrap">
                        <label for="lkn_autoconnect_wp_child_status_input"><?php _e('Status', 'lkn-autoconnect-wp-child')?></label>
                        <input name="lkn_autoconnect_wp_child_status" type="text" id="lkn_autoconnect_wp_child_status_input" class="regular-text" value="<?php echo $status; ?>" required readonly>
                    </div>

                    <div class="input-row-wrap">
                        <label for="lkn_autoconnect_wp_child_website_input"><?php _e('Admin website', 'lkn-autoconnect-wp-child')?></label>
                        <input name="lkn_autoconnect_wp_child_website" type="text" id="lkn_autoconnect_wp_child_website_input" class="regular-text" value="<?php echo $website; ?>" required readonly>
                    </div>

                    <div class="input-row-wrap">
                        <label for="lkn_autoconnect_wp_child_user_input"><?php _e('User', 'lkn-autoconnect-wp-child')?></label>
                        <input name="lkn_autoconnect_wp_child_user" type="text" id="lkn_autoconnect_wp_child_user_input" class="regular-text" value="<?php echo $user; ?>" required readonly>
                    </div>

                    <!-- // TODO maybe delete?
                    <div class="lkn-autoconnect-wp-child-action-btn">
                        <?php // submit_button(__('Save'));?>
                    </div>
                    -->
                </div>
            </div>
        </div>
        </form>
    </div>
    <style>
    .lkn-autoconnect-wp-child-action-btn {
        display: flex;
        flex-direction: row;
        justify-content: end;
    }
    .lkn-autoconnect-wp-child-row-wrap {
        display: flex;
        flex-direction: row;
    }
    .lkn-autoconnect-wp-child-column-wrap {
        display: flex;
        background-color: white;
        flex-direction: column;
        padding: 15px;
        border: 1px solid #c3c4c7;
    }
    .input-row-wrap {
        display: flex;
        flex-direction: column;
        padding: 6px 0px;
    }
    .lkn-autoconnect-wp-child-notice-positive {
        background-color: green;
        display: flex;
        justify-content: center;
        color: white;
        padding: 8px;
        font-size: 1.1em;
        animation: hideAnimation 0s ease-in 5s;
        animation-fill-mode: forwards;
    }
    .lkn-autoconnect-wp-child-notice-negative {
        background-color: rgb(175, 2, 2);
        display: flex;
        justify-content: center;
        color: white;
        padding: 8px;
        font-size: 1.1em;
        animation: hideAnimation 0s ease-in 5s;
        animation-fill-mode: forwards;
    }
    @keyframes hideAnimation {
        to {
        visibility: hidden;
        width: 0;
        height: 0;
        }
    }
    </style>
    <?php
}

// TODO maybe delete?
function lkn_autoconnect_wp_child_configuration_form_handle() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'lkn_client_save_config')) {
            if (isset($_POST['lkn_autoconnect_wp_child_website']) && !empty($_POST['lkn_autoconnect_wp_child_website']) && isset($_POST['lkn_autoconnect_wp_child_identifier']) && !empty($_POST['lkn_autoconnect_wp_child_identifier'])) {
                $userId = get_current_user_id();
                $adminId = $_POST['lkn_autoconnect_wp_child_identifier'];
                $website = $_POST['lkn_autoconnect_wp_child_website'];

                if (get_option('lkn_autoconnect_wp_child_login_user') === false) {
                    add_option('lkn_autoconnect_wp_child_login_user', $userId);
                } else {
                    update_option('lkn_autoconnect_wp_child_login_user', $userId);
                }

                if (get_option('lkn_autoconnect_wp_child_identifier') === false) {
                    add_option('lkn_autoconnect_wp_child_identifier', $adminId);
                } else {
                    update_option('lkn_autoconnect_wp_child_identifier', $adminId);
                }

                if (get_option('lkn_autoconnect_wp_child_website') === false) {
                    add_option('lkn_autoconnect_wp_child_website', $website);
                } else {
                    update_option('lkn_autoconnect_wp_child_website', $website);
                }

                echo '<div class="lkn-autoconnect-wp-child-notice-positive">' . __('Settings successfully saved', 'lkn-autoconnect-wp-child') . '</div>';
            } else {
                echo '<div class="lkn-autoconnect-wp-child-notice-negative">' . __('Error on save settings', 'lkn-autoconnect-wp-child') . '</div>';
            }
        } else {
            echo '<div class="lkn-autoconnect-wp-child-notice-negative">' . __('Error on save settings', 'lkn-autoconnect-wp-child') . '</div>';
        }
    }
}
