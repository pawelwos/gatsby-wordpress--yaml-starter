<?php
add_action( 'admin_menu', 'stop_access_profile' );
function stop_access_profile() {
  if( ! current_user_can('administrator') ) { 
    remove_menu_page( 'profile.php' );
    remove_submenu_page( 'users.php', 'profile.php' );
  }
}