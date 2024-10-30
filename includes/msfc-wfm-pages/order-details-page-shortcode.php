<?php
/**
 * Shortcode for orders Details
 * **/
function msfc_woocommerce_orders_detail_content($atts) {
extract( shortcode_atts( array(
    'expand' => '',
), $atts) );

    //include order details
    ob_start();
    require 'order/order-details.php'; //include order details function page
    $string = ob_get_clean();
    return $string;

}
// Register shortcode
add_shortcode('msfc_woocommerce_orders_detail', 'msfc_woocommerce_orders_detail_content');
?>