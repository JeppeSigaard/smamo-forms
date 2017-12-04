<?php


/*
Title: Betinget logik
Post Type: smamo_form
scope: post_meta
order: 2
priority: high
Flow: formFlow
Tab: Felter
*/

$post_id = (isset($_GET['post'])) ? esc_attr($_GET['post']) : false;
if($post_id) :

$fields = get_post_meta($post_id,'form_fields', true);
if ($fields && is_array($fields) && !empty($fields[0])) :

$field_select = array(0 => 'felt');
foreach($fields as $field){
    if (!isset($field['field_label']) || !isset($field['field_name'])) continue;

    $field_select[$field['field_name']] = $field['field_label'];
}

piklist('field',array(
    'type' => 'checkbox',
    'field' => 'smamo_form_logic_active',
    'label' => 'Aktiver betinget logik',
    'columns' => 12,
    'choices' => array(
        'checked' => '',
    ),
));

piklist('field',array(
    'type' => 'group',
    'field' => 'smamo_form_logic',
    'add_more' => true,
    'template' => 'field',
    'columns' => 12,
    'conditions' => array(
        array(
            'field' => 'smamo_form_logic_active',
            'value' => 'checked',
        ),
    ),

    'fields' => array(

        array(
            'type' => 'select',
            'field' => 'action',
            'columns' => 1,
            'choices' => array(
                'show' => 'Vis',
                'hide' => 'Skjul',
            ),
        ),

        array(
            'type' => 'select',
            'field' => 'target',
            'columns' => 3,
            'choices' => $field_select,
        ),

        array(
            'type' => 'text',
            'field' => 'if',
            'attributes' => array('disabled' => 'disabled', 'placeholder' => 'Hvis'),
            'columns' => 1,
        ),

        array(
            'type' => 'select',
            'field' => 'actor',
            'columns' => 3,
            'choices' => $field_select,
        ),

        array(
            'type' => 'select',
            'field' => 'compare',
            'columns' => 1,
            'choices' => array(
                '=' => '=',
                '!=' => '!=',
                '<' => '<',
                '>' => '>',
            ),
        ),

        array(
            'columns' => 3,
            'type' => 'text',
            'field' => 'value',
        ),
    ),
));


// Or  no
else :
piklist('field',array(
    'type' => 'html',
    'value' => '<p>Start med at oprette felter i formularen.</p>',
));
endif; // have fields?
endif; // have id?

unset($fields);
unset($field_select);
