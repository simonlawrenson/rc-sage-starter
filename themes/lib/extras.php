<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  // Add class that inits a slider within the content
  // $post = get_post();
  // if ( $post && \has_blocks( $post->post_content ) ) {
  //   $blocks = parse_blocks( $post->post_content );
  //   if ( $blocks && is_array($blocks) ) {
  //     foreach( $blocks as $block ) {
  //       if( $block['blockName'] === 'acf/content-slider' ) {
  //         $classes[] = 'slickc-content-slider-active';
  //       }
  //       if( $block['blockName'] === 'acf/gallery-slider' ) {
  //         $classes[] = 'gallery-active';
  //       }
  //       if( $block['blockName'] === 'acf/image-slider' ) {
  //         $classes[] = 'slickc-image-slider-active';
  //       }
  //     }
  //   }
  // }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Remove the_excerpt() read more
 */
function excerpt_more() {
  return '';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');
/*
 * Trim excerpt word count
 */
function excerpt_length() {
  return 30;
}
add_action('excerpt_length', __NAMESPACE__. '\\excerpt_length');

/*
 * Add ACF Options page
 */ 
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(); 
}

/*
 * Add Google Map API key for ACF
 */
function acf_google_map() {
  acf_update_setting('google_api_key', '');
}
add_action('acf/init', __NAMESPACE__. '\\acf_google_map');

/*
 * Load Gravity Forms in footer
 */
add_filter( 'gform_cdata_open', __NAMESPACE__. '\\wrap_gform_cdata_open' );
function wrap_gform_cdata_open( $content = '' ) {
  $content = 'document.addEventListener( "DOMContentLoaded", function() { ';
  return $content;
}
add_filter('gform_init_scripts_footer', '__return_true');

function wrap_gform_cdata_close( $content = '' ) {
  $content = ' }, false );';
  return $content;
}
add_filter( 'gform_cdata_close', __NAMESPACE__. '\\wrap_gform_cdata_close' );
/**
 * Customise Gravity Forms Spinner
 */
add_filter('gform_ajax_spinner_url', function () {
    return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
});

/*
 * Add GTM code to head
 */
add_action('wp_head', __NAMESPACE__ .'\\add_gtm_head', -10);
function add_gtm_head()
{

  if( $_SERVER['HTTP_HOST'] === 'URL.com.au' || $_SERVER['HTTP_HOST'] === 'www.url.com.au' ) : ?>
    <!-- Google Tag Manager -->
    <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-');
    </script>
    <!-- End Google Tag Manager -->
<?php endif;
}

/*
 * Add GTM code to body
 */
function add_gtm_body()
{
  if( $_SERVER['HTTP_HOST'] === 'url.com.au' || $_SERVER['HTTP_HOST'] === 'www.url.com.au' ) : ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
  <?php endif;
}
// add_action('before_header', __NAMESPACE__ .'\\add_gtm_body', 0);

/**
 * Order CPT by menu_order
 *
 * @param int  $post_id          IDs of the current post
 * @return
 */
function pre_get_posts( $query )
{
  if( !is_admin() && $query->is_main_query() && is_post_type_archive('') ) :
    $query->set('orderby', 'menu_order');
    $query->set('order', 'DESC');
  endif;
  return;
}
// add_action('pre_get_posts', __NAMESPACE__. '\\pre_get_posts');

/**
 * Inject ACF content in to the_content()
 * Save ACF image as the_post_thumbnail
 *
 * @param int  $post_id          IDs of the current post
 * @return
 */
function save_acf_as_content( $post_id )
{
  // If saving changes to a page
  if( is_admin() && get_current_screen()->post_type === 'page' ) {
    // Get ACF field data
    $has_thumbnail = get_the_post_thumbnail( $post_id );
    $image         = get_field('content_img', $post_id, false);
    $title         = get_field('content_title', $post_id, false);
    $content       = get_field('content_main', $post_id, false);

    // If ACF image is different to WP featured image overwrite
    if ( !$has_thumbnail || $image != get_post_thumbnail_id() ) {
      $image_id = $image;
      if ( $image_id ) {
        set_post_thumbnail( $post_id, $image_id );
      }
      else {
          // If no ACF image exists, remove WP featured image
          delete_post_thumbnail( $post_id );
      }
    }
    
    // Save ACF content to WP content so SEO plugins works as expected
    if ( $title && $content ) {
      $post_content = '<h2>'. $title .'</h2><div>'. $content .'</div>';
      wp_update_post([ 'ID' => $post_id, 'post_content' => $post_content]);
    } elseif ( $title ) {
      $post_content = '<h2>'. $title .'</h2>';
      wp_update_post([ 'ID' => $post_id, 'post_content' => $post_content]);
    } elseif ( $content ) {
      $post_content = '<div>'. $content .'</div>';
      wp_update_post([ 'ID' => $post_id, 'post_content' => $post_content]);
    }
    return;
  }
  return;
}
// add_action('acf/save_post', __NAMESPACE__ .'\\save_acf_as_content', 20);

/**
 * Clean phone number for click to call
 *
 * @param string $phone_number   Phone number from ACF Options
 * @return string $click_to_call  Phone number ready for tel:
 */
function clean_phone( $phone_number )
{
  $phone_number  = str_replace( [' ', '(', ')'], '', $phone_number);
  $click_to_call = substr_replace($phone_number, '+61', 0, $phone_number[0] === '0');
  return $click_to_call;
}


/**
 * Get an array of Gutenberg blocks used on the page
 * 
 * @param string  $block_alignment String of which alignment is set
 * @return string $align_classes   A string of classes to apply
 */
