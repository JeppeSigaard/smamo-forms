<?php 


/*
Title: Formularfelter
Post Type: smamo_form
scope: post_meta
order: 1
priority: high
Flow: formFlow
Tab: Felter
*/

piklist('field', array(
    'type' => 'group',
    'columns' => 12,
    'label' => 'felt',
    'field' => 'form_fields',
    'template' => 'field',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'select', 
            'choices' => array(
                'null' => '(Vælg felttype)',
                'text' => 'Tekstfelt',
                'email' => 'E-mail',
                'tel' => 'Telefonnummer',
                'pass' => 'Kodeord',
                'hidden' => 'Skjult felt',
                'textarea' => 'Tekstboks',
                'checkbox' => 'Checkboxe',
                'select' => 'Listevalg',
                
            ),
            'value' => 'null',
            'label' => 'Felttype',
            'field' => 'field_type',
            'required' => true,
            'columns' => 3,
            'help' => 'Felttypen afgør hvilken slags felt der vises i formularen.',
        ),

        array(
            'type' => 'text', 
            'label' => 'Feltnavn',
            'field' => 'field_name',
            'help' => 'Feltets navn bruges adminstrativt til opsætning af autosvar, visning af indtastninger med mere. Feltet skal udfyldes og må ikke indeholde specialtegn, æ, ø, å eller mellemrum.',
            'columns' => 3,
            'required' => true,
            'conditions' => array(
                array(
                    'field' => 'form_fields:field_type',
                    'value' => array('null'),
                    'compare' => '!=',
                )
            ),
            
            'sanitize' => array(
                array(
                    'type' => 'html_class'
                )
            ),
        ),

        array(
            'type' => 'text', 
            'label' => 'Label',
            'field' => 'field_label',
            'columns' => 3,
            'conditions' => array(
                array(
                    'field' => 'form_fields:field_type',
                    'value' => array('null','hidden'),
                    'compare' => '!=',
                )
            ),
        ),
        
        array(
            'type' => 'text', 
            'label' => 'Værdi',
            'field' => 'field_value',
            'help' => 'Skjulte felter vil typisk være udfyldt på forhånd.',
            'columns' => 3,
            'conditions' => array(
                array(
                    'field' => 'form_fields:field_type',
                    'value' => 'hidden',
                )
            ),
        ),

        array(
            'type' => 'select', 
            'label' => 'Skal udfyldes',
            'field' => 'field_required',
            'columns' => 2,
            'choices' => array(
                'false' => 'Nej',
                'true' => 'Ja',
            ),
            'conditions' => array(
                array(
                    'field' => 'form_fields:field_type',
                    'value' => array('null','hidden'),
                    'compare' => '!=',
                )
            ),
        ),
        
        array(
            'type' => 'html',
            'value' => '',
        ),

        array(
            'type' => 'group',
            'add_more' => true,
            'field' => 'field_opts',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => 'form_fields:field_type',
                    'value' => array('select','checkbox'),
                )
            ),

            'fields' => array(
                array(
                    'type' => 'text',
                    'field' => 'opt_label',
                    'label' => 'Label',
                    'help' => 'Opret en eller flere valgmuligheder',
                    'columns' => 4,
                ),

                array(
                    'type' => 'text',
                    'field' => 'opt_value',
                    'label' => 'Værdi',
                    'columns' => 4,
                ),

                array(
                    'type' => 'checkbox',
                    'field' => 'opt_checked',
                    'label' => 'valgt',
                    'columns' => 4,
                    'choices' => array(
                        'checked' => '',
                    ),
                ),
            ),
        ),
    ),
));