<?php

function smamo_form_message($form_id, $entry_id, $action){
    $response =  array(
        'heading' => do_shortcode(smamo_replace_brackets($action['message']['heading'], $form_id, $entry_id)),
        'body' => apply_filters('the_content', smamo_replace_brackets($action['message']['body'], $form_id, $entry_id)),
    );

    return $response;
}