function set_block_alignment( $block_alignment )
{
  if( !$block_alignment ) {
    return 'text-left';
  } elseif( $block_alignment === 'full' || $block_alignment === 'wide') {
    $align_classes = 'text-center';
  } elseif( $block_alignment === 'left' ) {
    $align_classes = 'col-md-10 col-lg-9 mr-md-auto text-'. $block_alignment;
  } elseif( $block_alignment === 'center' ) {
    $align_classes = 'col-md-10 col-lg-9 mr-md-auto ml-md-auto text-'. $block_alignment;
  } elseif ( $block_alignment === 'right' ) {
    $align_classes = 'col-md-10 col-lg-9 ml-md-auto text-'. $block_alignment;
  }
  return $align_classes;
}


/**
 * Prepare an array containing site address
 *
 * @return array $address Main address for the website
 */
function get_business_info()
{
  $company  = get_bloginfo('name');
  $street   = get_field('addi_street', 'options');
  $suburb   = get_field('addi_suburb', 'options');
  $city     = get_field('addi_city', 'options');
  $state    = get_field('addi_state', 'options');
  $country  = get_field('addi_country', 'options');
  $postcode = get_field('addi_postcode', 'options');
  // $hours    = get_field('opening_hours', 'options');

  $address = [
    'company'   => ( $company ? $company : '' ),
    'street'    => ( $street ? $street : '' ),
    'suburb'    => ( $suburb ? $suburb : '' ),
    'city'      => ( $city ? $city : '' ),
    'state'     => ( $state ? $state : '' ),
    'country'   => ( $country ? $country : '' ),
    'postcode'  => ( $postcode ? $postcode : '' ),
    // 'opening'   => ( $opening ? $opening : '' ),
  ];

  return $address;
}

/**
 * Prepare an array containing site social links
 *
 * @return array $social  Main social profiles for the website
 */
function get_social()
{
  $fb = get_field('social_fb', 'options');
  $tw = get_field('social_tw', 'options');
  $in = get_field('social_in', 'options');
  $yt = get_field('social_yt', 'options');
  $pi = get_field('social_pi', 'options');
  $li = get_field('social_li', 'options');
  
  // If there are any social profiles available create an array
  if( $fb || $tw || $in || $yt || $pi || $li ) {
    $social = [];
    ( $fb ? $social['Facebook']  = [ 'icon' => 'facebook-f', 'link' => 'https://facebook.com/'. $fb ] : false );
    ( $tw ? $social['Twitter']   = [ 'icon' => 'twitter',    'link' => 'https://twitter.com/'. $tw ] : false );
    ( $in ? $social['Instagram'] = [ 'icon' => 'instagram',  'link' => 'https://instagram.com/'. $in ] : false );
    ( $yt ? $social['Youtube']   = [ 'icon' => 'youtube',    'link' => 'https://youtube.com/user/'. $yt ] : false );
    ( $pi ? $social['Pinterest'] = [ 'icon' => 'pinterest-p','link' => 'https://www.pinterest.com.au/'. $pi ] : false );
    ( $pi ? $social['Linkedin']  = [ 'icon' => 'linkedin-in','link' => 'https://linkedin.com/'. $pi ] : false );
  }

  return $social;
}

/**
 * Wrap core Gutenberg blocks in Bootstrap classes
 *
 * @param string $block_content HTML layout of the blocks
 * @param obj $block Object containing blocks in use on the page
 * @return string $block_content HTML layout of the blocks, wrapped in new divs
 */
add_filter('render_block', __NAMESPACE__ .'\\render_block', 10, 2 );
function render_block( $block_content, $block ) {
  
  // Array of blocks to wrap
  $block_in_use = [
    'core/quote',
    'core/button',
    'core-embed/spotify',
    'core/heading',
    'core/latest-posts',
    'core/list',
    'core/image',
    'core/media-text',
    'core/paragraph',
    'core/pullquote',
    'core/shortcode'
  ];

  if( in_array($block['blockName'], $block_in_use) ) {
    // HTML opening wrap
    $block_wrap_open = '<div class="wp-block-container"><div class="container-fluid opt-container-fluid"><div class="row"><div class="col-12">';
    // HTML closing wrap
    $block_wrap_closing = '</div></div></div></div>';
    $block_content = $block_wrap_open . $block_content . $block_wrap_closing;

  } elseif( $block['blockName'] === 'core/media-text' ) {
    $block_wrap_open = '<div class="wp-block-container wp-media-text"><div class="container-fluid opt-container-fluid"><div class="row"><div     class="col-12">';
    $block_wrap_closing = '</div></div></div></div>';
    $block_content = $block_wrap_open . $block_content . $block_wrap_closing;

  } elseif( $block['blockName'] === 'core/table' ) { 
    $block_wrap_open = '<div class="table-container"><div class="container-fluid opt-container-fluid"><div class="row"><div class="col-12 table-col"><div class="table-responsive">';
    $block_wrap_closing = '</div></div></div></div></div>';
    $block_content = $block_wrap_open . $block_content . $block_wrap_closing;

  } elseif( $block['blockName'] === 'core-embed/youtube' ) { 
    $block_wrap_open = '<div class="video-container block-container"><div class="container-fluid opt-container-fluid"><div class="row"><div class="col-12 col-md-10 col-lg-9 mr-md-auto ml-md-auto">';
    $block_wrap_closing = '</div></div></div></div>';
    $block_content = $block_wrap_open . $block_content . $block_wrap_closing;
    
  }
  return $block_content;
}