<?PHP

require_once("./compatlib.php");








if (version_compare(phpversion(), '5.0.0', '>=')) {
  if (!file_exists("smtp_class.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (smtpcl)")); die();
  } else { require_once("smtp_class.php"); }
} else {
  if (!file_exists("smtp_class4.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (smtpcl)")); die();
  } else { require_once("smtp_class4.php"); }
}

if (!file_exists("smtp_conf.php")) { echo json_encode(array('status' => "err", 'text' => "error finding include (smtpc)")); die();
} else { require_once("smtp_conf.php"); }


if (!$smtpconf['galleryhash']) {
  echo json_encode(array('status' => "err", 'text' => "Gallery Hash unknown"));
} else {

  $form_comments = addslashes(trim($_POST['form_comments']));
  $form_name = addslashes(trim($_POST['form_name']));
  $form_email = addslashes(trim($_POST['form_email']));
  $form_from_name = addslashes(trim($_POST['form_from_name']));
  $form_from_email = addslashes(trim($_POST['form_from_email']));
  $form_phone = addslashes(trim($_POST['form_phone']));
  $form_gallerytitle = addslashes(trim($_POST['form_gallerytitle']));
  $form_photos_selected = addslashes(trim($_POST['form_photos_selected']));

  if (strlen($form_from_name) == 0) { $form_from_name = 'fontoGallery'; }
  if (strlen($form_from_email) == 0) { $form_from_email = 'fontogallery@fonto.pl'; }












































  $all = array();
  $foos = explode(",", $form_photos_selected);
  if (is_array($foos)) {
    foreach ($foos as $foo) {
      if ($foo) { $all[] = array('p_name' => $foo); }
    }
  }
  //print_r($foos);
  //print_r($all);




  if (is_array($all) && count($all) > 0) {
    $selected = array();

    $mail_html_content = "<b>fontoGallery Lite</b><br /><br />
                            <!--<b>Gallery dir:</b> {$smtpconf['galleryhash']}<br /><br />-->
                            <!--<b>Gallery hash:</b> {$smtpconf['galleryhash']}<br /><br />-->
                            <b>Gallery title:</b> {$form_gallerytitle}<br /><br />
                            <b>Person:</b> {$form_name}<br />
                            <b>Email:</b> {$form_email}<br />
                            <b>Phone:</b> {$form_phone}<br /><br />
                            <b>Notes:</b><br />{$form_comments}<br /><br />
                            <b>Photos:</b><br />";

    $photos_list = '';
    foreach ($all as $foo) {
      $mail_html_content .= $foo['p_name'] . ", ";
      $photos_list .= $foo['p_name'] . ", ";
      //$mail_text_content .= $foo['p_name'] . ".jpg\n";
      $selected[] = $foo['p_name'];
    }
    //print_r($selected);
    //print_r($photos_list);

    $mail_html_content .= "<br /><br />Total: " . count($selected) . "<br />";
    //$mail_text_content .= "\n\nTotal: " . count($selected) . "\n";

    $mail_text_content = $mail_html_content;

    if ($smtpconf['mailto'] != "email@example.com") {

      $postdata = http_build_query(
        array(
          'action' => "saveFormData",
          'photos_list' => $photos_list,
          'photos_count' => count($selected),
          'form_comments' => $form_comments,
          'form_name' => $form_name,
          'form_email' => $form_email,
          'form_phone' => $form_phone,
          'galleryhash' => $smtpconf['galleryhash']
        )
      );


      $opts = array('http' =>
        array(
          'method'  => 'POST',
          'header'  => array('Content-type: application/x-www-form-urlencoded', 'Custom-header: fonto', 'Connection: close'),
          'content' => $postdata,
          'timeout' => 60
        )
      );

      $context  = stream_context_create($opts);

      $url_headers_txt = myhttpget('lr.fonto.pl', '/lrdb.php');
      if (!$url_headers_txt) {
        echo json_encode(array('status' => "err", 'text' => "error connecting (rdb3) 1 ()")); die();
      } elseif (!strstr($url_headers_txt, 'HTTP/1.1 200 OK') && !strstr($url_headers_txt, 'HTTP/1.0 200 OK')) {
        echo json_encode(array('status' => "err", 'text' => "error finding include (rdb3) 2 ($url_headers_txt)")); die();
      } else {
        $time_start = microtime(true);

        $result = '';

        /*****if (version_compare(phpversion(), '12.0.0', '>=')) {
          $fp = fopen('http://lr.fonto.pl/lrdb.php', 'r', false, $context);
          //$result = stream_get_contents($fp);
          while(!feof($fp))
            $result .= fread($fp, 1000);
          fclose($fp);
        } else {*****/
          $sock = fsockopen("lr.fonto.pl", 80, $errno, $errstr, 30);
          if (!$sock) die("$errstr ($errno)\n");

          //$data = "foo=" . urlencode("Value for Foo") . "&bar=" . urlencode("Value for Bar");
          fputs($sock, "POST /lrdb3.php HTTP/1.0\r\n");
          fputs($sock, "Host: lr.fonto.pl\r\n");
          fputs($sock, "Content-type: application/x-www-form-urlencoded\r\n");
          fputs($sock, "Custom-header: fonto\r\n");
          fputs($sock, "Content-length: " . strlen($postdata) . "\r\n");
          fputs($sock, "Accept: */*\r\n");
          fputs($sock, "\r\n");
          fputs($sock, "$postdata\r\n");
          fputs($sock, "Connection: close\r\n");
          fputs($sock, "\r\n");

          $headers = "";
          while ($str = trim(fgets($sock, 4096))) {
            $headers .= "$str\n";
          }
          //print "\n";

          $result = "";
          while (!feof($sock))
            $result .= fgets($sock, 4096);

          fclose($sock);
        /*****}*****/

        $time_split1 = microtime(true);
        $time_split1 = round($time_split1 - $time_start, 5);
        //echo $result;
      }

      $arr = json_decode($result, true);
      if ($arr['status'] == 'ok') {
        $order_id = $arr['order_id'];
        $dbresult = true;
        $time = $arr['time'] - $time_start;
      } else {
        $dbresult = false;
        echo json_encode(array('status' => "err", 'text' => $arr['text']));
        die();
      }





      if ($dbresult) {

        /* $mail = new KM_Mailer(server, port, username, password, secure); */
        /* secure can be: null, tls or ssl */

        if ($smtpconf['custom'] == 'false') {
          //echo json_encode(array('status' => "err", 'text' => "Need to proxy send form."));
          // 'to' => "Piotr Kubiak <{form_mailto}>",

        //echo "==$mail_html_content==";

        $postvals = array(
          'from' => $form_from_name.' <'.$form_from_email.'>',
          'to' => addslashes($smtpconf['mailto']),
          'subject' => 'fontoGallery: ' . $form_gallerytitle . ', order nr: ' . $order_id,
          'body' => $mail_html_content,
          'galleryhash' => $smtpconf['galleryhash'],
          'order_id' => $order_id
        );
        // http_build_query does not work well with file_get_contents for contents > 1024 chars
        $postdata = http_build_query($postvals);


        //var_dump($postdata);

        $opts = array('http' =>
          array(
            'method'  => 'POST',
            'header'  => "Connection: close\r\n".
                          "Content-type: application/x-www-form-urlencoded\r\n".
                          "Content-Length: ".strlen($postdata)."\r\n".
                          "Custom-header: fonto\r\n",
            'content' => $postdata,
            'timeout' => 40
          )
        );

        $context  = stream_context_create($opts);

        $url_headers_txt = myhttpget('lr.fonto.pl', '/lrmail.php');
        if (!$url_headers_txt) {
          echo json_encode(array('status' => "err", 'text' => "error connecting (rml3) 1 ()")); die();
        } elseif (!strstr($url_headers_txt, 'HTTP/1.1 200 OK') && !strstr($url_headers_txt, 'HTTP/1.0 200 OK')) {
          echo json_encode(array('status' => "err", 'text' => "error finding include (rml3) 2 ($url_headers_txt)")); die();
        } else {
          $result ='';
          $time_split = microtime(true);


          // header information as well as meta data
          // about the stream
          // var_dump(stream_get_meta_data($fp));
          // actual data at $url
          // var_dump(stream_get_contents($stream));
          $time_split2 = microtime(true);
          // http://www.php-mysql-tutorial.com/wikis/php-tutorial/reading-a-remote-file-using-php.aspx

          $fp = fsockopen('lr.fonto.pl', 80);
          if (!$fp) {
            echo "$errstr ($errno)<br />\n";
          } else {
            fwrite($fp, "POST /lrmail.php HTTP/1.1\r\n");
            fwrite($fp, "Host: lr.fonto.pl\r\n");
            fwrite($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fwrite($fp, "Content-length: ".strlen($postdata)."\r\n");
            fwrite($fp, "Custom-header: fonto\r\n");
            fwrite($fp, "Connection: close\r\n");
            fwrite($fp, "\r\n");
            fwrite($fp, $postdata);

            $header = '';
            $break_cnt = 0; // just to be safe
            do { // loop until the end of the header
              $header .= fgets ($fp, 128);
              $break_cnt++;
            } while (strpos($header, "\r\n\r\n") === false && $break_cnt < 100);

            while (!feof($fp)) {
              $result .= fgets($fp, 1024);
            }
            fclose($fp);
          }

          //var_dump($http_response_header);

          $time_split3 = microtime(true);

          $time_split2 = round($time_split2 - $time_split, 5);
          $time_split3 = round($time_split3 - $time_split, 5);

          $time_split5 = microtime(true);

          $arr = json_decode($result, true);
          $time_split5 = round($time_split5 - $arr['time_split'], 5); // aftersend script time
          $time_split4 = round($arr['time_split_start'] - $time_split, 5); // presend script time
          if ($arr['status'] == 'ok') {
            $arrtimes = array(
              'dbtime' => $time_split1,
              'mailconntime' => $time_split2,
              'mailfulltime' => $time_split3,
              'mailinittime' => $time_split4,
              'mailfinishtime' => $time_split5,
              'mailsendtime' => $arr['time']
            );
            $timestxt = '';
            foreach ($arrtimes as $atkey => $atval) {
              if ($atval > 0.5) {
                if ($timestxt) {
                  $timestxt .= ", {$atkey}: {$atval}";
                } else {
                  $timestxt .= "{$atkey}: {$atval}";
                }
              }
            }
            echo json_encode(array('status' => "ok", 'text' => "Form sent successfully. Your order id: {$order_id}.<br />($timestxt)"));
          } else {
            $dbresult = false;
            echo json_encode(array('status' => "err", 'text' => "Form not sent. Mailer failure. " . $arr['text'] . " ($timestxt)"));
            die();
          }
        }

        } else {
          $mail = new KM_Mailer($smtpconf['server'], $smtpconf['port'], $smtpconf['username'], $smtpconf['pass'], $smtpconf['secure']);
          //  bWFpbGVyK2N6ZXJ3b25ha3JlZGthLnBs        dW85ZzRlenBOVUlN

          if ($mail->isLogin) {
            /* for localhost server no login is required: */
            /* $mail = new KM_Mailer('localhost', '25'); */

            /* $mail->send(from, to, subject, body, headers = optional) */
            //$mail->send('fontogallery@fonto.pl', 'fontogallery@fonto.pl', 'Chosen photos ' . $smtpconf['galleryhash'], $mail_html_content);

            /* more emails can be sent on the same connection: */
            //$mail->send('UserName <username@mydomain.com>', 'Recipient <recipent@somedomain.com>', 'test email 2', 'This is a <b>multipart email</b>!');

            /* add more recipients */
            //$mail->addRecipient('New Recipient <newrecipient@somedomain.com>');

            /* add CC recipient */
            //$mail->addCC('CC Recipient <ccrecipient@somedomain.com>');

            /* add BCC recipient */
            //$mail->addBCC('CC Recipient <bccrecipient@somedomain.com>');

            /* add attachment */
            //$mail->addAttachment('pathToFileToAttach');

            /* send multipart email with different plain text part */
            ////$mail->altBody = $mail_text_content;

            //if(!$mail->send('Fonto <fontogallery@fonto.pl>', 'Piotr Kubiak <pkubiak@gmail.com>', 'Wybrane zdjęcia ' . $smtpconf['galleryhash'], $mail_html_content)) {


            //if(!$mail->send($form_from_name.' <'.$form_from_email.'>', addslashes($smtpconf['mailto']) , 'Wybrane zdjęcia. Galeria: ' . $smtpconf['galleryhash'] . ', zamówienie nr: ' . $order_id, $mail_html_content)) {
            if(!$mail->send('Fonto <fontogallery@fonto.pl>', addslashes($smtpconf['mailto']) , 'Wybrane zdjęcia. Galeria: ' . $smtpconf['galleryhash'] . ', zamówienie nr: ' . $order_id, $mail_html_content)) {
              echo json_encode(array('status' => "err", 'text' => "Form not sent. Mailer failure."));
            } else {
              echo json_encode(array('status' => "ok", 'text' => "Form sent successfully. Your order id: {$order_id}.", 'lang' => "lang_sendsuccess", 'id' => "{$order_id}"));
            }

            /* send just a plain text email and test if it was sent successfully */
            //$mail->contentType = "text/plain";
            /*if(!$mail->send('username@mydomain.com', 'recipent@somedomain.com', 'test email 4', 'This is a plain text email.')) {
              echo "Failed to send email";
            } else {
              echo "Email sent successfully";
            }*/

            /* clear recipients and attachments */
            $mail->clearRecipients();
            $mail->clearCC();
            $mail->clearBCC();
            $mail->clearAttachments();
          } else {
            echo json_encode(array('status' => "err", 'text' => "Form not sent. Mailer login failed."));
          }
        }

      } else {
        echo json_encode(array('status' => "err", 'text' => "Problem saving order in DB. Form saving failure."));
      }
    } else {
      echo json_encode(array('status' => "err", 'text' => "Email configuration incomplet.", 'lang' => "lang_errmailto"));
    }

  } else {
    echo json_encode(array('status' => "err", 'text' => "Form not sent. Nothing selected.", 'lang' => "lang_nothingsel"));
  }
}


?>