<?php
/**
 * Functions PHP Ver 1.0.1
 */


/**
 * @snippet       Removes the Core Storefront Styles from running.
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.1.0
 * NOTE: Note currently in use because still want the core Storefront Styles
 */
//add_action( 'wp_enqueue_scripts', 'aralco_remove_css', 999 );
function aralco_remove_css() {
    wp_dequeue_style( 'storefront-woocommerce-style' );
    wp_dequeue_style( 'storefront-style' );
}


/**
 * @snippet       Remove Sidebar @ Single Product Page
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=19572
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 4.1.0
 */
add_action( 'wp', 'aralco_remove_sidebar_product_pages' );
function aralco_remove_sidebar_product_pages() {
    if ( is_product() ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    }
}


/**
 * @snippet       Remove Sidebar @ Single Product Page for Storefront Theme
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=19572
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 4.1.0
 */
add_action( 'get_header', 'aralco_remove_storefront_sidebar' );
function aralco_remove_storefront_sidebar() {
    if ( is_product() ) {
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    }
}


/**
 * @snippet       Adds Powered By Aralco Footer
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.1.0
 */
add_action( 'storefront_credit_links_output', 'aralco_do_footer', 25);
function aralco_do_footer($content) {
    $text = 'Powered by Aralco';
    $title = 'Aralco Inventory Management & POS Systems Software';
    return substr($content, 0, -1) .
        '<span role="separator" aria-hidden="true"></span>' .
        '<a href="https://aralco.com/" target="_blank" rel="noopener nofollow" title="' . $title . '">' .
        $text .
        '</a>.';
}


///**
// * @snippet       Adds our custom CSS
// * @author        Elias Turner, Aralco
// * @testedwith    WooCommerce 4.1.0
// */
//add_action( 'wp_enqueue_scripts', 'aralco_add_css', 999 );
//function aralco_add_css($content) {
//    global $aralco_ver;
//    wp_enqueue_style('aralco_storefront', get_stylesheet_directory_uri() . '/style.css', array(), $aralco_ver);
//}