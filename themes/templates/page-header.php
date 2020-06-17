<?php
use Roots\Sage\Titles;
use Roots\Sage\Extras;

if( is_archive() ) :
	$current_archive = get_queried_object();
	$current_page 	 = get_page_by_path($current_archive->rewrite['slug']);
	$acf						 = $current_page->ID;
else :
	$acf = get_the_ID();
endif;
?>

<?php $header_img = get_field('header_img', $acf); ?>
<?php if( get_field('enable_header', $acf) && $header_img ) : ?>

	<?php $header_title 			= get_field('header_title', $acf); ?>
	<?php $header_link 				= get_field('header_link', $acf); ?>
		
	<section class="header-img-container" role="header">
		<img <?= Extras\responsive_image_src($header_img['id'], 'header-image', '100%'); ?> title="<?= $header_img['title']; ?>" alt="<?= $header_img['alt']; ?>" class="header-img"/>
		<div class="header-content-overlay">
			<div class="container-fluid opt-container-fluid">
				<div class="row">
					<div class="col-12">
						<h1 class="header-title"><?= ( $header_title ? $header_title : Titles\title() ); ?></h1>
						<?= ( $header_content ? '<div class="header-content">' . $header_content .'</div>' : false ); ?>
						<?= ( $header_link ? '<a href="'. $header_link['url'] .'" tilte="'. $header_link['title'] .'" class="btn btn-header">'. $header_link['title'] .'</a>' : false ); ?>
					</div> <!-- end .col-12 -->
				</div> <!-- end .row -->
			</div> <!-- end .container-fluid -->
		</div> <!-- end .header-content-overlay -->
	</section> <!-- end .header-img-container -->
<?php elseif( (!get_field('enable_header') || !$header_img) && !get_field('content_title') ) : ?>
	<section class="header-container" role="header">
		<div class="container-fluid opt-container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="page-header">
				  	<h1><?= Titles\title(); ?></h1>
					</div>
				</div> <!-- end .col-12 -->
			</div> <!-- end .row -->
		</div> <!-- end .container-fluid -->
	</section> <!-- end .header-container -->
<?php endif; ?>