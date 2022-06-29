<?php

// Exit if accessed directly. ABSPATH is attribute in wp-admin - plugin.php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin row meta links.
 *
 * @since
 *
 * @param array $plugin_meta An array of the plugin's metadata.
 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
 *
 * @return array
*/
function __lkn_autoconnect_wp_child_plugin_row_meta($plugin_meta, $plugin_file) {
    // Settings row meta
    $new_meta_links['setting'] = sprintf(
        '<a href="%1$s">%2$s</a>',
        admin_url('admin.php?page=lkn-autoconnect-wp-child-config'),
        __('Settings')
    );

    return array_merge($plugin_meta, $new_meta_links);
}
