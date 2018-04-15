jQuery(document).ready(function ($) {
    // initialize tooltip
    $( ".product a.ViExtend-tooltip" ).tooltip({
      track:true,
      items: "[data-tooltip]",
      content: function() {
        var element = $( this );
        if ( element.is( "[data-tooltip]" ) ) {
            return element.attr( "data-tooltip" );
        }
      },
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
            async: true,
            success: function( data ) {
                $("#"+id).tooltip('option','content', data);
            },
        });
    }
    });
    
    $(".product a.ViExtend-tooltips").mouseout(function(){
        // re-initializing tooltip
        $(this).addClass('tooltip');
        $(this).tooltip();
        $('.ui-tooltip').hide();
        $('.ui-helper-hidden-accessible').remove();
    });
});