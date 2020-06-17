<?php
namespace Roots\Sage\Setup;
/*--------------------------------------------------------------------------------------
*
*		Post Type Class
*
*		@author 	Simon Lawrenson
*		@since 		01/06/2018
*
*
*		TABLE OF CONTENTS
*
*		1.0 Constructor
*		2.0 Post Type Labels
*		3.0 Post Type Arguments
*		4.0 Initiate Register Post Type
* 
*-------------------------------------------------------------------------------------*/

class PostType {
	private $cpt_type;
	private $cpt_singular;
	private $cpt_plural;
	private $cpt_slug;
	private $cpt_hierarchical;
	private $cpt_args;


	/*--------------------------------------------------------------------------------------
		*
		*		1.0 Constructor
		* 
		*-------------------------------------------------------------------------------------*/

	public function __construct( $type, $singular, $plural, $slug, $hierarchical, $args = [] ) {
		$this->cpt_type 				= $type;
		$this->cpt_singular 		= $singular;
		$this->cpt_plural 			= $plural;
		$this->cpt_slug 				= $slug;
		$this->cpt_hierarchical = (boolean)$hierarchical;
		$this->cpt_args 				= (array)$args;
		$this->arguments 				= $this->args();

		add_action('init', array($this,'register'), 10); //add content type
	}

	/*--------------------------------------------------------------------------------------
		*
		*		2.0 Post Type Labels
		* 
		*-------------------------------------------------------------------------------------*/
	public function labels() {
		$this->labels = [
			'name'              => __( ucwords($this->cpt_plural), 'Post Type General Name', 'optimising-8.5.6' ),
	    'singular_name'     => __( ucwords($this->cpt_singular), 'Post Type Singular Name', 'optimising-8.5.6' ),
	    'add_new'           => __( 'Add New', 'optimising-8.5.6' ),
	    'add_new_item'      => __( 'Add New '. ucwords($this->cpt_plural), 'optimising-8.5.6' ),
	    'edit_item'         => __( 'Edit '. ucwords($this->cpt_singular), 'optimising-8.5.6' ),
	    'new_item'          => __( 'New '. ucwords($this->cpt_singular), 'optimising-8.5.6' ),
	    'view_item'         => __( 'View '. ucwords($this->cpt_singular), 'optimising-8.5.6' ),
	    'view_items'        => __( 'View '. ucwords($this->cpt_plural), 'optimising-8.5.6' ),
	    'search_items'      => __( 'Search '. ucwords($this->cpt_singular), 'optimising-8.5.6' ),
	    'not_found'         => __( 'Not found', 'optimising-8.5.6' ),
	    'not_found_in_trash'=> __( 'Not found in Trash', 'optimising-8.5.6' ),
	    'all_items'         => __( 'All '. ucwords($this->cpt_plural), 'optimising-8.5.6' ),
	    'archives'          => __( ucwords($this->cpt_plural), 'optimising-8.5.6' ),
	    'attributes'        => __( ucwords($this->cpt_singular) .' Attributes', 'optimising-8.5.6' ),
	    'insert_into_item'      => __( 'Insert into '. ucwords($this->cpt_singular), 'optimising-8.5.6' ),
	    'uploaded_to_this_item' => __( 'Uploaded to this '. ucwords($this->cpt_singular), 'optimising-8.5.6' ),
	    'featured_image'        => __( 'Featured Image', 'optimising-8.5.6' ),
	    'set_featured_image'    => __( 'Set featured image', 'optimising-8.5.6' ),
	    'remove_featured_image' => __( 'Remove featured image', 'optimising-8.5.6' ),
	    'use_featured_image'    => __( 'Use as featured image', 'optimising-8.5.6' ),
	    'menu_name'             => __( ucwords($this->cpt_plural), 'optimising-8.5.6' ),
	    'filter_items_list'     => __( 'Filter '. ucwords($this->cpt_plural) .' list', 'optimising-8.5.6' ),
	    'items_list_navigation' => __( ucwords($this->cpt_plural) .' list navigation', 'optimising-8.5.6' ),
	    'items_list'            => __( ucwords($this->cpt_plural) .' list', 'optimising-8.5.6' ),
	    'name_admin_bar'        => __( ucwords($this->cpt_singular), 'optimising-8.5.6' ),
		];
		if( $this->cpt_hierarchical = true ) {
    	$this->labels['parent_item_colon'] = [__( 'Parent '. ucwords($this->cpt_plural) .':', 'optimising-8.5.6' )];
    }
		return $this->labels;
	}
	

	/*--------------------------------------------------------------------------------------
		*
		*		3.0 Post Type Arguments
		* 
		*-------------------------------------------------------------------------------------*/
	public function args() {
		// Set default Custom Post Type arguments
		$args = [
			'labels'              => $this->labels(),
			'show_ui'             => true, 
			'show_in_menu'        => true, 
			'show_in_rest'        => true,
			'rewrite'      				=> ( $this->cpt_slug ? ['slug' => $this->cpt_slug, 'with_front' => false] : false ),
			'hierarchical'        => ( $this->cpt_hierarchical ? true : false ), /* hierarchical */
			'menu_position'       => 20,
		];
		
		$this->args = array_merge($args, $this->cpt_args);
		
		return $this->args;
	}


	/*--------------------------------------------------------------------------------------
		*
		*		4.0 Initiate Register Post Type
		* 
		*-------------------------------------------------------------------------------------*/

	public function register() {
		register_post_type($this->cpt_type, $this->arguments);
	}
}