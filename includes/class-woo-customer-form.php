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

    public function __construct() {
        $this->add_shortcodes();
        $this->callback_hooks();
    }

    function add_shortcodes() {
        add_shortcode( 'woo_customer_form', array( $this, 'woo_customer_shortcode' ) );
    }

    function woo_customer_shortcode() {
        ob_start();
        wp_register_script(
            'woo-customer-form',
            WOO_CUSTOM_URL . '/assets/js/form.js',
            WOO_CUSTOM_VERSION,
            true
        );
        wp_enqueue_script( 'jquery-form' );
        wp_enqueue_script( 'woo-customer-form' );
        wp_localize_script(
            'woo-customer-form',
            'woo_customer_params',
            array(
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'woo-customer-nonce' ),
            )
        );
        ?>
        <form method="POST" id="woo-customer-form" class="woo-customer-form">
            <input type="text" name="name" placeholder="Name">
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="description" id="description" rows="4" placeholder="Description"></textarea>
            <input type="submit" value="Submit">
            <div id="woo-customer-form-callback"></div>
        </form>
        <?php $form = ob_get_clean();
        return $form;
    }

    function callback_hooks() {
        add_action( 'wp_ajax_woo_customer_form_callback', array( $this, 'woo_customer_form_callback' ) );
        add_action( 'wp_ajax_nopriv_woo_customer_form_callback', array( $this, 'woo_customer_form_callback' ) );
    }

    function woo_customer_form_callback() {
        $error_messages = array();
        if ( ! wp_verify_nonce( $_POST['nonce'], 'woo-customer-nonce' ) ) {
            wp_die( 'Error nonce' );
        }

        if( !isset( $_POST['email'] ) || empty( $_POST['email'] ) ) {
            $error_messages['email'] = 'The email field is empty';
        }
        else {
            $email = sanitize_email( $_POST['email'] );
        }

        if( !isset( $_POST['name'] ) ||  empty( $_POST['name'] ) ) {
            $error_messages['name'] = 'The name field is empty';
        } else {
            $name = sanitize_text_field( $_POST['name'] );
        }

        if( isset( $_POST['description'] ) && !empty( $_POST['description'] ) ) {
            $description = sanitize_text_field( $_POST['description'] );
        }

        if( $error_messages ) {
            wp_send_json_error( $error_messages );
        } else {
            $customer = new WC_Customer();
            $customer->set_username( $name );
            $customer->set_email( $email );
            $customer_id = $customer->save();
            if( $description ) {
                wp_update_user(
                    array(
                        'ID' => $customer_id,
                        'description' => $description
                    )
                );
            }
            wp_send_json_success( $email );
        }
        wp_die();
    }
}

function woo_customer_form() {
    return WOO_CUSTOMER_FORM::get_instance();
}
woo_customer_form();