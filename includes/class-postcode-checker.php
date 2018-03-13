<?php

namespace WooPostcodeChecker;

// If this file is access directly lets kill ourselves!
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class PC
 *
 * Handles Postcode Lookups
 *
 * @author      Olly Warren
 * @copyright   2018 Olly Warren
 */
class PC {

    public function __construct(){}

    /**
	 * Method to Render the Shortcode for the Postcode Checker
	 * 
	 * @package     WooPostcodeChecker\PC
     * @author      Olly Warren <olly@ollywarren.com>
     * @copyright   2018 Olly Warren
     * @version     1.0.0
	 */
    public static function render_shortcode( $atts ){
        $attributes = shortcode_atts( [
            'success' => 'Great News, We Currently Deliver in Your Area!',
            'failure' => 'Sorry, We don\'t Currently Deliver in Your Area. Check Back Again Soon.'
        ], $atts);

        ob_start();
        include( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/postcode-checker.php');
        return ob_get_clean();
    }

    /**
     * AJAX Callback to Check Postcode
     * against the WooCommerce Shipping Zones
     * 
     * @author Olly Warren
     * @version 1.0
     * @package WooPostcodeChecker\PC
     */
    public static function post_check_postcode()
    {
        // Verify Nonce We Pass in from the Form
        if( ! wp_verify_nonce($_POST['_wpnonce'], 'check_postcode_nonce') ){
            wp_send_json_error(['message' => 'Nonce Check Failed!']);
            wp_die();
        } else {

            // Validate the Input. Already done on the Fornt End, this is a Secuirty Check for the backend.
            $validation = self::validate_postcode($_POST['postcode']);

            if($validation !== true) {
                wp_send_json_error(['validation_errors' => $validation], 400); 
            }

            // Sanitize the Input
            $sanitized = wp_strip_all_tags($_POST['postcode']);

            // Get an instance of WooCommerce
            global $woocommerce;

            // If we dont have Access to the Shipping Zones then lets just Return an Error.
            if(!class_exists('WC_Shipping_Zones')) {
                wp_send_json_error(['message' => 'Could not Load Shipping Zones'], 404);  
            }

            // Lets assemble the Array Of Available Shipping Zones.
            $allZones = \WC_Shipping_Zones::get_zones();
            $postcodes = [];
            foreach ( $allZones as $zone ) {
                foreach($zone['zone_locations'] as $location) {
                    if($location->type == "postcode") {
                        $postcodes[] = $location;
                    }
                }
            }

            // Ok now we need to check our Postcode against the Available ones;
            foreach ($postcodes as $comparison) {
                if( $comparison->code == $sanitized || strpos($sanitized, str_replace('*', '', $comparison->code), 0) !== false ) {
                    wp_send_json(['message' => 'success', 'canDeliver' => true], 200);
                }
            }
        
            wp_send_json(['message' => 'success', 'canDeliver' => false], 200);
        }
    }

    /**
     * Validate the Inputted Postcode
     *
     * @param String $value
     * @return Bool
     */
    public static function validate_postcode( $value )
    {
        // Set the Error Bag
        $errorBag = [];

        // Not Empty
        if(empty($value)) {
            $errorBag[] = 'Postcode Cannot be Empty';
        }

        // Valid Postcode Format
        if(!self::check_postcode_format($value)){
            $errorBag[] = 'Invalid Postcode Format Found';
        }
        
        // Only LS Postcodes.
        // Project Specific, Remove for Other Projects
        $exists = strpos($value, 'LS');
        if( $exists === false) {
            $errorBag[] = 'Only LS Postcodes Allowed';
        }

        // Retun any Validation Errors
        if(count($errorBag) > 0) {
            return $errorBag;
        } else {
            return true;
        }
    }

    /**
     * Function to Check and Tranform Uk postcode Formats.
     *
     * @param String $original_postcode
     * @return void
     * @author Sepehr Lajevardi <https://gist.github.com/sepehr/3340289>
     */
    public static function check_postcode_format($original_postcode)
    {
        $alpha1 = "[abcdefghijklmnoprstuwyz]";
        $alpha2 = "[abcdefghklmnopqrstuvwxy]";
        $alpha3 = "[abcdefghjkpmnrstuvwxy]";
        $alpha4 = "[abehmnprvwxy]";
        $alpha5 = "[abdefghjlnpqrstuwxyz]";

        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
        $pcexp[0] = '/^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';
        // Expression for postcodes: ANA NAA
        $pcexp[1] = '/^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';
        // Expression for postcodes: AANA NAA
        $pcexp[2] = '/^('.$alpha1.'{1}'.$alpha2.'{1}[0-9]{1}'.$alpha4.')([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';
        // Exception for the special postcode GIR 0AA
        $pcexp[3] = '/^(gir)([[:space:]]{0,})(0aa)$/';
        // Standard BFPO numbers
        $pcexp[4] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';
        // c/o BFPO numbers
        $pcexp[5] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';
        // Overseas Territories
        $pcexp[6] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';
        // Anquilla
        $pcexp[7] = '/^ai-2640$/';
        // Load up the string to check, converting into lowercase
        $postcode = strtolower($original_postcode);

        // Assume we are not going to find a valid postcode
        $valid = FALSE;

        // Check the string against the six types of postcodes
        foreach ($pcexp as $regexp)
        {
            if (preg_match($regexp, $postcode, $matches))
            {
                // Load new postcode back into the form element
                $postcode = strtoupper ($matches[1] . ' ' . $matches [3]);

                // Take account of the special BFPO c/o format
                $postcode = preg_replace ('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

                // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
                preg_match($pcexp[7], strtolower($original_postcode), $matches) AND $postcode = 'AI-2640';

                // Remember that we have found that the code is valid and break from loop
                $valid = TRUE;
                break;
            }
        }

        // Return with the reformatted valid postcode in uppercase if the postcode was
        return $valid ? $postcode : FALSE;
    }
}