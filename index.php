<!DOCTYPE html>
<html lang="es" class="no-js js gr__localhost">
<head profile="http://www.w3.org/1999/xhtml/vocab">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio | Sistema de monitoreo de fondeo colectivo en México</title>
  <!--                          Style                                    -->
  <link rel="stylesheet" type="text/css" href="css/system.base.css"/>
  <link rel="stylesheet" type="text/css" href="css/field.css"/>
  <link rel="stylesheet" type="text/css" href="css/views.css"/>
  <link rel="stylesheet" type="text/css" href="css/ctools.css"/>
  <link rel="stylesheet" type="text/css" href="css/panels.css"/>
  <link rel="stylesheet" type="text/css" href="css/field.css"/>
  <link rel="stylesheet" type="text/css" href="css/flexible.css"/>
  <link rel="stylesheet" type="text/css" href="css/64d7d0a3c55866afec0187d45d6e1cfe.css"/>
  <link rel="stylesheet" type="text/css" href="css/jquery.magnific-popup.css"/>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="css/overrides.css"/>
  <link rel="stylesheet" type="text/css" href="css/common.css"/>
  <link rel="stylesheet" type="text/css" href="css/style.css"/>
  <link rel="stylesheet" type="text/css" href="css/css">
  <link rel="stylesheet" type="text/css" href="css/critical.css" />
  <link rel="stylesheet" type="text/css" href="css/plataformacf.css" />
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400"/>
  <!--                      script                                       -->
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.once.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/jquery.matchHeight-min.js"></script>
</head>
<?php
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
?>
<body class="html front not-logged-in no-sidebars page-inicio navbar-is-fixed-top bootstrap-anchors-processed" data-gr-c-s-loaded="true">
  <header id="dgm-navbar">
    <nav class="navbar navbar-inverse navbar-fixed-top gob-mx-navbar" role="navigation" style="width: 100%; margin: 0; max-width: 100%;">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" id="navbarMain" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarMainCollapse">
            <span class="sr-only">Interruptor de Navegación</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" title="Ir a la página de inicio de datos.gob.mx" href="http://datos.gob.mx/">
            <img src="img/ic-dgm-logo.png" alt="datos.gob.mx" style="padding: 4px;">
          </a>
        </div>
        <div class="collapse navbar-collapse" id="navbarMainCollapse">
          <ul class="nav navbar-nav navbar-right social-navigation" role="navigation" aria-label="Navegación social">
            <li class="item-facebook"><a href="http://facebook.com/datosgobmx" target="_blank">Facebook</a></li>
            <li class="item-twitter"><a href="http://twitter.com/datosgobmx" target="_blank">Twitter</a></li>
            <li class="item-github"><a href="http://github.com/mxabierto" target="_blank">Github</a></li>
          </ul>
          <ul class="nav navbar-nav">
            <li>
              <a aria-label="Ir al catalogo de datos" title="Ir al catalogo de datos" href="http://datos.gob.mx/busca">Datos</a>
            </li>
            <li>
              <a aria-label="Conoce los Datos Abiertos más buscados" target="_self" title="Conoce los Datos Abiertos más buscados" href="http://datos.gob.mx/visualizacion">Visualización</a>
            </li>
            <li>
              <a aria-label="Conoce las herramientas con datos" title="Conoce las herramientas con datos" href="http://datos.gob.mx/herramientas">Herramientas</a>
            </li>
            <li>
              <a aria-label="Conoce el impacto de los datos" title="Conoce el impacto de los datos" href="http://datos.gob.mx/blog">Blog</a>
            </li>
            <li>
              <a aria-label="Conoce más sobre este sitio" title="Conoce más sobre este sitio" href="/acerca">Acerca</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <nav class="navbar navbar-default navbar-fixed-top" style="background-color:#00CC99;">
      <div class="container-fluid">
        <div class="navbar-header" style="padding-top: 15px;padding-bottom: 15px;">
          <span style="font-size: 18px;font-weight: 700;color: white;"> Crowdfunding Mx </span>
        </div>
        <div class="collapse navbar-collapse" id="navbarSecondCollapse">
          <ul class="nav navbar-nav navbar-right " role="navigation">
            <li>
              <a aria-label="Ir al catalogo de datos" title="Ir al catalogo de datos" href="/explora"  style="font-size:16px;">Explora</a>
            </li>
            <li>
              <a aria-label="Conoce el impacto de los datos" title="Conoce el impacto de los datos" href="/indicadores"  style="font-size:16px;">Indicadores</a>
            </li>
            <li>
              <a aria-label="Conoce los Datos Abiertos más buscados" target="_self" title="Conoce los Datos Abiertos más buscados" href="/acerca"  style="font-size:16px;">Acerca</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <header class="header" style="color:white;">
    <div style="background:white;">
    <!-- Banner -->
    <div class="region region-header image_background" >
      <section id="block-block-1" class="block block-block clearfix">
        <div class="block block-block clearfix inner ">
          <div>
            </br>
            </br>
            </br>
            <h1 style="font-size: 45px;">Sistema de monitoreo de fondeo colectivo en México</h1>
          </div>
          <div>
            <p><b>El sistema de monitoreo de plataformas de fondeo colectivo en México es una iniciativa del proyecto Crowdfunding México</b></p>
            <p> <b>que te permitirá visualizar información en tiempo real sobre la evolución del fondeo colectivo en México </b></p>
            </br>
            </br>
            </br>
          </div>
        </div>
        <row>
          <div class="col-xs-12 col-sm-4">
            <h3> Proyectos totales fondeados:</h3>
            <h1 style="font-size: 40px;" >23,422</h1>
            </br>
            </br>
            </br>
          </div>
          <div class="col-xs-12 col-sm-4">
            <h3>Fondeo total:</h3>
            <h1 style="font-size: 40px;">$132 MDP</h1>
          </div>
          <div class="col-xs-12 col-sm-4">
            <h3>Plataformas en linea:</h3>
            <h1 style="font-size: 40px;">17</h1>
          </div>
        </row>
      </section>
    </div>
    <div class="region region-header" style="color:black;">
      <div class="container">
        <section id="block-block-2" class="block block-block clearfix">
          <div class="jumbotron-block col-xs-1 col-sm-12" style="color:#6A6A6A">
            <div class="header_mx">
              <h2>Indicadores</h2>
            </div>
          </div>
          <div class="jumbotron-block col-xs-1 col-sm-12" style="color:#6A6A6A">
            </br>
            <?php
              $dire = __DIR__ . '/json/partition';
              $fi = iterator_count(new FilesystemIterator($dire, FilesystemIterator::SKIP_DOTS));
              echo('<p style="color:black">Consulta datos de más de ' . $fi . ' indicadores sobre la evolución del financiamiento colectivo en México.</p>');
              ?>
            <p style="color:black">Primero selecciona un tipo de financiamiento colectivo, y posteriormente elige un indicador:</p>
          </div>
          <?php
            echo('<div class="jumbotron-block col-sm-12 col-xs-12">');
            $i = 0;
            foreach ($indicadores as $key => $objetivo) {
              if ($i == 9) {
                echo("</div>");
                echo('<div class="jumbotron-block col-sm-12 col-xs-12">');
              }
              if (count($objetivo) < 1) {
                $empty_class = " ind-empty";
                $tt = ' data-toggle="tooltip" data-placement="right" title="Próximamente" ';
              } else {
                $empty_class = "";
                $tt = "";
              }
              echo('<div class="col-xs-12 noselect indicador-group'.$empty_class.'" value="'.$i.'"'.$tt.'>
                    <div class="row indicador-row"><div class="col-xs-12">
                    <div class="objetivo-name" style="color:#6A6A6A">
                    <img src="img/'.$objetivo_icons[$key].'.png"/>
                    <strong>'.($i+1).'. '.$objetivo_nombres[$key].'</strong>'.'</div></div>
                    </div><div style="display: none;" class="row listed-indicadores"><div class="listed-indicadores-title">INDICADORES</div>');

              foreach ($objetivo as $indicador) {
                echo('<div onmousedown="visit_indicador(\''.$i.'\',\''.$indicador["Clave"].'\')" class="listed-indicador">
                      <div class="col-xs-12">'.$indicador["Nombre_del_indicador"]."</div></div>");
              }
              echo('</div></div>');
              $i++;
            }
          ?>
        </div>
        <script type="text/javascript">
          (function ($) {
            $(document).ready(function() {
              $('.popup-youtube').magnificPopup({
                disableOn: 640,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
              });
              $('[data-toggle="tooltip"]').tooltip();
            });
          }(jQuery));
        </script>
        <script type="text/javascript">
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
          }(jQuery));
          function visit_objetivo(n,i) {
            window.location.href='explora?o='+i;
          }
          function visit_indicador(o,i) {
            window.location.href='explora?o='+o+'&i='+i;
          }
        </script>
        </section> <!-- /.block -->
      </div>
    </div>
    </div>
    <div class="header-bottom">
      <div class="container">
      </div>
    </div>
  </header>
  <div class="header-bottom">
    <div class="container">
    </div>
  </div>
  <div class="region region-header" style="color:black;">
    <div class="container">
      <section id="block-block-3" class="block block-block clearfix">
        <div class="jumbotron-block col-xs-12 col-sm-8" style="color:#6A6A6A">
          <div class="header_mx">
            <h2>Explora</h2>
          </div>
        </div>
        <div class="jumbotron-block col-xs-12 col-sm-8" style="color:#6A6A6A">
          </br>
          <?php
              $dire = __DIR__ . '/json/partition';
              $fi = iterator_count(new FilesystemIterator($dire, FilesystemIterator::SKIP_DOTS));
              echo('<p style="color:black">Consulta datos de más de ' . $fi . '  indicadores sobre la evolución del financiamiento colectivo en México </p>');
              ?>
          <p style="color:black">Primero selecciona un tipo de financiamiento colectivo, y posteriormente elige un indicador:</p>
        </div>
        <section class="col-xs-12 col-sm-2">
          <button class="btn btn-line-export btn-outline" type="button" onclick="visit_indicador('0','i41')" > Ir a la sección </button>
        </section>
      </section>
    </div>
  </div>
  <?php include("footer.php"); ?>
</body>
</html>
