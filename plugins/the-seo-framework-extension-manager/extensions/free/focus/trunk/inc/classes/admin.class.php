<?php
/**
 * @package TSF_Extension_Manager\Extension\Focus\Classes
 */
namespace TSF_Extension_Manager\Extension\Focus;

defined( 'ABSPATH' ) or die;

if ( \tsf_extension_manager()->_has_died() or false === ( \tsf_extension_manager()->_verify_instance( $_instance, $bits[1] ) or \tsf_extension_manager()->_maybe_die() ) )
	return;

/**
 * Focus extension for The SEO Framework
 * Copyright (C) 2018 Sybre Waaijer, CyberWire (https://cyberwire.nl/)
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
 * Class TSF_Extension_Manager\Extension\Focus\Admin
 *
 * @since 1.0.0
 * @uses TSF_Extension_Manager\Traits
 * @final
 */
final class Admin extends Core {
	use \TSF_Extension_Manager\Enclose_Stray_Private,
		\TSF_Extension_Manager\Construct_Master_Once_Interface;

	/**
	 * Constructor.
	 */
	private function construct() {
		$this->prepare_ajax();
		$this->prepare_inpostgui();
	}

	/**
	 * Prepares inpost AJAX callbacks.
	 *
	 * @since 1.0.0
	 */
	private function prepare_ajax() {
		\wp_doing_ajax() and Ajax::_init( $this );
	}

	/**
	 * Prepares inpost GUI.
	 *
	 * @since 1.0.0
	 */
	private function prepare_inpostgui() {

		//= Prepares InpostGUI's class for nonce checking.
		\TSF_Extension_Manager\InpostGUI::prepare();

		\add_action( 'tsfem_inpostgui_enqueue_scripts', [ $this, '_enqueue_inpost_scripts' ] );

		//= Called late because we need to access the meta object after current_screen.
		\add_action( 'the_seo_framework_pre_page_inpost_box', [ $this, '_prepare_inpost_views' ] );

		\add_action( 'tsfem_inpostgui_verified_nonce', [ $this, '_save_meta' ], 10, 3 );
	}

	/**
	 * Returns active focus elements.
	 *
	 * @since 1.0.0
	 *
	 * @return array The active focus elements.
	 */
	private function get_focus_elements() {
		/**
		 * Applies filters 'the_seo_framework_focus_elements'.
		 *
		 * When a selector can't be found in the DOM, it's skipped and cannot be appended
		 * or be dominating.
		 *
		 * When a selector is dominating, the order is considered. When the selector is
		 * the last dominating thing on the list, it's the only thing used for the scoring.
		 *
		 * When a selector is appending, and no dominating items are available, then
		 * it's considered as an addition for scoring.
		 *
		 * The querySelector fields must be visible for highlighting. When it's
		 * not visible, highlighting is ignored.
		 *
		 * Elements can also be added dynamically in JS, for Gutengerg block support.
		 * @see JavaScript tsfem_e_focus_inpost.updateFocusRegistry();
		 *
		 * The fields must be in order of importance when dominating.
		 * Apply this filter with a high $priority value to ensure domination.
		 * @see WordPress `add_filter()`
		 * @see PHP `array_push()`
		 * @see PHP `array_unshift()`
		 * @since 1.0.0
		 *
		 * @param array $elements : { 'type' => [
		 *    'querySelector' => string 'append|dominate'.
		 * }
		 */
		return \apply_filters_ref_array( 'the_seo_framework_focus_elements', [
			[
				'pageTitle' => [
					'#titlewrap > input' => 'append',
					// NOTE: Can't reliably fetch Gutenberg's from DOM.
				],
				'pageUrl' => [
					'#sample-permalink' => 'dominate',
					// NOTE: Can't reliably fetch Gutenberg's from DOM.
				],
				'pageContent' => [
					'#content' => 'append',
					// NOTE: Can't reliably fetch Gutenberg's from DOM.
				],
				'seoTitle' => [
					'#autodescription_title' => 'dominate', //= backwards compatibility.
					'#tsf-title-reference'   => 'dominate', //! TSF 3.0.4+
				],
				'seoDescription' => [
					'#autodescription_description' => 'dominate', //= backwards compatibility.
					'#tsf-description-reference'   => 'dominate', //! TSF 3.0.4+
				],
			],
		] );
	}

	/**
	 * Determines if the API supports the language.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if supported, false otherwise.
	 */
	private function is_language_supported() {
		return substr( \get_locale(), 0, 2 ) === 'en';
	}

