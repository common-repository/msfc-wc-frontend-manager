<?php
/**
 * Shortcode for edit product form
 * **/
function msfc_woocommerce_edit_product_form_content($atts) {
extract( shortcode_atts( array(
    'expand' => '',
), $atts) );

    //include edit product
    ob_start();
    require 'product/product-edit.php';
    $string = ob_get_clean();
    return $string;

}
// Register shortcode
add_shortcode('msfc_woocommerce_edit_product_form', 'msfc_woocommerce_edit_product_form_content');
?>