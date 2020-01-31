<?php

/*
* property Property_cutom_post class used for creating custom post called Property
*/
class Custom_property_post{

    public function __construct(){

        add_action( 'init', array( &$this, 'create_posttype_property' ) );
        add_action( 'init', array( &$this, 'custom_post_type_property' ) );
        add_action( 'init', array( &$this, 'property_categories_register' ) );

    }
    public function create_posttype_property() {
         register_post_type( 'property',
             array(
                'labels'       => array(
                                      'name'          => __( 'Property' ),
                                      'singular_name' => __( 'property' )
                                 ),
                'public'       => true,
                'has_archive'  => true,
                 'rewrite' => array('slug' => 'property'),
            )
       );
    }
     // Hooking up our function to theme setup
    public function custom_post_type_property() {
    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'property', 'Post Type General Name', 'crater-free' ),
         'singular_name'       => _x( 'property', 'Post Type Singular Name', 'crater-free' ),
         'menu_name'           => __( 'property', 'crater-free' ),
         'parent_item_colon'   => __( 'Parent property', 'crater-free' ),
         'all_items'           => __( 'All property', 'crater-free' ),
         'view_item'           => __( 'View property', 'crater-free' ),
         'add_new_item'        => __( 'Add New property', 'crater-free' ),
         'add_new'             => __( 'Add New', 'crater-free' ),
         'edit_item'           => __( 'Edit property', 'crater-free' ),
         'update_item'         => __( 'Update property', 'crater-free' ),
         'search_items'        => __( 'Search property', 'crater-free' ),
         'not_found'           => __( 'Not Found', 'crater-free' ),
         'not_found_in_trash'  => __( 'Not found in Trash', 'crater-free' ),
     );
 // Set other options for Custom Post Type
    $args = array(
        'label'               => __( 'Property', 'crater-free' ),
        'description'         => __( 'Property news and reviews', 'crater-free' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
         'taxonomies'          => array( 'genres' ),
         /* A hierarchical CPT is like Pages and can have
         * Parent and child items. A non-hierarchical CPT
         * is like Posts.
         */ 
         'hierarchical'        => false,
         'public'              => true,
         'show_ui'             => true,
         'show_in_menu'        => true,
         'show_in_nav_menus'   => true,
         'show_in_admin_bar'   => true,
         'menu_position'       => 5,
         'can_export'          => true,
         'has_archive'         => true,
         'exclude_from_search' => false,
         'publicly_queryable'  => true,
         'capability_type'     => 'page',
     );   
     // Registering your Custom Post Type
     register_post_type( 'property', $args );
    }
     public function property_categories_register() {
    $labels = array(
        'name'                          => 'property categories',
        'singular_name'                 => 'property Category',
        'search_items'                  => 'Search property Categories',
        'popular_items'                 => 'Popular property Categories',
        'all_items'                     => 'All property Categories',
        'parent_item'                   => 'Parent Property Category',
        'edit_item'                     => 'Edit Property Category',
        'update_item'                   => 'Update Property Category',
        'add_new_item'                  => 'Add New Property Category',
        'new_item_name'                 => 'New Property Category',
        'separate_items_with_commas'    => 'Separate property categories with commas',
        'add_or_remove_items'           => 'Add or remove property categories',
        'choose_from_most_used'         => 'Choose from most used property categories'
        );

    $args = array(
        'label'                         => 'property Categories',
        'labels'                        => $labels,
        'public'                        => true,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => true,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite'                       => array( 'slug' => 'property', 'with_front' => true, 'hierarchical' => true ),
        'query_var'                     => true
    );

     register_taxonomy( 'property_categories', 'property', $args );
    }
}
$property_obj = new Custom_property_post();



