<?php

// Lets Grab the Composer Autoload within our Autoload if it Exists
if( file_exists( POSTCODE_CHECKER_AUTOLOAD_PATH . 'vendor/autoload.php' ) ) {
	require POSTCODE_CHECKER_AUTOLOAD_PATH . 'vendor/autoload.php';
}


/**
 * Autoload Handler
 *
 * @package     Geonet_SMS\GeonetSMS
 * @author      Olly Warren, Geonet Solutions
 * @version     1.0
 */

$classes = [
	'class-postcode-checker-boot',
	'class-postcode-checker'
];

/** Each Class is then auto loaded from the Path. */
foreach ( $classes as $class ) {
	require_once POSTCODE_CHECKER_AUTOLOAD_PATH . "includes/$class.php";
}