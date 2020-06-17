<?php
/**
 * This is the template that renders a custom gallery.
 *
 */
?>
<?php $padding 			= get_field('padding'); ?>
<?php $gallery 			= get_field('gallery'); ?>

<?php if( $gallery ) : $i = 1; ?>
	<section class="block-container gallery-container <?= ( $padding ? 'padding-'. $padding : 'padding-top-bottom'); ?>">
		<div class="container-fluid opt-container-fluid">
			<div class="row no-gutters align-items-start">
				<div class="col-12 d-md-flex gallery-slider-container">
					<div class="slick-gallery-container">
						<div class="slick-gallery">
	            <?php foreach( $gallery as $img ) : ?>
								<?= wp_get_attachment_image($img['id'], 'gallery', false, ['class' => 'slide-img gallery-img']); ?>
							<?php endforeach;?>
	        	</div> <!-- end .slick-gallery -->
						<div class="slick-gallery-arrows d-flex justify-content-end"></div>
        	</div> <!-- end .slick-gallery-container -->
					<div class="slick-nav-container">
						<div class="slick-gallery-nav">
	            <?php foreach( $gallery as $img ) : ?>
								<?= wp_get_attachment_image($img['id'], 'gallery-nav', false, ['class' => 'gallery-nav-img']); ?>
							<?php endforeach;?>
	        	</div> <!-- end .slick-gallery -->
						<div class="slick-gallery-nav-arrows"></div>
	        </div> <!-- end .slick-nav-container -->
        </div> <!-- end .col-12 -->
			</div> <!-- end .row -->
		</div> <!-- end .container-fluid -->
	</section> <!-- end .content-block-container -->
<?php endif; ?>