<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/StarterSite.php';

require_once __DIR__ . '/app/inc/branding.php';
require_once __DIR__ . '/app/inc/performance.php';
require_once __DIR__ . '/app/inc/hardening.php';

Timber\Timber::init();

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = ['templates', 'views'];

new StarterSite();

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('styles', get_template_directory_uri() . '/assets/css/app.css', [], '1.0.0');
    wp_enqueue_script('scripts', get_template_directory_uri() . '/assets/js/app.js', [], '1.0.0', true);
});

// allow svg upload
function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

add_action('after_setup_theme', function () {
    $logoDefaults = apply_filters('bitspecter_logo_defaults', array(
        'height'               => 100,
        'width'                => 400,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array('site-title', 'site-description'),
        'unlink-homepage-logo' => true,
    ));

    add_theme_support('custom-logo', $logoDefaults);
});

add_action('admin_init', function () {
    // only for front-page
    if (get_option('page_on_front') == get_the_ID()) {
        remove_post_type_support('page', 'editor');
    }
});
