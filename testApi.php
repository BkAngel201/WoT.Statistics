<html>
<head>
  <title>
  </title>
</head>
<body>
  <div id="resultado">
    <form action="testApi.php" method="POST">
      <input type="text" name="search" />
      <input type="submit" />
    </form>
    <?php
    if(isset($_POST["search"])) {
    $appi_id = "38a830f32e9f9d9740860672aa23aa08";
    $search = "search=".$_POST["search"];
    $api_response = json_decode(file_get_contents("https://api.worldoftanks.com/wot/account/list/?application_id=".$appi_id."&".$search."&type=exact"), true);


      foreach ($api_response['data'] as $key => $value) {
        echo "Nombre: ".$value["nickname"]."<br />";
        echo "Account Id: ".$value["account_id"]."<br />";

      }

      echo "<pre>";
      var_dump(json_decode(file_get_contents("https://api.worldoftanks.com/wot/account/tanks/?application_id=38a830f32e9f9d9740860672aa23aa08&account_id=1010210244&fields=mark_of_mastery"), true));
      echo"</pre>";
    }
    ?>
  </div>
</body>
</html>
