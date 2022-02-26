<?php
// Setup Timber Routes for API calls

Routes::map('api/:action/:post_id?', function($params){
  $query = null;
  Routes::load('api.php', $params, $query, 200);
});
