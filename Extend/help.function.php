<?php 
if ( !defined( 'ABSPATH' ) ) :
    exit;
endif;
require_once( 'Tooltip/class.ajax.php' );
new ViExtend_Tooltip_ajax;
//get hook
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open' );