<?php 

/*
Title: Formular
Post Type: smamo_form_entry
scope: post_meta
order: 2
priority: default
context: side
*/

$entry_id = (isset($_GET['post'])) ? esc_attr($_GET['post']) : false;
$form_id = ($entry_id) ? get_post_meta($entry_id, 'smamo_form', true) : false;

if (SMAMO_FORMS_TEST_MODE){
    
    piklist('field', array(
        'type' => 'text',
        'field' => 'smamo_form',
        'Label' => 'Form ID',
        'attributes' => array(
            'readonly' => 'readonly',
        ),
    ));
    
}

if($form_id){
    $form = get_post($form_id);
    
    piklist('field', array(
        'type' => 'text',
        'field' => 'smamo_form_name',
        'Label' => 'Formular',
        'value' => $form->post_title,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
    )); 
    
}