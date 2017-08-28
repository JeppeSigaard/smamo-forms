<?php

function smamo_form_ubivox($form_id, $entry_id, $action){
    
    require_once(SMAMO_FORMS_DIR . '/inc/ubivox/ubivox_api.php');
    
    $user = $action['uvx_user'];
    $password = $action['uvx_pass'];
    
    $list_id = $action['uvx_list'];
    $email = get_post_meta($entry_id, $action['uvx_email'], true);
    
    $merge_fields = array();
    foreach($action['uvx_merge_tags'] as $merge_tag){
        
        if(!isset($merge_tag['uvx_tag']) || $merge_tag['uvx_tag'] == '') continue;
        if(!isset($merge_tag['entry_field']) || $merge_tag['entry_field'] == '') continue;
        
        $merge_fields[$merge_tag['uvx_tag']] = get_post_meta($entry_id, $merge_tag['entry_field'], true);
    }
    
    $client = new UbivoxAPI( $user, $password, "https://" .$user.".clients.ubivox.com/xmlrpc/" );

    try {
 
        $client->call("ubivox.create_subscription", array($email, $list_id, true));

        if (!empty($merge_fields)){
            $client->call("ubivox.set_subscriber_data",  array($email, $merge_fields));
        }

    } catch(UbivoxAPIError $e) {

      if ($e->getCode() == 1003) { return "Email already subscribed";}
      return $e->getMessage();
    }
    
    return true;
}