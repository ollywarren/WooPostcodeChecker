jQuery( document ).ready( function() {
    // When the DOM is Ready...

    /********************************************************
     * --- DECLARATIONS FOR THE CHECK POSTCODE SHORTCODE --- *
     ********************************************************/

     // When the poscode input is changed, validation a mutate it.
    jQuery('#postcode_checker_value').change( function( event ) {
        var input = jQuery(this).val().toUpperCase().split(" ").join("").trim();
        var output = input.replace(/^(.*)(\d)/, "$1 $2");
        jQuery(this).val(output);
    });

    // If the Validation Message is Showing, Hide It
    jQuery('#postcode_checker_value').focus( function( event ) {
        jQuery('#validation_error').html('').hide();
        jQuery('#postcode_check_result').html('').hide();
    });

    // When we click the button fire the AJAX Request
    jQuery('#post_check_postcode').click( function( event ) { 
        event.preventDefault();
        var postcode = jQuery('#postcode_checker_value').val();
        
        if(!postcode) {
            jQuery('#validation_error').html('Please Enter a Postcode to Check').show();
        } else {
            var success = jQuery('input[name="success_message"]').val();
            var failure = jQuery('input[name="failure_message"]').val();

            check_postcode(postcode, success, failure);
        }
    });
});

/**
 *  Method to Fire an AJAX Request to the
 *  Postcode Checker to check the postcode against the 
 *  WooCommerce Shipping Zones
 *
 *  @author Olly Warren <olly@ollywarren.com>
 *  @version 1.0
 *  @package WooPostcodeChecker\PC
 */
function check_postcode( postcode, success, failure ) {
    jQuery.ajax({
        dataType: 'json',
        url: post_check_postcode.ajax_url,
        type: 'post',
        data: {
            action: 'post_check_postcode',
            postcode: postcode,
            _wpnonce: post_check_postcode.ajax_nonce 
        },
        success: function ( response, status, xhr ) {
            var result = (response.canDeliver === true) ? success : failure;
            jQuery('#postcode_check_result').html(
                '<h4>Postcode Checker Result</h4>'
                + '<p>' + result + '</p>'
            ).show();
        },
        error: function ( response, status, xhr) {
            if(response.status == 400 && response.responseJSON.data.validation_errors.length > 0) {
                jQuery('#validation_error').html(response.responseJSON.data.validation_errors[0]).show();
            } else {
                console.log([response, status, xhr]);
            }
        }
    });
}