<?php

/**
 * Delete Order (Actually Move to trash)
 * **/
function msfc_wfm_move_order_trash(){
    if( isset($_POST['msfc_wfm_dlt_nonce']) && wp_verify_nonce($_POST['msfc_wfm_dlt_nonce'], 'msfc_wfm_dlt_order_nonce_610') ){ //nonce check
        if(isset($_POST['id'])){
            $msfc_wfm_trash_order = wp_trash_post( $_POST['id'] );

            if( ($msfc_wfm_trash_order->ID) ==  intval($_POST['id']) ){
                //Store success message
                msfc_wfm_message(__('Order successfully deleted.','msfc-wfm'), 'success');
            }else{
                //Store error message
                msfc_wfm_message(__('The requested order does not exists.','msfc-wfm'), 'danger');
            }

            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid order delete request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }
    }else{
        //Store error message
        msfc_wfm_message(__('Order not deleted.','msfc-wfm'), 'danger');
        
        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}
add_action('wp_ajax_msfc_wfm_trash_order_action', 'msfc_wfm_move_order_trash');


/**
 * Update order status
 * **/
function msfc_wfm_status_order_action(){
    if( isset($_POST['msfc_wfm_order_nonce']) && wp_verify_nonce($_POST['msfc_wfm_order_nonce'], 'msfc_wfm_status_order_nonce_340') ){ //nonce check

        if( isset($_POST['id']) && isset($_POST['order-status']) ){
            $order = new WC_Order( sanitize_text_field($_POST['id']) );
            if (!empty($order)) {
                $msfc_wfm_update_order_status = $order->update_status( sanitize_text_field($_POST['order-status']) );

                //check mail notify and sent order status update email to customer
                if( $msfc_wfm_update_order_status && isset($_POST['msfc-wfm-mail-notify']) && (sanitize_text_field( $_POST['msfc-wfm-mail-notify'] ) == 1) ){

                    $msfc_wfm_wc_emails = WC()->mailer()->get_emails();

                    //order status check
                    if ($order->has_status( 'on-hold' )){
                        $msfc_wfm_order_email_id = 'customer_on_hold_order';
                    }elseif ($order->has_status( 'processing' )){
                        $msfc_wfm_order_email_id = 'customer_processing_order';
                    }elseif ($order->has_status( 'completed' )){
                        $msfc_wfm_order_email_id = 'customer_completed_order';
                    }elseif ($order->has_status( 'pending' )){ //on pending send on hold order email
                        $msfc_wfm_order_email_id = 'customer_on_hold_order';
                    }elseif ($order->has_status( 'cancelled' )){
                        $msfc_wfm_order_email_id = 'cancelled_order';
                    }elseif ($order->has_status( 'refunded' )){
                        $msfc_wfm_order_email_id = 'customer_refunded_order';
                    }elseif ($order->has_status( 'failed' )){
                        $msfc_wfm_order_email_id = 'failed_order';
                    }else{
                        $msfc_wfm_order_email_id = "nothingtosent";
                    }

                    //sent email to customer
                    if ( ! empty( $msfc_wfm_wc_emails ) ) {
                        foreach ( $msfc_wfm_wc_emails as $wc_mail ) {
                            if ( $wc_mail->id == $msfc_wfm_order_email_id ) {
                               $wc_mail->trigger( $order->get_id() );
                            }
                         }
                    }

                    //Store success message with email sent message
                    msfc_wfm_message( __( 'Order status successfully updated to '.wc_get_order_status_name( $order->get_status() ).'. Order status mail sent to customer.', 'msfc-wfm' ), 'success');

                }elseif( $msfc_wfm_update_order_status ){
                    //Store success message
                    msfc_wfm_message( __( 'Order status successfully updated to '.wc_get_order_status_name( $order->get_status() ).'.', 'msfc-wfm' ), 'success');
                }else{
                    //Store error message
                    msfc_wfm_message(__('The requested order does not exists.','msfc-wfm'), 'danger');
                }
            }

            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid order update request.','msfc-wfm'), 'danger');
            
            //Redirect current page
            $location = $_SERVER['HTTP_REFERER'];
            wp_safe_redirect($location);
            exit();
        }
    }else{
        //Store error message
        msfc_wfm_message(__('Order status update failed.','msfc-wfm'), 'danger');

        //Redirect current page
        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        exit();
    }
}
add_action('wp_ajax_msfc_wfm_status_order_action', 'msfc_wfm_status_order_action');




/**
 * Sent order Invoice to customer
 * **/
function msfc_wfm_sent_invoice_action_func(){
    if( isset($_POST['msfc_wfm_sent_invoice_nonce']) && wp_verify_nonce($_POST['msfc_wfm_sent_invoice_nonce'], 'msfc_wfm_sent_invoice_order_nonce_120') ){ //nonce check

        if( isset($_POST['id']) && isset($_POST['sent_invoice']) ){
            $order = new WC_Order( sanitize_text_field($_POST['id']) );
            if (!empty($order)) {

                //sent invoice email to customer
                $msfc_wfm_wc_invo_emails = WC()->mailer()->get_emails();
                
                if ( ! empty( $msfc_wfm_wc_invo_emails ) ) {
                    foreach ( $msfc_wfm_wc_invo_emails as $wc_mail ) {
                        if ( $wc_mail->id == 'customer_invoice' ) {
                           $wc_mail->trigger( $order->get_id() );
                        }
                     }
                }

                //Store success message with email sent message
                msfc_wfm_message( __( 'Order invoice mail sent to customer.', 'msfc-wfm' ), 'success');
            }else{
                //Store error message
                msfc_wfm_message(__('The requested order does not exists.','msfc-wfm'), 'danger');
            }
        }else{
            //Store error message
            msfc_wfm_message(__('Invalid order update request.','msfc-wfm'), 'danger');
        }
    }else{
        //Store error message
        msfc_wfm_message(__('Order status update failed.','msfc-wfm'), 'danger');
    }

    //Redirect current page
    $location = $_SERVER['HTTP_REFERER'];
    wp_safe_redirect($location);
    exit();
}
add_action('wp_ajax_msfc_wfm_sent_invoice_action', 'msfc_wfm_sent_invoice_action_func');