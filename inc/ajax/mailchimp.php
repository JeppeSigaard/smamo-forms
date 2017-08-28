<?php 

require SMAMO_FORMS_DIR . '/inc/drewm/MailChimp.php'; 
use \DrewM\MailChimp\MailChimp;

function smamo_form_mailchimp($form_id, $entry_id, $action){
    
    $api_key = $action['MC_key'];
    $user_list = $action['MC_list'];
    $email = '';
    
    $merge_fields = array();
    foreach($action['MC_merge_tags'] as $merge_tag){
        $merge_fields[$merge_tag['MC_tag']] = get_post_meta($entry_id,$merge_tag['entry_field'], true);
        if(in_array($merge_tag['MC_tag'], array('EMAIL','MERGE0'))){ $email = get_post_meta($entry_id,$merge_tag['entry_field'], true);}
    }
    
    if($email !== '' && $api_key !== '' && $user_list !== ''){
    
        $mc = new MailChimp($api_key);
        $return = $mc->post('lists/' . $user_list . '/members', array(
            'email_address' => $email,
            'status'        => 'subscribed',
            'merge_fields' => $merge_fields,
        ));
        
        return $return;
    }
    
     return $action;
}
