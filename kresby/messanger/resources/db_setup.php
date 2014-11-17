<?PHP

require_once("db_conf.php");

if ($dbconf['custom'] == 'true') {
  require_once("db_class.php");
  $db = new edb(array($dbconf['server'], $dbconf['user'], $dbconf['pass'], $dbconf['database']));
}

?>