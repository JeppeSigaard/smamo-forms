<?php 

/* 
Plugin name: Piklist form workflow by SmartMonkey
Description: adds raw form data editor to WordPress
Version: 1.0.0
Plugin Type: Piklist
*/

/* ----------------------------------------------------------------------------- */
/* ------------  Define plugin constant path, url and test mode  --------------- */
/* ----------------------------------------------------------------------------- */
if(!defined('SMAMO_FORMS_DIR')){ define('SMAMO_FORMS_DIR',dirname(__FILE__)); }
if(!defined('SMAMO_FORMS_URL')){ define('SMAMO_FORMS_URL',plugins_url('',__FILE__)); }
if(!defined('SMAMO_FORMS_TEST_MODE')){ define('SMAMO_FORMS_TEST_MODE', true); }

/* Check for piklist */
add_action('init', function (){
    if(is_admin()){
        smamo_partial('piklist-checker');
        if (!piklist_checker::check(__FILE__)){return;}
    }
});

/* api */
smamo_partial('api');


/* ajax */
smamo_partial(array('post', 'form-entry', 'mailchimp', 'ubivox', 'redirect', 'email', 'message', 'brackets'),'ajax');

/* Add post types */
smamo_partial(array( 'wacc', 'form', 'entry' ),'post-types');


/* 
 * smamo_partial( [string|array] $f, [string] $i ) 
 * 
 * $f : (required) string or array of file names
 * $i : (optional) path to files
 * Points to lib by deafult (sry bruh)
------------------------------------------ */
function smamo_rqpfx($f,$i=false){if($i){$f=$i.'/'.$f;}$f=SMAMO_FORMS_DIR.'/inc/'.$f.'.php';if(file_exists($f)){require_once $f;}elseif(defined('SMAMO_FORMS_TEST_MODE') && SMAMO_FORMS_TEST_MODE === true){echo '<pre> not found:' . $f . '</pre>';}}
function smamo_partial($f,$i=false){if(is_array($f)){foreach($f as $p){smamo_rqpfx($p,$i);}}else{smamo_rqpfx($f,$i);}}