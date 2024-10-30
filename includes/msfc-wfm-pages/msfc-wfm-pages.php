<?php
/**
 * Include Pages
 * **/

//Frontend Pages
require 'navigation.php'; //left navigation
require 'products-list-page-shortcode.php';
require 'product-add-page-shortcode.php';
require 'product-edit-page-shortcode.php';
require 'product-view-page-shortcode.php';
require 'orders-list-page-shortcode.php';
require 'order-details-page-shortcode.php';
require 'product-category-shortcode.php';


//Insert, Select, Update, Delete Actions files
require 'order/orders-actions.php'; //order trash, update, delete actions
require 'product-category/product-category-actions.php'; //product category trash, update, delete actions
require 'product/product-actions.php'; //product trash, update, delete actions



/**
 * Store Success or Error Message function
 */
function msfc_wfm_message($message, $status){
	//Store message by option
	$msfc_wfm_msg_option = 'msfc_wfm_msg_'.get_current_user_id();
	if ( get_option( $msfc_wfm_msg_option ) !== false ) { // The option already exists, so update it.
		update_option( $msfc_wfm_msg_option, $message );
	} else { // The option hasn't been created yet.
		add_option( $msfc_wfm_msg_option, $message );
	}
	
	//Store message status by option
	$msfc_wfm_msg_status = 'msfc_wfm_msg_status_'.get_current_user_id();
	if ( get_option( $msfc_wfm_msg_status ) !== false ) { // The option already exists, so update it.
		update_option( $msfc_wfm_msg_status, esc_html($status) );
	} else { // The option hasn't been created yet.
		add_option( $msfc_wfm_msg_status, esc_html($status) );
	}
}

/**
 * Retrive Message Success or Failed
 * **/
//Success/Error Messages From Option
function msfc_wfm_success_error_message(){
    $msfc_wfm_message = 'msfc_wfm_msg_'.get_current_user_id();
    $msfc_wfm_message_status = 'msfc_wfm_msg_status_'.get_current_user_id();

    if( ( get_option( $msfc_wfm_message ) !== false ) && !empty( get_option( $msfc_wfm_message ) ) && ( get_option( $msfc_wfm_message_status ) !== false ) && !empty( get_option( $msfc_wfm_message_status ) ) ){  ?>
        <div class="msfc-wfm-alert alert-msfc-fadeout alert alert-<?php echo esc_attr(get_option( $msfc_wfm_message_status )); ?> alert-dismissible fade show text-left mt-3" role="alert">
            <?php echo esc_html(get_option( $msfc_wfm_message )); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        //Empty Option
        update_option( $msfc_wfm_message, '' );
        update_option( $msfc_wfm_message_status, '' );
    }
}


/**
 * Get Form Validation Error Messages List
 * **/
function msfc_wfm_validation_errors($errors){
    $error_message = '<div class="msfc-wfm-alert alert alert-danger alert-dismissible fade show text-left mt-3" role="alert">';
    foreach( $errors as $error ){
        $error_message .= esc_html($error).'<br>';
    }
    $error_message .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
    return $error_message;
}

/**
 * Get Form Validation Error Messages Individual
 * **/
function msfc_wfm_show_error_msg($error_transient_key, $error_field_name){
    $msfc_wfm_error_msg = get_transient( $error_transient_key );
    if($msfc_wfm_error_msg == true){
        if( array_key_exists($error_field_name, $msfc_wfm_error_msg) ){
            return '<div class="invalid-feedback">'.esc_html($msfc_wfm_error_msg[$error_field_name]).'</div>';
        }else{
            return ''; 
        }
    }else{
        return '';
    }
}

/**
 * Get Form Validation Error Messages Individual Class
 * **/
function msfc_wfm_error_css_class($error_transient_key, $error_field_name){
    $msfc_wfm_error_class = get_transient( $error_transient_key );
    if($msfc_wfm_error_class == true){
        if( array_key_exists($error_field_name, $msfc_wfm_error_class) ){
            return ' is-invalid';
        }else{
            return ''; 
        }
    }else{
        return '';
    }
}


/**
*get last posted when we will get validation errors.
 * **/
function msfc_wfm_prev_post_value($transient_key, $field_name){
    $msfc_wfm_prev_posted = get_transient( $transient_key );
    if($msfc_wfm_prev_posted == true){
        if( array_key_exists($field_name, $msfc_wfm_prev_posted) ){
            return $msfc_wfm_prev_posted[$field_name];
        }else{
            return ''; 
        }
    }else{
        return '';
    }
}