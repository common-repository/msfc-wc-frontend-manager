<?php
/**
 * Order Customer Details
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function msfc_wfm_order_details_customer($order){
    $show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
    <section class="woocommerce-customer-details">
        <?php
        if ( $show_shipping ){
        ?>
        <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
            <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">';
        <?php } ?>
            <h2 class="woocommerce-column__title"><?php echo esc_html__( 'Billing address', 'msfc-wfm' ); ?></h2>
                <address>
                    <?php
                    echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'msfc-wfm' ) ) );

                    if ( $order->get_billing_phone() ){
                        echo '<p class="woocommerce-customer-details--phone">'.esc_html( $order->get_billing_phone() ).'</p>';
                    }

                    if ( $order->get_billing_email() ){
                        echo '<p class="woocommerce-customer-details--email">'.esc_html( $order->get_billing_email() ).'</p>';
                    }?>
                </address>
            <?php
            if ( $show_shipping ){
            ?>
            </div>
            <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
                <h2 class="woocommerce-column__title"><?php echo esc_html( 'Shipping address', 'msfc-wfm' ); ?></h2>
                <address>
                    <?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'msfc-wfm' ) ) ); ?>
                </address>
            </div>
        </section>
        <?php } ?>
    </section>
    <?php
}