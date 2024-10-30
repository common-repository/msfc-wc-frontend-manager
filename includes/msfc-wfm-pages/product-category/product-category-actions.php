<?php
/*****************
 * Sanitize Category POST array each item.
 * ****************/
function msfc_wfm_sanitize_category_info($items){
    if(is_array($items)){
        foreach ($items as $key => &$item){
            $item = sanitize_text_field($item);
        }
    }
    return $items;
}

//delete product category function
function msfc_wfm_dlt_product_cat_action_func() {
    if( isset($_POST['msfc_wfm_cat_dlt_non_nonce']) || wp_verify_nonce($_POST['msfc_wfm_cat_dlt_non_nonce'], 'msfc_wfm_cat_dlt_non_220') ){ //nonce check
        if(isset($_POST['id'])){
            //delete product_cat tax
            $msfc_product_cat_id = sanitize_text_field($_POST['id']);
            $msfc_wfm_dlt_term = wp_delete_term( $msfc_product_cat_id, 'product_cat' );

            if( $msfc_wfm_dlt_term ){
                //Store success message
                msfc_wfm_message(__('Category successfully deleted.','msfc-wfm'), 'success');
            }else{
                //Store error message
                msfc_wfm_message(__('The requested product category does not exists.','msfc-wfm'), 'danger');
            }

            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid product category delete request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }
    }else{
        //Store error message
        msfc_wfm_message(__('Product category not deleted.','msfc-wfm'), 'danger');
        
        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}
add_action( 'wp_ajax_msfc_wfm_dlt_product_cat_action', 'msfc_wfm_dlt_product_cat_action_func');



/*******
***Add Product Category***
*********/
function msfc_wfm_add_product_category_action_post(){
    if( isset($_POST['msfc_wfm_add_product_cat_nonce']) || wp_verify_nonce($_POST['msfc_wfm_add_product_cat_nonce'], 'msfc_wfm_cat_prodoct_n_456') ){ //nonce check
        if(isset($_POST['save_product_category'])){
            $errors = [];
            $product_category_name = isset( $_POST['product_category_name'] ) ? sanitize_text_field($_POST['product_category_name']) : '';
            $product_parent_category = isset( $_POST['product_parent_category'] ) ? sanitize_text_field($_POST['product_parent_category']) : '';
            $product_category_description = isset( $_POST['product_category_description'] ) ? sanitize_textarea_field($_POST['product_category_description']) : '';


            if ( empty( $product_category_name ) ) {
                $errors['product_category_name'] = __( 'Category name field is required.', 'msfc-wfm' );
            }

            //check errors
            if ( ! empty( $errors ) ) {
                
                //store validation errors array message on transient api
                set_transient( 'msfc_add_category_errors', $errors );
                
                //store posted data inside transient api
                set_transient( 'msfc_wfm_add_category_posted_arr', msfc_wfm_sanitize_category_info($_POST) );

                //Redirect current page
                $location = $_SERVER['HTTP_REFERER'];
                wp_safe_redirect($location);
                exit();

            }else{

                //Save Product Category but at first check exists this category
                $msfc_wfm_cat_term_exists = term_exists( $product_category_name, 'product_cat' );
                if ( $msfc_wfm_cat_term_exists !== 0 && $msfc_wfm_cat_term_exists !== null ) {
                    
                    //Store error message
                    msfc_wfm_message(__('Product category already exists.','msfc-wfm'), 'danger');
                                
                    //Redirect current page
                    $location = $_SERVER['HTTP_REFERER'];
                    wp_safe_redirect($location);
                    exit();

                }else{
                    
                    $msfc_wfm_parent_term = term_exists( $product_parent_category, 'product_cat' ); // array is returned if taxonomy is given

                    $msfc_wfm_term_insert = wp_insert_term(
                        $product_category_name, // the term 
                        'product_cat', // the Woocommerce product category taxonomy
                        array( // (optional)
                            'description'=> $product_category_description,
                            'parent'=> $msfc_wfm_parent_term['term_id']
                        )
                    );
                    
                    if( ! is_wp_error( $msfc_wfm_term_insert ) ){
                        //Store success message
                        msfc_wfm_message(__('Product Category successfully added.','msfc-wfm'), 'success');
                    }else{
                        //Store error message
                        msfc_wfm_message(__('Product Category insertion not possible.','msfc-wfm'), 'danger');
                    }
                    
                    
                    //Redirect current page
                    $location = $_SERVER['HTTP_REFERER'];
                    wp_safe_redirect($location);
                    exit();
                }
                
            }
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid product category add request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit(); 
        }
    }else{
        //Store error message
        msfc_wfm_message(__('Product category not added.','msfc-wfm'), 'danger');
        
        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}
add_action( 'wp_ajax_msfc_wfm_add_product_category_action', 'msfc_wfm_add_product_category_action_post');



/*******
***Edit/Update Product Category***
*********/
function msfc_wfm_edit_product_category_action_post(){
    if( isset($_POST['msfc_wfm_edit_product_cat_nonce']) || wp_verify_nonce($_POST['msfc_wfm_edit_product_cat_nonce'], 'msfc_wfm_cat_ed_prodoct_n_456') ){ //nonce check
        if( isset($_POST['edit_product_category']) && isset($_POST['term_id']) ){
            $errors = [];
            $product_category_id = isset( $_POST['term_id'] ) ? sanitize_text_field($_POST['term_id']) : '';
            $product_category_name = isset( $_POST['product_category_name'] ) ? sanitize_text_field($_POST['product_category_name']) : '';
            $product_parent_category = isset( $_POST['product_parent_category'] ) ? sanitize_text_field($_POST['product_parent_category']) : '';
            $product_category_description = isset( $_POST['product_category_description'] ) ? sanitize_textarea_field($_POST['product_category_description']) : '';


            if ( empty( $product_category_name ) ) {
                $errors['product_category_name'] = __( 'Category name field is required.', 'msfc-wfm' );
            }

            //check errors
            if ( ! empty( $errors ) ) {
                
                //store validation errors array message on transient api
                set_transient( 'msfc_edit_category_errors', $errors );
                
                //store posted data inside transient api
                set_transient( 'msfc_wfm_edit_category_posted_arr', msfc_wfm_sanitize_category_info($_POST) );

                //Redirect current page
                $location = $_SERVER['HTTP_REFERER'];
                wp_safe_redirect($location);
                exit();

            }else{

                //Save Product Category but at first check exists this category
                $msfc_wfm_cat_term_exists = term_exists( intval($product_category_id), 'product_cat' );
                if ( $msfc_wfm_cat_term_exists !== 0 && $msfc_wfm_cat_term_exists !== null ) {
                    
                    $msfc_wfm_parent_term = term_exists( $product_parent_category, 'product_cat' );

                    //Update category by id 
                    $msfc_wfm_category_update = wp_update_term( $product_category_id, 'product_cat', array(
                        'name' => $product_category_name,
                        'parent' => $msfc_wfm_parent_term['term_id'],
                        'description' => $product_category_description
                    ) );
                     
                    if( ! is_wp_error( $msfc_wfm_category_update ) ){
                        //Store success message
                        msfc_wfm_message(__('Product Category successfully updated.','msfc-wfm'), 'success');
                    }else{
                        //Store error message
                        msfc_wfm_message(__('Product Category update not possible.','msfc-wfm'), 'danger');
                    }
                                
                    //Redirect current page
                    $location = $_SERVER['HTTP_REFERER'];
                    wp_safe_redirect($location);
                    exit();

                }else{
                    
                    //Store error message
                    msfc_wfm_message(__('The update requested category not exists.','msfc-wfm'), 'danger');
                    
                    
                    //Redirect current page
                    $location = $_SERVER['HTTP_REFERER'];
                    wp_safe_redirect($location);
                    exit();
                }
                
            }
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid product category update request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit(); 
        }
    }else{
        //Store error message
        msfc_wfm_message(__('Product category not updated.','msfc-wfm'), 'danger');
        
        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}
add_action( 'wp_ajax_msfc_wfm_edit_product_category_action', 'msfc_wfm_edit_product_category_action_post');

?>