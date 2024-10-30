<?php
/**
 * Product Details Page Shortcode
 * **/

function msfc_woocommerce_product_detail_content($atts) {
extract( shortcode_atts( array(
    'expand' => '',
), $atts) );

    //include product view
    ob_start();
    require 'product/product-view.php'; //include product view page
    $string = ob_get_clean();

    return $string;
}
// Register shortcode
add_shortcode('msfc_woocommerce_product_detail', 'msfc_woocommerce_product_detail_content');
?>