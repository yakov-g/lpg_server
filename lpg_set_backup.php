<?php

require_once dirname(__FILE__). '/../config/pswd.php';

$username = DB_USER;
$password = DB_PASSWORD;
$hostname = DB_SERVER; 
$database = DB_DATABASE;


function update_time()
{
   $resp = array();
   $tst = time();

   $result = mysql_query("UPDATE config SET value = '$tst' WHERE id = '1'"); 
   $resp['status'] = 1; 
   $resp['message'] = 'time updated';

   return $resp;
}

$response = array();
$response['status'] = 0;

if (isset($_POST['op']))
{
    $op = $_POST['op'];
}
else
{
   $response['message'] = 'no op provided';
   echo json_encode($response);
   exit(0);
}

$dbhandle = mysql_connect($hostname, $username, $password);
if ($dbhandle)
{
  $selected = mysql_select_db($database, $dbhandle);
  if ($selected)
   {
      mysql_query("SET NAMES utf8");

      /* update price  */
      if ($op == "set_price")
      {
         if (isset($_POST['lat']))
         {
            $lat = $_POST['lat'];
         }
         if (isset($_POST['lng']))
         {
            $lng = $_POST['lng'];
         }
         if (isset($_POST['price']))
         {
            $price = $_POST['price'];
         }

         $date = date("d.m.Y");
         $result = mysql_query("UPDATE lpg_israel SET price = '$price', updated = '$date' WHERE lat = '$lat' and lng = '$lng'");
        
         if (!empty($result)) 
         {
            $response['status'] = 1;
            $response['message'] = 'No errors';
            $response = update_time();
         }
         else
         {
            $response['message'] = 'empty result';
         }
         
      }
      else if ($op == "inc_time")
      {
         $response = update_time();
      }
      else
      {
           $response['message'] = 'mistake in op provided';
      }
   }
}
else
{
   $response['message'] = 'Unable to connect to MySQL';
}

echo json_encode($response);
exit(0);

?> 