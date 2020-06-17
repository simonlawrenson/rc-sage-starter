<?php use Roots\Sage\Assets; ?>
<article <?php post_class(); ?>>
	<?php if( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail('thumbnail', ['class' => 'blog-archive-featured']); ?>
	<?php else : ?>
		<img src="<?= Assets\asset_path('images/placeholder-blog-archive.jpg'); ?>" title="" alt="logo for blog" class="no-featured-img blog-archive-featured" />
	<?php endif; ?>
  <header>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
</article>
