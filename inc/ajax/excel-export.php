<?php

add_action('wp_ajax_smamo_form_excel_export', 'smamo_form_ajax_exel_export');
function smamo_form_ajax_exel_export(){
  $response = array();
  $form_id = (isset($_POST['form_id'])) ? $_POST['form_id'] : false;
  if (!$form_id){
    $response['error'] = 'no form id set.';
    wp_die(json_encode($response));}

  $response['redirect'] = smamo_forms_run_excel_export(false, $form_id, null);

  wp_die(json_encode($response));
}
