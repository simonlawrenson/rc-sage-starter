<?php
namespace Roots\Sage\Setup;
/*--------------------------------------------------------------------------------------
*
*		Taxonomy Class
*
*		@author 	Simon Lawrenson
*		@since 		01/06/2018
*
*
*		TABLE OF CONTENTS
*
*		1.0 Constructor
*		2.0 Taxonomy Labels
*		3.0 Taxonomy Arguments
*		4.0 Initiate Register Taxonomy Type
* 
*-------------------------------------------------------------------------------------*/

class Taxonomy {
	private $post_type;
	private $tax_singular;
	private $tax_plural;
	private $tax_slug;
	private $tax_hierarchical;
	private $cpt_args;

	/*--------------------------------------------------------------------------------------
	*
	*		1.0 Constructor
	* 
	*-------------------------------------------------------------------------------------*/

	public function __construct($post_type, $tax, $singular, $plural, $slug, $hierarchical, $args = [] ) {
	
			$this->post_type 				= $post_type;
			$this->tax 							= $tax;
			$this->tax_singular 		= $singular;
			$this->tax_plural 			= $plural;
			$this->tax_slug 				= $slug;
			$this->tax_hierarchical = (boolean)$hierarchical;
			$this->tax_args 				= (array)$args;
			$this->arguments 				= $this->args();
			
			add_action('init', array($this, 'register' ), 10);
	}
	
	/*--------------------------------------------------------------------------------------
	*
	*		2.0 Taxonomy Labels
	* 
	*-------------------------------------------------------------------------------------*/

	function labels() {
		
		$this->labels = [
        'name'              => _x( ucwords($this->tax_plural), 'taxonomy general name', 'optimising-8.5.6' ),
        'singular_name'     => _x( ucwords($this->tax_singular), 'taxonomy singular name', 'optimising-8.5.6' ),
        'menu_name'         => __( ucwords($this->tax_singular), 'optimising-8.5.6' ),
        'all_items'         => __( 'All '. ucwords($this->tax_plural), 'optimising-8.5.6' ),
        'edit_item'         => __( 'Edit '. ucwords($this->tax_singular), 'optimising-8.5.6' ),
        'view_item'         => __( 'View '. ucwords($this->tax_singular), 'optimising-8.5.6' ),
        'update_item'       => __( 'Update '. ucwords($this->tax_singular), 'optimising-8.5.6' ),
        'add_new_item'      => __( 'Add New '. ucwords($this->tax_singular), 'optimising-8.5.6' ),
        'new_item_name'     => __( 'New '. ucwords($this->tax_singular) .' Name', 'optimising-8.5.6' ),
        'parent_item_colon' => __( 'Parent '. ucwords($this->tax_singular) .':', 'optimising-8.5.6' ),
        'search_items'      => __( 'Search '. ucwords($this->tax_plural), 'optimising-8.5.6' ),
        'not_found'         => __( 'No '. ucwords($this->tax_plural) .' found', 'optimising-8.5.6' ),
    ];
		if( $this->tax_hierarchical = true ) {
    	$this->labels['parent_item'] = __( 'Parent '. ucwords($this->tax_singular) .':', 'optimising-8.5.6' ); /* hierarchical */
    } else {
    	$this->labels['popular_items'] = __( 'Popular '. ucwords($this->tax_plural) .':', 'optimising-8.5.6' );  /* non-hierarchical */
    	$this->labels['separate_items_with_commas'] = __( 'Separate '. ucwords($this->tax_plural) .' with commas', 'optimising-8.5.6' );  /* non-hierarchical */
    	$this->labels['add_or_remove_items'] = __( 'Add or remove '. ucwords($this->tax_plural), 'optimising-8.5.6' );  /* non-hierarchical */
    	$this->labels['choose_from_most_used'] = __( 'Choose from the most used '. ucwords($this->tax_plural), 'optimising-8.5.6' );  /* non-hierarchical */
    }				
    return $this->labels;
		
	}
	
	/*--------------------------------------------------------------------------------------
	*
	*		3.0 Taxonomy Arguments
	* 
	*-------------------------------------------------------------------------------------*/

	public function args() {
		$args = [
			'labels'            => $this->labels(),
      'show_ui'           => true,
      'show_in_menu'      => true,
      'show_in_nav_menus' => true,
      'hierarchical'      => ( $this->tax_hierarchical ? true : false ), /* hierarchical */
      'rewrite'      			=> ( $this->tax_slug ? ['slug' => $this->tax_slug, 'with_front' => false] : false ),
		];
			
		$this->args = array_merge($args, $this->tax_args);
		return $this->args;
	
	}
	
	/*--------------------------------------------------------------------------------------
	*
	*		4.0 Register Taxonomy 
	* 
	*-------------------------------------------------------------------------------------*/

	function register() {

			register_taxonomy($this->tax, $this->post_type, $this->arguments);
	
	}
}