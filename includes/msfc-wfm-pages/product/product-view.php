<?php
/**
 * Product View Page
 * **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {

//get woocommerce weight and dimension unit
$msfc_wfm_weight_unit = get_option('woocommerce_weight_unit');
$msfc_wfm_dimension_unit = get_option('woocommerce_dimension_unit');

$product_id = get_query_var( 'view-product-id' );
$product = wc_get_product( $product_id );

if(!empty($product_id)){
?>
<div class="msfc-wfm-container">
    <div class="row">
        <div class="col-md-3"><?php echo msfc_wfm_navigation(); ?></div>
        <div class="col-md-9">
            <a href="<?php echo esc_url(get_home_url() . '/msfc-edit-product/' . $product_id . '/'); ?>" class="msfc-wfm-button"><?php echo esc_html('Edit Product', 'msfc-wfm'); ?></a>
            <div class="msfc-wfm-content">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="msfc-wfm-product-preview">
                        <?php
                        $msfc_wfm_product_image = get_the_post_thumbnail_url( $product_id, 'full' );
                        if( !empty( $msfc_wfm_product_image ) ){ ?>
                            <div class="msfc-wfm-big-preview-image">
                                <img id="msfc-wfm-big-preview-src" src="<?php echo esc_url($msfc_wfm_product_image); ?>" alt="<?php echo esc_attr(get_the_title($product_id)); ?>" />
                            </div>
                        <?php }
                        
                        //Gallery Images
                        $msfc_wfm_attachment_ids = $product->get_gallery_image_ids();
                        if( !empty($msfc_wfm_attachment_ids) ){

                            echo '<ul class="msfc-wfm-product-thumbnail p-0 list-unstyled mt-2">';
                            
                            $msfc_wfm_featured_product_image_thumb = get_the_post_thumbnail_url( $product_id, 'thumbnail' );
                            if( !empty( $msfc_wfm_featured_product_image_thumb ) ){
                                echo '<li class="thumb-gl-item list-inline-item"><img class="msfc-wfm-thumb-gl-item-img active" src="'.esc_url($msfc_wfm_featured_product_image_thumb).'" alt="'.esc_attr(get_the_title($product_id)).'" attr-big-image="'.esc_attr($msfc_wfm_product_image).'"></li>';
                            }

                            foreach( $msfc_wfm_attachment_ids as $msfc_wfm_attachment_id ) {
                                $msfc_wfm_image_link = wp_get_attachment_url( $msfc_wfm_attachment_id );
                                $msfc_wfm_image_thumb_link = wp_get_attachment_image_src( $msfc_wfm_attachment_id, 'thumbnail' );
                                echo '<li class="thumb-gl-item list-inline-item"><img class="msfc-wfm-thumb-gl-item-img" src="'.esc_url($msfc_wfm_image_thumb_link[0]).'" alt="'.esc_attr(get_the_title($product_id)).'" attr-big-image="'.esc_attr($msfc_wfm_image_link).'"></li>';
                            }
                            echo '<ul>';
                        }
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 msfc-wfm-product-simple">
                        <h1><?php echo esc_html(get_the_title($product_id)); ?></h1>
                        <div class="msfc_wfm_product_price mb-3"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                        <p><?php echo wp_kses_post(get_the_excerpt($product_id)); ?></p>
                        <ul class="msfc-wfm-metas p-0 list-unstyled mt-4 ml-0">
                        <?php
                            //get all categories
                            $msfc_wfm_all_cats = '';
                            $msfc_wfm_product_cats = get_the_terms( $product_id, 'product_cat' );
                            if( !empty($msfc_wfm_product_cats) ){
                                $msfc_wfm_all_cats = join(', ', wp_list_pluck( $msfc_wfm_product_cats, 'name'));
                                echo '<li><strong>'.esc_html__('Categories:', 'msfc-wfm').'</strong> '.esc_html($msfc_wfm_all_cats).'</li>';
                            }
                            
                            //get all tags
                            $msfc_wfm_all_terms = '';
                            $msfc_wfm_product_tags = get_the_terms( $product_id, 'product_tag' );
                            if( !empty($msfc_wfm_product_tags) ){
                                $msfc_wfm_all_terms = join(', ', wp_list_pluck( $msfc_wfm_product_tags, 'name'));
                                echo '<li><strong>'.esc_html__('Tags:', 'msfc-wfm').'</strong> '.esc_html($msfc_wfm_all_terms).'</li>';
                            }

                            //get weight
                            if( !empty( get_post_meta($product_id, '_weight', true) ) ){
                                echo '<li><strong>'.esc_html__('Weight ('.$msfc_wfm_weight_unit.')', 'msfc-wfm').': </strong>'.esc_html(get_post_meta($product_id, '_weight', true)).'</li>';
                            }

                            //get length
                            if( !empty( get_post_meta($product_id, '_length', true) ) ){
                                echo '<li><strong>'.esc_html__('Length:', 'msfc-wfm').' </strong>'.esc_html(get_post_meta($product_id, '_length', true)).' '.esc_html($msfc_wfm_dimension_unit).'</li>';
                            }

                            //get width
                            if( !empty( get_post_meta($product_id, '_width', true) ) ){
                                echo '<li><strong>'.esc_html__('Width:', 'msfc-wfm').' </strong>'.esc_html(get_post_meta($product_id, '_width', true)).' '.esc_html($msfc_wfm_dimension_unit).'</li>';
                            }

                            //get height
                            if( !empty( get_post_meta($product_id, '_height', true) ) ){
                                echo '<li><strong>'.esc_html__('Height:', 'msfc-wfm').' </strong>'.esc_html(get_post_meta($product_id, '_height', true)).' '.esc_html($msfc_wfm_dimension_unit).'</li>';
                            }
                        ?>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-3 msfc-wfm-product-details-desc">
                            <h4><?php echo esc_html__('Description', 'msfc-wfm'); ?></h4>
                            <p><?php echo wp_kses_post(get_the_content(null, false, $product_id)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php
    }else{
        echo "<h1>".esc_html__('This is not accessable directly. :)', 'msfc-wfm')."</h1>";
    }
}else{
    echo "<h1>".esc_html__('This is not your area. :)', 'msfc-wfm')."</h1>";
}