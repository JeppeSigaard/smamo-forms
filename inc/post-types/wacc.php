<?php 

class WACC{
    
    private $settings;
    
    function __construct($var){
        
        
        $this->settings = $var;
        $post_type = $this->settings['post_type'];
        
        /* ------------------------  */
        // Tilføj Kollonnernes header
        $filter = 'manage_edit-'.$post_type.'_columns';
        add_filter( $filter, function ($columns) {
        
            // Default felter
            $new_columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __('Title'),
                'author' => __('Author'),
                'date' => __('Date'),
            );
            
            
            
            // Overskriv eller fjern default
            if(isset($this->settings['defaults'])){
                foreach($this->settings['defaults'] as $key => $val){
                    if ($val !== false){
                        if(array_key_exists($key, $new_columns)){
                            $new_columns[$key] = $val;
                        }
                    }
                    else{unset($new_columns[$key]);}
                }
            }
            
            // Tilføj nye headers
            foreach($this->settings['columns'] as $field){
                $key = $field['slug'];
                $new_columns[$key] = $field['output'];
            }
            
            
            // Returner kolonner
            return $new_columns;
            
        });
        
        /* ------------------------  */
        // Tilføj indhold til klolonnen
        $filter = 'manage_'.$post_type.'_posts_custom_column';
        add_action($filter,function($column_name){
            
            global $post;
            foreach($this->settings['columns'] as $field){
                if($field['slug'] === $column_name){

                    // ID
                    if($field['data_type'] === 'ID' || $field['data_type'] === 'id'){
                        echo $post->ID;
                    }
                    
                    // tax
                    if($field['data_type'] === 'tax'){
                        
                        $terms = get_the_terms( $post->ID, $field['slug'] );
                        if ( !empty( $terms ) ) {

                            $out = array();

                            foreach ( $terms as $term ) {
                                $out[] = sprintf( '<a href="%s">%s</a>',
                                    esc_url( add_query_arg( array( 'post_type' => $post->post_type, $field['slug'] => $term->slug ), 'edit.php' ) ),
                                    esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $field['slug'], 'display' ) )
                                );
                            }

                            echo join( ', ', $out );
                        }                        
                    }
                    
                    // Post_meta
                    if ($field['data_type'] === 'post_meta'){
                    
                        if(isset($field['link']) && $field['link'] === 'post'){
                            echo edit_post_link( get_post_meta($post->ID,$field['meta_key'],true), '<b>', '</b>', $post->ID );
                        }
                        
                        
                        else if(isset($field['field_type']) && $field['field_type'] === 'filter'){
                            $form_id = get_post_meta($post->ID,$field['meta_key'],true);
                            $form = ($form_id) ? get_post($form_id) : false;
                            $form_name = ($form) ? $form->post_title : '';
                            
                            echo sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array( 
                                'post_type' => $post->post_type,
                                'meta_key' => $field['meta_key'],
                                'meta_value' => get_post_meta($post->ID,$field['meta_key'],true),
                            ), 'edit.php' ) ), esc_html( $form_name ));
                        }
                        
                        else if(isset($field['field_type']) && $field['field_type'] === 'options'){
                            echo $field['options'][get_post_meta($post->ID,$field['meta_key'],true)];   
                        }
                        
                        
                        else{
                            echo get_post_meta($post->ID,$field['meta_key'],true);
                        }
                    }
                    
                    // Shortcode
                    if ($field['data_type'] === 'shortcode'){
                        echo '[smamo_form id="'.$post->ID.'"]';
                    }
                    
                    // entry count
                    if($field['data_type'] === 'entry_count'){
                        $entry_link = 'edit.php?post_type=smamo_form_entry&meta_key=smamo_form&meta_value=' . $post->ID;
                       
                        $entry_count = count(get_posts(array(
                            'post_type' => 'smamo_form_entry',
                            'posts_per_page' => -1,
                            'meta_key' => 'smamo_form',
                            'meta_value' => $post->ID,
                        )));
                        
                        if($entry_count > 0){
                            $er = ($entry_count > 1) ? 'er': '';
                            echo '<a href="'. admin_url($entry_link) .'">'. $entry_count .' indtastning'. $er .'</a>';
                        }
                        
                        else {
                            echo $entry_count . ' indtastninger';
                        }
                        
                        
                    }
                    
                    // Action count
                    if($field['data_type'] === 'action_count'){
                        $link = admin_url('post.php?post='.$post->ID.'&action=edit&_%5Bflow%5D=formflow&_%5Bflow_page%5D=handlinger');
                        
                        $actions = get_post_meta($post->ID,'form_actions', true);
                        
                        if(!is_array($actions)){echo '<a href="'.$link.'">Ingen handlinger</a>'; continue;}
                        
                        $action_count = 0;
                        foreach($actions as $action){
                            if(isset($action['action_type']) && $action['action_type'] !== 'null'){
                                $action_count ++;
                            }
                        }
                        
                        if($action_count > 0){
                            $er = ($action_count > 1) ? 'er' : '' ;
                            echo '<a href="'.$link.'">'.$action_count.' handling'. $er .'</a>';    
                        }
                        
                        else{
                            echo '<a href="'.$link.'">Ingen handlinger</a>';    
                        }
                    }
                    
                    
                    // Tilføj andet?
                }
            }    
            
        });
        
        /* ------------------------  */
        // Tilføj sortering
        $filter = 'manage_edit-'.$post_type.'_sortable_columns';
        add_action($filter,function($columns){
            
            $custom = array();
            foreach($this->settings['columns'] as $field){
                
                if(isset($field['sort']) && false == $field['sort']){continue;}
                
                $key = $field['slug'];
                $custom[$key] = $field['slug'];
            }
            
            return wp_parse_args($custom, $columns);
            
        });
        
        /* ------------------------  */
        // Tilføj query var
        add_action('request',function($vars){
            
            foreach($this->settings['columns'] as $field){
                if(isset($field['order']) && false == $field['order']){continue;}
                
                if ( isset( $vars['orderby'] ) && $field['slug'] == $vars['orderby']) {
                    $order_by = (isset($field['order_by'])) ? $field['order_by'] : 'meta_value';
                    $merge_array = array(
                        'meta_key' => $field['meta_key'],
                        'orderby' => $order_by
                    );
                    if(isset($field['meta_type'])){
                        $merge_array['meta_type'] = $field['meta_type'];
                    }
                    $vars = array_merge( $vars, $merge_array);
                } 
            }
            return $vars;
            
        });
    }
};