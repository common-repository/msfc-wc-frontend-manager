<?php
/**
 * Product Add Form Page Shortcode
 * **/
function msfc_woocommerce_product_form_content($atts) {
extract( shortcode_atts( array(
    'expand' => '',
), $atts) );

    //include add product
    ob_start();
    require 'product/product-add.php';
    $string = ob_get_clean();
    return $string;

}
// Register shortcode
add_shortcode('msfc_woocommerce_product_form', 'msfc_woocommerce_product_form_content');
?>