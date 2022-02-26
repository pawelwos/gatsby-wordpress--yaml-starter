<?php
// Get all Posts

function get_blog() {
	$args['post_type'] = 'post';
	$args['posts_per_page'] = -1;
	$args['post_status'] = 'publish';
	$args['orderby'] = 'menu_order';
	$args['order'] = 'ASC';

  return new Timber\PostQuery($args);
}
