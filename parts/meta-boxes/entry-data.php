<?php

/*
Title: Indtastet Data
Post Type: smamo_form_entry
scope: post_meta
order: 1
priority: default
*/

$post_id = esc_attr($_GET['post']);
if($post_id) :

$form_id = get_post_meta($post_id,'form_id', true);
if($form_id) :

$fields = get_post_meta($form_id,'form_fields', true);
if ($fields && is_array($fields) && !empty($fields[0])) :

foreach($fields as $field){

    if (!isset($field['field_type'])
    || !isset($field['field_label'])
    || !isset($field['field_name'])
    || $field['field_type'] === 'info' ) { continue; }

    $label = $field['field_label'];
    if(!$label || $label == '') { $label = $field['field_name'];}

    $value = get_post_meta($post_id,$field['field_name'], true);


    if ($field['field_type'] === 'textarea') :

    piklist('field', array(
        'columns' => 8,
        'type' => 'textarea',
        'label' => $label,
        'value' => $value,
        'attributes' => array(
            'readonly' => 'readonly',
            'rows' => 10,
        ),
    ));

    else :

    piklist('field', array(
        'columns' => 8,
        'type' => 'text',
        'label' => $label,
        'value' => $value,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
    ));

    endif;
}


endif; endif; endif;
