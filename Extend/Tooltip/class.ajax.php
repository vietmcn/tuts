<?php 
if ( !defined( 'ABSPATH' ) ) :
    exit;
endif;
if ( !class_exists( 'ViExtend_Tooltip_ajax' ) ) {
    class ViExtend_Tooltip_ajax
    {
        protected $att = array();

        public function __construct( $att )
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'ViExtend_tootlip_scripts' ) );
            add_action( 'wp_ajax_ViExtend_tootlip', array( $this, 'ViExtend_tootlip' ) ); // wp_ajax_{action}
            add_action( 'wp_ajax_nopriv_ViExtend_tootlip', array( $this, 'ViExtend_tootlip' ) ); // wp_ajax_nopriv_{action}
            add_action( ( isset( $att['hook'] ) ) ? $att['hook'] : 'woocommerce_before_shop_loop_item', array( $this, 'before_shop_loop_item' ) );
            add_action( 'wp_head', array( $this, 'ViExtend_Style_print') );
        }
        public function ViExtend_tootlip_scripts() {
            wp_enqueue_script( 'Jquery_UI', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array( 'jquery' ), '1.12.1', true );
            wp_enqueue_script( 'ViExtend_Tooltip', get_template_directory_uri().'/tuts/Extend/Tooltip/tooltip.js', array( 'jquery' ), '0.1', true );
            wp_localize_script( 'ViExtend_Tooltip', 'ViExtend_tootlip', array(
                'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
                'check_nonce' => wp_create_nonce('app-nonce'),
            ) );
        }
        public function before_shop_loop_item()
        {
            global $post;
            echo '<a id="p_'.$post->ID.'" class="ViExtend-tooltip ViExtend-tooltips" href="'.get_permalink().'" data-tooltip="Đang tải..." title="'.get_the_title().'">';;
        }
        function get_price_stock( $att )
        {
            $price_regular = get_post_meta( $att['product_id'], '_regular_price', true );
            $price_sale = get_post_meta( $att['product_id'], '_sale_price', true );
            $sku = get_post_meta( $att['product_id'], '_sku', true );
            $price_saleClass = ( $price_sale ) ? 'price_s' : '';

            $out  = '<div class="price_all stock">';
            $out .= '<div class="product_sku">Mã sản phẩm: <span>'.$sku.'</span></div>';
            if ( $price_sale ) {
                $out .= '<div class="price_ '.$price_saleClass.'">Giá: <del>'.number_format( $price_regular ).'<strong>đ</strong></del></div>';
                $out .= '<div class="price_sales">Giá khuyến mãi: <ins class="price_sale">'.number_format( $price_sale ).'<strong>đ</strong></ins></div>';
            } else {
                $out .= '<div class="price_">Giá: <ins>'.number_format( $price_regular ).'<strong>đ</strong></ins></div>';
            }
            $out .= '</div>';
            return $out;
        }
        public function ViExtend_tootlip_item( $att )
        {
            $out  = '<aside id="ViEXtend_tootltip">';
            $out .= '<header>';
            $out .= '<h4>'.get_the_title( $att['product_id'] ).'</h4>';
            $out .= $this->get_price_stock( $att['product_id'] );
            $out .= '</header>';
            $out .= '<footer>';
            $out .= '</footer>';
            $out .= '</aside>';
            echo $out;
        }
        public function ViExtend_tootlip()
        {
            global $App_getcontent;
            check_ajax_referer( 'app-nonce', 'security' );
            header("Content-Type: text/html");
            // Get Tooltip Woocommerce
            $this->ViExtend_tootlip_item( array(
                'product_id' => $_GET['userid']
            ) );
            //Ngắt Vòng Lặp
            die;
        }
        public function ViExtend_Style_print()
        {
            ?>
            <style>
            #ViEXtend_tootltip h4 {
                background-color: red;
                color: #fff;
                padding: 10px;
                font-weight: normal;
                font-size: 16px;
                text-transform: uppercase;
            }
            .ui-tooltip {
                padding: 0px;
                border: 0px;
                width: 250px;
                background-color: #fff;
                font-size: 14px;
                border: 1px solid #ededed;
                position: absolute;
	            z-index: 9999;
            }
            .ui-helper-hidden {
                display: none;
            }
            .ui-helper-hidden-accessible {
                border: 0;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }
            .price_all {
                padding : 10px;
            }
            #ViEXtend_tootltip header {
                margin-bottom: 2rem;
                border-bottom: 1px solid rgba(220, 220, 220, 0.5215686274509804);
            }
            .price_all .product_sku {
                margin-bottom: 10px;
            }
            .price_all .price_sales {
                margin-top: 10px;
            }
            .price_all .price_sale, .price_all ins {
                font-size: 17px;
                font-weight: 700;
                color: red;
            }
            .price_all ins {
                background: none;
            } 
            .price_all ins strong {
                font-weight: 700;
            }
            .price_all strong {
                font-weight: normal;
            }
            .ViExtend-tooltip {
                display: block;
            }
            </style>
            <?php 
        }
    }
    
}

