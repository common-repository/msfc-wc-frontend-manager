<?php
/**
 * MSFC Product Category List table
 * ***/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Get product subcategories recursive function
function get_product_sub_categories($taxonomies, $parent_term_id, $pre_dash="-"){
    foreach( $taxonomies as $subcategory ) {
        if($subcategory->parent == $parent_term_id) {
    ?>
    <tr>
        <td> <?php echo esc_html($pre_dash); ?> <?php echo esc_html($subcategory->name); ?></td>
        <td><?php echo esc_html(wp_trim_words($subcategory->description, '9', '...' )); ?></td>
        <td><?php echo esc_html($subcategory->slug); ?></td>
        <td><?php echo esc_html($subcategory->count); ?></td>
        <td class="action-buttons">
            <a href="<?php echo esc_url(get_home_url() . '/product-category/' . $subcategory->slug . '/'); ?>" class="woocommerce-button view" target="_blank"><?php echo esc_html__('View', 'msfc-wfm'); ?></a>
            <a href="<?php echo esc_url( get_home_url()."/msfc-product-category/edit-product-category/".$subcategory->term_id."/" ); ?>" class="woocommerce-button edit"><?php echo esc_html__('Edit', 'msfc-wfm'); ?></a>
            <form class="d-inline" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                <?php wp_nonce_field( 'msfc_wfm_cat_dlt_non_220', 'msfc_wfm_cat_dlt_non_nonce' ); ?>
                <input type="hidden" name="id" value="<?php echo esc_attr($subcategory->term_id); ?>">
                <input type="hidden" name="action" value="msfc_wfm_dlt_product_cat_action">
                <button type="submit" class="woocommerce-button delete"><?php echo esc_html__('Delete', 'msfc-wfm'); ?></button>
            </form>
        </td>
    </tr>
    <?php
        $pre_dash .= "-";
        get_product_sub_categories($taxonomies, $subcategory->term_id, $pre_dash); //call recursively
        }
    }
}

?>
<a href="<?php echo esc_url(get_home_url() . '/msfc-product-category/add-product-category/'); ?>" class="msfc-wfm-button"><?php esc_html_e('Add New Category', 'msfc-wfm'); ?></a>
<table class="msfc-wfm-tbl msfc-wfm-product-list-table shop_table">
    <thead>
        <tr>
            <th width="210"><?php echo esc_html__('Name', 'msfc-wfm'); ?></th>
            <th><?php echo esc_html__('Description', 'msfc-wfm'); ?></th>
            <th width="210"><?php echo esc_html__('Slug', 'msfc-wfm'); ?></th>
            <th width="70"><?php echo esc_html__('Count', 'msfc-wfm'); ?></th>
            <th><?php echo esc_html__('Action', 'msfc-wfm'); ?></th>
        </tr>
        <tbody>
            
                <?php
                $taxonomies = get_terms( array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false
                ) );
                if ( !empty($taxonomies) ) :
                    foreach( $taxonomies as $category ) {
                        if( $category->parent == 0 ) {
                ?>
                    <tr>
                        <td><?php echo esc_html($category->name); ?></td>
                        <td><?php echo esc_html( wp_trim_words( $category->description, '9', '...' )); ?></td>
                        <td><?php echo esc_html($category->slug); ?></td>
                        <td><?php echo esc_html($category->count); ?></td>
                        <?php
                        if( $category->slug == 'uncategorized' ){
                        ?>
                        <td class="action-buttons">
                            <a href="<?php echo esc_url(get_home_url() . '/product-category/uncategorized/'); ?>" class="woocommerce-button view" target="_blank"><?php echo esc_html__('View', 'msfc-wfm'); ?></a>
                        </td>
                        <?php }else{ ?>
                        <td class="action-buttons">
                            <a href="<?php echo esc_url(get_home_url() . '/product-category/' . $category->slug . '/'); ?>" class="woocommerce-button view" target="_blank"><?php echo esc_html__('View', 'msfc-wfm'); ?></a>
                            <a href="<?php echo esc_url( get_home_url()."/msfc-product-category/edit-product-category/".$category->term_id."/" ); ?>" class="woocommerce-button edit"><?php echo esc_html__('Edit', 'msfc-wfm'); ?></a>
                            <form class="d-inline" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="POST">
                                <?php wp_nonce_field( 'msfc_wfm_cat_dlt_non_220', 'msfc_wfm_cat_dlt_non_nonce' ); ?>
                                <input type="hidden" name="id" value="<?php echo esc_attr($category->term_id); ?>">
                                <input type="hidden" name="action" value="msfc_wfm_dlt_product_cat_action">
                                <button type="submit" class="woocommerce-button delete"><?php echo esc_html__('Delete', 'msfc-wfm'); ?></button>
                            </form>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php
                        }
                    }
                endif;
                ?>
            
        </tbody>
    </thead>
</table>