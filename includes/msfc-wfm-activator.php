<?php
/**
 * Trigger on plugin active
 */

 /**
 * Create Plugins Pages with shortcodes
 */
//Includes pages shortcodes
require MSFC_WFM_DIR . 'includes/msfc-wfm-pages-shortcode.php';

// Create MSFC woocommerce product pages
msfc_wfm_create_product_list_page(); //product list page
msfc_wfm_create_product_details_page(); //product details page
msfc_wfm_create_add_product_page(); //add product page
msfc_wfm_create_edit_product_page(); //edit product form page

// Create MSFC woocommerce orders pages
msfc_wfm_create_order_list_page(); //order list page
msfc_wfm_create_order_details_page(); //order details & status update page
msfc_wfm_create_category_page(); //product category list, edit, update page