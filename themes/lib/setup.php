<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-relative-urls');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('optimising-8.5.6', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'optimising-8.5.6'),
    'footer_navigation'  => __('Footer Navigation', 'optimising-8.5.6')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Enable Gutenberg align wide
  add_theme_support('align-wide');

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));

  /*
   * Custom theme images sizes
   */
  set_post_thumbnail_size( 300, 300, array( 'center', 'center')  );
  add_image_size('medium', 600, 600, array( 'center', 'center')  );
  add_image_size('site-logo', 250, 'auto', false );
  add_image_size('header-img', 1500, 500, array('center', 'center') );
  add_image_size('header-img-lg', 991, 330, array('center', 'center') );
  add_image_size('header-img-md', 767, 256, array('center', 'center') );
  add_image_size('header-img-sm', 575, 192, array('center', 'center') );
  add_image_size('gallery', 864, 574, array('center', 'center') );
  add_image_size('gallery-lg', 628, 417, array('center', 'center') );
  add_image_size('gallery-md', 767, 510, array('center', 'center') );
  add_image_size('gallery-sm', 575, 382, array('center', 'center') );
  add_image_size('gallery-nav', 326, 186, array('center', 'center') );
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/*
 * Adjust image srcset sizes
 */ 
function content_image_sizes_attr( $sizes, $size ) {
  if( get_page_template_slug() === 'front-page.php' ) {
    $sizes = '(max-width: 575px) 60vw, (max-width: 768px) 60vw, (max-width: 991px) 60vw, 60vw';
  } else {
    $sizes = '(max-width: 575px) 575w, (max-width: 767px) 767w, (max-width: 991px) 991w, 100vw';
  }
  return $sizes;
}
// add_filter( 'wp_calculate_image_sizes', __NAMESPACE__. '\\content_image_sizes_attr', 10 , 2 );

function post_thumbnail_sizes_attr($attr, $attachment, $size) {
  //Calculate Image Sizes by type and breakpoint
  // Header Images
  if ( $size === 'front-header-img' || $size === 'page-header-img' ) {
      $attr['sizes'] = '(max-width: 575px) 60vw, (max-width: 767px) 60vw, (max-width: 991px) 60vw, (max-width: 1200px) 60vw, 60vw';
  
  // Award categories layout
  } elseif ($size === 'winners-logo') {
      $attr['sizes'] = '(max-width: 767px) 515px, (max-width: 991px) 215px, (max-width: 2000px) 285px, 285px';
  
  // Image slider
  } elseif ($size === 'image-slider') {
      $attr['sizes'] = '(max-width: 575px) 100vw, (max-width: 767px) 100vw, (max-width: 991px) 100vw, (max-width: 1200px) 100vw, 100vw';

  // Gallery slider
  } elseif ($size === 'gallery') {
      $attr['sizes'] = '(max-width: 575px) 575w, (max-width: 767px) 767w, (max-width: 991px) 628w, 867w';
  } else {
    $attr = $attr;
  }
  return $attr;
}
// add_filter('wp_get_attachment_image_attributes', __NAMESPACE__. '\\post_thumbnail_sizes_attr', 10 , 3 );


/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'optimising-8.5.6'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'optimising-8.5.6'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = in_array(true, [
    // The sidebar will be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_singular('post'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  //Register Google Fonts
  wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,700', null, false);
  //Register theme CSS
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);

  // Register Slick Carousel assets
  // wp_register_style('slickc', Assets\asset_path('styles/slick.css'), false, null);
  // wp_register_script('slickc', Assets\asset_path('scripts/slick.js'), ['jquery'], null, true);

  // Enqueue lightbox on single pages
  // if( is_singular('') && ( get_field('gallery') )  ) {
  //   wp_enqueue_style('lightbox', Assets\asset_path('styles/lightbox.css'), false, null);
  //   wp_enqueue_script('lightbox', Assets\asset_path('scripts/lightbox.js'), ['jquery'], null, true);
  // }
  
  // Enqueue Google Map on contact page
  // if( is_page_template('template-contact.php') && get_field('google_map', 'options') ) {
  //   wp_enqueue_script('google-map-api', 'https://maps.googleapis.com/maps/api/js?key=', [], null, true);
  //   wp_enqueue_script('google-map', Assets\asset_path('scripts/acf-google-map.js'), ['google-map-api'], null, true);
  // }

  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

/**
 * Alter HTML script tag
 */
add_filter('script_loader_tag', __NAMESPACE__ . '\\add_defer_attribute', 10, 3);
function add_defer_attribute($tag, $handle, $src) {
  $defer_scripts = [
    'sage/js',
    // 'slickc',
    // 'lightbox',
    // 'google-map',
  ];
  if( in_array($handle, $defer_scripts) ) {
    $tag = '<script type="text/javascript" defer="defer" src="' . $src . '"></script>' . "\n";
  }
  return $tag;
}

/**
 * Disable Gutenberg for Testimonials Post Type and Contact Template
 */
function disable_gutenberg() {
  if( is_admin() ) {
    $current_screen = get_current_screen();
    $post_type = $current_screen->post_type;
    // print_r($current_screen);
    if(!is_object($current_screen)) {
      return;
    }
    if( $post_type == 'testimonials' ) {
      return  false;
    } elseif( $post_type == 'page' ) {
      if( $current_screen->action !== 'add' ) {
        $id = ($_GET['post'] ? $_GET['post'] : false);
        $id = intval( $id );
        $excluded_templates = array(
          'template-contact.php',
          'template-past-winners.php',
        );
        $template = get_page_template_slug( $id );
        if (in_array($template, $excluded_templates) ) {
          return  false;
        } else {
          return true;
        }
      } else {
        return true;
      }
    } else {
      return true;
    }
  }
}
add_filter('use_block_editor_for_post_type', __NAMESPACE__ .'\\disable_gutenberg', 10);

/**
 * Register Custom Post Types
 */
function init() {
  /**
   * CPT - 
   * Taxonomy - 
   */
  new PostType(
    'cpt_type', /* CPT Type */
    'cpt_singular', /* CPT Singular */
    'cpt_plural', /* CPT Plural */
    'cpt_slug', /* CPT Slug rewrite */
    // true false, /* CPT Hierarchical  */
    array(
      'public'              => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'query_var'           => true,
      'has_archive'         => true,
      'supports'            => array('title', 'editor', 'thumbnail', 'page-attributes'),
      //See https://github.com/encharm/Font-Awesome-SVG-PNG/tree/master/black/svg
      'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 2048 1792" xmlns="http://www.w3.org/2000/svg"><path fill="white"/></svg>'),
      'taxonomies'          => ['']
    )
  );

  /**
   * Taxonomy - 
   * CPT -
   */
  new Taxonomy(
    'cpt_type', /* CPT Type */
    'taxonomy', /* Taxonomy */
    'tax_singular', /* Tax Singular */
    'tax_plural', /* Tax Plural */
    'tax_slug', /* Tax Slug rewrite */
    // true false, /* Tax Hierarchical  */
    array(
      'public'              => true,
      'publicly_queryable'  => true,
      'query_var'           => true,
    )
  );
}
// add_action('init', __NAMESPACE__ . '\\init', 0);

