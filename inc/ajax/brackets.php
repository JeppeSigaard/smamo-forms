<?php

function smamo_replace_brackets($str, $form_id, $entry_id){

    $fields = get_post_meta($form_id, 'form_fields', true);

    $replace = array();

    foreach($fields as $field){

        if (!isset($field['field_type'])
        || !isset($field['field_label'])
        || !isset($field['field_name'])
        || $field['field_type'] === 'info' ) { continue; }

        $replace['{'.$field['field_name'].'}'] = get_post_meta($entry_id, $field['field_name'], true);
    }

    foreach($replace as $k => $v){
       $str = str_replace($k,$v,$str);
    }

    return $str;
}
