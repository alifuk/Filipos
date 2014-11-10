<?PHP

require_once("./compatlib.php");

//ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

if (!file_exists("db_setup.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (dbs3)")); die(); 
} else { require_once("db_setup.php"); }

if (!file_exists("smtp_conf.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (smtpc)")); die(); 
} else { require_once("smtp_conf.php"); }


//var_dump($db);
//$user_id = addslashes($_POST['user_id']);

if ($_POST['user_token'] == md5('unique_saltimbocca' . $_POST['user_time'])) {

  $photoid = addslashes($_POST['photoId']);
  $galleryhash = addslashes($smtpconf['galleryhash']);

  if ($dbconf['custom'] == 'false') {
    $postdata = http_build_query(
      array(
        'action' => "updateSelection",
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
      echo json_encode(array('status' => "err", 'text' => "error finding include (rdb3)/4")); die();
    }
    //$result = file_get_contents('http://lr.fonto.pl/lrdb.php', false, $context, -1, 40000);
    echo $result;

  } else {

    if ($db->update('photos', array('p_selected' => '`p_selected` ^ 1'), array('p_gallery_hash' => $galleryhash, 'p_name' => $photoid))) {
      if ($db->affected()) {
        $SQL = "SELECT COUNT(*) FROM photos WHERE p_gallery_hash = '$galleryhash' AND p_selected = 1";
        $selcount = $db->one($SQL);
        if ($selcount >= 0) {
          echo json_encode(array('status' => "ok", 'text' => "selection saved", 'selected' => $photoid, 'count' => $selcount));
        } else {
          echo json_encode(array('status' => "err", 'text' => "problem counting selected photos in DB"));
        }
      } else {
        if ($db->insert('photos', array('p_selected' => '1', 'p_gallery_hash' => $galleryhash, 'p_name' => $photoid))) {
          $SQL = "SELECT COUNT(*) FROM photos WHERE p_gallery_hash = '$galleryhash' AND p_selected = 1";
          if (!$selcount = $db->one($SQL)) {
            echo json_encode(array('status' => "err", 'text' => "problem counting selected photos in DB"));
          } else {
            echo json_encode(array('status' => "ok", 'text' => "selection saved succesfully", 'selected' => $photoid, 'count' => $selcount));
          }
        } else {
          echo json_encode(array('status' => "err", 'text' => "problem inserting photo into DB"));
        }
      }
    } else {
      echo json_encode(array('status' => "err", 'text' => "error saving selection"));
    }
  }
} else {
  echo json_encode(array('status' => "err", 'text' => "unknown error"));
}

?>