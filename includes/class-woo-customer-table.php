<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_CUSTOMER_TABLE {
	protected static $_instance = null;

    public static function get_instance() {
        if( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct() {
        $this->add_shortcodes();
        $this->callbacks_hooks();
    }

    function add_shortcodes() {
        add_shortcode( 'woo_customer_table', array( $this, 'woo_customer_table_shortcode' ) );
    }

    function woo_customer_table_shortcode() {

        //administrator only
        if( is_super_admin() ) {
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
                'woo_customer_params_table',
                array(
                    'url'   => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'woo-customer-nonce' ),
                )
            );
            $number = 3;
            $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
            $offset = ( $paged - 1 ) * $number;
            $count_all_users = count_users();
            $count_customers = $count_all_users['avail_roles']['customer'];
            $count_pages = intval( $count_customers / $number ) + 1;
            $args = [
                'role__in'  => 'customer',
                'offset'    => $offset,
                'number'    => $number,
                'orderby'   => 'registered',
                'order'     => 'DESC',
                'fields'    => [ 'display_name', 'user_email' ]
            ];
            $customers = get_users( $args );?>
            <table class="woo-customer-table">
                <thead>
                    <tr><th colspan="2"><?php echo __('Clients')?></th></tr>
                    <tr>
                        <th><?php echo __('Name')?></th>
                        <th><?php echo __('Email')?></th>
                    </tr>
                </thead>
                <tbody class="woo-customer-table__tbody">
                    <?php foreach( $customers as $user ):?>
                        <tr>
                            <td><?php echo $user->display_name; ?></td>
                            <td><?php echo $user->user_email; ?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <?php if ( $count_customers >  count( $customers ) ):?>
                <div id="woo-customer-pagination" class="clearfix">
                <?php $curr_page = max( 1, get_query_var( 'paged') );
                    echo paginate_links( 
                        array(
                            'base'      => user_trailingslashit( wp_normalize_path( get_permalink() .'/%#%/' ) ),
                            'format'    => 'page/%#%/',
                            // 'current'   => $curr_page,
                            'total'     => $count_pages,
                            'prev_text' => __(''),
                            'next_text' => __(''),
                            'show_all'  => true,
                            'end_size'  => 3,
                            'mid_size'  => 3,
                            'type'      => 'plain',
                        )
                    );?>
            <?endif;?>
            <?php $table = ob_get_clean();
            return $table;
        } else {
            return __( 'Access error' );
        }
    }

    function callbacks_hooks() {
        add_action( 'wp_ajax_woo_customer_table_callback', array( $this, 'woo_customer_table_callback' ) );
        add_action('wp_ajax_nopriv_woo_customer_table_callback', array( $this, 'woo_customer_table_callback' ) );
    }

    function woo_customer_table_callback() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'woo-customer-nonce' ) ) {
            wp_die( 'Error nonce' );
        }
        $page = $_POST['page'];
        $customers = $this->woo_customer_get_users( $page );
        wp_die( json_encode( $customers ) );
    }

    function woo_customer_get_users( $page = 1 ) {
        $number = 3;
        $args = [
            'role__in'  => 'customer',
            'offset'    => ( $page - 1 ) * $number,
            'number'    => $number,
            'orderby'   => 'registered',
            'order'     => 'DESC',
            'fields'    => [ 'display_name', 'user_email' ]
        ];
        $customers = get_users( $args );
        return $customers;
    }
}
function woo_customer_table() {
    return WOO_CUSTOMER_TABLE::get_instance();
}
woo_customer_table();