<?php function smamo_forms_run_excel_export($post_ids = false, $form_id = false, $redirect_to = null){

  if (!$form_id){ return add_query_arg( 'excel_export', 'missing_form', $redirect_to ); }

  $fields = get_post_meta( $form_id, $key = 'form_fields', $single = true );

  $entry_args = array(
    'post_type' => 'smamo_form_entry',
    'posts_per_page' => -1,
    'meta_key' => 'smamo_form',
    'meta_value' => $form_id,
  );

  if($post_ids){
    $entry_args['include'] = $post_ids;
  }

  $entries = get_posts($entry_args);

  /*-------------------------------------------------------*/
  // LET'S MAKE A FILE


  /** Error reporting */
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
  date_default_timezone_set('Europe/Copenhagen');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  require_once SMAMO_FORMS_DIR . '/inc/phpexcel/Classes/PHPExcel.php';
  $column_array = array( 0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O', 15 => 'P', 16 => 'Q' );

  // Opret nyt excelark
  $xls = new PHPExcel();
  $sheet = $xls->getActiveSheet();

  $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

  $titleTimeFormat = date_i18n('d-m-Y-H-i');

  $xls->getProperties()->setCreator("Matrix Support")
                              ->setLastModifiedBy("Matrix Support")
                              ->setTitle("export-".$titleTimeFormat)
                              ->setSubject("Export oprettet ".$titleTimeFormat)
                              ->setDescription("Export oprettet ".$titleTimeFormat)
                              ->setKeywords("")
                              ->setCategory("");

  // Table Headings
  $sheet->setCellValue('A1', 'ID');
  $sheet->setCellValue('B1', 'Dato');

  $i = 2;

  foreach($fields as $field){

    if (!isset($field['field_type'])
    || !isset($field['field_label']) || $field['field_label'] == ''
    || !isset($field['field_name']) || $field['field_name'] == ''
    || $field['field_type'] === 'info' ) { continue; }

    $sheet->setCellValue($column_array[$i] . '1', $field['field_label']);

    $i ++;
  }

  // Table rows
  $i = 1;
  foreach ($entries as $entry) {
    $i ++;
    $sheet->setCellValue('A'. $i, $entry->ID);
    $sheet->setCellValue('B'. $i, $entry->post_title);

    // Table columns
    $ii = 2;
    foreach($fields as $field){

      if (!isset($field['field_type'])
      || !isset($field['field_label']) || $field['field_label'] == ''
      || !isset($field['field_name']) || $field['field_name'] == ''
      || $field['field_type'] === 'info' ) { continue; }

      $value = get_post_meta($entry->ID,$field['field_name'], true);

      $sheet->setCellValue($column_array[$ii]. $i, $value);

      $ii++;
    }
  }

  // Indstil navn
  $sheet->setTitle('export-'.$titleTimeFormat);
  $xls->setActiveSheetIndex(0);

  // Gem Excel 95 fil
  $callStartTime = microtime(true);

  $uploads_dir = wp_upload_dir();
  $save_file_to = $uploads_dir['path'] . '/xport-'.$titleTimeFormat.'.xls';

  $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
  $objWriter->save($save_file_to);
  $callEndTime = microtime(true);
  $callTime = $callEndTime - $callStartTime;

  // return
  return $uploads_dir['url'] . '/xport-'.$titleTimeFormat.'.xls';
}
