<?php
/**
 * This is the template that renders a list of CPT within columns
 *
 */
?>
<?php use Roots\Sage\Assets; ?>
<?php $posts = get_field('selected_posts'); ?>

<?php if( $posts ) : ?>
	<?php $padding 				= get_field('padding'); ?>
	<?php $list_title 		= get_field('list_title'); ?>
	<?php $list_link 			= get_field('list_link'); ?>

		<section class="block-container list-container <?= ( $padding ? 'padding-'. $padding : 'padding-top-bottom'); ?>">
		<div class="container-fluid opt-container-fluid">
			<div class="row">
				<?= ( $list_title ? '<div class="col-12"><h3 class="block-title">'. $list_title .'</h3></div>' : false ); ?>
				<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<div class="col-12 col-md">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php if( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail('archive-list', ['class' => 'list-img']); ?>
							<?php else : ?>
								<img src="<?= Assets\asset_path('images/post-thumbnail.jpg'); ?>" title="" alt="placeholder logo image" class="archive-list-img" />
							<?php endif; ?>
						</a>
						<h5 class="list-title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</h5>
						<div class="list-content"><?php the_excerpt(); ?></div>
					</div> <!-- end .col-12 -->
				<?php endforeach; wp_reset_postdata();  ?>
				<?= ( $list_link ? '<div class="col-12"><a href="'. $list_link['url'] .'" title="'. $list_link['title'] .'" class="btn">'. $list_link['title'] .'</a></div>' : false ); ?>
			</div> <!-- end .row -->
		</div> <!-- end .container-fluid -->
	</div> <!-- end .flex-container -->
<?php endif; ?>