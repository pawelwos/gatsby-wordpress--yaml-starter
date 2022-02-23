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

// Get all Posts

function get_blog() {
	$args['post_type'] = 'post';
	$args['posts_per_page'] = -1;
	$args['post_status'] = 'publish';
	$args['orderby'] = 'menu_order';
	$args['order'] = 'ASC';

  return new Timber\PostQuery($args);
}

// Setup Timber Routes for API calls

Routes::map('api/:action/:post_id?', function($params){
  $query = null;
  Routes::load('api.php', $params, $query, 200);
});


// special function to convert internal links into gatsby Link and all inline images into GatsbyImage

$count = 0;

function format_for_yaml($content) {
  global $count;
  $count = 0;
  $site_url = site_url();
  $site_url = preg_replace('/\//', "\/", $site_url);
  // replace A tag with JSX Link
  $content = preg_replace('/<a/', '<Link', $content);
  $content = preg_replace('/<\/a>/', '</Link>', $content);
  $content = preg_replace('/https?/', "https", $content);
  $content = preg_replace('/href="('.$site_url.')?(.+?)"/', 'to={"${2}"}', $content);
  $content = preg_replace('/class="(.+?)"/', 'className={"${1}"}', $content);
  // replace img with static Image
  $content = preg_replace_callback('/<img.+?\/>/', 'images_count', $content);

  // remove new lines tabs and so on
  $content = preg_replace('/[\n\r\t]/', '', $content);
  return json_encode($content);
}

// sattic iamge replacement callback

function images_count($matches) {
  global $count;
  return '<Image image={image' . $count++.'} className={"w-full relative"} />';
}

function get_inline_images($content) {
  preg_match_all('/src="(.+?)"/', $content, $results);
  return $results;
}

// we add preview functionality here, we generate previe link below
// !IMPORTANT
// need to add define( 'PREVIEW_URL', 'http://your-domain.com'); in you wp-config.php

add_filter('preview_post_link', function ($link) {

  $action = "security";
  $nonce = wp_create_nonce($action);

return PREVIEW_URL.'/preview?id='.get_the_ID().'&_wpnonce='.$nonce;
});


// AJAX preview action
add_action( 'wp_ajax_my_preview', 'headless_preview' );
add_action( 'wp_ajax_nopriv_my_preview', 'headless_preview' );

function headless_preview(){
  if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'security'  )) {
      die ( 'WP nonce error!');
  } else {
      $args['post_parent'] = intval($_POST['id']); 
      $args['posts_per_page'] = 1;
      $args['post_type'] = 'revision';
      $args['post_status'] = 'any';
      $args['order'] = 'DESC';
  
      $pages = new Timber\PostQuery($args);
      $context['pages'] = $pages;
      $context['id'] = intval($_POST['id']);
      Timber::render( array( 'api/sections.twig' ), $context );
      die();
  }
}

// some CORS setup
add_action('init', 'handle_preflight');
function handle_preflight() {
  $origin = get_http_origin();
  if ($origin === PREVIEW_URL) {
      header("Access-Control-Allow-Origin: ".PREVIEW_URL);
      //header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
      header("Access-Control-Allow-Credentials: true");
      header('Access-Control-Allow-Headers: Origin, X-Requested-With, X-WP-Nonce, Content-Type, Accept, Authorization');
      if ('OPTIONS' == $_SERVER['REQUEST_METHOD']) {
          status_header(200);
          exit();
      }
  }
}

// and less important stuff

// Adds client custom colors to WYSIWYG editor and ACF color picker.

function change_acf_color_picker() {

  echo "<script>
  (function($){
    try {
		acf.add_filter('color_picker_args', function( args, field ){
			
			// do something to args
			args.palettes = ['#663399', '#333', '#35051e', '#184235', '#818181', '#c0c0bf']
			
			
			// return
			return args;
					
		});
    }
    catch (e) {}
  })(jQuery)
  </script>";
}

add_action( 'acf/input/admin_head', 'change_acf_color_picker' );

function my_mce4_options($init) {

    $custom_colours = '
        "663399", "Primary",
        "333", "Secondary",
        "35051e", "Color 3",
        "184235", "Color 4",
        "818181", "Color 5",
        "c0c0bf", "Color 6",
        "000000", "Color 7",
        "ffffff", "Color 8",
    ';

    // build colour grid default+custom colors
    $init['textcolor_map'] = '['.$custom_colours.']';

    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
    $init['textcolor_rows'] = 1;

    return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');