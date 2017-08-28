<?php 

// form type
add_action( 'init', 'smamo_add_post_type_form' );
function smamo_add_post_type_form() {
	register_post_type( 'smamo_form', array(

        'menu_icon' 		 => 'dashicons-feedback',
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'smamo_form' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 22,
		'supports'           => array( 'title'),
        'labels'             => array(
            'name'               => _x( 'Formularer', 'post type general name', 'smamo' ),
            'singular_name'      => _x( 'Formular', 'post type singular name', 'smamo' ),
            'menu_name'          => _x( 'Formularer', 'admin menu', 'smamo' ),
            'name_admin_bar'     => _x( 'Formularer', 'add new on admin bar', 'smamo' ),
            'add_new'            => _x( 'Tilføj ny ', 'formular', 'smamo' ),
            'add_new_item'       => __( 'Ny formular', 'smamo' ),
            'new_item'           => __( 'Ny formular', 'smamo' ),
            'edit_item'          => __( 'Rediger', 'smamo' ),
            'view_item'          => __( 'Se formular', 'smamo' ),
            'all_items'          => __( 'Se alle', 'smamo' ),
            'search_items'       => __( 'Find formular', 'smamo' ),
            'parent_item_colon'  => __( 'Forældre:', 'smamo' ),
            'not_found'          => __( 'Der er ingen formularer. Start med at <a href="'. admin_url('post-new.php?post_type=smamo_form') .'">oprette en ny.</a>', 'smamo' ),
            'not_found_in_trash' => __( 'Papirkurven er tom.', 'smamo' ),
            ),
	   )
    );
}

$form_rows = new WACC(array(
    'post_type' => 'smamo_form',
    'defaults' => array(
        'date' => false,
        'author' => false,
    ),
    
    'columns' => array(
        
        /*
        'shortcode' => array(
            'slug' => 'shortcode',
            'output' => 'Shortcode',
            'data_type' => 'shortcode',
            'order' => false,
            'sort' => false
        ),
        */
        
        'entries' => array(
            'slug' => 'entries',
            'output' => 'Indtastninger',
            'data_type' => 'entry_count',
            'order' => false,
            'sort' => false
        ),
        
        'actions' => array(
            'slug' => 'actions',
            'output' => 'Handlinger',
            'data_type' => 'action_count',
            'order' => false,
            'sort' => false
        ),
    
    ),
));