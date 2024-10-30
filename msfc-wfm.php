<?php
/**
 * Plugin Name:       MSFC Frontend Manager for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/msfc-wfm
 * Description:       This plugin enable frontend store management system for woocommerce simple type product.
 * Version:           1.0.0
 * Author:            UnderDogs Dev
 * Author URI:        https://underdogs.dev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       msfc-wfm
 * Domain Path:       /languages
 * WC requires at least: 4.8.0
 * WC tested up to: 6.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'MSFC_WFM_VERSION', '1.0.0' );


/**
* Load plugin textdomain.
*/
function msfc_wfm_load_textdomain() {
   load_plugin_textdomain( 'msfc-wfm', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'msfc_wfm_load_textdomain' );


/***
* Plugin Directory
**/
define( 'MSFC_WFM_DIR',  plugin_dir_path( __FILE__ ) );
define( 'MSFC_WFM_ADMIN_ASSETS',  plugin_dir_url( __FILE__ ) . "admin/" );
define( 'MSFC_WFM_FRONT_ASSETS',  plugin_dir_url( __FILE__ ) . "public/");


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-msfc-wfm-activator.php
 */
function activate_msfc_wfm() {
	require_once MSFC_WFM_DIR . 'includes/msfc-wfm-activator.php';
}
register_activation_hook( __FILE__, 'activate_msfc_wfm' );


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-msfc-wfm-deactivator.php
 */
function deactivate_msfc_wfm() {
	require_once MSFC_WFM_DIR . 'includes/msfc-wfm-deactivator.php';
}
register_deactivation_hook( __FILE__, 'deactivate_msfc_wfm' );


/****
* Include Admin & Public Assets
***/
require MSFC_WFM_DIR . 'admin/msfc-wfm-admin.php';
require MSFC_WFM_DIR . 'public/msfc-wfm-public.php';


/**
 * Plugin Pages Shortcodes
 * **/
require MSFC_WFM_DIR . 'includes/msfc-wfm-pages/msfc-wfm-pages.php';


/**
 * Add Product Manager Menu on woocommerce my-account page
 * ***/
add_action('woocommerce_before_account_navigation', 'msfc_wfm_add_product_manager_navigation');
function msfc_wfm_add_product_manager_navigation(){
	if ( is_user_logged_in() && ( current_user_can('shop_manager') || current_user_can('administrator') ) ) {
		?>
		<a href="<?php echo esc_url(get_home_url().'/msfc-products/'); ?>" class="msfc-wfm-button ml-4"><?php esc_html_e('Manage Product', 'msfc-wfm'); ?></a>
		<?php
	}
}



/**
 * Add rewrite rules for plugin few pages
 */
add_action('init', function(){
	//Order Details Page
	add_rewrite_rule( 
	   '^msfc-order-details/([^/]+)([/]?)(.*)',
	   'index.php?pagename=msfc-order-details&order-id=$matches[1]', 
	   'top'
	);
	
	//Product Edit Page
	add_rewrite_rule( 
		'^msfc-edit-product/([^/]+)([/]?)(.*)',
		'index.php?pagename=msfc-edit-product&product-id=$matches[1]', 
		'top'
	);

	//Product Details Page
	add_rewrite_rule( 
		'^msfc-product-details/([^/]+)([/]?)(.*)',
		'index.php?pagename=msfc-product-details&view-product-id=$matches[1]', 
		'top'
	);
	
	//Product Category Edit Page
	//siteurl.com/msfc-product-category/edit-product-category/43 for edit product category
	add_rewrite_rule(
		'^msfc-product-category/([^/]*)/([^/]*)/?',
		'index.php?pagename=msfc-product-category&pro-category-action=$matches[1]&product-cat-id=$matches[2]',
		'top'
	);

	//Product Category Add Page
	//siteurl.com/msfc-product-category/add-product-category for add product category
    add_rewrite_rule(
		'^msfc-product-category/([^/]*)/?',
		'index.php?pagename=msfc-product-category&pro-category-action=$matches[1]',
		'top'
	);

 });

 /**
 * Filters the query variables allowed before processing
 */
add_filter('query_vars', function( $vars ){
    $vars[] = 'pagename'; 
	$vars[] = 'order-id';
	$vars[] = 'product-id';
	$vars[] = 'view-product-id';

	//Product category add/edit
	$vars[] = 'pro-category-action';
	$vars[] = 'product-cat-id';
	$vars[] = 'category-action';

    return $vars;
});


/**
 * Plugin Dependency
 */
add_action( 'admin_init', 'msfc_wfm_woocommerce_dependent_plugin' );
function msfc_wfm_woocommerce_dependent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        add_action( 'admin_notices', 'msfc_wfm_plugin_activation_error_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function msfc_wfm_plugin_activation_error_notice(){
    ?>
	<div class="notice notice-error">
        <p><?php _e( 'MSFC Frontend Manager for WooCommerce Plugin Requires <a href="'.esc_url('https://wordpress.org/plugins/woocommerce/').'" target="_blank">WooCommerce</a> Plugin.', 'msfc-wfm' ); ?></p>
    </div>
	<?php
}