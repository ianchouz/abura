<?
  include '../applib.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <head>
  </head>
  <body>
<?
  $strSQLQ = "select xmlcontent from ".$CFG->tbext."webconfig where id='contactset'";
  $query = mysql_query($strSQLQ);
  $row = mysql_fetch_row($query);
  $contactsetobj = new parseXML($row[0]);
  $sendsuccess = $contactsetobj->value('/content/sendsuccess');
?>
  <?=$sendsuccess?>
  </body>
</html>