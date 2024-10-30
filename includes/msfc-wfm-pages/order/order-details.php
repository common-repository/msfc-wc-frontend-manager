<?php
/**
 * Order details
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {
    
    require 'order-details-item.php'; //include order details item function page
    require 'order-details-customer.php'; //include order details function page

    $order_id = get_query_var( 'order-id' );
    if(!empty($order_id)){
    $order = wc_get_order($order_id);
    $order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
    $show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
    $show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
    $downloads             = $order->get_downloadable_items();
    $show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();
    echo msfc_wfm_success_error_message();
?>
<div class="msfc-wfm-container">
    <div class="row">
        <div class="col-md-3">
            <?php echo msfc_wfm_navigation(); ?>
        </div>
        <div class="col-md-9">
            <div class="msfc-wfm-content">
                <p>
                <?php
                printf( esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'msfc-wfm' ),
                    '<mark class="order-number">' . $order->get_order_number() . '</mark>',
                    '<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>',
                    '<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>' )
                ?>
                </p>
                <section class="woocommerce-order-details">
                    <div class="row align-items-end mb-3">
                        <div class="col-md-12">
                            <h2 class="woocommerce-order-details__title mb-0"><?php echo esc_html__( 'Order details', 'msfc-wfm' ); ?></h2>
                        </div>
                    </div>
                    

                    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

                        <thead>
                            <tr>
                                <th class="woocommerce-table__product-name product-name"><?php echo esc_html__( 'Product', 'msfc-wfm' ); ?></th>
                                <th class="woocommerce-table__product-table product-total"><?php echo esc_html__( 'Total', 'msfc-wfm' ); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            foreach ( $order_items as $item_id => $item ) {
                                $product = $item->get_product();
                                $purchase_note = $product ? $product->get_purchase_note() : '';
                                echo msfc_wfm_order_details_item($order, $item_id, $item, $show_purchase_note, $purchase_note, $product); //order item list function
                            }
                        ?>
                    </tbody>

                        <tfoot>
                        <?php
                            foreach ( $order->get_order_item_totals() as $key => $total ) {
                                ?>
                                <tr>
                                    <th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
                                    <td><?php echo wp_kses_post( $total['value'] ); ?></td>
                                </tr>
                                <?php
                            }
                            if ( $order->get_customer_note() ){
                                ?>
                                <tr>
                                    <th><?php echo esc_html__( 'Note:', 'msfc-wfm' ); ?></th>
                                    <td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
                                </tr>
                                <?php
                            }
                        ?>
                        </tfoot>
                    </table>
                </section>
                <?php echo msfc_wfm_order_details_customer($order); //order customer details?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="order-status-update-form">
                        <h2 class="woocommerce-order-details__title"><?php echo esc_html__( 'Update order status', 'msfc-wfm' ); ?></h2>
                        <form class="msfc-wfm-inline-forms" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                            <div class="form-group">
                                <select class="form-control" name="order-status">
                                    <option value=""><?php echo esc_html__( 'Select order status', 'msfc-wfm' ); ?></option>
                                <?php
                                    // get all stores, even inactive ones
                                    $order_statuses = wc_get_order_statuses();
                                    foreach ( $order_statuses as $key=>$statusname ) {
                                        echo '<option value="'.esc_attr($key).'" '.esc_attr( ( ('wc-'.$order->get_status()) == $key)  ? 'selected' : '').'>'.esc_html($statusname).'</option>';
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="msfc-wfm-mail-notify" id="msfc-wfm-mail-notify" value="1">
                                    <label class="form-check-label" for="msfc-wfm-mail-notify"><?php echo esc_html__( 'Send customer notification email', 'msfc-wfm' ); ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php wp_nonce_field( 'msfc_wfm_status_order_nonce_340', 'msfc_wfm_order_nonce' ); ?>
                                <input type="hidden" name="id" value="<?php echo esc_attr($order->get_id()); ?>">
                                <input type="hidden" name="action" value="msfc_wfm_status_order_action">
                                <button type="submit" class="msfc-wfm-button msfc-submit-btn"><?php echo esc_html__( 'Update Status', 'msfc-wfm' ); ?></button>
                            </div>
                        </form>
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