<?php

/*
Title: Handlinger
Post Type: smamo_form
scope: post_meta
order: 1
priority: high
Flow: formFlow
Tab: Handlinger
*/


// Get pages for page choices
$page_choices = array(
    'null' => '(Vælg side)',
);

$pages = get_posts(array('post_type' => 'page', 'posts_per_page' => -1));
foreach ($pages as $p){
    $page_choices[get_permalink($p->ID)] = $p->post_title;
}

$page_choices['custom'] = 'Indtast speciel';

// Get form fields
$form_id = (isset($_GET['post'])) ? esc_attr($_GET['post']) : false ;
$field_array = array('null' => '(Vælg felt)');
$field_placeholders = '';
if($form_id){
    $form_fields = get_post_meta($form_id, 'smamo_form_fields', true);

    foreach($form_fields as $field){
        if (!isset($field['field_type'])
        || !isset($field['field_label'])
        || !isset($field['field_name'])
        || $field['field_type'] === 'info' ) { continue; }

        $label = ($field['field_label'] !== '') ? $field['field_label'] : $field['field_name'];
        $field_array[$field['field_name']] = $label;

        $field_placeholders .= ' {'.$field['field_name'].'} ';
    }
}

piklist('field', array(
    'type' => 'group',
    'field' => 'smamo_form_actions',
    'template' => 'field',
    'add_more' => 'true',
    'columns' => 12,
    'fields' => array(

        array(
            'type' => 'select',
            'field' => 'action_type',
            'label' => 'Handling',
            'help' => 'Handlinger udføres, når en ny indtastning på formularen foretages.',
            'choices' => array(
                'null' => '(Indstil en handling)',
                'send_email' => 'Send e-mail',
                'redirect' => 'Naviger til en side',
                'return_success' => 'Returner en besked',
                //'mailchimp_subscribe' => 'Tilføj til en mailchimp liste',
                'ubivox_subscribe' => 'Tilføj til en Ubivox liste',
            ),
        ),

        array(
            'type' => 'html',
            'value' => '',
        ),

        // From name
        array(
            'type' => 'group',
            'field' => 'from_name',

            'fields' => array(
                array(
                    'columns' => 4,
                    'type' => 'select',
                    'label' => 'Afsender navn',
                    'field' => 'field',
                    'choices' => array_merge($field_array, array('custom' => 'Indtast speciel')),
                ),

                array(
                    'columns' => 4,
                    'type' => 'text',
                    'label' => 'Indtast navn',
                    'field' => 'custom',

                    'conditions' => array(
                        array(
                            'field' => 'smamo_form_actions:from_name:field',
                            'value' => 'custom',
                        ),
                    ),
                ),
            ),

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'send_email',
                ),
            ),
        ),

         array(
            'type' => 'group',
            'field' => 'from_email',

            'fields' => array(
                array(
                    'columns' => 4,
                    'type' => 'select',
                    'label' => 'Afsender e-mail',
                    'field' => 'field',
                    'choices' => array_merge($field_array, array('custom' => 'Indtast speciel')),
                ),

                array(
                    'columns' => 4,
                    'type' => 'text',
                    'label' => 'Indtast e-mail',
                    'field' => 'custom',

                    'conditions' => array(
                        array(
                            'field' => 'smamo_form_actions:from_email:field',
                            'value' => 'custom',
                        ),
                    ),
                ),
            ),

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'send_email',
                ),
            ),
        ),

        array(
            'type' => 'group',
            'field' => 'to_email',

            'fields' => array(
                array(
                    'columns' => 4,
                    'type' => 'select',
                    'label' => 'Modtager e-mail',
                    'field' => 'field',
                    'choices' => array_merge($field_array, array('custom' => 'Indtast speciel')),
                ),

                array(
                    'columns' => 4,
                    'type' => 'text',
                    'label' => 'Indtast e-mail',
                    'field' => 'custom',

                    'conditions' => array(
                        array(
                            'field' => 'smamo_form_actions:to_email:field',
                            'value' => 'custom',
                        ),
                    ),
                ),
            ),

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'send_email',
                ),
            ),
        ),

        // ubivox
        array(
            'type' => 'text',
            'columns' => 3,
            'field' => 'uvx_user',
            'label' => 'Bruger',
            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'ubivox_subscribe',
                ),
            ),
        ),

        array(
            'type' => 'text',
            'columns' => 3,
            'field' => 'uvx_pass',
            'label' => 'Adgangskode',
            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'ubivox_subscribe',
                ),
            ),
        ),

        array(
            'type' => 'text',
            'columns' => 3,
            'field' => 'uvx_list',
            'label' => 'Liste',
            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'ubivox_subscribe',
                ),
            ),
        ),

        array(
            'type' => 'select',
            'columns' => 3,
            'field' => 'uvx_email',
            'label' => 'Email felt',
            'choices' => $field_array,
            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'ubivox_subscribe',
                ),
            ),
        ),

        array(
            'type' => 'group',
            'field' => 'uvx_merge_tags',
            'add_more' => true,

            'fields' => array(

                array(
                    'columns' => 4,
                    'type' => 'text',
                    'label' => 'Ubivox tag',
                    'field' => 'uvx_tag',
                    'help' => 'Eksporter formulardata til de korresponderende felter i Ubivox. Kun felter, der allerede er oprettet i formularen kan tilføjes. Hvis et felt mangler, start da med at oprettet det under fanen "Felter".',
                ),

                array(
                    'columns' => 4,
                    'type' => 'select',
                    'label' => 'Formularfelt',
                    'field' => 'entry_field',
                    'choices' => $field_array,
                ),

            ),

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'ubivox_subscribe',
                ),
            ),


        ),

        //mailchimp
        /*
        array(
            'type' => 'text',
            'field' => 'MC_key',
            'label' => 'API nøgle',
            'help' => 'API nøgle og liste ID kan findes i mailchimp',
            'columns' => 4,

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'mailchimp_subscribe',
                ),
            ),
        ),

        array(
            'type' => 'text',
            'field' => 'MC_list',
            'label' => 'Liste ID',
            'columns' => 4,

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'mailchimp_subscribe',
                ),
            ),
        ),

        array(
            'type' => 'group',
            'field' => 'MC_merge_tags',
            'add_more' => true,

            'fields' => array(

                array(
                    'columns' => 4,
                    'type' => 'text',
                    'label' => 'Mailchimp tag',
                    'field' => 'MC_tag',
                    'help' => 'Eksporter formulardata til de korresponderende felter i Mailchimp. Kun felter, der allerede er oprettet i formularen kan tilføjes. Hvis et felt mangler, start da med at oprettet det under fanen "Felter".',
                ),

                array(
                    'columns' => 4,
                    'type' => 'select',
                    'label' => 'Formularfelt',
                    'field' => 'entry_field',
                    'choices' => $field_array,
                ),

            ),

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'mailchimp_subscribe',
                ),
            ),


        ),
        */
        // redirect
        array(
            'type' => 'select',
            'field' => 'redirect_page',
            'label' => 'Eksisterende side',
            'columns' => 4,
            'choices' => $page_choices,

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'redirect',
                ),
            ),
        ),

        array(
            'type' => 'url',
            'field' => 'redirect_path',
            'label' => 'Tilpasset url',
            'columns' => 4,
            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => 'redirect',
                ),

                array(
                    'field' => 'smamo_form_actions:redirect_page',
                    'value' => 'custom',
                ),
            ),
        ),



        // Message
        array(
            'type' => 'group',
            'field' => 'message',
            'columns' => 12,

            'conditions' => array(
                array(
                    'field' => 'smamo_form_actions:action_type',
                    'value' => array('return_success','send_email'),
                ),
            ),

            'fields' => array(
                array(
                    'field' => 'heading',
                    'type' => 'text',
                    'label' => 'Overskrift',
                    'help' => 'Du kan bruge feltnavne fra formularen omringet af {krølleparenteser} i overskrift og besked.',
                    'columns' => 12,
                ),

                array(
                    'field' => 'body',
                    'type' => 'editor',
                    'label' => 'Besked <br/><span style="display: block; color: #999;margin-top: 10px;">Feltdata: ' . $field_placeholders . '</span>',
                    'columns' => 12,
                    'options' => array( // Pass any option that is accepted by wp_editor()
                      'wpautop' => true,
                      'media_buttons' => true,
                      'shortcode_buttons' => false,
                      'teeny' => false,
                      'dfw' => false,
                      'quicktags' => true,
                      'drag_drop_upload' => true,
                      'tinymce' => array(
                        'resize' => false,
                        'wp_autoresize_on' => true
                      )
                    ),
                ),
            ),
        ),

    ),
));


unset($page_choices);
unset($pages);
unset($field_array);
unset($field_placeholders);
unset($form_fields);
