<?php

namespace Bitspecter\Hardening;

defined('ABSPATH') || exit;

class SecurityEnhancements
{
    public function __construct()
    {
        $this->defineConstants();
        $this->addSecurityHooks();
    }

    private function defineConstants()
    {
        defined('FORCE_SSL_ADMIN') || define('FORCE_SSL_ADMIN', true);
        defined('DISALLOW_FILE_EDIT') || define('DISALLOW_FILE_EDIT', true);
    }

    private function addSecurityHooks()
    {
        add_action('init', [$this, 'disableWpFeatures']);
        add_filter('redirect_canonical', [$this, 'preventUserEnumeration'], 10, 2);
        add_filter('login_errors', [$this, 'hideLoginErrors']);
        add_action('init', [$this, 'removeUnnecessaryWpHeadItems']);
        add_filter('upload_mimes', [$this, 'customMimeTypes']);
    }

    public function disableWpFeatures()
    {
        add_filter('json_enabled', '__return_false');
        add_filter('json_jsonp_enabled', '__return_false');
        add_filter('pings_open', '__return_false');
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('the_generator', '__return_empty_string');
        header_remove('x-powered-by');
    }

    public function preventUserEnumeration($redirect_url, $requested_url)
    {
        if (preg_match('/\?author=([0-9]*)/i', $requested_url)) {
            return home_url();
        }
        return $redirect_url;
    }

    public function hideLoginErrors()
    {
        return __('Login details are incorrect.', 'theme-domain');
    }

    public function removeUnnecessaryWpHeadItems()
    {
        $head_items_to_remove = [
            'feed_links', 'feed_links_extra', 'rsd_link', 'wlwmanifest_link',
            'wp_generator', 'start_post_rel_link', 'index_rel_link',
            'parent_post_rel_link', 'adjacent_posts_rel_link_wp_head',
            'wp_oembed_add_discovery_links', 'rest_output_link_wp_head',
            'rest_output_link_header', 'wp_shortlink_header'
        ];

        foreach ($head_items_to_remove as $item) {
            remove_action('wp_head', $item);
        }
    }

    public function customMimeTypes($mime_types)
    {
        $mime_types['svg'] = 'image/svg+xml';
        return $mime_types;
    }
}

new SecurityEnhancements();
