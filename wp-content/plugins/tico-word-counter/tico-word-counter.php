<?php

/*
	Plugin Name: Tico Word Counter
	Description: Post word counter plugin.
	Version: 1.0.0
	Author: Carolina Ahn
	Author URI: https://github.com/carolahn
	Text Domain: wcpdomain
	Domain Path: /languages
*/

class WordCountAndTimePlugin {
	function __construct() {
		add_action('admin_menu', array($this, 'adminPage'));
		add_action('admin_init', array($this, 'settings'));
		add_filter('the_content', array($this, 'ifWrap'));
		add_action('init', array($this, 'languages'));
	}

	function languages() {
		load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	function ifWrap($content) {
		// Precisa ser Post ou Page, não pode ser resultado de uma query, e pelo menos um dos checkbox precisa ser selecionado
		if (is_main_query() AND is_single() AND (get_option('wcp_wordcount', '1') OR get_option('wcp_charactercount', '1') OR get_option('wcp_readtime', '1'))) {
			return $this->createHTML($content);
		}
		return $content;
	}

	function createHTML($content) {
		$html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

		if (get_option('wcp_wordcount', '1') OR get_option('wcp_readtime', '1')) {
			$wordCount = str_word_count(strip_tags($content));
		}

		if (get_option('wcp_wordcount', '1')) {
			$html .= esc_html__('This post has', 'wcpdomain') . ' ' . $wordCount . ' ' . esc_html__('words', 'wcpdomain') . '.<br>';
		}

		if (get_option('wcp_charactercount', '1')) {
			$html .= 'This post has ' . strlen(strip_tags($content)) . ' characters.<br>';
		}

		if (get_option('wcp_readtime', '1')) {
			$time = round($wordCount/225);
			$html .= 'This post will take about  ' . $time . ' minute(s) to read.<br>';
		}

		$html .= '</p>';

		if (get_option('wcp_location', '0') == '0') {
			return $html . $content;
		}
		return $content . $html;
	}
	
	function settings() {
		// Amarrar o field criado (abaixo) a uma section
		add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

		// Location
		// Amarrar a option registrada no DB (abaixo) a um field de formulário HTML
		add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
		// Registrar as options no DB
		register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));

		// Headline text (título que aparecerá no post, anstes dos dados)
		add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
		register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

		// Show word count - Antigo
		// add_settings_field('wcp_wordcount', 'Word Count', array($this, 'wordcountHTML'), 'word-count-settings-page', 'wcp_first_section');
		// register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

		// Show word count - Novo - reutilizável
		add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
		register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

		// Show character count
		add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
		register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

		// Show read time
		add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
		register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
	}

	function sanitizeLocation($input) {
		if ($input != '0' AND $input != '1') {
			add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either beginning or end.');
			return get_option('wcp_location');
		}
		return $input;
	}

	/* Antigo
	function wordcountHTML() { ?>
		<input type="checkbox" name="wco_wordcount" value="1" <?php checked(get_option('wcp_wordcount', '1')) ?>>
	<?php }
	*/

	// Novo - reutilizável
	function checkboxHTML($args) { ?>
		<input type="checkbox" name="<?php echo $args['theName']; ?>" value="1" <?php checked(get_option($args['theName'], '1')) ?>>
	<?php }

	function headlineHTML() { ?>
		<input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">
	<?php }

	function locationHTML() { ?>
		<select name="wcp_location">
			<option value="0" <?php selected(get_option('wcp_location', '0')); ?>>Beginning of post</option>
			<option value="1" <?php selected(get_option('wcp_location', '1')); ?>>End of post</option>
		</select>
	<?php }

	function adminPage() {
		// Adiciona no menu dashboard, em Settings
		add_options_page('Word Count Settings', __('Word Count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
	}

	function ourHTML() { ?> 
		<!-- em vez de return, podemos escrever HTML diretamente aqui -->
		<div class="wrap">
			<h1>Word Count Settings</h1>
			<form action="options.php" method="POST">
				<?php 
					settings_fields('wordcountplugin');
					do_settings_sections('word-count-settings-page'); 
					submit_button();
				?>
			</form>
		</div>
	<?php }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();

// Adiciona uma frase no final dos Posts
// add_filter('the_content', 'addToEndOfPost');
// function addToEndOfPost($content) {
// 	if (is_single() && is_main_query()) {
// 		return $content . '<p>Meu nome é Carol.</p>';
// 	}
// 	return $content;
// }