<?php
/*
Plugin Name: Showcase Register
Version: auto
Description: Register on Piwigo Showcase (piwigo.org/showcase)
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=
Author: plg
Author URI: http://piwigo.wordpress.com
*/

if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

/* Plugin admin */
add_event_handler('get_admin_plugin_menu_links', 'showcase_subscribe_menu');
function showcase_subscribe_menu($menu)
{
  if (preg_match('/^2.1/', PHPWG_VERSION))
  {
    $url = get_admin_plugin_menu_link(dirname(__FILE__).'/admin.php');
  }
  else
  {
    $url = get_root_url().'admin.php?page=plugin-showcase_subscribe';
  }
  
  array_push(
    $menu,
    array(
      'NAME' => 'Showcase',
      'URL'  => $url,
      )
    );

  return $menu;
}
?>
