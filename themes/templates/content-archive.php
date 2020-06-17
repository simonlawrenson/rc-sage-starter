<?php
$current_archive = get_queried_object();
$current_page 	 = get_page_by_path($current_archive->rewrite['slug']);
$content      	 = apply_filters( 'the_content', $current_page->post_content );
echo $content;
?>