/**
 * Register Gutenberg ACF Blocks
 */
function acf_init() {
  
  // Register an accordion layout block
  acf_register_block([
    'name'            => 'accordian',
    'title'           => __('Accordian'),
    'description'     => __('A accordian dropdown.'),
    'render_template' => 'templates/blocks/block-accordion.php',
    'category'        => 'layout',
    'icon'            => 'excerpt-view',
    'mode'            => 'preview',
    'keywords'        => ['accordian', 'dropdown'],
    'supports'        => [
      'align'     => false,
      'alignWide' => false,
      'anchor'    => false,
      'mode'      => true,
      'multiple'  => true,
    ],
  ]);

  // Register a full width content block
  acf_register_block([
    'name'            => 'content-block',
    'title'           => __('Content Block'),
    'description'     => __('A full width content block.'),
    'render_template' => 'templates/blocks/block-full-width.php',
    'category'        => 'layout',
    'icon'            => 'text',
    'mode'            => 'preview',
    'keywords'        => ['content', 'content block', 'content area'],
    'supports'        => [
      'align'     => true,
      'alignWide' => false,
      'anchor'    => false,
      'mode'      => true,
      'multiple'  => true,
    ],
  ]);
  
  // Register a repeater block for creating columns
  acf_register_block([
    'name'            => 'columns',
    'title'           => __('Custom Columns'),
    'description'     => __('A varible number of columns that supports titles, images and content.'),
    'render_template' => 'templates/blocks/block-columns.php',
    'category'        => 'layout',
    'icon'            => 'columns',
    'mode'            => 'preview',
    'keywords'        => ['columns', 'content', 'image', 'title'],
    'supports'        => [
      'align'     => true,
      'alignWide' => false,
      'anchor'    => false,
      'mode'      => true,
      'multiple'  => true,
    ],
  ]);

  // Register a custom block
  acf_register_block([
    'name'            => 'gallery-slider',
    'title'           => __('Gallery Slider'),
    'description'     => __('A gallery with verticle thumbnails'),
    'render_template' => 'templates/blocks/block-gallery.php',
    'category'        => 'layout',
    'icon'            => 'images-alt',
    'mode'            => 'preview',
    'keywords'        => ['gallery', 'images', 'verticle'],
    'supports'        => [
      'align'     => false,
      'alignWide' => false,
      'anchor'    => false,
      'mode'      => true,
      'multiple'  => true,
    ],
  ]);

  // Register a short archive list
  acf_register_block([
    'name'            => 'posts-list',
    'title'           => __('Posts List'),
    'description'     => __('A block that displays a list of selected within columns'),
    'render_template' => 'templates/blocks/block-posts-list.php',
    'category'        => 'layout',
    'icon'            => 'editor-table',
    'mode'            => 'preview',
    'keywords'        => ['archive', 'posts', 'list'],
    'supports'        => [
      'align'     => true,
      'alignWide' => false,
      'anchor'    => false,
      'mode'      => true,
      'multiple'  => true,
    ],
  ]);

  // Register a custom block
  // acf_register_block([
  //   'name'            => 'block-name',
  //   'title'           => __('Block Title'),
  //   'description'     => __('Describe the block here.'),
  //   'render_template' => 'templates/blocks/block-template.php',
  //   'category'        => 'layout',
  //   'icon'            => 'id',
  //   'mode'            => 'preview',
  //   'keywords'        => ['key', 'words', 'go', 'here'],
  //   'supports'        => [
  //     'align'     => true,
  //     'alignWide' => false,
  //     'anchor'    => false,
  //     'mode'      => true,
  //     'multiple'  => true,
  //   ],
  // ]);

}
add_action('acf/init', __NAMESPACE__ .'\\acf_init');