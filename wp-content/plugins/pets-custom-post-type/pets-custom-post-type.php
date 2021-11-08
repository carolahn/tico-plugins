<?php

/*
  Plugin Name: Pet Adoption (Custom Post Type)
  Version: 1.0
  Author: Brad
  Author URI: https://www.udemy.com/user/bradschiff/
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('init', 'ourInit');

function ourInit() {
  register_post_type('pet', array(
    'label' => 'Pets',
    'public' => true,
    'menu_icon' => 'dashicons-buddicons-activity',
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'custom-fields')
  ));
}

add_filter('template_include', function($template) {
  if (is_page('pet-adoption')) {
    return plugin_dir_path(__FILE__) . 'inc/template-pets.php';
  }
  return $template;
}, 99);

add_action('wp_enqueue_scripts', function() {
  if (is_page('pet-adoption')) {
    wp_enqueue_style('petadoptioncss', plugin_dir_url(__FILE__) . 'pet-adoption.css');
  }
});

require_once plugin_dir_path(__FILE__) . 'inc/CreatePets.php';

// by Carol Ahn
if( ! function_exists( 'post_meta_request_params' ) ) {
	function post_meta_request_params( $args, $request )
	{
		$args += array(
			'meta_key'   => $request['meta_key'],
			'meta_value' => $request['meta_value'],
			'meta_query' => $request['meta_query'],
		);

	    return $args;
	}
	// add_filter( 'rest_post_query', 'post_meta_request_params', 99, 2 );
	// add_filter( 'rest_page_query', 'post_meta_request_params', 99, 2 ); // Add support for `page`
  // add_filter( 'rest_my-custom-post_query', 'post_meta_request_params', 99, 2 ); // Add support for `my-custom-post`
  add_filter( 'rest_pet_query', 'post_meta_request_params', 99, 2 );
}

/*
    Uncomment the following "add_action" line of code, and then
    load / refresh the admin dashboard to automatically insert 10
    pet posts. Feel free to increase the number from 10 to a larger
    number on line #52. Once you are happy with the number of pet
    posts you have you can re-comment the line below to stop adding
    new pets on each reload.
*/

// add_action('admin_head', 'insertPetPosts');

function insertPetPosts() {
  for ($i = 0; $i < 10; $i++) {
    $pet = generatePet();

    wp_insert_post(array(
      'post_type' => 'pet',
      'post_title' => $pet['name'],
      'post_status' => 'publish',
      'meta_input' => array(
        'species' => $pet['species'],
        'favFood' => $pet['favFood'],
        'birthYear' => $pet['birthyear'],
        'weight' => $pet['weight'],
        'favColor' => $pet['favColor'],
        'favHobby' => $pet['favHobby']
        )
    ));
  }
}