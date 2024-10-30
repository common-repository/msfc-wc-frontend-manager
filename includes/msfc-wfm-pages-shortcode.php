<?php
/**
 * Create product list page with shortcode
 *  */
function msfc_wfm_create_product_list_page(){
    $new_page_title     = __('MSFC Products List','msfc-wfm');
    $new_page_content   = '[msfc_woocommerce_products_list]';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type'     => 'page', 
            'post_title'    => $new_page_title,
            'post_content'  => $new_page_content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_name'     => 'msfc-products'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        wp_insert_post($new_page);
    }
}

/**
 * Create product Details page with shortcode
 *  */
function msfc_wfm_create_product_details_page(){
    $new_page_title     = __('MSFC Product Details','msfc-wfm');
    $new_page_content   = '[msfc_woocommerce_product_detail]';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type'     => 'page', 
            'post_title'    => $new_page_title,
            'post_content'  => $new_page_content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_name'     => 'msfc-product-details'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        wp_insert_post($new_page);
    }
}



/**
 * Create add product form page with shortcode
 *  */
function msfc_wfm_create_add_product_page(){
    $new_page_title     = __('MSFC Add New Product','msfc-wfm');
    $new_page_content   = '[msfc_woocommerce_product_form]';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type'     => 'page', 
            'post_title'    => $new_page_title,
            'post_content'  => $new_page_content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_name'     => 'msfc-add-product'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        wp_insert_post($new_page);
    }
}


/**
 * Create edit product form page with shortcode
 *  */
function msfc_wfm_create_edit_product_page(){
    $new_page_title     = __('MSFC Edit Product','msfc-wfm');
    $new_page_content   = '[msfc_woocommerce_edit_product_form]';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type'     => 'page', 
            'post_title'    => $new_page_title,
            'post_content'  => $new_page_content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_name'     => 'msfc-edit-product'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        wp_insert_post($new_page);
    }
}


/**
 * Create order list page with shortcode
 *  */
function msfc_wfm_create_order_list_page(){
    $new_page_title     = __('MSFC Orders List','msfc-wfm');
    $new_page_content   = '[msfc_woocommerce_orders_list]';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type'     => 'page', 
            'post_title'    => $new_page_title,
            'post_content'  => $new_page_content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_name'     => 'msfc-orders'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        wp_insert_post($new_page);
    }
}


/**
 * Create order details page with shortcode
 *  */
function msfc_wfm_create_order_details_page(){
    $new_page_title     = __('MSFC Order Detail','msfc-wfm');
    $new_page_content   = '[msfc_woocommerce_orders_detail]';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type'     => 'page', 
            'post_title'    => $new_page_title,
            'post_content'  => $new_page_content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_name'     => 'msfc-order-details'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        wp_insert_post($new_page);
    }
}


/**
 * Create order details page with shortcode
 *  */
function msfc_wfm_create_category_page(){
    $new_page_title     = __('MSFC Product Category','msfc-wfm');
    $new_page_content   = '[msfc_woocommerce_product_category]';
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type'     => 'page', 
            'post_title'    => $new_page_title,
            'post_content'  => $new_page_content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_name'     => 'msfc-product-category'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        wp_insert_post($new_page);
    }
}