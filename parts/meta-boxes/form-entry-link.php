<?php
/*
Title: Indtastninger
Post Type: smamo_form
scope: post_meta
order: 2
priority: default
Flow: formFlow
Tab: Felter, Handlinger
context: side
*/

$form_id = (isset($_GET['post'])) ? esc_attr($_GET['post']) : false;

$entries = get_posts(array(
    'post_type' => 'smamo_form_entry',
    'posts_per_page' => -1,
    'meta_key' => 'smamo_form',
    'meta_value' => $form_id,
));

$entry_count = count($entries);

if($form_id){

    $entry_link = 'edit.php?post_type=smamo_form_entry&meta_key=smamo_form&meta_value=' . $form_id;

    $html = '<p>Indtastninger: <strong>' . $entry_count .'</strong></p>';

    if($entry_count > 0){

        //$html .= '<p>Seneste: '. date_i18n('d. F Y', $entries[0]->post_date) .'</p>';
        $html .= '<p><a class="button" href="'.admin_url($entry_link).'">Se indtastninger</a></p>';
        $html .= '<p><a class="button" onclick="smamo_form_export_excel('.$form_id.')" href="#">Exporter indtastninger til excel</a></p>';
    }

    else{
        $html .= '<p style="color: #999;">Der er endnu ingen indtastninger at vise</p>';
    }

    piklist('field', array(
        'type' => 'html',
        'value' => $html,
    ));
}

unset ($entries);
