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
  <script src="json/data_explanation.json"></script>

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
  <!-- Crowdfunding banner -->

  <div id="site-banner">
    <div style="display: table;">
      <div class="row">
        <div class="col-md-12 title-banner" >
          <span >Sistema de monitoreo de Fondeo Colectivo</span>
        </div>
        <div class="col-md-12 subtitle-banner">
          <span> El sistema de monitoreo de plataformas de fondeo colectivo en México es una iniciativa del proyecto Crowdfunding México </span>
        </br>
          <span>que te permitirá visualizar información en tiempo real sobre la evolución del fondeo colectivo en México
          </span>
        </div>
        <div class="col-md-12 data-title-banner">
          <div class="col-md-4">
            <span>PROYECTOS TOTALES</span>
            <br>
            <span>FONDEADOS</span>
          </div>
          <div class="col-md-4">
            <span>FONDEO</span>
            <span>TOTAL</span>
          </div>
          <div class="col-md-4">
            <span>PLATAFORMAS</span>
            <span>EN LINEA</span>
          </div>
        </div>
        <div class="col-md-12 data-banner" >
          <div class="col-md-4">
            <span id="total_proyect">23,422</span>
          </div>
          <div class="col-md-4">
            <span id="total_amount">$132 MDP</span>
          </div>
          <div class="col-md-4">
            <span id="total_plataforms"> 17</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Crowdfunding banner end-->
  <!-- Indicadores section -->
  <div>
    <div class="container" style="padding-top:12px;">
      <div class="col-sm-12">
        <div>
          <h2><b>Indicadores</b></h2>
        </div>
      </div>
      <div class="col-sm-12 texto_parrafo" >
        <!--CAMBIAR DINAMICAMENTE #indicadores -->
        <p>
          Consulta datos de más de 11 indicadores sobre la evolución del financiamiento colectivo en México.</p>
        <p>Primero selecciona un tipo de financiamiento colectivo, y posteriormente elige un indicador:</p>
      </div>
      <?php
        // Read indicadores
        include('h_objetivos.php');
        $result = file_get_contents("json/cf_metadata.json");
        $metadata = json_decode($result, true);
        $indicadores_id = array();
        foreach ($metadata["results"] as $value) {
          if (array_key_exists($value["Nombre_del_objetivo"], $indicadores)) {
            array_push($indicadores[$value["Nombre_del_objetivo"]], $value);
          }
          $indicadores_id[$value["Clave"]] = $value;
        }
        //Write indicadores
        echo('<div class="col-sm-12">');
        $i = 0;
        foreach ($indicadores as $key => $objetivo) {
          if ($i == 9) { //CAMBIAR DINAMICAMENTE #indicadores
            echo("</div>");
            echo('<div class="col-sm-12">');
          }
          if (count($objetivo) < 1) {
            $empty_class = " ind-empty";
            $tt = ' data-toggle="tooltip" data-placement="right" title="Próximamente" ';
          } else {
            $empty_class = "";
            $tt = "";
          }
          echo('<div class="noselect indicador-group'.$empty_class.'" value="'.$i.'"'.$tt.'>
                  <div class="row indicador-row">
                    <div class="col-xs-12">
                      <div class="objetivo-name texto_parrafo" >
                        <div class="objetivo-padding">
                          <img src="img/'.$objetivo_icons[$key].'.png"/>
                          <span style="margin-left: 10px;"><strong>'.($i+1).'. '.$objetivo_nombres[$key].'</strong></span>'.
                        '<span class="end-indicador">&#8250;</span></div>
                      </div>
                    </div>
                  </div>
                  <div style="display: none;" class="row listed-indicadores">
                    <div class="listed-indicadores-title">INDICADORES
                  </div>');
              foreach ($objetivo as $indicador) {
                if (strlen($indicador["Clave"]) == 3) {
                  echo('<div onmousedown="visit_indicador(\''.$i.'\',\''.$indicador["Clave"].'\')" class="listed-indicador">
                      <div class="col-xs-12">'.$indicador["Nombre_del_indicador"].
                      "</div>
                    </div>");
                  }
                }
                echo('</div></div>');
            $i++;
          }
        ?>
      </div>
    </div>
  </div>
  <!-- Indicadores section End -->
  <!-- Expora section -->
  <div>
    <div class="container">
      <section>
        <div class="col-sm-12">
          <h2><b>Explora</b></h2>
        </div>
        <div class="col-sm-9 texto_parrafo" style="padding-bottom: 30px;">
          </br>
          <p >Consulta datos de más de 11  indicadores sobre la evolución del financiamiento colectivo en México.</p>
          <p>Primero selecciona un tipo de financiamiento colectivo, y posteriormente elige un indicador:</p>
        </div>
        <section class="col-sm-3" style="padding-top:30px">
          <button class="btn btn-primary mxbutton" type="button" onclick="visit_indicador('0','i41')"> Ir a la sección </button>
        </section>
      </section>
    </div>
  </div>
  <!-- Expora section end -->
  <script src="bower_components/jquery/dist/jquery.js"></script>
  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
  <script src="bower_components/underscore/underscore-min.js"></script>
  <script src="bower_components/bootstrap-year-calendar/js/bootstrap-year-calendar.min.js"></script>
  <script src="bower_components/moment/min/moment.min.js"></script>
  <script src="bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript">
  function commaSeparateNumber(x){
    var parts=x.toString().split(".");
    parts[0]=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g,",");
    return parts.join(".");
  }
    (function ($) {
      $(".indicador-group:not(.ind-empty) .indicador-row").mousedown(function() {
        if ($(this).parents('.indicador-group').find(".listed-indicadores").is(":visible")) {
          $(".listed-indicadores").slideUp();
        }
        else {
          $(".listed-indicadores").slideUp();
          $(this).parents('.indicador-group').find(".listed-indicadores").slideToggle();
        }
      });
      //$(#total_amount).text();
      //
    }(jQuery));
    $("#total_proyect").html(commaSeparateNumber(data_explanation["total_proyect"]));
    $("#total_amount").html("$"+commaSeparateNumber(data_explanation["total_amount"])+" MDP");
    $("#total_plataforms").html(commaSeparateNumber(data_explanation["total_plataforms"]));

    function visit_objetivo(n,i) {
      window.location.href='explora?o='+i;
    }
    function visit_indicador(o,i) {
      window.location.href='explora?o='+o+'&i='+i;
    }


  </script>
  <?php  include('acknowledgment.php');?>

</body>
</html>
