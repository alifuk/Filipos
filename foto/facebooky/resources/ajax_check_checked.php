<?PHP

require_once("./compatlib.php");

if (!file_exists("db_setup.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (dbs1)")); die(); 
} else { require_once("db_setup.php"); }

if (!file_exists("smtp_conf.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (smtpc)")); die(); 
} else { require_once("smtp_conf.php"); }


if (!$smtpconf['galleryhash']) {
  echo json_encode(array('status' => "err", 'text' => "Gallery ID unknown"));
} else {

  /*$foo = $db->checkTableExists($dbconf['database'], 'photos');
  if ($foo == 0) {
    echo json_encode(array('status' => "err", 'text' => "Table does not exist"));
  }*/

  $galleryhash = addslashes($smtpconf['galleryhash']);
  $photoid = addslashes($_POST['filename']);

  if (!$photoid) {
    echo json_encode(array('status' => "err", 'text' => "Filename not provided"));
  } else {


    if ($dbconf['custom'] == 'false') {
      $postdata = http_build_query(
        array(
          'action' => "checkChecked",
          'photoid' => $photoid,
          'galleryhash' => $galleryhash
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
        echo json_encode(array('status' => "err", 'text' => "error finding include (rdb3)/3")); die();
      }

      //$result = file_get_contents('http://lr.fonto.pl/lrdb.php', false, $context, -1, 40000);
      echo $result;

    } else {

      $SQL = "SELECT p_selected
                FROM photos
                WHERE p_gallery_hash = '{$galleryhash}' AND p_name = '{$photoid}' ";
      $all = $db->q($SQL);
      if (is_array($all) && count($all) > 0) {
        $selected = $all[0]['p_selected'];
        echo json_encode(array('status' => "ok", 'text' => "Got checked", 'selected' => $selected));
      } else {
        echo json_encode(array('status' => "ok", 'text' => "Filename not found", 'selected' => 0));
      }
    }
  }
}


?>