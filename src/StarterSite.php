<?php

use Timber\Site;

/**
 * Class StarterSite
 */
class StarterSite extends Site
{
	public function __construct()
	{
		add_action('after_setup_theme', array($this, 'theme_supports'));
		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));
		add_action('init', array($this, 'register_menus'));

		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		add_filter('timber/twig/environment/options', [$this, 'update_twig_environment_options']);

		parent::__construct();
	}

	public function register_menus()
	{
		register_nav_menus([
			'primary' => __('Primary Menu'),
			'footer' => __('Footer Menu'),
		]);
	}

	/**
	 * This is where you can register custom post types.
	 */
	public function register_post_types()
	{
		// this is where you can register custom post types
		// https://codex.wordpress.org/Function_Reference/register_post_type
	}

	/**
	 * This is where you can register custom taxonomies.
	 */
	public function register_taxonomies()
	{
		// this is where you can register custom taxonomies
		// https://codex.wordpress.org/Function_Reference/register_taxonomy
	}

	/**
	 * This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context($context)
	{
		if (function_exists('get_fields')) {
			$context['options'] = get_fields('options');
		}

		foreach (get_registered_nav_menus() as $menu => $label) {
			$context[$menu] = Timber::get_menu($menu);
		}

		$context['site']  = $this;

		return $context;
	}

	public function theme_supports()
	{
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */

		add_theme_support('menus');

		add_theme_support('custom-logo');
	}


	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @param Twig\Environment $twig get extension.
	 */
	public function add_to_twig($twig)
	{
		/**
		 * Required when you want to use Twig’s template_from_string.
		 * @link https://twig.symfony.com/doc/3.x/functions/template_from_string.html
		 */
		// $twig->addExtension( new Twig\Extension\StringLoaderExtension() );

		$twig->addFilter(new Twig\TwigFilter('myfoo', [$this, 'myfoo']));

		return $twig;
	}

	/**
	 * Updates Twig environment options.
	 *
	 * @link https://twig.symfony.com/doc/2.x/api.html#environment-options
	 *
	 * \@param array $options An array of environment options.
	 *
	 * @return array
	 */
	function update_twig_environment_options($options)
	{
		// $options['autoescape'] = true;

		return $options;
	}
}
