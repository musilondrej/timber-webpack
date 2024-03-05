<?php

namespace Bitspecter\Performance;

defined('ABSPATH') || exit;

add_action('init', __NAMESPACE__ . '\\disable_emojis');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\dequeue_unnecessary_scripts', 100);
add_action('after_setup_theme', __NAMESPACE__ . '\\remove_wp_meta_tags');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\optimize_jquery');
add_action('init', __NAMESPACE__ . '\\enable_lazy_load');
add_action('widgets_init', __NAMESPACE__ . '\\disable_default_widgets', 11);
add_action('wp_print_styles', __NAMESPACE__ . '\\remove_all_jp_css');
add_filter('use_default_gallery_style', '__return_false');

/**
 * Disables emojis which removes the extra scripts and styles loaded by WordPress.
 */
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}

/**
 * Dequeues unnecessary scripts and styles to improve performance.
 */
function dequeue_unnecessary_scripts()
{
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_script('wp-embed');
    }
}

/**
 * Removes unnecessary WordPress meta tags from the head.
 */
function remove_wp_meta_tags()
{
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}

/**
 * Optimizes jQuery loading by loading it from a CDN and only when necessary.
 */
function optimize_jquery()
{
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', false, '3.6.0', true);
        add_filter('script_loader_tag', __NAMESPACE__ . '\\add_defer_attribute', 10, 2);
    }
}

/**
 * Adds defer attribute to scripts for non-blocking loading.
 */
function add_defer_attribute($tag, $handle)
{
    if ('jquery' === $handle) {
        return str_replace(' src', ' defer="defer" src', $tag);
    }
    return $tag;
}

/**
 * Enables native lazy loading of images in WordPress.
 */
function enable_lazy_load()
{
    add_filter('wp_lazy_loading_enabled', '__return_true');
}

/**
 * Disables default WordPress widgets to improve performance.
 */
function disable_default_widgets()
{
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('Twenty_Eleven_Ephemera_Widget');
}

/**
 * Remove all unwanted Jetpack CSS components from the head
 */
function remove_all_jp_css()
{
    // First, make sure Jetpack doesn't concatenate all its CSS
    add_filter('jetpack_implode_frontend_css', '__return_false');


    wp_deregister_style('AtD_style'); // After the Deadline
    wp_deregister_style('jetpack_likes'); // Likes
    wp_deregister_style('jetpack_related-posts'); //Related Posts
    wp_deregister_style('jetpack-carousel'); // Carousel
    wp_deregister_style('grunion.css'); // Grunion contact form
    wp_deregister_style('the-neverending-homepage'); // Infinite Scroll
    wp_deregister_style('infinity-twentyten'); // Infinite Scroll - Twentyten Theme
    wp_deregister_style('infinity-twentyeleven'); // Infinite Scroll - Twentyeleven Theme
    wp_deregister_style('infinity-twentytwelve'); // Infinite Scroll - Twentytwelve Theme
    wp_deregister_style('noticons'); // Notes
    wp_deregister_style('post-by-email'); // Post by Email
    wp_deregister_style('publicize'); // Publicize
    wp_deregister_style('sharedaddy'); // Sharedaddy
    wp_deregister_style('sharing'); // Sharedaddy Sharing
    wp_deregister_style('stats_reports_css'); // Stats
    wp_deregister_style('jetpack-widgets'); // Widgets
    wp_deregister_style('jetpack-slideshow'); // Slideshows
    wp_deregister_style('presentations'); // Presentation shortcode
    wp_deregister_style('jetpack-subscriptions'); // Subscriptions
    wp_deregister_style('tiled-gallery'); // Tiled Galleries
    wp_deregister_style('widget-conditions'); // Widget Visibility
    wp_deregister_style('jetpack_display_posts_widget'); // Display Posts Widget
    wp_deregister_style('gravatar-profile-widget'); // Gravatar Widget
    wp_deregister_style('widget-grid-and-list'); // Top Posts widget
    wp_deregister_style('jetpack-widgets'); // Widgets
}
