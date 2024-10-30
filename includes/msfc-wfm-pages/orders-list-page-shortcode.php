<?php
/**
 * Order List Page Shortcode
 * **/
function msfc_woocommerce_orders_list_content($atts) {
extract( shortcode_atts( array(
    'expand' => '',
), $atts) );

    //include order list page
    ob_start();
    require 'order/order-list-item.php';
    $string = ob_get_clean();
    return $string;

}
// Register shortcode
add_shortcode('msfc_woocommerce_orders_list', 'msfc_woocommerce_orders_list_content');
?>