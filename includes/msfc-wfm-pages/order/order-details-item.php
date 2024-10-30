<?php
/**
 * Order Item Details
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function msfc_wfm_order_details_item($order, $item_id, $item, $show_purchase_note, $purchase_note, $product){
?>
    <tr class="woocommerce-table__line-item order_item">
        <td class="woocommerce-table__product-name product-name">
        <?php
            $is_visible        = $product && $product->is_visible();
            $product_permalink = $product->get_permalink( $item );

            echo sprintf( '<a href="%s">%s</a>', esc_url($product_permalink), esc_html($item->get_name()) );

            $qty = $item->get_quantity();
            $refunded_qty = $order->get_qty_refunded_for_item( $item_id );

            if ( $refunded_qty ) {
                $qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
            } else {
                $qty_display = esc_html( $qty );
            }
        ?>
            <strong class="product-quantity"><?php echo sprintf( '&times;&nbsp;%s', esc_html($qty_display) ); ?></strong>
        </td>

        <td class="woocommerce-table__product-total product-total"><?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?></td>

    </tr>
    <?php
    if ( $show_purchase_note && $purchase_note ){
    ?>
    <tr class="woocommerce-table__product-purchase-note product-purchase-note">
        <td colspan="2"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
    </tr>

<?php
    }
}
