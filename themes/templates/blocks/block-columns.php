<?php
/**
 * This is the template that renders a number of columns with an image, title and content area
 *
 */
$padding = get_field('padding');
?>
<?php if( have_rows('columns') ) : ?>
	<section class="block-container columns-container <?= ( $padding ? 'padding-'. $padding : 'padding-top-bottom'); ?>">
		<div class="container-fluid opt-container-fluid">
			<div class="row">
				<?php while( have_rows('columns') ) : the_row(); ?>
					<?php $col_title 		= get_sub_field('col_title'); ?>
					<?php $col_img 			= get_sub_field('col_image'); ?>
					<?php $col_content 	= get_sub_field('col_content'); ?>
					<div class="col-12 col-md col-container <?= ( $block['align'] ? 'text-'. $block['align'] : false ); ?>">
						<?= ( $col_img ? '<img src="'. $col_img['sizes'][''] .'" title="'. $col_img['title'] .'" alt="'. $col_img['alt'] .'" class="col-img" />' : false ); ?>
						<?= ( $col_title ? '<h4 class="col-title">'. $col_title .'</h4>' : false ); ?>
						<?= ( $col_content ? '<div class="col-content">'. $col_content .'</div>' : false ); ?>
					</div> <!-- end .col-12 -->
				<?php endwhile; ?>
			</div> <!-- end .row -->
		</div> <!-- end .container-fluid -->
	</section> <!-- end .content-block-container -->
<?php endif; ?>