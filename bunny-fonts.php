<?php
/**
 * Plugin Name:     Replace Google Fonts with Bunny Fonts
 * Plugin URI:      https://github.com/AntonioLeutsch/Replace-Google-Fonts-with-Bunny-Fonts-WordPress-Plugin
 * Description:     Easily replace Google Fonts with Bunny Fonts.
 * Author:          antonioleutsch
 * Author URI:      https://antonio-leutsch.com
 * Text Domain:     al_bunny-fonts
 * Domain Path:     /languages
 * Version:         2.0.0
 *
 * @package         Bunny_Fonts
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if autoptimize is active
 */
if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if (is_plugin_active('autoptimize/autoptimize.php')) {

    add_filter('autoptimize_html_after_minify', function ($content) {
        return apply_filters('al_bunny_fonts_filter_output', $content);
    });

} /**
 * Check if wp-fastest-cache is active
 */
elseif (is_plugin_active('wp-fastest-cache/wpFastestCache.php')) {

    add_filter('wpfc_buffer_callback_filter', function ($content) {
        return apply_filters('al_bunny_fonts_filter_output', $content);
    });

} /**
 * Check if wp-rocket is active
 */
elseif (is_plugin_active('wp-rocket/wp-rocket.php')) {

    add_filter('rocket_buffer', function ($content) {
        return apply_filters('al_bunny_fonts_filter_output', $content);
    });

} /**
 * Check if w3-total-cache is active
 */
elseif (is_plugin_active('w3-total-cache/w3-total-cache.php')) {
    add_filter('w3tc_process_content', function ($content) {
        return apply_filters('al_bunny_fonts_filter_output', $content);
    });
} /**
 * Check if wp-super-cache is active
 */
elseif (is_plugin_active('wp-super-cache/wp-cache.php')) {

    add_filter('wp_cache_ob_callback_filter', function ($content) {
        return apply_filters('al_bunny_fonts_filter_output', $content);
    });

} else {

    //we use 'init' action to use ob_start()
    add_action('init', 'al_bunny_init_ob');

    function al_bunny_init_ob()
    {
        ob_start();
    }

    // get the pages html
    add_action('shutdown', 'al_bunny_shutdown', 0);

    function al_bunny_shutdown()
    {
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


}

add_filter('al_bunny_fonts_filter_output', function ($output) {

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

    if (apply_filters('al_bunny_remove_google_prefetch', true)) {
        // if html contains <link rel='dns-prefetch' href='//fonts.googleapis.com' /> remove it
        if (str_contains($output, "<link rel='dns-prefetch' href='//fonts.googleapis.com' />")) {
            $output = str_replace("<link rel='dns-prefetch' href='//fonts.googleapis.com' />", '', $output);
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
