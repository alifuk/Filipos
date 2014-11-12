<?PHP

require_once("./compatlib.php");

//require_once("custom.css");

//$user_id = addslashes($_POST['user_id']);

if (file_exists('./custom.css')) {
  echo json_encode(array('status' => "ok", 'text' => "custom CSS configuration OK"));
} else {
  if (file_exists('../content/custom.php')) {
    if (!$fh_css = @fopen("./custom.css", "w")) {
      echo json_encode(array('status' => "err", 'text' => "custom CSS configuration file cannot be created")); die();
    } else {
      if (!$fh_foo = @fopen("../content/custom.php", "r")) {
        echo json_encode(array('status' => "err", 'text' => "custom CSS setup file cannot be opened")); die();
      } else {
        $inphase = false;
        while ($buffer = fgets($fh_foo, 4096)) {
          if ($inphase) {
            if (strstr($buffer, '}')) {
              $inphase = false;
            }
          } else {
            if (strstr($buffer, '#phpdata')) {
              $inphase = true;
            } else {
              if (!fwrite($fh_css, $buffer)) {
                echo json_encode(array('status' => "err", 'text' => "custom CSS setup file not found")); die();
              }
            }
          }
        }
        fclose($fh_foo);
        echo json_encode(array('status' => "ok", 'text' => "custom CSS configuration file created and saved"));
      }
      fclose($fh_css);
    }
    //$fh_css = fopen("./content/custom.css", "w");
  } else {
    echo json_encode(array('status' => "err", 'text' => "custom CSS setup file not found")); die();
  }
}

?>