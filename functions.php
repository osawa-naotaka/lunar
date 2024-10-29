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

function call_when_template_exists(callable $f)
{
    global $post;
    if ($post) {
        $templates = get_template_hierarchy($post->post_name, true);
        foreach($templates as $template)
        {
            if(file_exists(get_theme_file_path('templates/' . $template . '.html')))
            {
                $f($template);
                break;
            }
        }
        unset($template);
    }
}

function get_template_id()
{
    global $_wp_current_template_id;    // private variable. not good.

    preg_match_all("|[^/]+//(.*)|", $_wp_current_template_id, $matches, PREG_PATTERN_ORDER);
    return $matches[1][0];
}

function enqueue_theme_css($name, $filename, $deps = [])
{
    wp_enqueue_style(
        $name,
        esc_url(get_theme_file_uri($filename)),
        $deps,
        null);
}

function enqueue_theme_script($name, $filename, $deps = [])
{
    wp_enqueue_script(
        $name,
        esc_url(get_theme_file_uri($filename)),
        $deps,
        null,
        ['in_footer' => true]);
}

// read stylesheet (Production and Editor)
add_action('wp_enqueue_scripts', function ()
{
    // call_when_template_exists(fn ($x) =>
    //     call_when_exists($x, 'enqueue_theme_css'));

    call_when_exists(STYLE_FILE, fn ($x) =>
        enqueue_theme_css(THEME_NAME, $x));
});

add_action('after_setup_theme', function ()
{
    // call_when_template_exists(fn ($x) => 
    //     call_when_exists($x, 'add_editor_style'));
    
    call_when_exists(STYLE_FILE, 'add_editor_style');
});

// read JavaScript (Production and Editor)
function enqueue_theme_script_if_exists()
{
    // call_when_template_exists(fn ($x) =>
    //     call_when_exists($x, 'enqueue_theme_script'));

    call_when_exists(JS_FILE, fn ($x) =>
        enqueue_theme_script(THEME_NAME, $x));
}

add_action('wp_enqueue_scripts', 'enqueue_theme_script_if_exists');
add_action('enqueue_block_editor_assets', 'enqueue_theme_script_if_exists');

