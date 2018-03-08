<?php
// If Access Directly, bin Out.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Tidy up any Options Registered.
$options = [];

foreach ( $options as $option ) {
    delete_option( $option );
}