	/**
	 * Enqueues in-post scripts, dependencies, colors, etc.
	 *
	 * @since 1.0.0
	 * @access private
	 * @uses \TSF_Extension_Manager\InpostGUI
	 * Callback via \TSF_Extension_Manager\InpostGUI
	 *
	 * @param string Static class name: \TSF_Extension_Manager\InpostGUI $inpostgui
	 */
	public function _enqueue_inpost_scripts( $inpostgui ) {
		$inpostgui::register_script( [
			'type' => 'js',
			'name' => 'tsfem-focus-inpost',
			'base' => TSFEM_E_FOCUS_DIR_URL,
			'ver'  => TSFEM_E_FOCUS_VERSION,
			'deps' => [ 'jquery', 'tsf', 'tsfem-inpost' ],
			'l10n' => [
				'name' => 'tsfem_e_focusInpostL10n',
				'data' => [
					'nonce'              => \current_user_can( 'edit_post', $GLOBALS['post']->ID ) ? \wp_create_nonce( 'tsfem-e-focus-inpost-nonce' ) : false,
					'focusElements'      => $this->get_focus_elements(),
					'defaultLexicalForm' => json_encode( $this->default_lexical_form ),
					'languageSupported'  => $this->is_language_supported(),
				],
			],
			'tmpl' => [
				'file' => $this->get_view_location( 'inpost/js-templates' ),
			],
		] );
		$inpostgui::register_script( [
			'type'   => 'css',
			'name'   => 'tsfem-focus-inpost',
			'base'   => TSFEM_E_FOCUS_DIR_URL,
			'ver'    => TSFEM_E_FOCUS_VERSION,
			'deps'   => [ 'tsf', 'tsfem-inpost' ],
			'inline' => [
				'.tsfem-e-focus-content-loader-bar' => [
					'background:{{$color_accent}}',
				],
				'.tsfem-e-focus-collapse-header:hover .tsfem-e-focus-arrow-item' => [
					'color:{{$color}}',
				],
			],
		] );
	}

	/**
	 * Prepares inpost options for the 'audit' tab.
	 *
	 * Defered because we need to access meta.
	 *
	 * @since 1.0.0
	 * @uses class \TSF_Extension_Manager\InpostGUI
	 * @uses trait \TSF_Extension_Manager\Extensions_Post_Meta_Cache
	 * @access private
	 */
	public function _prepare_inpost_views() {

		\TSF_Extension_Manager\InpostGUI::activate_tab( 'audit' );

		$post_meta = [
			'pm_index' => $this->pm_index,
			'post_id'  => \the_seo_framework()->get_the_real_ID(),
			'kw'       => [
				'label'        => [
					'title' => \__( 'Subject Analysis', 'the-seo-framework-extension-manager' ),
					'desc'  => \__( 'Set subjects and learn how you can improve their focus.', 'the-seo-framework-extension-manager' ),
					'link'  => 'https://theseoframework.com/extensions/focus/#usage',
				],
				//! Don't set default, it's already pre-populated.
				'values'       => $this->get_post_meta( 'kw', null ),
				'option_index' => 'kw',
			],
		];

		\TSF_Extension_Manager\InpostGUI::register_view(
			$this->get_view_location( 'inpost/inpost' ),
			[
				'post_meta'          => $post_meta,
				'defaults'           => $this->pm_defaults,
				'template_cb'        => [ $this, '_output_focus_template' ],
				'is_premium'         => \tsf_extension_manager()->is_premium_user(),
				'language_supported' => $this->is_language_supported(),
			],
			'audit'
		);
	}

	/**
	 * Saves or deletes post meta.
	 *
	 * @since 1.0.0
	 * @see \TSF_Extension_Manager\InpostGUI::_verify_nonce()
	 * @see action 'tsfem_inpostgui_verified_nonce'
	 *
	 * @param \WP_Post      $post              The post object.
	 * @param array|null    $data              The meta data.
	 * @param int (bitwise) $save_access_state The state the save is in.
	 */
	public function _save_meta( $post, $data, $save_access_state ) {

		if ( $save_access_state ^ 0b1111 )
			return;

		$this->process_meta( $post, $data );
	}

	/**
	 * Saves or deletes post meta on AJAX callbacks.
	 *
	 * Unused!
	 *
	 * @since 1.0.0
	 * @see \TSF_Extension_Manager\InpostGUI::_verify_nonce()
	 * @see action 'tsfem_inpostgui_verified_nonce'
	 *
	 * @param \WP_Post      $post              The post object.
	 * @param array|null    $data              The meta data.
	 * @param int (bitwise) $save_access_state The state the save is in.
	 */
	public function _wp_ajax_save_meta( $post, $data, $save_access_state ) {

		//= Nonce check failed. Show notice?
		if ( ! $save_access_state )
			return;

		//= If doing more than just AJAX, stop.
		if ( $save_access_state ^ 0b1111 ^ 0b0100 )
			return;

		$this->process_meta( $post, $data );
	}

