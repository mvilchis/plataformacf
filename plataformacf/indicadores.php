<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Inicio | Sistema de monitoreo de fondeo colectivo en México</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link href="https://framework-gb.cdn.gob.mx/assets/styles/main.css" rel="stylesheet">
  <link rel="shortcut icon" href="https://framework-gb.cdn.gob.mx/favicon.ico">
  <link rel="stylesheet" type="text/css" href="css/plataformacf.css" />
  <link href="https://framework-gb.cdn.gob.mx/assets/styles/main.css" rel="stylesheet">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

  <link rel="import" href="bower_components/polymer/polymer.html">
  <link rel="import" href="https://cdn.datos.gob.mx/bower_components/dgm-navbar/dgm-navbar.html">
  <link rel="import" href="https://cdn.datos.gob.mx/bower_components/dgm-footer/dgm-footer.html">
  <script type="text/javascript" src="bower_components/webcomponentsjs/webcomponents-lite.min.js"></script>
</head>
<body>
  <!-- Crowdfunding bar  -->
  <nav class=" navbar-crowdfunding navbar2 navbar-default navbar-fixed-top ">
    <div class="container container-title">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">
          <span>Fondeo Colectivo</span>
        </a>
      </div>
      <div class="collapse navbar-collapse" id="crowdfunding-navbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="explora.php">Explora</a></li>
          <li><a href="indicadores.php">Indicadores</a></li>
          <li><a href="acerca.php">Acerca</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- /Crowdfunding end bar -->
  <div>
    <div class="container" style="padding-top:60px;">
      <div class="col-sm-12">
        <div>
          <h2><b>Indicadores</b></h2>
        </div>
      </div>
      <div class="col-sm-12 texto_parrafo " >
    <?php
    include('h_objetivos.php');
    $result = file_get_contents("https://storage.googleapis.com/db-crowdfunding/json/cf_metadata.json");
    $metadata = json_decode($result, true);
    $indicadores_id = array();
    foreach($metadata["results"] as $value) {
    	if (array_key_exists($value["Nombre_del_objetivo"],$indicadores)) array_push($indicadores[$value["Nombre_del_objetivo"]],$value);
    	$indicadores_id[$value["Clave"]] = $value;
    }
    $i = 0;
    foreach($indicadores as $key => $objetivo) {
    	echo ('<div class="row indicador-header">
              <div class="col-xs-1" style="padding-top: 15px;">
                <img src="img/'.$objetivo_icons[$key].'.png"/>
              </div>
              <div class="col-xs-11">
                <h4><strong>'.($i+1).'.</strong> '.$objetivo_nombres[$key].'</h4>
              </div>
            </div>');
  	  foreach($objetivo as $indicador) {
        echo ( '<div class="row indicador-page-row" onmousedown="visit_indicador(\''.$i.'\',\''.$indicador["Clave"].'\')" >
                  <div class="col-xs-1"></div>
                  <div class="col-xs-11">'.$indicador["Nombre_del_indicador"]." : ".$indicador["Descripcion"]."
                  </div>
                </div>" );
  	  }
  	  $i++;
    }
  ?>
      </div>
    </div>
  </div>
  <script src="bower_components/jquery/dist/jquery.js"></script>
  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
  <script src="bower_components/underscore/underscore-min.js"></script>
  <script src="bower_components/moment/min/moment.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
   function visit_indicador(o,i) {
     window.location.href='explora?o='+o+'&i='+i;
    }
  </script>
  <?php include("acknowledgment.php"); ?>
   </body>
</html>
