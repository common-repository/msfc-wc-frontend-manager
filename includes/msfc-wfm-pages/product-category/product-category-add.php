<?php
/***
 * Product category add form
 * ***/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//validation error message
$msfc_wfm_errors = get_transient( 'msfc_add_category_errors' );
if($msfc_wfm_errors == true){
    echo msfc_wfm_validation_errors($msfc_wfm_errors);
}
?>
<form  action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
    <div class="form-group">
        <label for="product_category_name"><?php esc_html_e('Category Name','msfc-wfm'); ?> <span class="req"><?php esc_html_e( '*','msfc-wfm'); ?></span></label>
        <input type="text" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_category_errors', 'product_category_name')); ?>" id="product_category_name" name="product_category_name" placeholder="<?php echo esc_attr__('Product category name','msfc-wfm'); ?>" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_category_posted_arr', 'product_category_name')); ?>">
        <?php echo msfc_wfm_show_error_msg('msfc_add_category_errors', 'product_category_name'); ?>
    </div>
    <div class="form-group">
        <label for="product_parent_category"><?php esc_html_e('Select Parent Category','msfc-wfm'); ?></label>
        <select class="form-control" id="product_parent_category" name="product_parent_category">
            <option value=""><?php esc_html_e( '-- Select parent category --','msfc-wfm' ); ?></option>
            <?php
            $product_categories = get_categories( array(
                'orderby' => 'name',
                'order'   => 'ASC',
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
            ) );
            foreach( $product_categories as $product_category ) {
                if(msfc_wfm_prev_post_value('msfc_wfm_add_category_posted_arr', 'product_parent_category') == $product_category->slug){
                    $msfc_wfm_selected = ' selected';
                }else{
                    $msfc_wfm_selected = '';
                }
            ?>
            <option value="<?php esc_attr_e($product_category->slug); ?>" <?php esc_attr_e($msfc_wfm_selected); ?> ><?php echo esc_html($product_category->name); ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="product_category_description"><?php esc_html_e('Category Description','msfc-wfm'); ?></label>
        <textarea class="form-control" id="product_category_description" name="product_category_description" placeholder="<?php echo esc_attr__('Product category description','msfc-wfm'); ?>" rows="3"><?php echo esc_html(msfc_wfm_prev_post_value('msfc_wfm_add_category_posted_arr', 'product_category_description')); ?></textarea>
    </div>
    <div class="form-group">
        <?php wp_nonce_field( 'msfc_wfm_cat_prodoct_n_456', 'msfc_wfm_add_product_cat_nonce' ); ?>
        <input type="hidden" name="action" value="msfc_wfm_add_product_category_action">
        <button class="msfc-wfm-button msfc-submit-btn" name="save_product_category" type="submit"><?php esc_html_e('Save New Category','msfc-wfm'); ?></button>
    </div>
    <?php
    //Delete transient of previous posted data if stored when occure errors
    $msfc_wfm_prev_posted_data = get_transient( 'msfc_wfm_add_category_posted_arr' );
    if($msfc_wfm_prev_posted_data == true){
        delete_transient( 'msfc_wfm_add_category_posted_arr' );
    }

    //delete errors transient
    $msfc_wfm_errors = get_transient( 'msfc_add_category_errors' );
    if($msfc_wfm_errors == true){
        delete_transient( 'msfc_add_category_errors' );
    }
    ?>
</form>