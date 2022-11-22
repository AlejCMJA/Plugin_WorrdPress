<?php
/**
 * Plugin Name: Bac Payment JCAMPOS & JLOPEZ
 * Plugin URI: https://github.com/AlejCMJA/Plugin_WorrdPress.git
 * Description: Este es un plugin de pasarela de pago
 * Version: 1.0.0
 * Author: Julian Alejandro Campos Mairena & Josue Alexander Lopez Lopez
 * Author URI: https://github.com/AlejCMJA
 * License: GPL2
 * text-domain: https://github.com/AlejCMJA/Plugin_WorrdPress.git
 */

function activar(){

}

function desactivar(){

}

register_activation_hook(__FILE__,'activar');
register_deactivation_hook(__FILE__,'desactivar');

//Verifica si WC esta instalado
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

add_action( 'plugins_loaded', 'bac_payment_init', 11 );

function bac_payment_init() {
    if( class_exists( 'WC_Payment_Gateway' ) ) {
        class WC_bac_pay_Gateway extends WC_Payment_Gateway {
            public function __construct() {
                $this->id   = 'bac_payment';
                $this->icon = apply_filters( 'woocommerce_bac_icon', plugins_url('/assets/icon.png', __FILE__ ) );
                $this->has_fields = false;
                $this->method_title = __( 'bac Payment', 'bac-pay-woo');
                $this->method_description = __( 'bac sistemas de pago de contenido local.', 'bac-pay-woo');

                $this->title = $this->get_option( 'title' );
                $this->description = $this->get_option( 'description' );
                $this->instructions = $this->get_option( 'instructions', $this->description );

                $this->init_form_fields();
                $this->init_settings();

                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            }

            public function init_form_fields() {
                $this->form_fields = apply_filters( 'woo_bac_pay_fields', array(
                    'enabled' => array(
                        'title' => __( 'Habilitar/Deshabilitar', 'bac-pay-woo'),
                        'type' => 'checkbox',
                        'label' => __( 'Habilitar o deshabilitar pagos en bac', 'bac-pay-woo'),
                        'default' => 'no'
                    ),
                    'title' => array(
                        'title' => __( 'bac pasarela de pagos', 'bac-pay-woo'),
                        'type' => 'text',
                        'default' => __( 'bac pasarela de pagos', 'bac-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Agregue un nuevo título para la pasarela de pagos bac que los clientes verán cuando estén en la página de pago.', 'bac-pay-woo')
                    ),
                    'description' => array(
                        'title' => __( 'bac pasarela de pagos descripcion', 'bac-pay-woo'),
                        'type' => 'textarea',
                        'default' => __( 'Envíe su pago a la tienda para permitir que se realice la entrega.', 'bac-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Agregue un nuevo título para la pasarela de pagos bac que los clientes verán cuando estén en la página de pago.', 'bac-pay-woo')
                    ),
                    'instructions' => array(
                        'title' => __( 'Instrucciones', 'bac-pay-woo'),
                        'type' => 'textarea',
                        'default' => __( 'Instrucciones predeterminadas', 'bac-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Instrucciones que se añadirán a la página de agradecimiento y odrer email', 'bac-pay-woo')
                    )
                ));
            }

            public function process_payments( $order_id ) {
                
                $order = wc_get_order( $order_id );

                $order->update_status( 'on-hold',  __( 'En espera de pago', 'bac-pay-woo') );

                $order->reduce_order_stock();

                WC()->cart->empty_cart();

                return array(
                    'result'   => 'success',
                    'redirect' => $this->get_return_url( $order ),
                );
            }

            public function thank_you_page(){
                if( $this->instructions ){
                    echo wpautop( $this->instructions );
                }
            }
        }
    }
}

add_filter( 'woocommerce_payment_gateways', 'add_to_woo_bac_payment_gateway');

function add_to_woo_bac_payment_gateway( $gateways ) {
    $gateways[] = 'WC_bac_pay_Gateway';
    return $gateways;
}

?>