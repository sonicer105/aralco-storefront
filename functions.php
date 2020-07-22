<?php
/**
 * Functions PHP Ver 1.3.3
 */

define('ARALCO_THEME_SLUG', 'storefront-aralco');

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
add_filter('gettext', 'aralco_override_string', 999, 3);
function aralco_override_string($translation, $text, $domain) {
    if ($domain == 'woocommerce') {
        if ($text == 'Please select some product options before adding this product to your cart.') {
            return 'Please select product options before adding this product to your cart.';
        }
        return str_ireplace(array('Category', 'Categories'), array('Department', 'Departments'), $text);
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

/**
 * Copied from Storefront and modified
 * @snippet       Overrides the default display post taxonomies
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
add_action('init', 'aralco_replace_action_post_taxonomy');
function aralco_replace_action_post_taxonomy(){
    remove_action('storefront_loop_post', 'storefront_post_taxonomy', 40);
    remove_action('storefront_single_post_bottom', 'storefront_post_taxonomy', 5);
    add_action('storefront_loop_post', 'aralco_post_taxonomy', 40);
    add_action('storefront_single_post_bottom', 'aralco_post_taxonomy', 5);
}
function aralco_post_taxonomy() {
    /* translators: used between list items, there is a space after the comma */
    $categories_list = get_the_category_list( __( ', ', 'storefront' ) );

    /* translators: used between list items, there is a space after the comma */
    $tags_list = get_the_tag_list( '', __( ', ', 'storefront' ) );
    ?>

    <aside class="entry-taxonomy">
        <?php if ( $categories_list ) : ?>
            <div class="cat-links">
                <?php echo esc_html( _n( 'Department:', 'Departments:', count( get_the_category() ), 'storefront' ) ); ?> <?php echo wp_kses_post( $categories_list ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $tags_list ) : ?>
            <div class="tags-links">
                <?php echo esc_html( _n( 'Tag:', 'Tags:', count( get_the_tags() ), 'storefront' ) ); ?> <?php echo wp_kses_post( $tags_list ); ?>
            </div>
        <?php endif; ?>
    </aside>

    <?php
}

/**
 * Copied from Storefront and modified
 * @snippet       Display a menu intended for use on handheld devices
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */

function storefront_handheld_footer_bar() {
    $links = array(
        'my-account'        => array(
            'priority' => 10,
            'callback' => 'storefront_handheld_footer_bar_account_link',
        ),
        'advanced-search'   => array(
            'priority' => 20,
            'callback' => 'storefront_handheld_footer_bar_search',
        ),
        'cart'              => array(
            'priority' => 30,
            'callback' => 'storefront_handheld_footer_bar_cart_link',
        ),
    );

    if (did_action('woocommerce_blocks_enqueue_cart_block_scripts_after') || did_action('woocommerce_blocks_enqueue_checkout_block_scripts_after')) {
        return;
    }

    if (wc_get_page_id('myaccount') === -1) {
        unset($links['my-account']);
    }

    if (wc_get_page_id('cart') === -1) {
        unset($links['cart']);
    }

    $links = apply_filters('storefront_handheld_footer_bar_links', $links);
    ?>
    <div class="storefront-handheld-footer-bar">
        <ul class="columns-<?php echo count($links); ?>">
            <?php foreach ($links as $key => $link): ?>
                <li class="<?php echo esc_attr($key); ?>">
                    <?php
                    if ($link['callback']) {
                        call_user_func($link['callback'], $key, $link);
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/**
 * Copied from Storefront and modified
 * @snippet       The search callback function for the handheld footer bar
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
function storefront_handheld_footer_bar_search() {
    echo '<a href="' . get_permalink(get_page_by_path('advanced-search')) . '">' . esc_attr__( 'Search', 'storefront' ) . '</a>';
//    storefront_product_search();
}

/**
 * @snippet       Allows to check if a page slug exists
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
function aralco_the_slug_exists($post_name) {
    global $wpdb;
    return boolval(
        $wpdb->get_row(
            "SELECT post_name FROM " . $wpdb->prefix . "posts WHERE post_name = '" . $post_name . "'",
            'ARRAY_A'
        )
    );
}

/**
 * @snippet       If the advanced search page slug exists, Adds a button to the search field to jump to advanced search page
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.2.0
 */
if(aralco_the_slug_exists('advanced-search')) {
    $the_ref = home_url('/advanced-search/');
    wc_enqueue_js(/** @lang JavaScript */
"$('.site-search .aws-search-form').after('<a href=\"{$the_ref}\">Advanced<br>Search</a>');
$('.site-search .aws-container').addClass('flex-for-button');"
    );
}

/**
 * @snippet       Redirect user to homepage after login as long as they are not logging in from the cart page
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.3.1
 */
add_filter('woocommerce_login_redirect', 'aralco_redirect_login', 100, 1);
function aralco_redirect_login($redirect) {

    $redirect_page_id = url_to_postid($redirect);
    $checkout_page_id = wc_get_page_id('checkout');

    if ($redirect_page_id == $checkout_page_id) {
        return $redirect;
    }

    return get_home_url();
}

/**
 * @snippet       Modify the way stock is displayed
 * @author        Elias Turner, Aralco
 * @testedwith    WooCommerce 4.3.1
 */
add_filter('woocommerce_get_availability_text', 'aralco_get_availability_text', 100, 2);
function aralco_get_availability_text($text, $product) {
    /* @var $product WC_Product */
    if (!$product->is_in_stock()) {
        return __('Not Available', ARALCO_THEME_SLUG);
    } else if ($product->managing_stock() && $product->is_on_backorder(1)) {
        return $product->backorders_require_notification()? __('Sold Out', ARALCO_THEME_SLUG) : '';
    } else if (!$product->managing_stock() && $product->is_on_backorder(1)) {
        return __('Sold Out', ARALCO_THEME_SLUG);
    } else if ($product->managing_stock()) {
        $display = __('Available', ARALCO_THEME_SLUG);
        $stock_amount = $product->get_stock_quantity();
        switch (get_option('woocommerce_stock_format')) {
            case 'low_amount':
//                if ($stock_amount <= get_option('woocommerce_notify_low_stock_amount')) {
//                    /* translators: %s: stock amount */
//                    $display = sprintf(__('Only %s left in stock', 'woocommerce'), wc_format_stock_quantity_for_display($stock_amount, $product));
//                }
//                break;
            case '':
                /* translators: %s: stock amount */
                $display = sprintf(__('%s available', ARALCO_THEME_SLUG), wc_format_stock_quantity_for_display($stock_amount, $product));
                break;
        }
//        if ($product->backorders_allowed() && $product->backorders_require_notification()) {
//            $display .= ' ' . __('(can be backordered)', 'woocommerce');
//        }
        return $display;
    }
    return $text;
}