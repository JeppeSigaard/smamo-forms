<?php

add_action('wp_ajax_smamo_form_submit', 'smamo_form_submit');
add_action('wp_ajax_nopriv_smamo_form_submit', 'smamo_form_submit');
function smamo_form_submit(){

    // Disallow CORS and things
    // check_ajax_referer( 'smamo_form_nonce', 'nonce' );
    $response = array();

    // Form ID
    $form_id = esc_attr($_POST['form_id']);
    if(!$form_id){wp_die(json_encode('error: no form ID set'));}

    $entry_id = smamo_create_form_entry($form_id, $_POST);

    $actions = get_post_meta($form_id, 'form_actions', true);

    foreach($actions as $action){
        if ('return_success' == $action['action_type']){
          $response['message'] = smamo_form_message($form_id, $entry_id, $action);
        }
        if ('send_email' == $action['action_type']){$response['email'][] = smamo_form_email($form_id, $entry_id, $action);}
        if ('mailchimp_subscribe' == $action['action_type']){$response['mailchimp'][] = smamo_form_mailchimp($form_id, $entry_id, $action);}
        if ('ubivox_subscribe' == $action['action_type']){$response['ubivox'][] = smamo_form_ubivox($form_id, $entry_id, $action);}
        if ('redirect' == $action['action_type']){$response['redirect'] = smamo_form_redirect($form_id, $entry_id, $action);}
    }

    wp_die(json_encode($response));
}
