<?php
/**
 * Plugin Name:     Replace Google Fonts with Bunny Fonts
 * Plugin URI:      https://github.com/AntonioLeutsch/Replace-Google-Fonts-with-Bunny-Fonts-WordPress-Plugin
 * Description:     Easily replace Google Fonts with Bunny Fonts.
 * Author:          antonioleutsch
 * Author URI:      https://antonio-leutsch.com
 * Text Domain:     al_bunny-fonts
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Bunny_Fonts
 */


//we use 'init' action to use ob_start()
add_action( 'init', 'al_bunny_init_ob' );

function al_bunny_init_ob() {
	ob_start();
}

// get the pages html
add_action('shutdown', 'al_bunny_shutdown', 0);

function al_bunny_shutdown() {
	$final = '';

	// We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
	// that buffer's output into the final output.
	$levels = ob_get_level();

	for ($i = 0; $i < $levels; $i++) {
		$final .= ob_get_clean();
	}

	// Apply any filters to the final output
	echo apply_filters('al_bunny_fonts_filter_output', $final);
}

add_filter('al_bunny_fonts_filter_output', function($output) {

	// if html contains 'fonts.googleapis.com'
	if (str_contains($output, 'fonts.googleapis.com/css')) {
		// replace with 'fonts.bunny.net'
		$output = str_replace('fonts.googleapis.com/css', 'fonts.bunny.net/css', $output);
	}

	if (apply_filters('al_bunny_remove_google_preconnect', true)) {
		// if html contains <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> remove it
		if (str_contains($output, '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>')) {
			$output = str_replace('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>', '', $output);
		}
		// if html contains <link rel="preconnect" href="https://fonts.googleapis.com"> remove it
		if (str_contains($output, '<link rel="preconnect" href="https://fonts.googleapis.com">')) {
			$output = str_replace('<link rel="preconnect" href="https://fonts.googleapis.com">', '', $output);
		}
	}

	if (apply_filters('al_bunny_insert_al_bunny_preconnect', true)) {
		// check if html contains <link rel="preconnect" href="https://fonts.bunny.net"> when not insert it
		if (!str_contains($output, '<link rel="preconnect" href="https://fonts.bunny.net">')) {
			$output = str_replace('<link href="https://fonts.bunny.net', '<link rel="preconnect" href="https://fonts.bunny.net"> <link href="https://fonts.bunny.net', $output);
		}
	}


	return $output;
});
