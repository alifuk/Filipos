<?PHP

require_once("./compatlib.php");

//require_once("db_conf.php");

//$user_id = addslashes($_POST['user_id']);

if (file_exists('./db_conf.php')) {
  require_once('./db_conf.php');
  echo json_encode(array('status' => "ok", 'text' => "DB configuration OK"));
} else {
  if (file_exists('../content/custom.php')) {
    if (!$fh_db = @fopen("./db_conf.php", "w")) {
      echo json_encode(array('status' => "err", 'text' => "DB configuration file cannot be created"));
    } else {
      if (!$fh_foo = @fopen("../content/custom.php", "r")) {
        echo json_encode(array('status' => "err", 'text' => "DB setup file cannot be opened"));
      } else {
        fwrite($fh_db, "<?PHP\n\n");
        $dbconf = array();
        while ($buffer = fgets($fh_foo, 4096)) {
          $foo = '';
          if ($foo = strstr($buffer, 'DBdatabase')) {
            $foo = strstr(trim($foo), '!important;', true);
            list($foo, $dbconf['database']) = explode(" ", $foo, 2);
            fwrite($fh_db, '$dbconf[\'database\'] = \'' . trim($dbconf['database']) . "';\n");
          }
          if ($foo = strstr($buffer, 'DBpass')) {
            $foo = strstr(trim($foo), '!important;', true);
            list($foo, $dbconf['pass']) = explode(" ", $foo, 2);
            fwrite($fh_db, '$dbconf[\'pass\'] = \'' . trim($dbconf['pass']) . "';\n");
          }
          if ($foo = strstr($buffer, 'DBuser')) {
            $foo = strstr(trim($foo), '!important;', true);
            list($foo, $dbconf['user']) = explode(" ", $foo, 2);
            fwrite($fh_db, '$dbconf[\'user\'] = \'' . trim($dbconf['user']) . "';\n");
          }
          if ($foo = strstr($buffer, 'DBserver')) {
            $foo = strstr(trim($foo), '!important;', true);
            list($foo, $dbconf['server']) = explode(" ", $foo, 2);
            fwrite($fh_db, '$dbconf[\'server\'] = \'' . trim($dbconf['server']) . "';\n");
          }
          if ($foo = strstr($buffer, 'DBgalleryid')) {
            $foo = strstr(trim($foo), '!important;', true);
            list($foo, $dbconf['galleryid']) = explode(" ", $foo, 2);
            fwrite($fh_db, '$dbconf[\'galleryid\'] = \'' . trim($dbconf['galleryid']) . "';\n");
          }
          if ($foo = strstr($buffer, 'DBcustom')) {
            $foo = strstr(trim($foo), '!important;', true);
            list($foo, $dbconf['custom']) = explode(" ", $foo, 2);
            $dbconf['custom'] = trim($dbconf['custom']);
            if (!in_array($dbconf['custom'], array('false', 'true'))) {
              $dbconf['custom'] = 'false';
            }
            fwrite($fh_db, '$dbconf[\'custom\'] = \'' . trim($dbconf['custom']) . "';\n");
          }

          if (count($dbconf) == 6) { break; }
        }
        fwrite($fh_db, "\n?>");
        fclose($fh_foo);
        if (count($dbconf) == 6) {
          echo json_encode(array('status' => "ok", 'text' => "DB configuration file created and DB setup saved", 'galleryhash' => ''));
        } else {
          echo json_encode(array('status' => "err", 'text' => "DB configuration incomplete"));
        }
      }
      fclose($fh_db);
    }
    //$fh_css = fopen("./content/db_conf.php", "w");
  } else {
    echo json_encode(array('status' => "err", 'text' => "DB setup file not found"));
  }
}

?>