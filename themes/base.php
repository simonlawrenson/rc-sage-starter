<?php
use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
use Roots\Sage\Extras;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'optimising-8.5.6'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('before_header');
      get_template_part('templates/header');
      do_action('after_header');
    ?>
    <div class="wrap" role="document">
      <?= ( is_singular('post') ? '<div class="container-fluid opt-container-fluid"><div class="content row">' : '<div class="content">' ); ?>
      <div class="content">
        <main class="main">
          <?php include Wrapper\template_path(); ?>
        </main><!-- /.main -->
        <?php if (Setup\display_sidebar()) : ?>
          <aside class="sidebar">
            <?php include Wrapper\sidebar_path(); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
        <?= ( is_singular('post') ? '</div></div>' : '</div>' ); ?>
      </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
      do_action('before_footer');
      get_template_part('templates/footer');
      do_action('after_footer');
      wp_footer();
    ?>
  </body>
</html>
