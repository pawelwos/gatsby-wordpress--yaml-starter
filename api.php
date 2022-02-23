<?php
global $params;
$context = Timber::get_context();
$context['params'] = $params;

// all WP post types are served in a single pages.yml file filtered by type field. We assign what post types should be included in YAML file here
$post_types = ['page', 'post'];

// we grab all the pages and post types
if ($params['action'] == 'pages') {
	$args['post_type'] = $post_types;
	$args['posts_per_page'] = -1;
	$args['post_status'] = 'publish';
	$args['orderby'] = 'menu_order';
	$args['order'] = 'ASC';

  $context['pages'] = new Timber\PostQuery($args);

	Timber::render( array( 'api/'.$params['action'].'.twig' ), $context );
	exit();
}
// we grab all the sections / blocks for assigned post types
if ($params['action'] == 'sections') {
	$args['post_type'] = $post_types;
	$args['posts_per_page'] = -1;
	$args['post_status'] = 'publish';
	$args['orderby'] = 'menu_order';
	$args['order'] = 'ASC';

  $context['pages'] = new Timber\PostQuery($args);

	Timber::render( array( 'api/sections.twig' ), $context );
	exit();
}
header('Content-Type: application/json; charset=utf-8');
Timber::render( array( 'api/'.$params['action'].'.twig' ), $context );
?>