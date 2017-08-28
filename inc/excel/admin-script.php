<?php

add_action('admin_enqueue_scripts', 'smamo_forms_excel_export_button_onclick');
function smamo_forms_excel_export_button_onclick(){

  $ajax_url = admin_url('admin-ajax.php');

  ob_start(); ?>
  <script>
    function smamo_form_export_excel(form_id){
      jQuery.post( "<?php echo $ajax_url ?>", {
        form_id : form_id,
        action : 'smamo_form_excel_export'
      }, function( data ) {
        console.log(data);
        var res = JSON.parse(data);
        if(res.redirect != null){
          window.open(res.redirect);
        }
      });
    }
  </script>
  <?php echo ob_get_clean();
}
