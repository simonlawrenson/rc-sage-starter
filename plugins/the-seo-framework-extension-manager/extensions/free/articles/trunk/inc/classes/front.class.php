<?php
/**
 * @package TSF_Extension_Manager\Extension\Articles\Classes
 */
namespace TSF_Extension_Manager\Extension\Articles;

defined( 'ABSPATH' ) or die;

if ( \tsf_extension_manager()->_has_died() or false === ( \tsf_extension_manager()->_verify_instance( $_instance, $bits[1] ) or \tsf_extension_manager()->_maybe_die() ) )
	return;

/**
 * Local extension for The SEO Framework
 * Copyright (C) 2017-2018 Sybre Waaijer, CyberWire (https://cyberwire.nl/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published
 * by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class TSF_Extension_Manager\Extension\Articles\Front
 *
 * @since 1.2.0
 * @uses TSF_Extension_Manager\Traits
 * @final
 */
final class Front extends Core {
	use \TSF_Extension_Manager\Enclose_Stray_Private,
		\TSF_Extension_Manager\Construct_Master_Once_Interface;

	/**
	 * States if the output is valid.
	 * If the output is invalidated, the output should be cancelled.
	 *
	 * @since 1.0.0
	 * @var array $is_json_valid : { key => bool }
	 */
	private $is_json_valid = [];

	/**
	 * Registers the image size name.
	 *
	 * @since 1.1.0
	 * @var string $image_size_name
	 */
	private $image_size_name = 'tsfem-e-articles-logo';

	/**
	 * The constructor, initialize plugin.
	 *
	 * @since 1.0.0
	 */
	private function construct() {

		$this->is_json_valid = [
			'amp'    => true,
			'nonamp' => true,
		];

		$this->init();
	}

	/**
	 * Initializes hooks.
	 *
	 * @since 1.0.0
	 */
	private function init() {

		if ( $this->is_amp() ) {
			//* Initialize output in The SEO Framework's front-end AMP meta object.
			\add_filter( 'the_seo_framework_amp_pro', [ $this, '_articles_hook_amp_output' ] );
		} else {
			//* Initialize output in The SEO Framework's front-end meta object.
			\add_filter( 'the_seo_framework_after_output', [ $this, '_articles_hook_output' ] );
		}
	}

	/**
	 * Registers logo image size in WordPress.
	 *
	 * @since 1.1.0
	 */
	private function register_logo_image_size() {
		\add_image_size( $this->image_size_name, 60, 60, false );
	}

	/**
	 * Determines if the current page is AMP supported.
	 *
	 * @since 1.0.0
	 * @uses const AMP_QUERY_VAR
	 * @staticvar bool $cache
	 *
	 * @return bool True if AMP is enabled.
	 */
	private function is_amp() {

		static $cache;

		if ( isset( $cache ) )
			return $cache;

		return $cache = defined( 'AMP_QUERY_VAR' ) && \get_query_var( AMP_QUERY_VAR, false ) !== false;
	}

	/**
	 * Determines if the script is invalidated.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if valid, false otherwise.
	 */
	private function is_json_valid() {
		return $this->is_amp() ? $this->is_json_valid['amp'] : $this->is_json_valid['nonamp'];
	}

	/**
	 * Invalidates JSON output.
	 *
	 * @since 1.0.0
	 * @see $this->is_json_valid
	 * @see $this->is_json_valid()
	 *
	 * @param string $what
	 */
	private function invalidate( $what = 'both' ) {

		switch ( $what ) :
			case 'both' :
				$this->is_json_valid['amp'] = $this->is_json_valid['nonamp'] = false;
				break;

			case 'amp' :
				$this->is_json_valid['amp'] = false;
				break;

			case 'nonamp' :
				$this->is_json_valid['nonamp'] = false;
				break;
		endswitch;
	}

	/**
	 * Returns current WP_Post object.
	 *
	 * @since 1.0.0
	 * @staticvar object $post
	 *
	 * @return object WP_Post
	 */
	private function get_current_post() {

		static $post;

		return isset( $post ) ? $post : $post = \get_post( $this->get_current_id() );
	}

	/**
	 * Returns current WP_Query object ID.
	 *
	 * @since 1.0.0
	 * @staticvar int $id
	 *
	 * @return int Queried Object ID.
	 */
	private function get_current_id() {

		static $id = null;

		return $id ?: $id = \get_queried_object_id();
	}

	/**
	 * Outputs the AMP Articles script.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Changed from action to filter output.
	 * @access private
	 *
	 * @param string $output The current AMP pro output.
	 * @return string
	 */
	public function _articles_hook_amp_output( $output = '' ) {
		return $output .= $this->_get_articles_json_output();
	}

