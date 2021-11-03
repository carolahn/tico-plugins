<?php

add_action('init', 'tico_post_types');
function tico_post_types() {

    // Pattern Post Type
    register_post_type('pattern', array(
        // 'supports' => array('title', 'editor', 'excerpt', 'custom-fields'),
        'capability_type' => 'pattern',
        'map_meta_cap' => true,
        'supports' => array('title', 'excerpt'),
        'rewrite' => array('slug' => 'patterns'),
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Patterns',
            'add_new_item' => 'Add New Pattern',
            'edit_item' => 'Edit Pattern',
            'all_items' => 'All Paterns',
            'singular_name' => 'Pattern'
        ),
        'menu_icon' => 'dashicons-text-page'
    ));

    // Product Post Type
    register_post_type('product', array(
        // 'supports' => array('title', 'editor', 'excerpt', 'custom-fields'),
        'supports' => array('title', 'excerpt'),
        'rewrite' => array('slug' => 'products'),
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Products',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'all_items' => 'All Products',
            'singular_name' => 'Product'
        ),
        'menu_icon' => 'dashicons-buddicons-activity'
    ));

    // Designer Post Type
    register_post_type('designer', array(
        'capability_type' => 'designer',
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'excerpt', 'thumbnail', 'editor'),
        // 'rewrite' => array('slug' => 'products'),
        // 'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Designers',
            'add_new_item' => 'Add New Designer',
            'edit_item' => 'Edit Designer',
            'all_items' => 'All Designers',
            'singular_name' => 'Designer'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    // Store Post Type
    register_post_type('store', array(
        // 'supports' => array('title', 'editor', 'excerpt', 'custom-fields'),
        'supports' => array('title', 'excerpt'),
        'rewrite' => array('slug' => 'stores'),
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Stores',
            'add_new_item' => 'Add New Store',
            'edit_item' => 'Edit Store',
            'all_items' => 'All Stores',
            'singular_name' => 'Store'
        ),
        'menu_icon' => 'dashicons-location-alt'
    ));

    // Note Post Type
    register_post_type('note', array(
        'capability_type' => 'note',
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor'),
        'public' => false, // não aparecerá em public queries ou resultado de pesquisas, mas tbm esconde Note do admin dassboard
        'show_ui' => true, // para aparecer no dashboard, show user interface
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
    ));

    // Like Post Type
    register_post_type('like', array(
        'supports' => array('title'),
        'public' => false, // não aparecerá em public queries ou resultado de pesquisas, mas tbm esconde Note do admin dassboard
        'show_ui' => true, // para aparecer no dashboard, show user interface
        'labels' => array(
            'name' => 'Likes',
            'add_new_item' => 'Add New Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like'
        ),
        'menu_icon' => 'dashicons-heart'
    ));

    // Slide Post Type
    register_post_type('slide', array(
        'supports' => array('title'),
        'public' => false, // não aparecerá em public queries ou resultado de pesquisas, mas tbm esconde Note do admin dassboard
        'show_ui' => true, // para aparecer no dashboard, show user interface
        'labels' => array(
            'name' => 'Slides',
            'add_new_item' => 'Add New Slide',
            'edit_item' => 'Edit Slides',
            'all_items' => 'All Slides',
            'singular_name' => 'Slide'
        ),
        'menu_icon' => 'dashicons-images-alt'
    ));
}