<?php

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

// grab inline images from inline content
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