<?php 
if ( !defined( 'ABSPATH' ) ) :
    exit;
endif;
if ( !class_exists( 'ViExtend_Tooltip_ajax' ) ) {
    class ViExtend_Tooltip_ajax
    {
        protected $att = array();

        public function __construct()
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'ViExtend_tootlip_scripts' ) );
            add_action( 'wp_ajax_ViExtend_tootlip', array( $this, 'ViExtend_tootlip' ) ); // wp_ajax_{action}
            add_action( 'wp_ajax_nopriv_ViExtend_tootlip', array( $this, 'ViExtend_tootlip' ) ); // wp_ajax_nopriv_{action}
            add_action( 'wp_footer', array( $this, 'print_script') ); // wp_ajax_nopriv_{action}
            add_action( 'woocommerce_before_shop_loop_item', array( $this, 'before_shop_loop_item' ) );
        }
        public function ViExtend_tootlip_scripts() {
            wp_enqueue_style( 'Jquery_UI_CSS', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', '1.12.1', 'all' );
            wp_enqueue_script( 'ViExtend_Tooltip', plugins_url( '/love.js', __FILE__ ), array('jquery'), '1.0', true );
            wp_enqueue_script( 'Jquery_UI', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array( 'jquery' ), '1.12.1', true );
            $Query = new WP_Query( array(
                'post_type' => 'product',
            ) );
            if ( $Query ) {
                $id = [];
                while ( $Query->have_posts() ) : $Query->the_post();
                    $id[] .= 'id:'.$Query->post->ID;
                endwhile;
                wp_localize_script( 'ViExtend_Tooltip', 'ViExtend_tootlip', array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'check_nonce' => wp_create_nonce('app-nonce'),
                ) );
            } else {
                echo 'Oop! Lổi Rồi';
            }
        }
        public function before_shop_loop_item()
        {
            global $post;
            echo '<a id="p_'.$post->ID.'" href="'.get_permalink().'">';;
        }
        public function ViExtend_tootlip_item( $att )
        {
            $out  = '<div id="ViEXtend_tootltip">';
            $out .= '<h4>'.get_the_title().'</h4>';
            $out .= get_post_meta( $att['product_id'], '_regular_price', true );
            $out .= '</div>';
            echo $att['product_id'];
        }
        public function ViExtend_tootlip()
        {
            global $App_getcontent;
            check_ajax_referer( 'app-nonce', 'security' );
            header("Content-Type: text/html");
            #$args = json_decode( stripslashes( $_GET['userid'] ), true );
            // Get Tooltip Woocommerce
            $this->ViExtend_tootlip_item( array(
                'product_id' => $_GET['userid']
            ) );
            //Ngắt Vòng Lặp
            die;
        }
        public function print_script()
        {
            ?>
            <script>
            jQuery(document).ready(function ($) {
            $(document).ready(function(){

// initialize tooltip
$( ".product a" ).tooltip({
  track:true,
  open: function( event, ui ) {
  var id = this.id;
  var split_id = id.split('_');
  var userid = split_id[1];
  var data = {
			'action': 'ViExtend_tootlip',
            'security' : ViExtend_tootlip.check_nonce,
            'userid': userid
	};
  $.ajax({
   url: ViExtend_tootlip.ajaxurl,
   type: 'GET',
   data: data,
   cache : false,
   async: true,
   success: function( response ){
    $('.product').html( response );
  },
  complete: function( response ) {
        // $('.product').hide(); // This is called when the success part is still executing
  }
 });
 }
});

$(".product a").mouseout(function(){
  // re-initializing tooltip
  $(this).attr('title','Please wait...');
  $(this).tooltip();
  $('.ui-tooltip').hide();
});

});
});
</script>
            <?php
        }
    }
    
}

