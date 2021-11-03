<?php

/*
  Plugin Name: Featured Designer Block Type
  Description: Adds updated information about a designer.
  Version: 1.0.0
  Author: Carolina Ahn
	Author URI: https://github.com/carolahn
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once plugin_dir_path(__FILE__) . 'inc/generateDesignerHTML.php';
require_once plugin_dir_path(__FILE__) . 'inc/relatedPostsHTML.php';

class FeaturedDesigner {
  function __construct() {
    add_action('init', [$this, 'onInit']);
    add_action('rest_api_init', [$this, 'designerHTML']);
    add_filter('the_content', [$this, 'addRelatedPosts']);
  }

  function addRelatedPosts($content) {
    if (is_singular('designer') && in_the_loop() && is_main_query()) {
      return $content . relatedPostsHTML(get_the_id());
    }
    return $content;
  }

  function designerHTML() {
    register_rest_route('featuredDesigner/v1', 'getHTML', array(
      'methods' => WP_REST_SERVER::READABLE,
      'callback' => [$this, 'getDesignerHTML']
    ));
  }

  function getDesignerHTML($data) {
    return generateDesignerHTML($data['designerId']);
  }

  function onInit() {
    register_meta('post', 'featureddesigner', array(
      'show_in_rest' => true,
      'type' => 'number',
      'single' => false
    ));

    wp_register_script('featuredDesignerScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor'));
    wp_register_style('featuredDesignerStyle', plugin_dir_url(__FILE__) . 'build/index.css');

    register_block_type('ticoplugin/featured-designer', array(
      'render_callback' => [$this, 'renderCallback'],
      'editor_script' => 'featuredDesignerScript',
      'editor_style' => 'featuredDesignerStyle'
    ));
  }

  function renderCallback($attributes) {
    if ($attributes['designerId']) {
      wp_enqueue_style('featuredDesignerStyle');
      return generateDesignerHTML($attributes['designerId']);
    } else {
      return NULL;
    }
  }

}

$featuredProfessor = new FeaturedDesigner();