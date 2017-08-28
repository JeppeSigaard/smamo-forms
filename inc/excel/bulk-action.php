<?php
add_filter( 'bulk_actions-edit-smamo_form_entry', 'register_my_bulk_actions' );
function register_my_bulk_actions($bulk_actions) {
  $bulk_actions['export_excel'] = __( 'Eksporter til excel', 'export_excel');
  return $bulk_actions;
}

add_action( 'admin_notices', 'smamo_forms_excel_export_admin_notice' );
function smamo_forms_excel_export_admin_notice(){
  if ( ! empty( $_REQUEST['excel_export'] ) ) {
    $excel_export = intval( $_REQUEST['excel_export'] );
    $message = '';
    $classes = '';

    if($excel_export == 'missing_vars'){
      $classes = 'error fade';
      $message = '<p>Eksport fra liste kan kun bruges for én formular af gangen. Start med at klikke på en formulars navn i listen.</p>';
    }

    if($excel_export == 'missing_form'){
      $classes = 'error fade';
      $message = '<p>Kunne ikke finde formularen</p>';
    }

    echo '<div id="message" class="'.$classes.'">'. $message.'</div>';
  }
}

add_filter( 'handle_bulk_actions-edit-smamo_form_entry', 'smamo_forms_excel_export', 10, 3 );
function smamo_forms_excel_export( $redirect_to, $doaction, $post_ids ) {
  if ( $doaction !== 'export_excel' ) { return $redirect_to; }

  // get _wp_http_referer if exists
  $get = (isset($_GET['_wp_http_referer'])) ? parse_url($_GET['_wp_http_referer']) : false;
  if (!$get) { return $redirect_to; }

  // get sanitized query_string if exists
  $query_string = (isset($get['query'])) ? $get['query'] : false;
  if (!$query_string) { return $redirect_to; }

  // parse query string
  $query_array = array();
  $parsed_query = parse_str($query_string, $query_array);



  if (!isset($query_array['meta_key'])
  || $query_array['meta_key'] !== 'smamo_form'
  || !isset($query_array['meta_value'])){
      return add_query_arg( 'excel_export', 'missing_vars', $redirect_to );
  }

  return smamo_forms_run_excel_export($post_ids, esc_attr($query_array['meta_value']), $redirect_to);
}