	/**
	 * Hooks into 'the_seo_framework_after_output' filter.
	 * This allows output object caching.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $functions The hooked functions.
	 * @return array The hooked functions.
	 */
	public function _articles_hook_output( $functions = [] ) {

		$functions[] = [
			'callback' => [ $this, '_get_articles_json_output' ],
			'args'     => [],
		];

		return $functions;
	}

	/**
	 * Returns the article JSON-LD script output.
	 * Runs at 'the_seo_framework_after_output' filter.
	 *
	 * @since 1.0.0
	 * @link https://developers.google.com/search/docs/data-types/articles
	 * @access private
	 *
	 * @return string The additional JSON-LD Article scripts.
	 */
	public function _get_articles_json_output() {

		\the_seo_framework()->set_timezone();

		$data = [
			$this->get_article_context(),
			$this->get_article_type(),
			$this->get_article_main_entity(),
			$this->get_article_headline(),
			$this->get_article_image(),
			$this->get_article_published_date(),
			$this->get_article_modified_date(),
			$this->get_article_author(),
			$this->get_article_publisher(),
			$this->get_article_description(),
		];

		\the_seo_framework()->reset_timezone();

		if ( ! $this->is_json_valid() )
			return '';

		//* Build data, and fetch it.
		array_filter( array_filter( $data ), [ $this, 'build_article_data' ] );
		$data = $this->get_article_data();

		if ( ! empty( $data ) )
			return sprintf( '<script type="application/ld+json">%s</script>', json_encode( $data, JSON_UNESCAPED_SLASHES ) ) . PHP_EOL;

		return '';
	}

	/**
	 * Builds up article data by shifting array keys through reset.
	 *
	 * @since 1.0.0
	 * @see $this->get_article_data()
	 *
	 * @param array $array The input element
	 */
	private function build_article_data( array $array ) {
		$this->get_article_data( false, $array );
	}

	/**
	 * Builds up and returns article data by shifting array keys through reset.
	 *
	 * @since 1.0.0
	 * @staticvar $data The generated data.
	 * @see $this->build_article_data()
	 *
	 * @param bool $get Whether to return the accumulated data.
	 * @param array $array The input element
	 * @return array The article data.
	 */
	private function get_article_data( $get = true, array $entry = [] ) {

		static $data = [];

		if ( $get )
			return $data;

		$data[ key( $entry ) ] = reset( $entry );

		return [];
	}

	/**
	 * Returns the Article Context.
	 *
	 * @since 1.0.0
	 *
	 * @requiredSchema Always
	 * @ignoredSchema Never
	 * @return array The Article context.
	 */
	private function get_article_context() {
		return [ '@context' => 'https://schema.org' ];
	}

	/**
	 * Returns the Article Type.
	 *
	 * Possibilities: 'Article', 'NewsArticle', 'BlogPosting'.
	 * 'Article' is most conventional and convinient, and covers all three types.
	 *
	 * @since 1.0.0
	 * @since 1.2.0 Now listens to post meta.
	 * @todo TSF allow selection of article/news/blogpost.
	 * @todo Maybe extension? i.e. News SEO.
	 *
	 * @requiredSchema Always
	 * @ignoredSchema Never
	 * @return array The Article type.
	 */
	private function get_article_type() {
		return [ '@type' => $this->get_post_meta( 'type' ) ];
	}

	/**
	 * Returns the Article Main Entity of Page.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Added TSF v3.0 compat.
	 *
	 * @requiredSchema Never
	 * @ignoredSchema nonAMP
	 * @return array The Article's main entity of the page.
	 */
	private function get_article_main_entity() {

		if ( ! $this->is_json_valid() )
			return [];

		$tsf = \the_seo_framework();

		if ( method_exists( $tsf, 'get_current_permalink' ) ) {
			$url = $tsf->get_current_permalink();
		} else {
			$url = $tsf->the_url_from_cache();
		}

		if ( ! $url ) {
			$this->invalidate( 'amp' );
			return [];
		}

		return [
			'mainEntityOfPage' => [
				'@type' => 'WebPage',
				'@id'   => $url,
			],
		];
	}

