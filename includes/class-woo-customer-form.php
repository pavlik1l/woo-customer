<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_CUSTOMER_FORM {
	protected static $_instance = null;

    public static function get_instance() {
        if( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
function woo_customer_shortcode() {
    ob_start();
    wp_register_script(
        'woo-customer-form',
        WOO_CUSTOM_URL . '/assets/js/form.js',
        WOO_CUSTOM_VERSION,
        true
    );
    wp_enqueue_script( 'woo-customer-form' );
    ?>
    <form action="woo_customer_form" method="POST" id="woo-customer-form" class="woo-customer-form">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="description" id="description" rows="4" placeholder="description"></textarea>
        <input type="submit" value="Submit">
    </form>
    <?php $form = ob_get_clean();
    return $form;
}
add_shortcode( 'woo_customer_form', 'woo_customer_shortcode' );

function woo_customer_form() {
    return WOO_CUSTOMER_FORM::get_instance();
}
woo_customer_form();