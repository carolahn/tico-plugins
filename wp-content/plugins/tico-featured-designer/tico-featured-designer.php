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

class FeaturedDesigner {
  function __construct() {
    add_action('init', [$this, 'onInit']);
  }

  function onInit() {
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