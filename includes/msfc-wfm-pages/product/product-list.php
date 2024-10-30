<?php
/**
 * MSFC WFM Product List
 * ***/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {
    echo msfc_wfm_success_error_message();
?>
<div class="msfc-wfm-container">
    <div class="row">
        <div class="col-md-3">
            <?php echo msfc_wfm_navigation(); ?>
        </div>
        <div class="col-md-9">
            <a href="<?php echo esc_url(get_home_url() . '/msfc-add-product/'); ?>" class="msfc-wfm-button"><?php esc_html_e('Add New Product', 'msfc-wfm'); ?></a>
            <div class="msfc-wfm-content">
                <?php
                $posts_per_page = -1;
                $query = array(
                    'posts_per_page' => $posts_per_page,
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'orderby' => 'publish_date',
                    'order' => 'DESC',
                );
                
                $post_query = new WP_Query( $query );
                if($post_query->found_posts > 0){
                ?>
                <table class="msfc-wfm-tbl msfc-wfm-product-list-table shop_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php esc_html_e('Product Title', 'msfc-wfm'); ?></th>
                            <th><?php esc_html_e('Category', 'msfc-wfm'); ?></th>
                            <th><?php esc_html_e('Price', 'msfc-wfm'); ?></th>
                            <th><?php esc_html_e('Action', 'msfc-wfm'); ?></th>
                        </tr>
                        <tbody>
                        <?php
                        while($post_query->have_posts()) : $post_query->the_post();
                        $product_id = get_the_ID();
                        $product = wc_get_product( get_the_ID() );
                        $msfc_wfm_thumb = get_the_post_thumbnail_url($product_id, 'thumbnail');
                        if(!empty( $msfc_wfm_thumb )){
                            $msfc_wfm_thumb = $msfc_wfm_thumb;
                        }else{
                            $msfc_wfm_thumb = wc_placeholder_img_src('thumbnail');
                        }
                        ?>
                            <tr class="single-product-item">
                                <td><img src="<?php echo esc_url($msfc_wfm_thumb); ?>" class="msfc-wfm-thumb" alt="<?php echo esc_attr(get_the_title()); ?>"></td>
                                <td><a href="<?php echo esc_url(get_home_url() . '/msfc-product-details/' . $product_id . '/'); ?>"><?php echo esc_attr(get_the_title()); ?></a></td>
                                <td><?php echo wp_kses_post(wc_get_product_category_list($product_id, ', ', '', '')); ?></td>
                                <td><?php echo wp_kses_post($product->get_price_html()); ?></td>
                                <td class="action-buttons">
                                    <a href="<?php echo esc_url(get_home_url() . '/msfc-product-details/' . $product_id . '/'); ?>" class="woocommerce-button view"><?php esc_html_e('View', 'msfc-wfm'); ?></a>
                                    <a href="<?php echo esc_url(get_home_url() . '/msfc-edit-product/' . $product_id . '/'); ?>" class="woocommerce-button edit"><?php esc_html_e('Edit', 'msfc-wfm'); ?></a>
                                    <form class="d-inline" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                                        <?php wp_nonce_field( 'msfc_wfm_dlt_product_nonce_211', 'msfc_wfm_dlt_nonce' ); ?>
                                        <input type="hidden" name="id" value="<?php echo esc_attr($product_id); ?>">
                                        <input type="hidden" name="action" value="msfc_wfm_trash_product_action">
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
                    <div class="alert alert-warning" role="alert"><?php esc_html_e('No product found!', 'msfc-wfm'); ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    echo "<h1>".esc_html__('This is not your area. :)', 'msfc-wfm')."</h1>";
}