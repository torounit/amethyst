<?php
/**
 * othello functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package othello
 */

if ( ! function_exists( 'othello_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function othello_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on _s, use a find and replace
	 * to change 'othello' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'othello', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Extra image sizes.
	 */
	set_post_thumbnail_size( 774, 400, true );
	add_image_size( 'thumb774x400', 774, 400, true );
	add_image_size( 'thumb288x154', 288, 154, true );

	/*
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'global_nav' => esc_html__( 'Global Menu', 'othello' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	/*
	 * Set up the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'othello_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	/*
	 * Enable support editor-style on WordPress dashboard.
	 */
	add_editor_style( array(
		'assets/css/editor-style.css',
		'assets/genericons/genericons.css'
	) );

}
endif; // othello_setup
add_action( 'after_setup_theme', 'othello_setup' );

/**
 * Extend the default WordPress body classes.
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function othello_body_classes( $classes ) {
	if ( ( ! is_active_sidebar( 'sidebar' ) )
		|| is_attachment() ) {
		$classes[] = 'no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'othello_body_classes' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function othello_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'othello_content_width', 774 );
}
add_action( 'after_setup_theme', 'othello_content_width', 0 );

/**
 * Register search form.
 *
**/
function othello_search_form( $form ) {
	$form = '<div class="search"><form role="search" method="get" id="searchform" class="search__form" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __( 'Search for:' ) . '</label>
	<input class="search__field" type="text" value="' . get_search_query() . '" placeholder="Seach for..." name="s" id="s" />
	<input class="search__submit" type="submit" id="searchsubmit" value="'. esc_attr__( 'Search' ) .'" /></form></div>';

	return $form;
}
add_filter( 'get_search_form', 'othello_search_form' );

/**
 * Register excerpt length.
**/
function custom_excerpt_length( $length ) {
  return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function othello_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'othello' ),
		'id'            => 'sidebar',
		'description'   => 'Main Widgets Sidebar. Shows up in all pages.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget__title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widgets', 'othello' ),
		'id'            => 'footer-widgets',
		'description'   => 'Will show a widgets area on the footer. Shows up in all pages.',
		'before_widget' => '<aside id="%1$s" class="widget widget--footer %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget__title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'othello_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function othello_scripts() {

	// genericons
	wp_enqueue_style( 'othello-genericons', get_template_directory_uri() . '/assets/genericons/genericons.css', array(), '3.4' );

	// stylesheet
	wp_enqueue_style( 'othello-style', get_stylesheet_uri() );

	// javascripts
	wp_enqueue_script( 'othello-js-lib', get_template_directory_uri() . '/assets/js/lib.min.js', array( 'jquery' ), true );
	wp_enqueue_script( 'othello-js-script', get_template_directory_uri() . '/assets/js/script.min.js', array( 'jquery' ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'othello_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
