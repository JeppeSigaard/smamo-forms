<?php
function smamo_create_form_entry($form_id, $postvars){
    
    // Create post
    $new = wp_insert_post(array(
        'post_title' => date_i18n('d/m Y H:i:s'),
        'post_status' => 'publish',
        'post_type' => 'smamo_form_entry',
    ));

    // Update metadata
    add_post_meta($new,'smamo_form',$form_id, true);
    foreach($postvars as $key => $value){
        add_post_meta($new, $key, htmlspecialchars(wp_strip_all_tags($value)), true);
    }
    
    // Return ID
    return $new;
}