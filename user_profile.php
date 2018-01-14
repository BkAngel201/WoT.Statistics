<!doctype html>
<html lang="en">
  <head>
    <title>WoT.Statistics</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="css/custom-style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/font-wot/css/font-wot.css">
  </head>
  <body class="d-flex flex-column">
    <header>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
          <a class="navbar-brand" href="#">
            <img src="img/wot-logo.svg" width="30" height="30" alt="">
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Disabled</a>
              </li>
            </ul>
            <form class="form-inline col-12 col-lg-5" action="user_profile.php" method="post">
              <div class="input-group col px-0">
                <input type="text" name="search" class="form-control" placeholder="Search For Players, Clans, or Tanks" aria-label="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-info" type="button">Go!</button>
                </span>
              </div>
            </form>
          </div>
        </div>
      </nav>
      <div class="jumbotron jumbotron-fluid text-center d-none d-md-block py-5 mb-0">
        <div class="container mx-auto p-md-1 p-md-1 text-white">
          <h1 class="display-3 mt-lg-2">Fluid jumbotron</h1>
          <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
        </div>
      </div>
    </header>
    <?php
    if(isset($_POST["search"])) {
      $appi_id = "38a830f32e9f9d9740860672aa23aa08";
      $search = "search=".$_POST["search"];
      $api_response = json_decode(file_get_contents("https://api.worldoftanks.com/wot/account/list/?application_id=".$appi_id."&".$search."&type=exact"), true);
      $account_id = $api_response['data'][0]['account_id'];
      $nickname = $api_response['data'][0]['nickname'];

      $personal_data = json_decode(file_get_contents("https://api.worldoftanks.com/wot/account/info/?application_id=".$appi_id."&fields=global_rating%2Cstatistics.all.spotted%2Cstatistics.all.battles%2Cstatistics.all.wins%2Cstatistics.all.hits_percents%2Cstatistics.all.damage_dealt%2Cstatistics.all.frags%2Cstatistics.all.xp&account_id=".$account_id), true);
      $data = $personal_data['data'][$account_id];

      $mastery_data = json_decode(file_get_contents("https://api.worldoftanks.com/wot/account/tanks/?application_id=".$appi_id."&account_id=".$account_id."&fields=mark_of_mastery"), true);
      $mastery = array('total' => 0,"ace" => 0 );
      foreach ($mastery_data["data"][$account_id] as $key => $value) {
        $mastery["total"]++;
        if($value["mark_of_mastery"] == 4) {
          $mastery["ace"]++;
        }
      }
    } else {
      header("Location: index.html");
    }
    ?>
    <main>
      <div class="container d-flex flex-wrap align-items-stretch pl-lg-0">
        <div class="col col-lg-3 py-3 py-lg-5 bg-secondary inner-shadow text-center d-inline-flex d-lg-flex flex-wrap">
          <div class="col-3 col-lg-12 px-0 stats-group d-flex flex-wrap order-lg-2 mt-lg-3">
            <div class="col-12 col-lg-6 px-0 my-auto">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-battles-done text-dark" aria-hidden="true"></i> <?php echo $data['statistics']['all']['battles']; ?>
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Batallas Totales
              </div>
            </div>
            <div class="col-12 col-lg-6 px-0 my-auto">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-battles-win text-dark" aria-hidden="true"></i> <?php echo $data['statistics']['all']['wins']; ?>
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Victorias Totales
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-12 stats-global-ratings order-lg-1 mb-lg-3">
            <div class="col stat-icon text-warning">
              <i class="wot fa icon-wot-stats-global-ratings" aria-hidden="true"></i>
            </div>
            <div class="col text-white stat-value">
              <?php echo $data['global_rating']; ?>
            </div>
            <div class="d-inline-flex justify-content-center text-dark stat-desc font-weight-bold">
              Puntuaci&oacute;n Global
            </div>
          </div>
          <div class="col-3 col-lg-12 px-0 stats-group d-flex flex-wrap order-lg-3 mt-lg-3">
            <div class="col-12 col-lg-6 px-0 my-auto">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-icon-mastery-badges text-dark" aria-hidden="true"></i> <?php echo $mastery["ace"]; ?>/<small class="text-dark"><?php echo $mastery["total"]; ?></small>
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Maestr&iacute;as
              </div>
            </div>
            <div class="col-12 col-lg-6 px-0 my-auto">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-experience text-dark" aria-hidden="true"></i> <?php echo round($data['statistics']['all']['xp']/$data['statistics']['all']['battles']); ?>
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Experiancia/Batalla
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-12 stats-group d-flex justify-content-between flex-wrap px-0 order-lg-4 mt-lg-3">
            <div class="col-6 col-lg-6 px-0 my-auto ">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-hit-rating text-dark" aria-hidden="true"></i> <?php echo $data['statistics']['all']['hits_percents']; ?>%
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Presici&oacute;n
              </div>
            </div>
            <div class="col-6 col-lg-6 px-0 my-auto">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-spotted text-dark" aria-hidden="true"></i> <?php echo round($data['statistics']['all']['spotted']/$data['statistics']['all']['battles']); ?>
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Expuestos/Batalla
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-12 stats-group d-flex justify-content-between flex-wrap px-0 order-lg-5 mt-lg-3">
            <div class="col-6 col-lg-6 px-0 my-auto">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-damage-done text-dark" aria-hidden="true"></i> <?php echo round($data['statistics']['all']['damage_dealt']/$data['statistics']['all']['battles']); ?>
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Da&ntilde;o/Batalla
              </div>
            </div>
            <div class="col-6 col-lg-6 px-0 my-auto ">
              <div class="col text-white stat-value px-0">
                <i class="wot fa icon-wot-stats-max-tank-detroyed text-dark" aria-hidden="true"></i> <?php echo round($data['statistics']['all']['frags']/$data['statistics']['all']['battles']); ?>
              </div>
              <div class="d-flex justify-content-center text-dark stat-desc mx-1 mx-lg-0">
                Destruidos/Batalla
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-9 pt-3 p-lg-4">
          <h2><?php echo $nickname; ?></h1>
        </div>
      </div>
    </main>
    <footer class="mt-auto">
      <div class="container d-lg-flex text-muted text-center text-lg-right flex-wrap">
        <hr class="col col-lg-12 mt-lg-0" />
        <div class="col col-lg-4 mr-lg-auto pb-2">
          <ul class="nav d-flex justify-content-around justify-content-lg-start">
            <li class="nav-item">
              <a class="nav-link" href="#">Active</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Disabled</a>
            </li>
          </ul>
        </div>
        <div class="col col-lg-4 pt-2 pb-3 py-lg-2 d-flex justify-content-center justify-content-lg-end order-lg-3">
          <span>Fallow us on:</span>
          <a href="#"><i class="fa fa-twitter-square link" aria-hidden="true"></i></a>
          <a href="#"><i class="fa fa-facebook-square link" aria-hidden="true"></i></a>
          <a href="#"><i class="fa fa-google-plus-square link" aria-hidden="true"></i></a>
        </div>
        <div class="col col-lg-4 text-center pt-lg-2 order-lg-2">
          <small>WoT.Statistics es gratuito, servicio web creado por un jugador para <a href="http://www.wargaming.net/" target="_blank">World of Tanks</a>. WoT-Life no es un sitio oficial de Wargaming.net o cualquiera de sus servicios.<br> World of Tanks es una marca comercial de <a href="http://www.wargaming.net/" target="_blank">Wargaming.net</a></small>
        </div>
      </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
