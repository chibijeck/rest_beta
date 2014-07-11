<html>
 <body>

<?php
error_reporting(E_ALL);

if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == "get_app") 
{
  $url = "http://localhost/rest_beta/api?action=get_app&id=" . $_GET['id'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FAILONERROR, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  $app_info = curl_exec($ch);
  $app_info = json_decode($app_info,true);
  //print_r($app_info);
  ?>
    <form method="POST">
    <table>
      <tr>
        <td></td><td><input type="hidden" name="id" value="<?php echo $app_info["id"]; ?>"></td>
      </tr>
      <tr>
        <td>App Name: </td><td><input type="text" name="name" value="<?php echo $app_info["app_name"]; ?>"></td>
      </tr>
      <tr>
        <td>Price: </td><td><input type="text" name="price" value="<?php echo $app_info["app_price"]; ?>"></td>
      </tr>
      <tr>
        <td>Version: </td><td> <input type="text" name="version" value="<?php echo $app_info["app_version"]; ?>"></td>
      </tr>
    </table>
    <input type="submit" value="update" name="update">
    <input type="submit" value="delete" name="delete">
    </form>
    <br />
    <a href="http://localhost/rest_beta/REST_Client?action=get_app_list" alt="app list">Return to the app list</a>
    <br />
  <?php
    if(isset($_POST["update"])){
          $data = array(
              "id" => $_POST["id"],
              "name" => $_POST["name"],
              "price" => $_POST["price"],
              "version" => $_POST["version"]
          );

          $put_url = "http://localhost/rest_beta/api?".http_build_query($data);
          $ch_url = curl_init($put_url);
          // print_r($data);
          // print_r(http_build_query($data));
          curl_setopt($ch_url, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch_url, CURLOPT_CUSTOMREQUEST, 'PUT');
          curl_setopt($ch, CURLOPT_HEADER, false);
          curl_setopt($ch_url, CURLOPT_HTTPHEADER, array('Accept: application/json'));
          //curl_setopt($ch_url, CURLOPT_POSTFIELDS, http_build_query($data));
          
          $response = curl_exec($ch_url);
          echo $response;
          curl_close($ch);
    }

    if(isset($_POST["delete"])){
      $data = array("id" => $_GET["id"]);
      $url = "http://localhost/rest_beta/api?".http_build_query($data);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      $response = curl_exec($ch);
      echo $response;
      curl_close($ch);
    }
  

}elseif(isset($_GET['action']) && $_GET['action'] == "add_app_list"){
  ?>
  <form method="POST">
  <table>
      <tr>
        <td>App Name: </td><td> <input type="text" name="name"> </td>
      </tr>
      <tr>
        <td>Price: </td><td> <input type="text" name="price"> </td>
      </tr>
      <tr>
        <td>Version: </td><td> <input type="text" name="version"></td>
      </tr>
    </table>
    <input type="submit" value="submit" name="submit">
  </form>
  <a href="http://localhost/rest_beta/REST_Client?action=get_app_list" alt="app list">Return to the app list</a>
<?php
    if(isset($_POST["submit"])){
          $url = "http://localhost/rest_beta/api";
          $curl = curl_init($url);
          $curl_post_data = array(
              "name" => $_POST["name"],
              "price" => $_POST["price"],
              "version" => $_POST["version"]
          );
          //print_r($curl_post_data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
          $curl_response = curl_exec($curl);
          echo $curl_response;
          curl_close($curl);
          
    }
   
}
else // else take the app list
{
  $url = "http://localhost/rest_beta/api?action=get_app_list";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FAILONERROR, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  $app_list = curl_exec($ch);
  $app_list = json_decode($app_list,true);

  ?>
    <ul>
    <?php foreach ($app_list as $app): ?>
      <li>
        <a href=<?php echo "http://localhost/rest_beta/REST_Client?action=get_app&id=" . $app["id"];  ?> alt=<?php echo "app_" . $app["id"]; ?>><?php echo $app["name"]; ?></a>
    </li>
    <?php endforeach; ?>
  <br />
    <a href="http://localhost/rest_beta/REST_Client?action=add_app_list" alt="add app list">add app</a>
    </ul>
  <?php
} ?>
 </body>
</html>
