# WooPostcodeChecker

### Contributors: 
ollywarren,

### Tags: 
woocommerce, wordpress, postcode, checker, shipping, shipping-zones

### Description
Implements a very simple shortcode that allows a visitor to check if their delivery postcode matches any of the postcodes configured in the shipping zones.

Created for a specific project in mind where the client delivers only in a very postcode specific area, so might not be for everyone.

Made freely available here for anyone who wants to use it or build on it. Feel free to push any additions back to this repo.


### Installation

1. Clone the repo `git clone` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

### Usage

Simply add the shortcode to any page or post you want the checker input to appear:

``` [postcode-checker] ```

You can override the default success / failure messages that are output by passing in the shortcode atrributes as follows:

``` [postcode-checker success="New Success Message" failure="New Failure Message"] ```

### Changelog
##### 1.0 
* Initial Release
