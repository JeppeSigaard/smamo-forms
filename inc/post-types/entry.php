<?php 

// form type
add_action( 'init', 'smamo_add_post_type_form_entry' );
function smamo_add_post_type_form_entry() {
	register_post_type( 'smamo_form_entry', array(

        'menu_icon' 		 => 'dashicons-feedback',
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'edit.php?post_type=smamo_form',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'smamo_form_entry' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 22,
		'supports'           => array(''),
        'labels'             => array(
            'name'               => _x( 'Indtastninger', 'post type general name', 'smamo' ),
            'singular_name'      => _x( 'Indtastning', 'post type singular name', 'smamo' ),
            'menu_name'          => _x( 'Indtastninger', 'admin menu', 'smamo' ),
            'name_admin_bar'     => _x( 'Indtastninger', 'add new on admin bar', 'smamo' ),
            'add_new'            => _x( 'Tilføj ny ', 'indtastning', 'smamo' ),
            'add_new_item'       => __( 'Ny indtastning', 'smamo' ),
            'new_item'           => __( 'Ny indtastning', 'smamo' ),
            'edit_item'          => __( 'Rediger', 'smamo' ),
            'view_item'          => __( 'Se indtastninger', 'smamo' ),
            'all_items'          => __( 'Indtastninger', 'smamo' ),
            'search_items'       => __( 'Find indtastning', 'smamo' ),
            'parent_item_colon'  => __( 'Forældre:', 'smamo' ),
            'not_found'          => __( 'Der er ingen indstastninger.', 'smamo' ),
            'not_found_in_trash' => __( 'Papirkurven er tom.', 'smamo' ),
            ),
	   )
    );
}

$entry_rows = new WACC(array(
    'post_type' => 'smamo_form_entry',
    'defaults' => array(
        'date' => false,
        'author' => false,
    ),
    
    'columns' => array(
        
        'ID' => array(
            'slug' => 'ID',
            'output' => 'ID',
            'data_type' => 'ID',
            'order' => false,
        ),
        
        'smamo_form' => array(
            'slug' => 'smamo_form',
            'output' => 'Formular',
            'data_type' => 'post_meta',
            'meta_key' => 'smamo_form',
            'field_type' => 'filter',
        ),
        
    ),
));

add_filter('pre_get_posts', 'smamo_filter_admin_pages');
function smamo_filter_admin_pages($query) {
    if ( is_admin() && isset($_GET['meta_key']) && isset($_GET['meta_value'])){
        $query->set('meta_key', esc_attr($_GET['meta_key']) );
        $query->set('meta_value', esc_attr($_GET['meta_value']) );
     return $query;
    }
}