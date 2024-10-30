<?php
//Product Add Form

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {
    
    echo msfc_wfm_success_error_message();
?>
<div class="msfc-wfm-container">
    <div class="row">
        <div class="col-md-3">
            <?php echo msfc_wfm_navigation(); ?>
        </div>
        <div class="col-md-9">
            <div class="msfc-wfm-content">  
                <?php
                //validation error message
                $errors = get_transient( 'msfc_add_product_errors' );
                if($errors == true){
                    echo msfc_wfm_validation_errors($errors);
                }
                ?>

                <form action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                    <div class="form-group">
                        <label for="product_title"><?php esc_html_e('Prduct Title','msfc-wfm'); ?> <span class="req"><?php esc_html_e('*','msfc-wfm'); ?></span></strong></label>
                        <input type="text" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'product_title')); ?>" id="product_title" name="product_title" placeholder="<?php echo esc_attr__('Product name','msfc-wfm'); ?>" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_title')); ?>">
                        <?php echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'product_title'); ?>
                    </div>
                    <div class="form-group<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'product_description')); ?>">
                        <label for="product_description"><?php esc_html_e('Prduct Description','msfc-wfm'); ?> <span class="req"><?php esc_html_e('*','msfc-wfm'); ?></span></strong></label>
                    <?php
                        $content = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_description');
                        $editor_id = 'product_description';
                        $settings =   array(
                            'wpautop' => true,
                            'media_buttons' => false,
                            'textarea_name' => $editor_id, 
                            'textarea_rows' =>get_option('default_post_edit_rows', 10), 
                            'tabindex' => '',
                            'editor_css' => '', 
                            'editor_class' => '',
                            'teeny' => true,
                            'dfw' => true,
                            'tinymce' => true,
                            'quicktags' => false 
                            );
                        wp_editor( htmlspecialchars_decode(wp_kses_post($content), ENT_NOQUOTES), $editor_id, $settings);
                        echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'product_description');
                    ?>
                    </div>
                    <div class="form-group">
                        <label for="product_short_description"><?php esc_html_e('Prduct Short Description','msfc-wfm'); ?></strong></label>
                        <textarea class="form-control" id="product_short_description" name="product_short_description" placeholder="<?php echo esc_attr__('Product short description','msfc-wfm'); ?>" rows="4"><?php echo wp_kses_post(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_short_description')); ?></textarea>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="product_thumbnail_id"><?php esc_html_e('Upload Prouduct Image','msfc-wfm'); ?></label>
                                <input type="hidden" id="product_thumbnail_id" name="product_thumbnail_id" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_thumbnail_id')); ?>">
                                <input type="hidden" id="product_thumbnail_url" name="product_thumbnail_url" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_thumbnail_url')); ?>">
                                <div id="product-single-image" class="image-drop-container">
                                    <div id="product_thumb_img" class="preview-image"></div> 
                                    <div class="image-drop-text">
                                        <i class="las la-image"></i>
                                        <span><span><?php esc_html_e('Click Here','msfc-wfm'); ?></span> <?php esc_html_e('To Upload Image','msfc-wfm'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="product_image_gallery"><?php esc_html_e('Upload Prouduct Gallery Images','msfc-wfm'); ?></label>
                                <input type="hidden" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_image_gallery')); ?>">
                                <input type="hidden" id="product_image_gallery_url" name="product_image_gallery_url" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_image_gallery_url')); ?>">
                                <div id="product-gallery-images" class="image-drop-container">
                                    <div id="product_gallery_img" class="preview-image privew-gimages"></div>
                                    <div class="image-drop-text">
                                        <i class="las la-image"></i>
                                        <span><span><?php esc_html_e('Click Here','msfc-wfm'); ?></span> <?php esc_html_e('To Upload Gallery Images','msfc-wfm'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_category"><?php esc_html_e('Select Category','msfc-wfm'); ?> <span class="req"><?php esc_html_e('*','msfc-wfm'); ?></span></label>
                                <select class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'product_category')); ?>" id="product_category" name="product_category">
                                    <option value=""><?php esc_html_e('-- Select category --','msfc-wfm'); ?></option>
                                    <?php
                                    $product_categories = get_categories( array(
                                        'orderby' => 'name',
                                        'order'   => 'ASC',
                                        'taxonomy' => 'product_cat',
                                        'hide_empty' => false,
                                    ) );
                                    foreach( $product_categories as $product_category ) {
                                        if(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_category') == $product_category->term_id){
                                            $msfc_wfm_selected = ' selected';
                                        }else{
                                            $msfc_wfm_selected = '';
                                        }
                                        echo '<option value="'.esc_attr($product_category->term_id).'" '.esc_attr($msfc_wfm_selected).'>'.esc_html($product_category->name).'</option>';
                                    }
                                    ?>
                                </select>
                                <?php echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'product_category'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_tags"><?php esc_html_e('Product Tags','msfc-wfm'); ?> <small><?php esc_html_e('(Tags must be comma separeted)','msfc-wfm'); ?></small></label>
                                <input type="text" class="form-control" id="product_tags" name="product_tags" placeholder="<?php echo esc_attr__('Ex: shirt, t-shirt, men','msfc-wfm'); ?>" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_tags')); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="regular_price"><?php esc_html_e('Regular Price','msfc-wfm'); ?> <span class="req"><?php esc_html_e('*','msfc-wfm'); ?></span></label>
                                <input type="number" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'regular_price')); ?>" id="regular_price" name="regular_price" step="any" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'regular_price')); ?>">
                                <?php echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'regular_price'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_price"><?php esc_html_e('Sale Price','msfc-wfm'); ?></label>
                                <input type="number" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'sale_price')); ?>" id="sale_price" name="sale_price" step="any" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'sale_price')); ?>">
                                <?php echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'sale_price'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight"><?php esc_html_e('Weight','msfc-wfm'); ?> (<?php echo esc_html(get_option('woocommerce_weight_unit')); ?>)</label>
                                <input type="number" class="form-control" id="weight" name="weight" step="any" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'weight')); ?>">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="length"><?php esc_html_e('Dimensions','msfc-wfm'); ?> (<?php echo esc_html(get_option('woocommerce_dimension_unit')); ?>)</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" id="length" name="length" placeholder="<?php echo esc_attr__('Length','msfc-wfm'); ?>" step="any" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'length')); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" id="width" name="width" placeholder="<?php echo esc_attr__('Width','msfc-wfm'); ?>" step="any" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'width')); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" id="height" name="height" placeholder="<?php echo esc_attr__('Height','msfc-wfm'); ?>" step="any" value="<?php echo esc_attr(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'height')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php wp_nonce_field( 'msfc_wfm_add_product_nonce_894', 'msfc_wfm_product_nonce' ); ?>
                        <input type="hidden" name="action" value="msfc_wfm_add_product_action">
                        <button class="msfc-wfm-button msfc-submit-btn" name="save_product" type="submit"><?php esc_html_e('Save Product','msfc-wfm'); ?></button>
                    </div>
                    <?php
                    //Delete transient of previous posted data if stored when occure errors
                    $msfc_wfm_prev_posted_data = get_transient( 'msfc_wfm_add_product_posted_arr' );
                    if($msfc_wfm_prev_posted_data == true){
                        delete_transient( 'msfc_wfm_add_product_posted_arr' );
                    }

                    //delete errors transient
                    $msfc_wfm_errors = get_transient( 'msfc_add_product_errors' );
                    if($msfc_wfm_errors == true){
                        delete_transient( 'msfc_add_product_errors' );
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    echo "<h1>".esc_html__('This is not your area. :)','msfc-wfm')."</h1>";
}