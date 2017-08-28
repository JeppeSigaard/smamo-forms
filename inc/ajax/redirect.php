<?php 

function smamo_form_redirect($form_id, $entry_id, $action){
   
    if('custom' === $action['redirect_page']){
        return $action['redirect_path'];
    }
    
    else{return $action['redirect_page'];}
}