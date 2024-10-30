<?php
/**
 * Product Category Main Page
 * ***/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {

msfc_wfm_success_error_message();
?>
<div class="msfc-wfm-container">
    <div class="row">
        <div class="col-md-3">
            <?php echo msfc_wfm_navigation(); ?>
        </div>
        <div class="col-md-9">
            <div class="msfc-wfm-content">
            <?php
                if( !empty( get_query_var( 'pro-category-action' ) ) && ( get_query_var( 'pro-category-action' ) == 'edit-product-category' ) && !empty( get_query_var( 'product-cat-id' ) ) ){
                    //product category update page
                    $product_cat_id = get_query_var( 'product-cat-id' );
                    require 'product-category-update.php';
                }elseif( !empty( get_query_var( 'pro-category-action' ) ) && ( get_query_var( 'pro-category-action' ) == 'add-product-category' ) ){
                    //Check Product category add page
                    require 'product-category-add.php';

                }else{
                    //product category list page
                    require 'product-category-list.php';
                }
            ?>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    echo '<h1>'.esc_html__('This is not your area. :)', 'msfc-wfm').'</h1>';
}
