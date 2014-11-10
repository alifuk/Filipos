<?PHP

require_once("./compatlib.php");

if (file_exists('./smtp_conf.php')) {
  require_once('./smtp_conf.php');

  // check for valid email address
  if (isset($smtpconf['mailto'])) {
    $emails = array();
    if (preg_match_all('/\s*"?([^><,"]+)"?\s*((?:<[^><,]+>)?)\s*/', $smtpconf['mailto'], $matches, PREG_SET_ORDER) > 0) {
      foreach($matches as $m) {
        if (! empty($m[2])) {
          $emails[trim($m[2], '<>')] = $m[1];
        } else {
          $emails[$m[1]] = '';
        }
      }
    }
    if (count($emails) > 0) {
      echo json_encode(array('status' => "ok", 'text' => "SMTP configuration OK", 'galleryhash' => $smtpconf['galleryhash']));
    } else {
      echo json_encode(array('status' => "err", 'text' => "No valid mailto address found in the config file"));
    }
  } else {
    echo json_encode(array('status' => "err", 'text' => "No mailto address found in the config file"));
  }
} else {
  if (file_exists('../content/custom.php')) {
    if (!$fh_smtp = @fopen("./smtp_conf.php", "w")) {
      echo json_encode(array('status' => "err", 'text' => "SMTP configuration file cannot be created"));
    } else {
      if (!$fh_foo = @fopen("../content/custom.php", "r")) {
        echo json_encode(array('status' => "err", 'text' => "SMTP setup file cannot be opened"));
      } else {
        fwrite($fh_smtp, "<?PHP\n\n");
        $smtpconf = array();
        while ($buffer = fgets($fh_foo, 4096)) {
          $foo = '';
          $foo2 = '';
          if ($foo2 = strstr($buffer, 'SMTPcustom')) {
            //$foo = strstr(trim($foo2), );
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            //echo "SMTPcustom: $buffer | $foo2 | $foo || ";
            //$verbose .= "SMTPcustom: $buffer | $foo2 | $foo || ";
            list($foo, $smtpconf['custom']) = explode(" ", $foo, 2);
            $smtpconf['custom'] = trim($smtpconf['custom']);
            if (!in_array($smtpconf['custom'], array('false', 'true'))) {
              $smtpconf['custom'] = 'false';
            }
            fwrite($fh_smtp, '$smtpconf[\'custom\'] = \'' . trim($smtpconf['custom']) . "';\n");
            /*if ($smtpconf['custom'] == 'false') {
              rewind($fh_smtp);
              fwrite($fh_smtp, "<?PHP\n\n");
              fwrite($fh_smtp, '$smtpconf[\'custom\'] = \'' . trim($smtpconf['custom']) . "';\n");
              break;
            }*/
          }
          if ($foo2 = strstr($buffer, 'EmailAddres')) {
            //$foo = strstr(trim($foo2), '!important;', true);
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            //echo "EmailAddres: $buffer | $foo2 | $foo || ";
            //$verbose .= "EmailAddres: $buffer | $foo2 | $foo || ";
            list($foo, $smtpconf['mailto']) = explode(" ", $foo, 2);
            fwrite($fh_smtp, '$smtpconf[\'mailto\'] = \'' . trim($smtpconf['mailto']) . "';\n");
          }
          if ($foo2 = strstr($buffer, 'GalleryHash')) {
            //$foo = strstr(trim($foo2), '!important;', true);
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            //$verbose .= "SMTPcustom: $buffer | $foo2 | $foo || ";
            list($foo, $smtpconf['galleryhash']) = explode(" ", $foo, 2);
            fwrite($fh_smtp, '$smtpconf[\'galleryhash\'] = \'' . trim($smtpconf['galleryhash']) . "';\n");
          }
          if ($foo2 = strstr($buffer, 'SMTPserver')) {
            //$foo = strstr(trim($foo2), '!important;', true);
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            //$verbose .= "SMTPcustom: $buffer | $foo2 | $foo || ";
            list($foo, $smtpconf['server']) = explode(" ", $foo, 2);
            fwrite($fh_smtp, '$smtpconf[\'server\'] = \'' . trim($smtpconf['server']) . "';\n");
          }
          if ($foo2 = strstr($buffer, 'SMTPusername')) {
            //$foo = strstr(trim($foo), '!important;', true);
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            list($foo, $smtpconf['username']) = explode(" ", $foo, 2);
            fwrite($fh_smtp, '$smtpconf[\'username\'] = \'' . trim($smtpconf['username']) . "';\n");
          }
          if ($foo2 = strstr($buffer, 'SMTPpass')) {
            //$foo = strstr(trim($foo), '!important;', true);
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            list($foo, $smtpconf['pass']) = explode(" ", $foo, 2);
            fwrite($fh_smtp, '$smtpconf[\'pass\'] = \'' . trim($smtpconf['pass']) . "';\n");
          }
          if ($foo2 = strstr($buffer, 'SMTPport')) {
            //$foo = strstr(trim($foo), '!important;', true);
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            list($foo, $smtpconf['port']) = explode(" ", $foo, 2);
            fwrite($fh_smtp, '$smtpconf[\'port\'] = \'' . intval(trim($smtpconf['port'])) . "';\n");
          }
          if ($foo2 = strstr($buffer, 'SMTPsecure')) {
            //$foo = strstr(trim($foo), '!important;', true);
            $foo = substr(trim($foo2), 0, strpos(trim($foo2), '!important;'));
            list($foo, $smtpconf['secure']) = explode(" ", $foo, 2);
            if (!in_array($smtpconf['secure'], array('ssl', 'tls'))) {
              $smtpconf['secure'] = null;
            }
            fwrite($fh_smtp, '$smtpconf[\'secure\'] = \'' . trim($smtpconf['secure']) . "';\n");
          }
          if (count($smtpconf) == 8) { break; }
        }
        fwrite($fh_smtp, "\n?>");
        fclose($fh_foo);
        if (count($smtpconf) == 8) {
          if (trim($smtpconf['mailto']) == 'email@example.com') {
            echo json_encode(array('status' => "err", 'text' => "SMTP configuration problem. Export gallery once again providing proper email address not: " . $smtpconf['mailto'], 'verb' => $verbose));
          } else {
            echo json_encode(array('status' => "ok", 'text' => "SMTP configuration file created and SMTP setup saved", 'verb' => $verbose));
          }
        } else {
          echo json_encode(array('status' => "err", 'text' => "SMTP configuration incomplete", 'verb' => $verbose));
        }
      }
      fclose($fh_smtp);
    }
    //$fh_css = fopen("./content/smtp_conf.php", "w");
    require_once('./smtp_conf.php');
  } else {
    echo json_encode(array('status' => "err", 'text' => "SMTP setup file not found"));
  }
}

?>