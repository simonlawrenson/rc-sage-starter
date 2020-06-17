<?php
namespace Roots\Sage\Setup;
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',         // Scripts and stylesheets
  'lib/extras.php',         // Custom functions
  'lib/class.PostType.php', // Theme class - PostType
  'lib/class.Taxonomy.php', // Theme class - Taxonomy
  'lib/class.Carousel.php', // Theme class - Carousel
  'lib/setup.php',          // Theme setup
  'lib/titles.php',         // Page titles
  'lib/wrapper.php',        // Theme wrapper class
  'lib/class-wp-bootstrap-navwalker.php', // Walker nav for Bootstrap 4
  'lib/customizer.php'      // Theme customizer
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'optimising-8.5.6'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);