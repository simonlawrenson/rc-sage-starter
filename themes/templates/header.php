<?php use Roots\Sage\Extras; ?>
<header class="banner sticky-top" role="banner">
  <div class="container-fluid opt-container-fluid">
    <nav class="nav-primary navbar navbar-expand-lg" role="navigation">
      <?php $logo = get_field('site_logo', 'options'); ?>
      <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>" title="<?= get_bloginfo('name'); ?>">
        <?php if( $logo ) : ?>
          <img src="<?= $logo['sizes']['site-logo']; ?>" title="<?= get_bloginfo('name'); ?>" alt="<?= get_bloginfo('name').' Logo'; ?>" />
        <?php else : ?>
          <?php bloginfo('name'); ?>
        <?php endif; ?>
      </a>
      <?php if( has_nav_menu('primary_navigation') ) : ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        wp_nav_menu([
          'theme_location'  => 'primary_navigation',
          'container'       => 'div',
          'container_id'    => 'navbarNav',
          'container_class' => 'collapse navbar-collapse',
          'menu_class'      => 'nav navbar-nav nav-justified',
          'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
          'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
          'walker'          => new WP_Bootstrap_Navwalker(),
        ]);
        ?>
      <?php endif; ?>
      
      <?php $phone = get_field('ct_tel', 'options'); ?>
      <?php if( $phone ) : ?>
        <a href="tel:<?= Extras\clean_phone($phone); ?>" title="Click to Call" class="btn-c2c">
          <span class="click-cta">Call </span>
          <span class="click-icon"><i class="fas fa-phone"></i></span>
          <span class="click-phone"><?= $phone; ?></span>
        </a>
      <?php endif; ?>

    </nav>
  </div> <!-- end .opt-container-fluid -->
</header>