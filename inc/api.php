<?php

function smamo_rest_form($data){
    $response = array();

    $form_id = esc_attr($data['id']);
    $form = get_post($form_id);
    if(!$form){return $response;}

    /* id */
    $response['id'] = $form_id;

    /* titel */
    $response['title'] = $form->post_title;

    /* action */
    $response['action'] = admin_url('admin-ajax.php');

    /* ajax action */
    $response['ajax_action'] = 'smamo_form_submit';

    /* felter */
    $response['fields'] = get_post_meta($form_id,'smamo_form_fields', true);

    /* logik */
    $response['logic_active'] = get_post_meta($form_id,'smamo_form_logic_active', true);
    if('checked' === $response['logic_active']){
        $response['logic'] = get_post_meta($form_id,'smamo_form_logic', true);
    }

    /* submit*/
    $response['submit_text'] = get_post_meta($form_id, 'submit_text', true);

    return $response;
}


add_action( 'rest_api_init', function () {
    register_rest_route( 'smamo-forms', 'form/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'smamo_rest_form',
	));

});
