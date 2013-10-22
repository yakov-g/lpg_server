<?php

require_once dirname(__FILE__). '/../config/pswd.php';

$username = DB_USER;
$password = DB_PASSWORD;
$hostname = DB_SERVER; 
$database = DB_DATABASE;

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

if (isset($_POST['time']))
{
    $time = $_POST['time'];
}

$dbhandle = mysql_connect($hostname, $username, $password);
if ($dbhandle)
{
  $selected = mysql_select_db($database, $dbhandle);
  if ($selected)
   {
      mysql_query("SET NAMES utf8");

      /* get timestamp */
      if ($op == "get_timestamp")
      {
         $result = mysql_query("SELECT value FROM config WHERE id = '1'");
         if (!empty($result)) 
         {
            if (mysql_num_rows($result) > 0)
            {
                $it = mysql_fetch_array($result);
                $response['status'] = 1;
                $response['timestamp'] = intval($it['value']);
            }
            else
            {
                $response['message'] = 'Result is OK, but now rows were returned';
            }
            mysql_free_result($result);
         }
         else
         {
            $response['message'] = 'empty result';
         }
      }
      /* when everyone will update to ver9, remove get_timestamp*/
      elseif ($op == "get_config")
      {
         $result = mysql_query("SELECT * FROM config WHERE 1");
         if (!empty($result)) 
         {
            if (mysql_num_rows($result) > 0)
            {
               $response['status'] = 1;
               $config_data = array();
               while ($row = mysql_fetch_array($result)) 
               {
                  if ($row{'name'} == "timestamp")
                     $config_data['timestamp'] = intval($row{'value'});
                  elseif ($row{'name'} == "min_price")
                     $config_data['min_price'] = doubleval($row{'value'});
                }
                $response['config_data'] = $config_data;
            }
            else
            {
                $response['message'] = 'Result is OK, but now rows were returned';
            }
            mysql_free_result($result);
         }
         else
         {
            $response['message'] = 'empty result';
         }
      }

      elseif ($op == "get_all")
      {
        if (isset($_POST['id']))
        {
          $id = $_POST['id'];
        }
        
        $result = mysql_query("SELECT * FROM lpg_israel WHERE 1");
        if (!empty($result)) 
        {
            if (mysql_num_rows($result) > 0)
            {
               $response['data'] = array();
               $response['status'] = 1;
               while ($row = mysql_fetch_array($result)) 
               {
                  $it = array();
                  $it['id'] = intval($row{'id'});
                  $it['name'] = $row{'name'};
                  $it['time'] = $row{'time'};
                  $it['lat'] = doubleval($row{'lat'});
                  $it['lng'] = doubleval($row{'lng'});
                  $it['price'] = doubleval($row{'price'});
                  $it['owner'] = intval($row{'owner'});
                  $it['updated'] = $row{'updated'};
                  $it['address'] = $row{'address'};
                  $it['phone'] = $row{'phone'};
                  $it['type'] = intval($row{'type'});
                  array_push($response["data"], $it);
              }
            }
            else
            {
                $response['message'] = 'Result is OK, but now rows were returned';
            }
            mysql_free_result($result);
        }
        else
        {
           $response['message'] = 'empty result';
        }
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