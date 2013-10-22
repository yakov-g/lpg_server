<?php

require_once dirname(__FILE__). '/../config/pswd.php';

$username = DB_USER;
$password = DB_PASSWORD;
$hostname = DB_SERVER; 
$database = DB_DATABASE;

if (isset($_POST['op']))
{
    $op = $_POST['op'];
    echo '<p>post op: '.$op.'</p>';
}
if (isset($_GET['op']))
{
    $op = $_GET['op'];
    echo '<p>get op: '.$op.'</p>';
}
if (isset($_POST['time']))
{
    $time = $_POST['time'];
    echo '<p>post time: '.$time.'</p>';
}
if (isset($_POST['json']))
{
    $json = $_POST['json'];
    echo '<p>post json: '.$json.'</p>';
    echo "<br>";
    echo PHP_EOL;
}

$dbhandle = mysql_connect($hostname, $username, $password);
if ($dbhandle)
{
  //echo '<p>Connected to MySQL</p>';
  $selected = mysql_select_db($database, $dbhandle);
  if ($selected)
   {
      mysql_query("SET NAMES utf8");

      $sp = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      //print update_counter
      $result = mysql_query("SELECT * FROM config WHERE id = 3");
      while ($row = mysql_fetch_array($result)) 
      {
          echo "".$row{'name'}.":".$sp.$row{'value'}."<br>";
      }

      $result = mysql_query("SELECT * FROM lpg_israel WHERE 1");
      while ($row = mysql_fetch_array($result)) 
      {
          $product = array();
          $product['id'] = 1;
          $product['name'] = $row{'name'};
          $product['time'] = $row{'time'};
          //echo json_encode($product);
          //echo "<p>".json_encode($product)."</p>";
          echo "".$row{'lat'}.";".$sp.$row{'lng'}.";".$sp.$row{'price'}.";".$sp.$row{'name'}."<br>";
      }
   }



}
else
{
   echo '<p>Unable to connect to MySQL</p>';
}

?> 