	/**
	 * Returns the Article Headline.
	 *
	 * @NOTE If the title is above 110 chars or is empty : {
	 *   'amp'    => Will invalidate output.
	 *   'nonamp' => Will return empty.
	 * }
	 * @since 1.0.0
	 * @since 1.3.0 Added TSF v3.1 compat.
	 *
	 * @requiredSchema AMP
	 * @ignoredSchema Never
	 * @return array The Article's Headline.
	 */
	private function get_article_headline() {

		if ( ! $this->is_json_valid() )
			return [];

		$id  = $this->get_current_id();
		$tsf = \the_seo_framework();

		if ( method_exists( $tsf, 'get_raw_generated_title' ) ) {
			$title = $tsf->get_raw_generated_title( [ 'id' => $id ] );
		} else {
			$title = $tsf->post_title_from_ID( $id ) ?: $tsf->title_from_custom_field( '', false, $id );
			$title = trim( $tsf->s_title_raw( $title ) );
		}

		if ( ! $title || mb_strlen( $title ) > 110 ) {
			$this->invalidate( 'amp' );
			return [];
		}

		return [
			'headline' => $tsf->escape_title( $title ),
		];
	}

	/**
	 * Returns the Article Image.
	 *
	 * @NOTE If the image is smaller than 696 pixels width : {
	 *   'amp'    => Will invalidate output.
	 *   'nonamp' => Will return empty.
	 * }
	 *
	 * @since 1.0.0
	 *
	 * @requiredSchema AMP
	 * @ignoredSchema Never
	 * @return array The Article's Image
	 */
	private function get_article_image() {

		if ( ! $this->is_json_valid() )
			return [];

		$image = $this->get_article_image_params();

		if ( empty( $image['url'] ) ) {
			$this->invalidate( 'amp' );
			return [];
		}

		return [
			'image' => [
				'@type'  => 'ImageObject',
				'url'    => \esc_url( $image['url'], [ 'http', 'https' ] ),
				'height' => abs( filter_var( $image['height'], FILTER_SANITIZE_NUMBER_INT ) ),
				'width'  => abs( filter_var( $image['width'], FILTER_SANITIZE_NUMBER_INT ) ),
			],
		];
	}

	/**
	 * Returns image parameters for Article image.
	 *
	 * @since 1.0.0
	 *
	 * @return array The article image parameters. Unescaped.
	 */
	private function get_article_image_params() {

		$id = $this->get_current_id();
		$w  = $h = 0;

		if ( $url = \the_seo_framework()->get_social_image_url_from_post_meta( $id, true ) ) {

			//* TSF 2.9+
			$dimensions = \the_seo_framework()->image_dimensions;

			$d = ! empty( $dimensions[ $id ] ) ? $dimensions[ $id ] : false;
			if ( $d ) {
				$w = $d['width'];
				$h = $d['height'];
			}

			if ( $w >= 696 )
				goto retvals;
		}

		//* Don't use `\the_seo_framework()->get_image_from_post_thumbnail` because it will overwrite vars.
		if ( $_img_id = \get_post_thumbnail_id( $id ) ) {

			$_src = \wp_get_attachment_image_src( $_img_id, 'full', false );

			if ( is_array( $_src ) && count( $_src ) >= 3 ) {
				$url = $_src[0];
				$w   = $_src[1];
				$h   = $_src[2];

				if ( $w >= 696 )
					goto retvals;
			}
		}

		retempty :;

		return [];

		retvals :;

		return [
			'url'    => $url,
			'width'  => $w,
			'height' => $h,
		];
	}

	/**
	 * Returns the Article Published Date.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 : 1. Now also outputs on non-AMP.
	 *                2. Now only invalidates AMP when something's wrong.
	 *
	 * @requiredSchema AMP (docs)
	 * @ignoredSchema nonAMP
	 * @return array The Article's Published Date
	 */
	private function get_article_published_date() {

		if ( ! $this->is_json_valid() )
			return [];

		if ( ! ( $post = $this->get_current_post() ) ) {
			$this->invalidate( 'amp' );
			return [];
		}

		$i = strtotime( $post->post_date );

		return [
			'datePublished' => \esc_attr( date( 'c', $i ) ),
		];
	}

	/**
	 * Returns the Article Modified Date.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 : 1. Now also outputs on non-AMP.
	 *
	 * @requiredSchema Never
	 * @ignoredSchema nonAMP
	 * @return array The Article's Published Date
	 */
	private function get_article_modified_date() {

		if ( ! $this->is_json_valid() )
			return [];

		if ( ! ( $post = $this->get_current_post() ) )
			return [];

		$i = strtotime( $post->post_modified );

		return [
			'dateModified' => \esc_attr( date( 'c', $i ) ),
		];
	}

	/**
	 * Returns the Article Author.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 : 1. Now also outputs on non-AMP.
	 *                2. Now only invalidates AMP when something's wrong.
	 *
	 * @requiredSchema AMP
	 * @ignoredSchema nonAMP
	 * @return array The Article's Author
	 */
	private function get_article_author() {

		if ( ! $this->is_json_valid() )
			return [];

		if ( ! $post = $this->get_current_post() ) {
			$this->invalidate( 'amp' );
			return [];
		}

		$author = \get_userdata( $post->post_author );
		$name   = isset( $author->display_name ) ? $author->display_name : '';

		if ( ! $name ) {
			$this->invalidate( 'amp' );
			return [];
		}

		return [
			'author' => [
				'@type' => 'Person',
				'name'  => \esc_attr( $name ),
			],
		];
	}

