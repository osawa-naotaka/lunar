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

// favicon
function get_favicon_url($name)
{
    $media_items = get_posts( [
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_status'    => 'inherit',
    ]);
  
    foreach ( $media_items as $item ) {
      $file_name = basename( get_attached_file( $item->ID ) );
  
      if ( $file_name === $name ) {
        return wp_get_attachment_url( $item->ID );
      }
    }

    return NULL;
}
add_action( 'wp_head', function() {
    if($ico = get_favicon_url('favicon.ico'))
    {
        echo '<link rel="icon" sizes="48x48" href="' . esc_url( $ico ) . '">';
    }
    if($svg = get_favicon_url('favicon.svg'))
    {
        echo '<link rel="icon" sizes="any" type="image/svg+xml" href="' . esc_url( $svg ) . '">';
    }
});
