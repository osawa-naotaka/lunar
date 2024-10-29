<?php

// settings
define('THEME_NAME', 'lunar');
define('STYLE_FILE', 'assets/css/style.css');
define('JS_FILE', 'assets/js/app.js');

function call_when_exists($filename, callable $f)
{
    if(file_exists(get_theme_file_path(($filename))))
    {
        $f($filename);
    }    
}

function with_url($filename)
{
    return esc_url(get_theme_file_uri($filename));
}

// read stylesheet (Production and Editor)
add_action('wp_enqueue_scripts', function ()
{
    call_when_exists(STYLE_FILE, fn ($x) =>
        wp_enqueue_style(
            THEME_NAME,
            with_url($x),
            [],
            null)
        );
});

add_action('after_setup_theme', fn () =>
    call_when_exists(STYLE_FILE, 'add_editor_style'));

// read JavaScript (Production and Editor)
function enqueue_theme_script()
{
    call_when_exists(JS_FILE, fn ($x) =>
        wp_enqueue_script(
            THEME_NAME,
            with_url($x),
            [],
            null,
            ['in_footer' => true]
        ));
}

add_action('wp_enqueue_scripts', 'enqueue_theme_script');
add_action('enqueue_block_editor_assets', 'enqueue_theme_script');
