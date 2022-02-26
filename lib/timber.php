<?php

// Add Timber
if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}
Timber::$dirname = array('twig', 'views');

add_filter( 'timber/context', 'add_to_context' );

function add_to_context( $context ) {
	$args = array(
	    'depth' => 2,
	);
    $context['menu'] = new \Timber\Menu( 'header-menu', $args );

    return $context;
}