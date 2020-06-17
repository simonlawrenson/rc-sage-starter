<?php
/**
 * This is the template that renders a full width content block.
 *
 */
?>
<?php use Roots\Sage\Extras; ?>
<?php $padding 			= get_field('padding'); ?>
<?php $c_title 			= get_field('c_title'); ?>
<?php $c_content 		= get_field('c_content'); ?>
<?php $c_link 			= get_field('c_link'); ?>

<?php if( $c_title || $c_content  || $c_link ) : ?>
	<section class="block-container content-block-container <?= ( $padding ? 'padding-'. $padding : 'padding-top-bottom'); ?>">
		<div class="container-fluid opt-container-fluid">
			<div class="row">
				<div class="col-12 content-container <?= Extras\set_block_alignment($block['align']) ?>">
					<?= ( $c_title ? '<h3 class="h1 content-title">'. $c_title .'</h3>' : false ); ?>
					<?= ( $c_content ? '<div class="content-content">'. $c_content .'</div>' : false ); ?>
					<?= ( $c_link ? '<a href="'. $c_link['url'] .'" title="'. $c_link['title'] .'" class="btn content-link">'. $c_link['title'] .'</a>' : false ); ?>
				</div> <!-- end .col-12 -->
			</div> <!-- end .row -->
		</div> <!-- end .container-fluid -->
	</section> <!-- end .content-block-container -->
<?php endif; ?>