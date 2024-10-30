<?php
/**
 * MSFC WFM Navigation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function msfc_wfm_navigation(){
    $nav_content = '<nav class="msfc-wfm-left-navigation mb-4">
        <ul>
            <li class="msfc-navigation-navigation-link msfc-navigation-navigation-link--msfc-products">
                <a href="'.esc_url(get_home_url().'/msfc-products/').'">'.esc_html__('Products List','msfc-wfm').'</a>
            </li>
            <li class="msfc-navigation-navigation-link msfc-navigation-navigation-link--msfc-add-product">
                <a href="'.esc_url(get_home_url().'/msfc-add-product/').'">'.esc_html__('Add Product','msfc-wfm').'</a>
            </li>
            <li class="msfc-navigation-navigation-link msfc-navigation-navigation-link--msfc-add-product">
                <a href="'.esc_url(get_home_url().'/msfc-product-category/').'">'.esc_html__('Product Categories','msfc-wfm').'</a>
            </li>
            <li class="msfc-navigation-navigation-link msfc-navigation-navigation-link--msfc-add-product">
                <a href="'.esc_url(get_home_url().'/msfc-product-category/add-product-category/').'">'.esc_html__('Add New Category','msfc-wfm').'</a>
            </li>
            <li class="msfc-navigation-navigation-link msfc-navigation-navigation-link--msfc-orders">
                <a href="'.esc_url(get_home_url().'/msfc-orders/').'">'.esc_html__('Orders List','msfc-wfm').'</a>
            </li>
        </ul>
    </nav>';
    return $nav_content;
}