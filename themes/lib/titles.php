<?php

namespace Roots\Sage\Titles;

/**
 * Page titles
 */
function title() {
  if (is_home()) {
    if (get_option('page_for_posts', true)) {
      return get_the_title(get_option('page_for_posts', true));
    } else {
      return __('Latest Posts', 'optimising-8.5.6');
    }
  // } elseif (is_tax() ){
    // return single_term_title( '', false );
  // } elseif (is_category() ){
    // return single_cat_title( '', false );
  } elseif (is_archive()) {
    // return post_type_archive_title( '', false );
    return get_the_archive_title();
  } elseif (is_search()) {
    return sprintf(__('Search Results for %s', 'optimising-8.5.6'), get_search_query());
  } elseif (is_404()) {
    return __('Not Found', 'optimising-8.5.6');
  } else {
    return get_the_title();
  }
}
