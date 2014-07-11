<?php
// This is the API, 2 possibilities: show the app list or show a specific app by id.
// This would normally be pulled from a database but for demo purposes, I will be hardcoding the return values.

header('Content-Type: application/json');

function get_app_by_id($id)
{
  $app_info = array();

  // normally this info would be pulled from a database.
  // build JSON array.
  $mysqli = new mysqli("localhost","root","","rest_api_db");
  if($mysqli->connect_errno){
    printf("Connect failed: %s\n",$mysqli->connect_error);
    exit();
  }
  if($result = $mysqli->query("SELECT * FROM app_info WHERE id = ".$id)){
      $data = mysqli_fetch_array($result);
      $app_info = array("id" => $data["id"], "app_name" => $data["app_name"], "app_price" => $data["app_price"], "app_version" => $data["app_version"]);
      $result->close();
  }

  return $app_info;
  $mysqli->close();
}

function get_app_list()
{
  //normally this info would be pulled from a database.
  //build JSON array
  $mysqli = new mysqli("localhost","root","","rest_api_db");
  if($mysqli->connect_errno){
    printf("Connect failed:%s\n",$mysqli->connect_error);
    exit();
  }
  if($result = $mysqli->query("SELECT * FROM app_info")){
      while ($data = mysqli_fetch_array($result)){
        $app[] = array("id" => $data["id"], "name" => $data["app_name"]);
      }
      $app_list = $app;
      $result->close();
  }
  return $app_list;
  $mysqli->close();
}

function add_app_list($name,$price,$version)
{
  //normally this info would be pulled from a database.
  //build JSON array
  $mysqli = new mysqli("localhost","root","","rest_api_db");
  if($mysqli->connect_errno){
    printf("Connect failed:%s\n",$mysqli->connect_error);
    exit();
  }
  $mysqli->query("INSERT INTO app_info VALUES (NULL,'$name','$price','$version')");
  $add = array($name,$price,$version);
  $add_list = "APP created!!";

  return $add_list;
  $mysqli->close();
}

function update_app_list($id,$name,$price,$version)
{
  //normally this info would be pulled from a database.
  //build JSON array
  $mysqli = new mysqli("localhost","root","","rest_api_db");
  if($mysqli->connect_errno){
    printf("Connect failed:%s\n",$mysqli->connect_error);
    exit();
  }
  $mysqli->query("UPDATE app_info SET app_name='$name',app_price='$price',app_version='$version' WHERE id = '$id' ");
  $update = array($name,$price,$version);
  $update_list = "APP updated!!";

  return $update_list;
  $mysqli->close();
}

function delete_app_list($id)
{
  //normally this info would be pulled from a database.
  //build JSON array
  $mysqli = new mysqli("localhost","root","","rest_api_db");
  if($mysqli->connect_errno){
    printf("Connect failed:%s\n",$mysqli->connect_error);
    exit();
  }
  $mysqli->query("DELETE FROM app_info WHERE id = '$id' ");
  //$update = array($name,$price,$version);
  $delete_list = "APP deleted!!";

  return $delete_list;
  $mysqli->close();
}


$get_url = array("get_app_list", "get_app");

$value = "An error has occurred";

$verb = $_SERVER['REQUEST_METHOD'];

if($verb == "GET"){
    if (isset($_GET["action"]) && in_array($_GET["action"], $get_url))
    {
      switch ($_GET["action"])
        {
          case "get_app_list":
              $value = get_app_list();
            break;
          case "get_app":
            if (isset($_GET["id"]))
              $value = get_app_by_id($_GET["id"]);
            else
              $value = "Missing argument";
            break;
        }
    }
}elseif($verb == "POST"){

  $value = $verb." method = ".add_app_list($_POST["name"],$_POST["price"],$_POST["version"]);

}elseif($verb == "PUT"){
    
    $value = $verb." method = ".update_app_list($_GET["id"],$_GET["name"],$_GET["price"],$_GET["version"]);

}elseif($verb == "DELETE"){

  $value = $verb." method = ".delete_app_list($_GET["id"]);
  
}



//return JSON array
exit(json_encode($value));
?>
