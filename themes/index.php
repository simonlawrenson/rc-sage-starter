<?php get_template_part('templates/page', 'header'); ?>
<div class="posts-container">
	<div class="container-fluid opt-container-fluid">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
  			<?php get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="row">
			  <div class="col-12">
			  	<div class="alert alert-warning">
			    	<?php _e('Sorry, no results were found.', 'premiercranes'); ?>
					</div> <!-- end .alert -->
				</div> <!-- end .col-12 -->
			</div> <!-- end .row -->
		<?php endif; ?>
		<?php $prev_page = get_previous_posts_link('<i class="fas fa-angle-left"></i> Previous'); ?>
		<?php $next_page = get_next_posts_link('Next <i class="fas fa-angle-right"></i>'); ?>
		<?php if( $prev_page || $next_page ) : ?>
			<div class="row nav-post-links">
				<?= ( $prev_page ? '<div class="col-12 col-sm text-left">'. $prev_page .'</div>' : false ); ?>
				<?= ( $next_page ? '<div class="col-12 col-sm text-right">'. $next_page .'</div>' : false ); ?>
			</div> <!-- end .row -->
		<?php endif; ?>
	</div> <!-- end .container-fluid -->
</div> <!-- end .posts-container -->