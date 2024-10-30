<?php
/**
 * Product List Page Shortcode
 * **/

function msfc_woocommerce_products_list_content($atts) {
extract( shortcode_atts( array(
    'expand' => '',
), $atts) );

    //include product list
    ob_start();
    require 'product/product-list.php'; //include product list page
    $string = ob_get_clean();

    return $string;

}
// Register shortcode
add_shortcode('msfc_woocommerce_products_list', 'msfc_woocommerce_products_list_content');
?>