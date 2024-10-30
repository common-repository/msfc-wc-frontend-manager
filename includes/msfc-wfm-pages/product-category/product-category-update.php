<?php
/**
 * MSFC Product Category Update
 * ***/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//edit product categroy terms by id
$msfc_wfm_product_terms = get_term( intval($product_cat_id), 'product_cat' );


//validation error message
$msfc_wfm_errors = get_transient( 'msfc_edit_category_errors' );
if($msfc_wfm_errors == true){
    echo msfc_wfm_validation_errors($msfc_wfm_errors);
}
?>
<!--Product category add form-->
<form  action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
    <div class="form-group">
        <?php
        /**category tile
        **show data from transient after getting errros.
        ***/
        if( $msfc_wfm_errors == true ){
            $msfc_wfm_ucategory_title = msfc_wfm_prev_post_value('msfc_wfm_edit_category_posted_arr', 'product_category_name');
        }else{
            $msfc_wfm_ucategory_title = $msfc_wfm_product_terms->name;
        }
        ?>
        <label for="product_category_name"><?php echo esc_html__('Category Name','msfc-wfm'); ?> <span class="req"><?php echo esc_html__('*', 'msfc-wfm'); ?></span></label>
        <input type="text" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_edit_category_errors', 'product_category_name')); ?>" id="product_category_name" name="product_category_name" placeholder="<?php echo esc_attr__('Product category name','msfc-wfm'); ?>" value="<?php echo esc_attr($msfc_wfm_ucategory_title); ?>">
        <?php echo msfc_wfm_show_error_msg('msfc_edit_category_errors', 'product_category_name'); ?>
    </div>
    <div class="form-group">
        <label for="product_parent_category"><?php echo esc_html__('Select Parent Category', 'msfc-wfm'); ?></label>
        <select class="form-control" id="product_parent_category" name="product_parent_category">
            <option value=""><?php echo esc_html__('-- Select parent category --', 'msfc-wfm'); ?></option>
            <?php
            $product_categories = get_categories( array(
                'orderby' => 'name',
                'order'   => 'ASC',
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
            ) );
            foreach( $product_categories as $product_category ) {

                //get current category and skip it from dropdown
                $product_current_terms = get_term_by('id', intval($product_cat_id), 'product_cat');
                if( $product_current_terms->slug == $product_category->slug){
                    continue;
                }

                //show data from transient after getting errros.
                if( $msfc_wfm_errors == true ){
                    if(msfc_wfm_prev_post_value('msfc_wfm_edit_category_posted_arr', 'product_parent_category') == $product_category->slug){
                        $msfc_wfm_selected = 'selected';
                    }else{
                        $msfc_wfm_selected = '';
                    }
                }else{
                    if( $product_category->term_id == $msfc_wfm_product_terms->parent ){
                        $msfc_wfm_selected = 'selected';
                    }else{
                        $msfc_wfm_selected = '';
                    }
                }
            ?>
            <option value="<?php echo esc_attr($product_category->slug); ?>" <?php echo esc_attr($msfc_wfm_selected); ?>><?php echo esc_html($product_category->name); ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <?php
        //show data from transient after getting errros.
        if( $msfc_wfm_errors == true ){
            $msfc_wfm_ucategory_desc = msfc_wfm_prev_post_value('msfc_wfm_edit_category_posted_arr', 'product_category_description');
        }else{
            $msfc_wfm_ucategory_desc = $msfc_wfm_product_terms->description;
        }
        ?>
        <label for="product_category_description"><?php echo esc_html__('Category Description', 'msfc-wfm'); ?></label>
        <textarea class="form-control" id="product_category_description" name="product_category_description" placeholder="<?php echo esc_attr__('Product category description','msfc-wfm'); ?>" rows="3"><?php echo esc_html($msfc_wfm_ucategory_desc); ?></textarea>
    </div>
    <div class="form-group">
        <?php wp_nonce_field( 'msfc_wfm_cat_ed_prodoct_n_456', 'msfc_wfm_edit_product_cat_nonce' ); ?>
        <input type="hidden" name="action" value="msfc_wfm_edit_product_category_action">
        <input type="hidden" name="term_id" value="<?php echo esc_attr($msfc_wfm_product_terms->term_id); ?>">
        <button class="msfc-wfm-button msfc-submit-btn" name="edit_product_category" type="submit"><?php echo esc_html__('Update Category', 'msfc-wfm'); ?></button>
    </div>
    <?php
    //Delete transient of previous posted data if stored when occure errors
    $msfc_wfm_prev_posted_data = get_transient( 'msfc_wfm_edit_category_posted_arr' );
    if($msfc_wfm_prev_posted_data == true){
        delete_transient( 'msfc_wfm_edit_category_posted_arr' );
    }

    //delete errors transient
    $msfc_wfm_errors = get_transient( 'msfc_edit_category_errors' );
    if($msfc_wfm_errors == true){
        delete_transient( 'msfc_edit_category_errors' );
    }
    ?>
</form>