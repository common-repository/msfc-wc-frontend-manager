<?php
//Product Edit

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {

    //check product id
    $product_id = get_query_var( 'product-id' );
    if(!empty($product_id)){
    
    //get error from transient API
    $errors = get_transient( 'msfc_add_product_errors' );

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
                //validation error message
                if($errors == true){
                    echo msfc_wfm_validation_errors($errors);
                }
                ?>
                <form action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                        <div class="form-group">
                            <label for="product_title"><?php esc_html_e('Prduct Title','msfc-wfm'); ?> <span class="req"><?php esc_html_e('*','msfc-wfm'); ?></span></strong></label>
                            <?php
                            /**product tile
                            **show data from transient after getting errros.
                            ***/
                            if( $errors == true ){
                                $msfc_wfm_uproduct_title = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_title');
                            }else{
                                $msfc_wfm_uproduct_title = get_the_title($product_id);
                            }
                            ?>
                            <input type="text" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'product_title')); ?>" id="product_title" name="product_title" placeholder="<?php echo esc_attr__('Product name','msfc-wfm'); ?>" value="<?php echo esc_attr($msfc_wfm_uproduct_title); ?>">
                            <?php echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'product_title'); ?>
                        </div>
                        <div class="form-group<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'product_description')); ?>">
                            <label for="product_description"><?php esc_html_e('Prduct Description','msfc-wfm'); ?> <span class="req"><?php esc_html_e('*','msfc-wfm'); ?></span></strong></label>
                            <?php
                            /**product description
                            **show data from transient after getting errros.
                            ***/
                            if( $errors == true ){
                                $msfc_wfm_uproduct_title = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_description');
                            }else{
                                $msfc_wfm_uproduct_title = get_the_content(null, false, $product_id);
                            }
                            
                            $content = $msfc_wfm_uproduct_title;
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
                            <?php
                            /**short description
                            **show data from transient after getting errros.
                            ***/
                            if( $errors == true ){
                                $msfc_wfm_product_short_description = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_short_description');
                            }else{
                                $msfc_wfm_product_short_description = get_the_excerpt($product_id);
                            }
                            ?>
                            <textarea class="form-control" id="product_short_description" name="product_short_description" placeholder="<?php echo esc_attr__('Product short description','msfc-wfm'); ?>" rows="4"><?php echo esc_attr($msfc_wfm_product_short_description); ?></textarea>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="product_thumbnail_id"><?php esc_html_e('Upload Prouduct Image','msfc-wfm'); ?></label>
                                    <?php
                                    /**Thumbanil ID
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_product_thumbnail_id = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_thumbnail_id');
                                    }else{
                                        $msfc_wfm_product_thumbnail_id = get_post_thumbnail_id($product_id);
                                    }


                                    /**Thumbanil URL
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_product_thumbnail_url = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_thumbnail_url');
                                    }else{
                                        $msfc_wfm_product_thumbnail_url = get_the_post_thumbnail_url($product_id);
                                    }
                                    ?>
                                    <input type="hidden" id="product_thumbnail_id" name="product_thumbnail_id" value="<?php echo esc_attr($msfc_wfm_product_thumbnail_id); ?>">
                                    <input type="hidden" id="product_thumbnail_url" name="product_thumbnail_url" value="<?php echo esc_attr($msfc_wfm_product_thumbnail_url); ?>">
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
                                    <?php
                                    /**Gallery IDs
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_product_image_gallery = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_image_gallery');
                                    }else{
                                        $msfc_wfm_product_image_gallery = get_post_meta($product_id, '_product_image_gallery', true);
                                    }

                                    /**Gallery URLs
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_product_image_gallery_url = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_image_gallery_url');
                                    }else{
                                        $msfc_wfm_gallery_ids = get_post_meta($product_id, '_product_image_gallery', true);
                                        $msfc_wfm_gallery_ids_arr = explode(",", $msfc_wfm_gallery_ids);
                                        $msfc_wfm_gallery_urls = "";
                                        foreach($msfc_wfm_gallery_ids_arr as $msfc_wfm_gallery_single_id){
                                            $msfc_wfm_gallery_urls .= wp_get_attachment_url($msfc_wfm_gallery_single_id).',';
                                        }
                                        $msfc_wfm_product_image_gallery_url = rtrim($msfc_wfm_gallery_urls, ',');
                                    }
                                    ?>
                                    <input type="hidden" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr($msfc_wfm_product_image_gallery); ?>">
                                    <input type="hidden" id="product_image_gallery_url" name="product_image_gallery_url" value="<?php echo esc_attr($msfc_wfm_product_image_gallery_url); ?>">
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
                                    <label for="product_category"><?php esc_html_e('Select Category','msfc-wfm'); ?> <span class="req">*</span></label>
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
                                        
                                        /**categories
                                        **show data from transient after getting errros.
                                        ***/
                                        if( $errors == true ){
                                            if(msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_category') == $product_category->term_id){
                                                $msfc_wfm_selected = ' selected';
                                            }else{
                                                $msfc_wfm_selected = '';
                                            }
                                        }else{
                                            if( get_the_terms( $product_id, 'product_cat' )[0]->term_id == $product_category->term_id ){
                                                $msfc_wfm_selected = ' selected';
                                            }else{
                                                $msfc_wfm_selected = '';
                                            }
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
                                <?php
                                /**tags
                                **show data from transient after getting errros.
                                ***/
                                if( $errors == true ){
                                    $msfc_wfm_all_terms = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'product_tags');
                                }else{
                                    $msfc_wfm_all_terms = '';
                                    $msfc_wfm_product_tags = get_the_terms( $product_id, 'product_tag' );
                                    if( !empty($msfc_wfm_product_tags) ){
                                        $msfc_wfm_all_terms = join(', ', wp_list_pluck( $msfc_wfm_product_tags, 'name'));
                                    }
                                }
                                ?>

                                
                                <label for="product_tags"><?php esc_html_e('Product Tags','msfc-wfm'); ?> <small><?php esc_html_e('(Tags must be comma separeted)','msfc-wfm'); ?></small></label>
                                    <input type="text" class="form-control" id="product_tags" name="product_tags" placeholder="<?php echo esc_attr__('Ex: shirt, t-shirt, men','msfc-wfm'); ?>" value="<?php echo esc_attr($msfc_wfm_all_terms); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="regular_price"><?php esc_html_e('Regular Price','msfc-wfm'); ?> <span class="req">*</span></label>
                                    <?php
                                    /**categories
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_regular_price = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'regular_price');
                                    }else{
                                        $msfc_wfm_regular_price = get_post_meta($product_id, '_regular_price', true);
                                    }
                                    ?>
                                    <input type="number" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'regular_price')); ?>" id="regular_price" name="regular_price" step="any" value="<?php echo esc_attr($msfc_wfm_regular_price); ?>">
                                    <?php echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'regular_price'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_price"><?php esc_html_e('Sale Price','msfc-wfm'); ?></label>
                                    <?php
                                    /**sale price
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_sale_price = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'sale_price');
                                    }else{
                                        $msfc_wfm_sale_price = get_post_meta($product_id, '_sale_price', true);
                                    }
                                    ?>
                                    <input type="number" class="form-control<?php echo esc_attr(msfc_wfm_error_css_class('msfc_add_product_errors', 'sale_price')); ?>" id="sale_price" name="sale_price" step="any" value="<?php echo esc_attr($msfc_wfm_sale_price); ?>">
                                    <?php echo msfc_wfm_show_error_msg('msfc_add_product_errors', 'sale_price'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="weight"><?php esc_html_e('Weight','msfc-wfm'); ?> (<?php echo esc_html(get_option('woocommerce_weight_unit')); ?>)</label>
                                    <?php
                                    /**weight
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_weight = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'weight');
                                    }else{
                                        $msfc_wfm_weight = get_post_meta($product_id, '_weight', true);
                                    }
                                    ?>
                                    <input type="number" class="form-control" id="weight" name="weight" step="any" value="<?php echo esc_attr($msfc_wfm_weight); ?>">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="length"><?php esc_html_e('Dimensions','msfc-wfm'); ?> (<?php echo esc_html(get_option('woocommerce_dimension_unit')); ?>)</label>
                                    <?php
                                    /**length
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_length = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'length');
                                    }else{
                                        $msfc_wfm_length = get_post_meta($product_id, '_length', true);
                                    }

                                    /**width
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_width = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'width');
                                    }else{
                                        $msfc_wfm_width = get_post_meta($product_id, '_width', true);
                                    }

                                    /**height
                                    **show data from transient after getting errros.
                                    ***/
                                    if( $errors == true ){
                                        $msfc_wfm_height = msfc_wfm_prev_post_value('msfc_wfm_add_product_posted_arr', 'height');
                                    }else{
                                        $msfc_wfm_height = get_post_meta($product_id, '_height', true);
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" id="length" name="length" placeholder="<?php echo esc_attr__('Length','msfc-wfm'); ?>" step="any" value="<?php echo esc_attr($msfc_wfm_length); ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" id="width" name="width" placeholder="<?php echo esc_attr__('Width','msfc-wfm'); ?>" step="any" value="<?php echo esc_attr($msfc_wfm_width); ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" id="height" name="height" placeholder="<?php echo esc_attr__('Height','msfc-wfm'); ?>" step="any" value="<?php echo esc_attr($msfc_wfm_height); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php wp_nonce_field( 'msfc_wfm_edit_product_nonce_194', 'msfc_wfm_product_edit_nonce' ); ?>
                            <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
                            <input type="hidden" name="action" value="msfc_wfm_edit_product_action">
                            <button class="msfc-wfm-button msfc-submit-btn" name="update_product" type="submit"><?php esc_html_e('Update Product','msfc-wfm'); ?></button>
                        </div>
                        <?php
                        //Delete transient of previous posted data if stored whirle occure errors
                        $msfc_wfm_prev_posted_data = get_transient( 'msfc_wfm_add_product_posted_arr' );
                        if($msfc_wfm_prev_posted_data == true){
                            delete_transient( 'msfc_wfm_add_product_posted_arr' );
                        }

                        //delete errors transient when save success
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
        echo "<h1>".esc_html__('This is not accessable directly. :)','msfc-wfm')."</h1>";
    }
}else{
    echo "<h1>".esc_html__('This is not your area. :)','msfc-wfm')."</h1>";
}