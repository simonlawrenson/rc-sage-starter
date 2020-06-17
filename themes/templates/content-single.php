<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  </article>
  <div class="row nav-post-links">
		<div class="col-12 col-sm text-left">
			<?= ( get_option('page_for_posts') ? '<a href="'. get_the_permalink(get_option('page_for_posts')) .'" title="Back to Posts" class="btn"><i class="fas fa-angle-left"></i> Back to Posts</a>' : false ); ?>
		</div> <!-- end .col-12 -->
	</div><!-- end .row -->
<?php endwhile; ?>