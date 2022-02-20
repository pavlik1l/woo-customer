<?php
/**
* Plugin Name: Woocommerce Customer
* Plugin URI: #
* Author: Pavel L
* Version: 1.0
* Text Domain: woo-customer
* Domain Path: /languages
* Author URI: #
* Description: ...
*/
if( !defined('ABSPATH') ){
	return;
}


if ( ! defined( 'WOO_CUSTOMER_PLUGIN_FILE' ) ) {
	define( 'WOO_CUSTOMER_PLUGIN_FILE', __FILE__ );
}

function woo_customer_init() {

	if( !class_exists( 'woocommerce' ) ) return;

    if( !class_exists( 'Woo_Custom_Loader' ) ) {
        require_once 'includes/class-woo-custom-loader.php';
    }
    woo_customer();
}
add_action( 'plugins_loaded', 'woo_customer_init' );

function woo_customer() {
    return Woo_Custom_Loader::get_instance();
}