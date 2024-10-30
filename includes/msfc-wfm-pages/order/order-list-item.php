<?php
/**
 * Orders List Page
 * **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {
    echo msfc_wfm_success_error_message();
?>
<div class="msfc-wfm-container">
    <div class="row">
        <div class="col-md-3"><?php echo msfc_wfm_navigation(); ?></div>
        <div class="col-md-9">
            <div class="msfc-wfm-content">
                <?php
                $orders_per_page = -1;
                $orders_query = array(
                    'posts_per_page' => $orders_per_page,
                    'post_type' => 'shop_order',
                    'post_status' =>  array_keys( wc_get_order_statuses() ),
                );
                
                $order_query = new WP_Query( $orders_query );

                if($order_query->found_posts > 0){
                ?>
                <table class="msfc-wfm-tbl msfc-wfm-product-list-table shop_table msfc-wfm-order-list-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Order No.', 'msfc-wfm'); ?></th>
                            <th><?php esc_html_e('Date', 'msfc-wfm'); ?></th>
                            <th><?php esc_html_e('Status', 'msfc-wfm'); ?></th>
                            <th><?php esc_html_e('Total', 'msfc-wfm'); ?></th>
                            <th><?php esc_html_e('Action', 'msfc-wfm'); ?></th>
                        </tr>
                    <tbody>
                        <?php
                        while($order_query->have_posts()) : $order_query->the_post();
                        $order_id = get_the_ID();
                        $order = wc_get_order($order_id);
                        $item_count = $order->get_item_count();
                        ?>
                            <tr class="single-product-item">
                                <td><a href="<?php echo esc_url(get_home_url().'/msfc-order-details/'.$order_id.'/'); ?>"><?php echo esc_html($order->get_order_number()); ?></a></td>
                                
                                <td><time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time></td>
                                
                                <td><span class="status order-<?php echo esc_attr($order->get_status());?>"><?php echo esc_html(wc_get_order_status_name( $order->get_status())); ?></span></td>
                                
                                <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                                
                                <td class="action-buttons">
                                    <a href="<?php echo esc_url(get_home_url().'/msfc-order-details/'.$order_id); ?>" class="woocommerce-button view"><?php esc_html_e('View/Status', 'msfc-wfm'); ?></a>
                                    <form class="d-inline" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                                        <?php wp_nonce_field( 'msfc_wfm_sent_invoice_order_nonce_120', 'msfc_wfm_sent_invoice_nonce' ); ?>
                                        <input type="hidden" name="id" value="<?php echo esc_attr($order_id); ?>">
                                        <input type="hidden" name="sent_invoice" value="<?php echo esc_attr($order_id); ?>">
                                        <input type="hidden" name="action" value="msfc_wfm_sent_invoice_action">
                                        <button type="submit" class="woocommerce-button edit"><?php esc_html_e('Sent Invoice', 'msfc-wfm'); ?></button>
                                    </form>
                                    <form class="d-inline" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                                        <?php wp_nonce_field( 'msfc_wfm_dlt_order_nonce_610', 'msfc_wfm_dlt_nonce' ); ?>
                                        <input type="hidden" name="id" value="<?php echo esc_attr($order_id); ?>">
                                        <input type="hidden" name="action" value="msfc_wfm_trash_order_action">
                                        <button type="submit" class="woocommerce-button delete"><?php esc_html_e('Delete', 'msfc-wfm'); ?></button>
                                    </form>
                                </td>
                            </tr>     
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                        </tbody>
                    </thead>
                </table>
                <?php }else{ ?>
                    <div class="alert alert-warning" role="alert"><?php esc_html_e('No order placed yet!', 'msfc-wfm'); ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    echo "<h1>".esc_html__('This is not your area. :)', 'msfc-wfm')."</h1>";
}