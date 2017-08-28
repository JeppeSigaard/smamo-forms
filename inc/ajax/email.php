<?php

// email
function smamo_form_send_email( $from_name, $from, $to, $subject, $message ){
    $header = "From: ".$from_name." <".$from.">\r\n";
    $header.= "MIME-Version: 1.0\r\n";
    $header.= "Content-Type: text/html; charset=utf-8\r\n";
    $header.= "X-Priority: 1\r\n";
    $email = wp_mail($to, $subject, $message, $header);
    return $email;
}

function smamo_form_email($form_id, $entry_id, $action){
    
    $from_name = get_post_meta($entry_id, $action['from_name']['field'], true);
    if ('custom' === $action['from_name']['field']){$from_name = $action['from_name']['custom'];}
    
    
    $from_email = get_post_meta($entry_id, $action['from_email']['field'], true);
    if ('custom' === $action['from_email']['field']){$from_email = $action['from_email']['custom'];}
    
    $to_email = get_post_meta($entry_id, $action['to_email']['field'], true);
    if ('custom' === $action['to_email']['field']){$to_email = $action['to_email']['custom'];}
    
    $subject = do_shortcode(smamo_replace_brackets($action['message']['heading'], $form_id, $entry_id));
    
    $message = apply_filters('the_content', smamo_replace_brackets($action['message']['body'], $form_id, $entry_id));
    
    return array(
        'from_name' => $from_name,
        'from_email' => $from_email,
        'to_email' => $to_email,
        'subject' => $subject,
        'message' => $message,
        'sent' => smamo_form_send_email($from_name,$from,$to,$subject,$message),
    );
}