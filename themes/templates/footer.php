<?php use Roots\Sage\Extras; ?>
<footer class="content-info">
  <div class="container-fluid opt-container-fluid">
  	<div class="row">
  		<div class="col-12 col-md-4">
    		<?php $social = Extras\get_social(); ?>
	      <?php if( $social ) : ?>
	        <div class="header-social">
	          <?php foreach( $social as $platform => $links ) : ?>
	            <?= '<a href="'. $links['link'] .'" title="'. $platform .'" target="_blank" class="header-social"><i class="fab fa-'. $links['icon'] .'"></i></a>'; ?>
	          <?php endforeach; ?>
	        </div> <!-- end .header-phone -->
	      <?php endif; ?>
  		</div> <!-- end .col-12 -->
  		<div class="col-12 col-md-4">
    		<?php dynamic_sidebar('sidebar-footer'); ?>
  		</div> <!-- end .col-12 -->
  	</div> <!-- end .row -->
		<div class="row">
  		<div class="col-12 copyright">
	  		<div class="d-sm-flex justify-content-center">
	  			<p>&copy; <?= date('Y') .' '. get_bloginfo('name'); ?> P/L. All rights reserved.</p>
	  			<?php
	        // wp_nav_menu([
	        //   'theme_location'  => 'footer_navigation',
	        //   'container'       => '',
	        //   'menu_class'      => 'nav footer-nav',
	        //   'walker'          => new wp_bootstrap_navwalker(),
	        // ]);
	        ?>
	  		</div>
  		</div> <!-- end .copyright -->
  	</div> <!-- end .row -->
  </div> <!-- end .container-fluid -->
</footer>
