<?php
/**
 * Functions PHP Ver 1.2.1
 */


/**
 * @snippet       Removes the Core Storefront Styles from running.
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.1.1
 * NOTE: Not currently in use because still want the core Storefront Styles
 */
//add_action('wp_enqueue_scripts', 'aralco_remove_css', 999);
function aralco_remove_css() {
    wp_dequeue_style('storefront-woocommerce-style');
    wp_dequeue_style('storefront-style');
}


/**
 * @snippet       Remove Sidebar @ Single Product Page
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=19572
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 4.2.0
 */
add_action('wp', 'aralco_remove_sidebar_product_pages');
function aralco_remove_sidebar_product_pages() {
    if (is_product()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}


/**
 * @snippet       Remove Sidebar @ Single Product Page for Storefront Theme
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=19572
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 4.2.0
 */
add_action('get_header', 'aralco_remove_storefront_sidebar');
function aralco_remove_storefront_sidebar() {
    if (is_product()) {
        remove_action('storefront_sidebar', 'storefront_get_sidebar', 10);
    }
}


/**
 * @snippet       Adds Powered By Aralco Footer
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
add_action('storefront_credit_links_output', 'aralco_do_footer', 25);
function aralco_do_footer($content) {
    $text = 'Powered by Aralco';
    $title = 'Aralco Inventory Management & POS Systems Software';
    return substr($content, 0, -1) .
        '<span role="separator" aria-hidden="true"></span>' .
        '<a href="https://aralco.com/" target="_blank" rel="noopener nofollow" title="' . $title . '">' .
        $text .
        '</a>.';
}

/**
 * @snippet       Override string translation
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
add_filter('gettext', 'aralco_override_string', 10, 3);
function aralco_override_string($translation, $text, $domain) {
    if ($domain == 'woocommerce') {
        if ($text == 'Please select some product options before adding this product to your cart.') {
            $translation = 'Please select product options before adding this product to your cart.';
        }
    }

    return $translation;
}

/**
 * @snippet       Adds a alternative secondary nav
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
register_nav_menus(
    apply_filters(
        'storefront_aralco_register_nav_menu', array(
            'secondary-alt'  => __( 'Secondary Alt Menu', 'storefront_aralco' ),
        )
    )
);
add_filter('storefront_header', 'aralco_second_nav', 70);
function aralco_second_nav() {
    if(!has_nav_menu('secondary-alt')) return; // Nothing to do
    ?><div class="secondary-nav"><div class="col-full">
        <a href="#" class="nav-shift move-left" aria-hidden="true"><span class="dashicons dashicons-arrow-left-alt2"></span></a>
        <?php
        wp_nav_menu(
            array(
                'theme_location'  => 'secondary-alt'
            )
        );
        ?>
        <a href="#" class="nav-shift move-right" aria-hidden="true"><span class="dashicons dashicons-arrow-right-alt2"></span></a>
    </div></div><script>
        jQuery('.nav-shift').on('click', function (e) {
            console.log('hit');
            e.preventDefault();
            let target = (jQuery(this).hasClass('move-right'))? 1000 : 0;
            jQuery('#menu-secondary')[0].scrollTo({left: target, behavior: 'smooth'})
        })
    </script><?php
}

/**
 * @snippet       Adds our custom CSS
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
add_action('wp_enqueue_scripts', 'aralco_add_css', 999);
function aralco_add_css($content) {
    wp_enqueue_style('dashicons');
//    global $aralco_ver;
//    wp_enqueue_style('aralco_storefront', get_stylesheet_directory_uri() . '/style.css', array(), $aralco_ver);
}