<?php
namespace Roots\Sage\Setup;
/*--------------------------------------------------------------------------------------
*
*		Carousel Class
*
*		@author 	Simon Lawrenson
*		@since 		01/06/2018
*
*
*		TABLE OF CONTENTS
*
*		1.0 Constructor
*		2.0 Register Post Type
*		3.0 Output CSS & JS the Slider Carousel
*		4.0 Add classes to body
*		5.0 Output HTML for the Slider Carousel
* 
*-------------------------------------------------------------------------------------*/

class Carousel {

	/*--------------------------------------------------------------------------------------
		*
		*		1.0 Constructor
		* 
		*-------------------------------------------------------------------------------------*/

	public function __construct() {
		add_action('init', array($this,'add_carousel_post_type'), 0); //add post type
		add_action('wp_enqueue_scripts', array($this,'enqueue_styles_and_scripts')); // load scripts and styles
		add_filter('body_class', array($this,'add_body_class')); // Add slick class to body for js init
		add_action('after_header', array($this,'output')); // prepare HTML for slider on the home page
	}

	/*--------------------------------------------------------------------------------------
		*
		*		2.0 Register Post Type - carousel
		* 
		*-------------------------------------------------------------------------------------*/
	public function add_carousel_post_type() {
		new PostType(
	    'carousel',
	    'Slide Image',
	    'Slider Images',
	    false,
	    false,
	    array(
	      'public'              => false,
	      'exclude_from_search' => true,
	      'publicly_queryable'  => false,
	      'query_var'           => false,
	      'capability_type'		=> 'page',
	      'has_archive'         => false,
	      //See https://github.com/encharm/Font-Awesome-SVG-PNG/tree/master/black/svg
	      'menu_position'			=> 19,
	      'menu_icon'           => 'dashicons-images-alt2',
	      'supports'            => array('title', 'editor', 'thumbnail', 'page-attributes'),
	      'taxonomies'          => ['carousel-type'],
	    )
	  );
	  new Taxonomy(
	    'carousel', /* CPT Type */
	    'carousel-type', /* Taxonomy */
	    'Location', /* Tax Singular */
	    'Carousel Locations', /* Tax Plural */
	    false, /* Tax Slug rewrite */
	    false, /* Tax Hierarchical  */
	    array(
	      'public'              => false,
	      'publicly_queryable'  => false,
	      'query_var'           => true,
	      'show_in_nav_menus' 	=> false,
	    )
	  );
		return;
	}
	
	/*--------------------------------------------------------------------------------------
		*
		*		3.0 Output CSS & JS the Slider Carousel
		* 
		*-------------------------------------------------------------------------------------*/
	public function enqueue_styles_and_scripts() {
		wp_enqueue_style('slick-carousel', Assets\asset_path('styles/slick.css'), false, null);
		// wp_enqueue_style('sage/slick-carousel', Assets\asset_path('styles/slick.css'), false, null);

  	wp_enqueue_script('slick-carousel', Assets\asset_path('scripts/slick.js'), ['jquery'], null, true);
  	// wp_enqueue_script('sage/slick-carousel', Assets\asset_path('scripts/slick.js'), ['jquery'], null, true);
	}

	/*--------------------------------------------------------------------------------------
		*
		*		4.0 Add classes to body
		* 
		*-------------------------------------------------------------------------------------*/
	public function add_body_class( $classes ) {
		if( function_exists('get_field') ) {
			if (is_front_page() && get_field('enable_slider')) {
		    $classes[] = 'slickc-active';
		  }
		}
		return $classes;
	}

	/*--------------------------------------------------------------------------------------
		*
		*		5.0 Output HTML for the Slider Carousel
		* 
		*-------------------------------------------------------------------------------------*/
	public function output() {
		if( function_exists('get_field') ) {
			$slider_type = get_field('select_slider');
			if( $slider_type ) {
				$args = array(
					'post_type'		=> 'carousel',
					'numberposts'	=> -1,
					'orderby'		=> 'menu_order',
					'order'			=> 'ASC',
					'tax_query'		=> array(
						array(
							'taxonomy'	=> 'carousel-type',
							'field'		=> 'id',
							'terms'		=> $slider_type,
						),
					),
				);
				$carousel = new WP_Query( $args );
				if( $carousel->have_posts() ) :
					ob_start(); ?>
					<div id="fullscreen-slider-container" class="header-image-container">
						<div id="slides">
							<?php while( $carousel->have_posts() ) : $carousel->the_post(); ?>
								<?php if( has_post_thumbnail() ) { ?>
									<div>
										<?php $img_src = wp_get_attachment_image_url(get_post_thumbnail_id(), 'slider-image'); ?>
										<?php $img_srcset = wp_get_attachment_image_srcset( get_post_thumbnail_id(), 'slider-image' ); ?>
										<img src="<?= esc_url($img_src); ?>" srcset="<?= esc_attr( $img_srcset ); ?>" sizes="(max-width: 575px) 100vw,(max-width: 767px) 100vw,(max-width: 991px) 100vw, 100vw"  title="<?= get_the_title(); ?>" alt="<?= get_the_title(); ?>" class="slide-img header-img" />
									</div>
								<?php } ?>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div> <!-- end #slides -->
					</div> <!-- end #fullscreen-slider-container -->
					<?php
					$slider_html = ob_get_clean();
					echo $slider_html;
					return;
				endif;
			}
		}
		return;
	}
}