	/**
	 * Processes post metdata after validation.
	 *
	 * @since 1.0.0
	 * @uses trait \TSF_Extension_Manager\Extensions_Post_Meta_Cache
	 *
	 * @param \WP_Post   $post The post object.
	 * @param array|null $data The meta data.
	 */
	private function process_meta( $post, $data ) {

		if ( empty( $data[ $this->pm_index ] ) )
			return;

		$this->set_extension_post_meta_id( $post->ID );

		$store = [];
		foreach ( $data[ $this->pm_index ] as $key => $value ) :
			switch ( $key ) {
				case 'kw':
					$store[ $key ] = $this->sanitize_keyword_data( (array) $value );
					break;

				default:
					break;
			}
			if ( is_null( $store[ $key ] ) ) unset( $store[ $key ] );
		endforeach;

		if ( empty( $store ) ) {
			$this->delete_post_meta_index();
		} else {
			foreach ( $store as $key => $value ) {
				$this->update_post_meta( $key, $value );
			}
		}
	}

	/**
	 * Sanitizes all keyword data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $values The keyword data.
	 * @return array|null The sanitized keyword data.
	 */
	private function sanitize_keyword_data( array $values ) {
		$output = [];
		foreach ( $values as $id => $items ) {
			//= Don't store when no keyword is set.
			if ( ! isset( $items['keyword'] ) || ! strlen( $items['keyword'] ) )
				continue;

			foreach ( (array) $items as $key => $value ) {
				$out = $this->sanitize_keyword_data_by_type( $key, $value );
				if ( isset( $out ) )
					$output[ $id ][ $key ] = $out;
			}
		}

		//= When all entries are emptied, clear meta data.
		if ( empty( $output ) )
			return null;

		//= Fills missing data to maintain consistency.
		foreach ( [ 0, 1, 2 ] as $k ) {
			if ( ! isset( $output[ $k ] ) ) {
				$output[ $k ] = $this->pm_defaults['kw'][ $k ];
			}
		}
		return $output ?: null;
	}

	/**
	 * Sanitizes keyword data by type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type  The keyword entry type.
	 * @param string $value The corresponding value.
	 * @return string|null The sanitized value.
	 */
	private function sanitize_keyword_data_by_type( $type, $value ) {
		switch ( $type ) :
			case 'keyword':
				$value = \sanitize_text_field( $value );
				break;

			case 'lexical_form':
			case 'definition_selection':
				$value = (string) \absint( $value );
				break;

			case 'lexical_data':
			case 'inflection_data':
			case 'synonym_data':
				//= Decode and encode the values. An empty array will be returned on failure.
				$value = json_decode(
					json_encode(
						json_decode( stripslashes( $value ) ) ?: [],
						JSON_UNESCAPED_UNICODE
					), true
				);
				break;

			case 'scores':
				if ( ! is_array( $value ) ) {
					$value = [];
				} else {
					foreach ( $value as $_t => $_v ) {
						//= Convert to float, have 2 f decimals, trim trailing zeros, trim trailing dots, convert to string.
						$value[ $_t ] = (string) ( rtrim( rtrim( sprintf( '%.2F', (float) $_v ), '0' ), '.' ) ?: 0 );
					}
				}
				break;

			case 'active_inflections':
			case 'active_synonyms':
				$patterns = [
					'/[^0-9,]+/', // Remove everything but "0-9,"
					'/(?=(,,)),|,[^0-9]?+$/', // Fix ",,,"->"," and remove trailing ","
				];
				$value = preg_replace( $patterns, '', $value );
				break;

			default:
				unset( $value );
				break;
		endswitch;

		return isset( $value ) ? $value : null;
	}

	/**
	 * Outputs focus template.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $args The focus template arguments.
	 */
	public function _output_focus_template( array $args ) {
		$this->get_view( 'inpost/focus-template', $args );
	}

	/**
	 * Outputs focus template.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $args The focus template arguments.
	 */
	private function output_score_template( array $args ) {
		$this->get_view( 'inpost/score-template', $args );
	}

	/**
	 * Fetches files based on input to reduce memory overhead.
	 * Passes on input vars.
	 *
	 * @since 1.0.0
	 *
	 * @param string $view The file name.
	 * @param array $args The arguments to be supplied within the file name.
	 *        Each array key is converted to a variable with its value attached.
	 */
	protected function get_view( $view, array $args = [] ) {

		foreach ( $args as $key => $val ) {
			$$key = $val;
		}

		include $this->get_view_location( $view );
	}

	/**
	 * Returns view location.
	 *
	 * @since 1.0.0
	 *
	 * @param string $view The relative file location and name without '.php'.
	 * @return string The view file location.
	 */
	private function get_view_location( $view ) {
		return TSFEM_E_FOCUS_DIR_PATH . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
	}
}
