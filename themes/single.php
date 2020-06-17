<?php if( !is_singular('post') ) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/page', 'header'); ?>
		<?php get_template_part('templates/content-single', get_post_type()); ?>
		<?php get_template_part('templates/content', 'page'); ?>
	<?php endwhile; ?>
<?php else : ?>
		<?php get_template_part('templates/content-single', get_post_type()); ?>
<?php endif; ?>

