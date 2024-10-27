<?php

// settings
define('THEME_NAME', 'lunar');

// read stylesheet (Production and Editor)
add_action('wp_enqueue_scripts', fn () =>
    wp_enqueue_style(
        THEME_NAME,
        esc_url(get_theme_file_uri('assets/css/theme.css')),
        [],
        null)
);
add_action('after_setup_theme', fn () => add_editor_style('assets/css/theme.css'));

// read JavaScript (Production and Editor)
function enqueue_theme_script()
{
    wp_enqueue_script(
        THEME_NAME,
        esc_url(get_theme_file_uri('assets/js/app.js')),
        [],
        null,
        ['in_footer' => true]);
}
add_action('wp_enqueue_scripts', 'enqueue_theme_script');
add_action('enqueue_block_editor_assets', 'enqueue_theme_script');
