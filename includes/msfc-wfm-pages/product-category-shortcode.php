<?php
/**
 * Shortcode for product category add, edit, update & delete
 * **/
function msfc_woocommerce_product_category_content($atts) {
extract( shortcode_atts( array(
    'expand' => '',
), $atts) );

    //include order details
    ob_start();
    require 'product-category/product-category-view.php'; //include product category view page
    $string = ob_get_clean();
    return $string;

}
// Register shortcode
add_shortcode('msfc_woocommerce_product_category', 'msfc_woocommerce_product_category_content');
?>