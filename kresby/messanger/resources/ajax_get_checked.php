<?PHP

require_once("./compatlib.php");

if (!file_exists("db_setup.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (dbs2)")); die();
} else { require_once("db_setup.php"); }

if (!file_exists("smtp_conf.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (smtpc)")); die();
} else { require_once("smtp_conf.php"); }



if (!$smtpconf['galleryhash']) {
  echo json_encode(array('status' => "err", 'text' => "Gallery ID unknown"));
} else {
  if ($dbconf['custom'] == 'false') {
    $postdata = http_build_query(
      array(
        'action' => "checkTableExists",
        'galleryhash' => $smtpconf['galleryhash']
      )
    );

    $opts = array('http' =>
      array(
        'method'  => 'POST',
        'header'  => array('Content-type: application/x-www-form-urlencoded', 'Custom-header: fonto'),
        'content' => $postdata,
        'timeout' => 60
      )
    );

    $context  = stream_context_create($opts);

    //if (!$result = @file_get_contents('http://anistudio/lr2/resources/lrdb.php', false, $context, -1, 40000)) {
    if (!$result = @file_get_contents('http://lr.fonto.pl/lrdb.php', false, $context, -1, 40000)) {
      echo json_encode(array('status' => "err", 'text' => "error finding include (rdb)")); die();
    }
    
    //$result = file_get_contents('http://lr.fonto.pl/lrdb.php', false, $context, -1, 40000);
    $arr = json_decode($result, true);
    if ($arr['status'] == 'ok') {
      $all = $arr['selected'];
    } else {
      echo json_encode(array('status' => "err", 'text' => "Table finding problem: ".$arr['text']));
      die();
    }
  } else {
    $foo = $db->checkTableExists($dbconf['database'], 'photos');
    if ($foo == 0) {
      $SQL = "CREATE TABLE `photos` (
                `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `p_gallery_id` varchar(20) DEFAULT 'yymmddnn',
                `p_gallery_hash` varchar(32) NULL,
                `p_name` varchar(20) DEFAULT NULL,
                `p_selected` char(1) DEFAULT '0',
                PRIMARY KEY (`p_id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      if (!$db->s($SQL)) {
        echo json_encode(array('status' => "err", 'text' => "problem creating 'photos' table"));
        die();
      }
    $SQL = "CREATE TABLE `galls` (
              `g_id` varchar(20) NOT NULL,
              `g_site_title` varchar(200) DEFAULT NULL,
              `g_set_title` varchar(200) DEFAULT NULL,
              `g_set_descr` text,
              `g_contact_name` varchar(100) DEFAULT NULL,
              `g_contact_email` varchar(100) DEFAULT NULL,
              `g_contact_url` varchar(200) DEFAULT NULL,
              `g_client_email` varchar(100) DEFAULT NULL,
              `g_client_name` varchar(100) DEFAULT NULL,
              `g_closed` enum('false','true') DEFAULT 'false',
              PRIMARY KEY (`g_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      if (!$db->s($SQL)) {
        echo json_encode(array('status' => "err", 'text' => "problem creating 'galls' table"));
        die();
      }
      $SQL = "CREATE TABLE `orders` (
                `o_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `o_gallery_id` varchar(20) DEFAULT NULL,
                `o_gallery_hash` varchar(32) NULL,
                `o_photos_list` text,
                `o_photos_count` int(10) unsigned DEFAULT NULL,
                `o_notes` text,
                `o_name` varchar(100) DEFAULT NULL,
                `o_email` varchar(70) DEFAULT NULL,
                `o_phone` varchar(20) DEFAULT NULL,
                PRIMARY KEY (`o_id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      if (!$db->s($SQL)) {
        echo json_encode(array('status' => "err", 'text' => "problem creating 'orders' table"));
        die();
      }
    }

    $SQL = "SELECT p_name
              FROM photos
              WHERE p_gallery_hash = '{$smtpconf['galleryhash']}' AND p_selected = 1 ";
    $all = $db->q($SQL);
  }
  if (is_array($all) && count($all) > 0) {
    $selected = array();
    foreach ($all as $foo) {
      $selected[] = $foo['p_name'];
    }
    echo json_encode(array('status' => "ok", 'text' => "Got checked", 'count' => count($all), 'selected' => $selected));
  } else {
    echo json_encode(array('status' => "ok", 'text' => "Got checked", 'count' => 0));
  }
}


?>