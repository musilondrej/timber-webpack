<?php

namespace Bitspecter\Branding;

defined('ABSPATH') || exit;

class BrandingEnhancements
{
    public function __construct()
    {
        add_filter('login_headerurl', [$this, 'loginLogoUrl']);
        add_action('login_enqueue_scripts', [$this, 'loginLogo']);
        add_action('login_enqueue_scripts', [$this, 'customLoginStylesheet']);
        add_action('login_head', [$this, 'bitspecterLoginLogo']);
        add_filter('admin_footer_text', [$this, 'customAdminFooterText']);
    }


    public function loginLogoUrl()
    {
        return home_url();
    }

    public function loginLogo()
    {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

        if ($logo) {
            wp_enqueue_style('bitspecter-custom-login', get_stylesheet_directory_uri() . '/css/custom-login.css');
        }
    }

    public function customLoginStylesheet()
    {
        wp_enqueue_style('custom-login', get_template_directory_uri() . '/css/login.css');
    }

    public function bitspecterLoginLogo()
    {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

        $logoSrc = $logo ? $logo[0] : get_template_directory_uri() . '/resources/images/logo.png';

        echo '<style type="text/css">
            #wpml-login-ls-form, #backtoblog { display: none !important; }
            h1 a { background-image:url(' . $logoSrc . ') !important; background-size: contain !important; }
        </style>';
    }

    public function customAdminFooterText()
    {
        return 'Powered by <a href="https://bitspecter.com" target="_blank">BitSpecter</a>';
    }
}

new BrandingEnhancements();