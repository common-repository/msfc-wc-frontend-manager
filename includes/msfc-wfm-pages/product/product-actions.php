<?php
/*****************
 * Sanitize POST array each item.
 * ****************/
function msfc_wfm_sanitize_product_info($items){
    if(is_array($items)){
        foreach ($items as $key => &$item){

            if($key == "product_description"){
                $item = wp_kses_post($item);
            }else{
                $item = sanitize_text_field($item);
            }
        }
    }
    return $items;
}

/*****************
 * Delete product (Actually Move to trash)
 * ****************/
function msfc_wfm_move_product_trash(){
    if( isset($_POST['msfc_wfm_dlt_nonce']) && wp_verify_nonce($_POST['msfc_wfm_dlt_nonce'], 'msfc_wfm_dlt_product_nonce_211') ){ //nonce check
        if(isset($_POST['id'])){
            $msfc_wfm_trash_product = wp_trash_post( $_POST['id'] );

            if( ($msfc_wfm_trash_product->ID) ==  intval($_POST['id']) ){
                //Store success message
                msfc_wfm_message(__('Product successfully deleted.','msfc-wfm'), 'success');
            }else{
                //Store error message
                msfc_wfm_message(__('The requested product does not exists.','msfc-wfm'), 'danger');
            }

            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid product delete request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }
    }else{

        //Store error message
        msfc_wfm_message(__('Product not deleted.','msfc-wfm'), 'danger');
        
        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
 }
 add_action('wp_ajax_msfc_wfm_trash_product_action', 'msfc_wfm_move_product_trash');




  /***************
 * Add Product Submit Handle
 * ****************/
function msfc_wfm_add_product_action(){
    if( isset($_POST['msfc_wfm_product_nonce']) && wp_verify_nonce($_POST['msfc_wfm_product_nonce'], 'msfc_wfm_add_product_nonce_894') ){ //nonce check
        if(isset($_POST['save_product'])){
            $errors = [];
            $product_title = isset( $_POST['product_title'] ) ? sanitize_text_field($_POST['product_title']) : '';
            $product_description = isset( $_POST['product_description'] ) ? wp_kses_post($_POST['product_description']) : '';
            $product_short_description = isset( $_POST['product_short_description'] ) ? sanitize_text_field($_POST['product_short_description']) : '';
            $product_thumbnail_id = isset( $_POST['product_thumbnail_id'] ) ? sanitize_text_field($_POST['product_thumbnail_id']) : '';
            $product_image_gallery = isset( $_POST['product_image_gallery'] ) ? sanitize_text_field($_POST['product_image_gallery']) : '';
            $product_category = isset( $_POST['product_category'] ) ? sanitize_text_field($_POST['product_category']) : '';
            $product_tags = isset( $_POST['product_tags'] ) ? sanitize_text_field($_POST['product_tags']) : '';
            $regular_price = isset( $_POST['regular_price'] ) ? sanitize_text_field($_POST['regular_price']) : '';
            $sale_price = isset( $_POST['sale_price'] ) ? sanitize_text_field($_POST['sale_price']) : '';
            $weight = isset( $_POST['weight'] ) ? sanitize_text_field($_POST['weight']) : '';
            $length = isset( $_POST['length'] ) ? sanitize_text_field($_POST['length']) : '';
            $width = isset( $_POST['width'] ) ? sanitize_text_field($_POST['width']) : '';
            $height = isset( $_POST['height'] ) ? sanitize_text_field($_POST['height']) : '';
            $author_id = get_current_user_id();


            if ( empty( $product_title ) ) {
                $errors['product_title'] = __( 'Product title field is required.', 'msfc-wfm' );
            }

            if ( empty( $product_description ) ) {
                $errors['product_description'] = __( 'Product description field is required.', 'msfc-wfm' );
            }

            if ( empty( $product_category ) ) {
                $errors['product_category'] = __( 'Please select product category.', 'msfc-wfm' );
            }

            if ( empty( $regular_price ) ) {
                $errors['regular_price'] = __( 'Product regular price field is required.', 'msfc-wfm' );
            }

            if ( !empty( $regular_price ) && !empty( $sale_price ) && ( $sale_price >= $regular_price ) ) {
                $errors['sale_price'] = __( 'Please enter sale price value less than the regular price.', 'msfc-wfm' );
            }

            //check errors
            if ( ! empty( $errors ) ) {
                //store validation errors array message on transient api
                set_transient( 'msfc_add_product_errors', $errors );

                //store posted data inside transient api
                set_transient( 'msfc_wfm_add_product_posted_arr', msfc_wfm_sanitize_product_info($_POST) );

                //Redirect current page
                $location = $_SERVER['HTTP_REFERER'];
                wp_safe_redirect($location);
                exit();

            }else{

                //Save Product
                $inserted_post_id = wp_insert_post( array(
                    'post_title' => $product_title,
                    'post_status' => 'publish',
                    'post_type' => "product",
                    'post_content' => $product_description,
                    'post_excerpt' => $product_short_description,
                    '_thumbnail_id' => $product_thumbnail_id,
                    'tax_input'    => array(
                        "product_cat" => $product_category,
                        "product_tag" => $product_tags
                    ),
                    'post_author' => $author_id
                ) );
                wp_set_object_terms( $inserted_post_id, 'simple', 'product_type' );
                update_post_meta( $inserted_post_id, '_stock_status', 'instock');
                update_post_meta( $inserted_post_id, '_regular_price', $regular_price );
                if( !empty( $sale_price ) ){
                    update_post_meta( $inserted_post_id, '_price', $sale_price );
                }else{
                    update_post_meta( $inserted_post_id, '_price', $regular_price );
                }
                update_post_meta( $inserted_post_id, '_sale_price', $sale_price );
                update_post_meta( $inserted_post_id, '_weight', $weight );
                update_post_meta( $inserted_post_id, '_length', $length );
                update_post_meta( $inserted_post_id, '_width', $width );
                update_post_meta( $inserted_post_id, '_height', $height );
                update_post_meta( $inserted_post_id, '_sku', 'msfc-'.$inserted_post_id );
                update_post_meta( $inserted_post_id, '_product_image_gallery', $product_image_gallery );

                //Store success message
                msfc_wfm_message(__('Product successfully added.','msfc-wfm'), 'success');
                
                //Redirect current page
                $location = $_SERVER['HTTP_REFERER'];
                wp_safe_redirect($location);
                exit();
            }
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid product add request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit(); 
        }
    }else{

        //Store error message
        msfc_wfm_message(__('Product not added.','msfc-wfm'), 'danger');
        
        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}
add_action('wp_ajax_msfc_wfm_add_product_action', 'msfc_wfm_add_product_action');



 /****************
 * Edit Product Submit Handle
 * **************/
function msfc_wfm_edit_product_action_content(){
    if( isset($_POST['msfc_wfm_product_edit_nonce']) && wp_verify_nonce($_POST['msfc_wfm_product_edit_nonce'], 'msfc_wfm_edit_product_nonce_194') ){ //nonce check
        if(isset($_POST['update_product'])){
            $errors = [];
            $product_id = isset( $_POST['product_id'] ) ? sanitize_text_field($_POST['product_id']) : '';
            $product_title = isset( $_POST['product_title'] ) ? sanitize_text_field($_POST['product_title']) : '';
            $product_description = isset( $_POST['product_description'] ) ? wp_kses_post($_POST['product_description']) : '';
            $product_short_description = isset( $_POST['product_short_description'] ) ? sanitize_text_field($_POST['product_short_description']) : '';
            $product_thumbnail_id = isset( $_POST['product_thumbnail_id'] ) ? sanitize_text_field($_POST['product_thumbnail_id']) : '';
            $product_image_gallery = isset( $_POST['product_image_gallery'] ) ? sanitize_text_field($_POST['product_image_gallery']) : '';
            $product_category = isset( $_POST['product_category'] ) ? sanitize_text_field($_POST['product_category']) : '';
            $product_tags = isset( $_POST['product_tags'] ) ? sanitize_text_field($_POST['product_tags']) : '';
            $regular_price = isset( $_POST['regular_price'] ) ? sanitize_text_field($_POST['regular_price']) : '';
            $sale_price = isset( $_POST['sale_price'] ) ? sanitize_text_field($_POST['sale_price']) : '';
            $weight = isset( $_POST['weight'] ) ? sanitize_text_field($_POST['weight']) : '';
            $length = isset( $_POST['length'] ) ? sanitize_text_field($_POST['length']) : '';
            $width = isset( $_POST['width'] ) ? sanitize_text_field($_POST['width']) : '';
            $height = isset( $_POST['height'] ) ? sanitize_text_field($_POST['height']) : '';
            $author_id = get_current_user_id();


            if ( empty( $product_title ) ) {
                $errors['product_title'] = __( 'Product title field is required.', 'msfc-wfm' );
            }

            if ( empty( $product_description ) ) {
                $errors['product_description'] = __( 'Product description field is required.', 'msfc-wfm' );
            }

            if ( empty( $product_category ) ) {
                $errors['product_category'] = __( 'Please select product category.', 'msfc-wfm' );
            }

            if ( empty( $regular_price ) ) {
                $errors['regular_price'] = __( 'Product regular price field is required.', 'msfc-wfm' );
            }

            if ( !empty( $regular_price ) && !empty( $sale_price ) && ( $sale_price >= $regular_price ) ) {
                $errors['sale_price'] = __( 'Please enter sale price less than the regular price.', 'msfc-wfm' );
            }

            //check errors
            if ( ! empty( $errors ) ) {
                
                //store validation errors array message on transient api
                set_transient( 'msfc_add_product_errors', $errors );
                
                //store posted data inside transient api
                set_transient( 'msfc_wfm_add_product_posted_arr', msfc_wfm_sanitize_product_info($_POST) );

                //Redirect current page
                $location = $_SERVER['HTTP_REFERER'];
                wp_safe_redirect($location);
                exit();

            }else{

                //Update Product
                wp_update_post( array(
                    'ID' => $product_id,
                    'post_title' => $product_title,
                    'post_status' => 'publish',
                    'post_type' => "product",
                    'post_content' => $product_description,
                    'post_excerpt' => $product_short_description,
                    '_thumbnail_id' => $product_thumbnail_id,
                    'tax_input'    => array(
                        "product_cat" => $product_category,
                        "product_tag" => $product_tags
                    ),
                    'post_author' => $author_id
                ) );
                // wp_set_object_terms( $product_id, 'simple', 'product_type' );
                update_post_meta( $product_id, '_stock_status', 'instock');
                update_post_meta( $product_id, '_regular_price', $regular_price );
                if( !empty( $sale_price ) ){
                    update_post_meta( $product_id, '_price', $sale_price );
                }else{
                    update_post_meta( $product_id, '_price', $regular_price );
                }
                update_post_meta( $product_id, '_sale_price', $sale_price );
                update_post_meta( $product_id, '_weight', $weight );
                update_post_meta( $product_id, '_length', $length );
                update_post_meta( $product_id, '_width', $width );
                update_post_meta( $product_id, '_height', $height );
                update_post_meta( $product_id, '_sku', 'msfc-'.$product_id );
                update_post_meta( $product_id, '_product_image_gallery', $product_image_gallery );

                //Store success message
                msfc_wfm_message(__('The Product successfully updated.','msfc-wfm'), 'success');
                
                //Redirect current page
                $location = $_SERVER['HTTP_REFERER'];
                wp_safe_redirect($location);
                exit();
            }
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid product update request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }
    }else{

        //Store error message
        msfc_wfm_message(__('Product not updated.','msfc-wfm'), 'danger');
        
        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}
add_action('wp_ajax_msfc_wfm_edit_product_action', 'msfc_wfm_edit_product_action_content');