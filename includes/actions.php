<?php

// Exit if accessed directly. ABSPATH is attribute in wp-admin - plugin.php
if (!defined('ABSPATH')) {
    exit;
}

function lkn_autoconnect_wp_child_listen_for_login() {
    // TODO add key connection
    if (isset($_POST['action']) && isset($_POST['data']) && $_POST['action'] === 'generatesso') {
        $decodedInfo = json_decode(base64_decode($_POST['data']));
        $origin = $_SERVER['HTTP_ORIGIN'];

        if (get_option('lkn_autoconnect_wp_child_identifier') === false) {
            add_option('lkn_autoconnect_wp_child_identifier', $decodedInfo->authkey);
        } else {
            update_option('lkn_autoconnect_wp_child_identifier', $decodedInfo->authkey);
        }

        if (get_option('lkn_autoconnect_wp_child_website') === false) {
            add_option('lkn_autoconnect_wp_child_website', $origin);
        } else {
            update_option('lkn_autoconnect_wp_child_website', $origin);
        }

        if (get_option('lkn_autoconnect_wp_child_status') === false) {
            add_option('lkn_autoconnect_wp_child_status', 'Conectado');
        } else {
            update_option('lkn_autoconnect_wp_child_status', 'Conectado');
        }
    }

    // TODO treat login request via POST
    if (isset($_GET['action']) && isset($_GET['i']) && $_GET['action'] === 'validatesso') {
        // TODO add salt/nonce validation
        // TODO add website validation
        // TODO how to not get brute forced?
        $decodedInfo = json_decode(base64_decode($_GET['i']));

        $authKey = $decodedInfo->auth;
        $origin = $decodedInfo->origin; // get_site_url()

        $userLogin = get_option('lkn_client_login_user');

        if ($userLogin == false) {
            wp_redirect(admin_url('index.php'));
            exit;
        } else {
            $authkeyClient = get_option('lkn_autoconnect_wp_child_identifier');
            $admWebsite = get_option('lkn_autoconnect_wp_child_website');

            if ($authKey === $authkeyClient && $origin === $admWebsite) {
                // TODO check user permission
                wp_set_auth_cookie((int) $userLogin);
                wp_redirect(admin_url('index.php'));
                exit;
            } else {
                wp_die('origin url: ' . $origin . ' admwebsite: ' . $admWebsite . ' ||authkey: ' . $authKey . ' authkeyclient: ' . $authkeyClient, 'Authentication error');
            }
        }
    }
}

add_filter('init', 'lkn_autoconnect_wp_child_listen_for_login', 10, 0);
