<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based picture gallery                                  |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2008-2011 Piwigo Team                  http://piwigo.org |
// | Copyright(C) 2003-2008 PhpWebGallery Team    http://phpwebgallery.net |
// | Copyright(C) 2002-2003 Pierrick LE GALL   http://le-gall.net/pierrick |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

if( !defined("PHPWG_ROOT_PATH") )
{
  die ("Hacking attempt!");
}

include_once(PHPWG_ROOT_PATH.'admin/include/functions.php');

load_language('plugin.lang', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');

define('SHOWCASE_SUBSCRIBE_BASE_URL', get_root_url().'admin.php?page=plugin-showcase_subscribe');

if (!isset($conf['showcase_subscribe_url']))
{
  $conf['showcase_subscribe_url'] = 'http://piwigo.org/showcase';
}

// +-----------------------------------------------------------------------+
// | Check Access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+

check_status(ACCESS_ADMINISTRATOR);

// +-----------------------------------------------------------------------+
// | process subscription                                                  |
// +-----------------------------------------------------------------------+

// searching for the date_creation
$query = '
SELECT registration_date
  FROM '.USER_INFOS_TABLE.'
  WHERE user_id = 1
;';
list($date_creation) = pwg_db_fetch_row(pwg_query($query));
list($year) = explode('-', $date_creation);
if ($year == 0)
{
  $query = '
SELECT MIN(date_available)
  FROM '.IMAGES_TABLE.'
;';
  list($date_creation) = pwg_db_fetch_row(pwg_query($query));
}

if (isset($_POST['submit_subscribe']))
{
  $url = $conf['showcase_subscribe_url'].'/ws.php';
  
  $get_data = array(
    'format' => 'php',
    );
  
  $post_data = array(
    'method' => 'pwg.showcase.subscribe',
    'url' => get_absolute_root_url(),
    'date_creation' => $date_creation,
    'name' => stripslashes($_POST['title']),
    'comment' => stripslashes($_POST['description']),
    'tags' => stripslashes($_POST['tags']),
    'author' => stripslashes($_POST['author']),
    'email' => $_POST['email'],
    );

  if (fetchRemote($url, $result, $get_data, $post_data))
  {
    $result = @unserialize($result);
    // echo '<pre>'; print_r($result); echo '</pre>';
  }
  else
  {
    echo 'fetchRemote has failed<br>';
  }
}

// +-----------------------------------------------------------------------+
// | template init                                                         |
// +-----------------------------------------------------------------------+

$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__).'/admin.tpl'
    )
  );

// +-----------------------------------------------------------------------+
// | subscription state                                                    |
// +-----------------------------------------------------------------------+

$url = $conf['showcase_subscribe_url'].'/ws.php';

$get_data = array(
  'format' => 'php',
  );
  
$post_data = array(
  'method' => 'pwg.showcase.exists',
  'url' => get_absolute_root_url(),
  );

if (fetchRemote($url, $result, $get_data, $post_data))
{
  $exists_result = @unserialize($result);
  $subscription_status = $exists_result['result']['status'];
}

if (!isset($subscription_status))
{
  $subscription_status = 'connectionFailure';

  array_push(
    $page['errors'],
    sprintf(
      l10n('An error has occured, no connection to %s'),
      $conf['showcase_subscribe_url']
      )
    );
}

if ('pending' == $subscription_status)
{
  array_push(
    $page['infos'],
    l10n('Your subscription is currently pending, if you have provided an email, you will be notified as soon as your gallery is registered')
    );
}

if ('registered' == $subscription_status)
{
  array_push(
    $page['infos'],
    sprintf(
      l10n('Your gallery is already registered in Piwigo Showcase, <a href="%s">see it →</a>'),
      $exists_result['result']['picture_url']
      )
    );
}


// +-----------------------------------------------------------------------+
// | prepare data for template                                             |
// +-----------------------------------------------------------------------+

$template->assign(
  array(
    'CURRENT_STATUS' => $subscription_status,
    'URL' => get_absolute_root_url(),
    'DATE_CREATION' => format_date($date_creation),
    'EMAIL' => get_webmaster_mail_address(),
    'TITLE' => strip_tags($conf['gallery_title']),
    'TAGS' => '',
    'DESCRIPTION' => '',
    )
  );

// +-----------------------------------------------------------------------+
// | sending html code                                                     |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>