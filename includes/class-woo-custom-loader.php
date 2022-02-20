<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Custom_Loader {
    
	protected static $_instance = null;

    public static function get_instance() {
        if( is_null( self::$_instance ) ) {
            self::$_instance == new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->set_constants();
        $this->includes();
    }

    public function set_constants() {
        $this->define( "WOO_CUSTOM_VERSION", "1.0" );
        $this->define( 'WOO_CUSTOM_PLUGIN_PATH', plugin_dir_path( WOO_CUSTOMER_PLUGIN_FILE ) );
        $this->define( 'WOO_CUSTOM_PLUGIN_BASENAME', plugin_basename( WOO_CUSTOMER_PLUGIN_FILE ) );
        $this->define( 'WOO_CUSTOM_URL', untrailingslashit( plugins_url( '/', WOO_CUSTOMER_PLUGIN_FILE ) ) );

    }

	public function define( $constant_name, $constant_value ){
		if( !defined( $constant_name ) ){
			define( $constant_name, $constant_value );
		}
	}

    public function includes() {
        require_once WOO_CUSTOM_PLUGIN_PATH . '/includes/class-woo-customer-form.php';
    }
}