	/**
	 * Returns the Article Publisher and logo.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 : 1. Now also outputs on non-AMP.
	 *                2. Now only invalidates AMP when something's wrong.
	 * @since 1.1.0 : Now fetches TSF 3.0 logo ID.
	 *
	 * @requiredSchema AMP
	 * @ignoredSchema nonAMP
	 * @return array The Article's Publisher
	 */
	private function get_article_publisher() {

		if ( ! $this->is_json_valid() )
			return [];

		$tsf = \the_seo_framework();

		/**
		 * Applies filters the_seo_framework_articles_name : string
		 * @since 1.0.0
		 */
		$name = (string) \apply_filters( 'the_seo_framework_articles_name', $tsf->get_option( 'knowledge_name' ) ) ?: $tsf->get_blogname();

		$_default_img_id = (int) $tsf->get_option( 'knowledge_logo_id' ) ?: \get_option( 'site_icon' );
		/**
		 * Applies filters the_seo_framework_articles_logo_id : int
		 * @since 1.0.0
		 */
		$_img_id = (int) \apply_filters( 'the_seo_framework_articles_logo_id', 0 ) ?: $_default_img_id;

		if ( ! $_img_id ) {
			$this->invalidate( 'amp' );
			return [];
		}

		$this->register_logo_image_size();
		$resize = false;

		if ( $_default_img_id === $_img_id ) {
			$size   = $this->image_size_name;
			$resize = true;
		} else {
			$size = 'full';
		}

		$_src = \wp_get_attachment_image_src( $_img_id, $size );
		if ( $resize && isset( $_src[3] ) && ! $_src[3] ) {
			//= Add intermediate size, so $_src[3] will return true next time.
			if ( $this->make_amp_logo( $_img_id ) )
				$_src = \wp_get_attachment_image_src( $_img_id, $size );
		}

		if ( is_array( $_src ) && count( $_src ) >= 3 ) {
			$url = $_src[0];
			$w   = $_src[1];
			$h   = $_src[2];
		}

		if ( empty( $url ) ) {
			$this->invalidate( 'amp' );
			return [];
		}

		return [
			'publisher' => [
				'@type' => 'Organization',
				'name'  => \esc_attr( $name ),
				'logo'  => [
					'@type'  => 'ImageObject',
					'url'    => \esc_url( $url, [ 'http', 'https' ] ),
					'width'  => abs( filter_var( $w, FILTER_SANITIZE_NUMBER_INT ) ),
					'height' => abs( filter_var( $h, FILTER_SANITIZE_NUMBER_INT ) ),
				],
			],
		];
	}

	/**
	 * Returns the Article Description.
	 *
	 * @since 1.0.0
	 * @since 1.0.0-gamma-2: Changed excerpt length to 155, from 400.
	 * @since 1.0.1 : 1. Now also outputs on non-AMP.
	 *                2. Now takes description from cache.
	 * @since 1.3.0 Added TSF v3.1 compat.
	 *
	 * @requiredSchema Never
	 * @ignoredSchema nonAMP
	 * @return array The Article's Description
	 */
	private function get_article_description() {

		if ( ! $this->is_json_valid() )
			return [];

		$tsf = \the_seo_framework();

		if ( method_exists( $tsf, 'get_description' ) ) {
			$description = $tsf->get_description( $this->get_current_id() );
		} else {
			$description = $tsf->description_from_cache();
		}

		return [
			'description' => \esc_attr( $description ),
		];
	}

	/**
	 * Makes AMP logo from attachment ID.
	 *
	 * @NOTE: The image size must be registered first.
	 * @see $this->register_logo_image_size().
	 * @since 1.1.0
	 *
	 * @param int $attachment_id The attachment to make a logo from.
	 * @return bool True on success, false on failure.
	 */
	private function make_amp_logo( $attachment_id ) {

		$success = false;

		$size = \wp_get_additional_image_sizes()[ $this->image_size_name ];

		$_file = \get_attached_file( $attachment_id );
		$_resized_file = \image_make_intermediate_size( $_file, $size['width'], $size['height'], false );

		if ( $_resized_file ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$_data   = \wp_generate_attachment_metadata( $attachment_id, $_file );
			$success = (bool) \wp_update_attachment_metadata( $attachment_id, $_data );
		}

		return $success;
	}
}
