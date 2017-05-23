<!DOCTYPE html>
<html lang="es" class="no-js js gr__localhost">
   <head profile="http://www.w3.org/1999/xhtml/vocab">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="Generator" content="Drupal 7 (http://drupal.org)">
      <title>Inicio | Objetivos de Desarrollo Sostenible</title>
      <!--                          Style                                    -->
      <link rel="stylesheet" type="text/css" href="css/system.base.css" />
      <link rel="stylesheet" type="text/css" href="css/field.css" />
      <link rel="stylesheet" type="text/css" href="css/views.css" />
      <link rel="stylesheet" type="text/css" href="css/ctools.css" />
      <link rel="stylesheet" type="text/css" href="css/panels.css" />
      <link rel="stylesheet" type="text/css" href="css/flexible.css" />
      <link rel="stylesheet" type="text/css" href="css/64d7d0a3c55866afec0187d45d6e1cfe.css" />
      <link rel="stylesheet" type="text/css" href="css/jquery.magnific-popup.css" />
      <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" media="all"/>
      <link rel="stylesheet" type="text/css" href="css/overrides.css" />
      <link rel="stylesheet" type="text/css" href="css/common.css" />
      <link href="css/css" rel="stylesheet" type="text/css">
      <link rel="stylesheet" type="text/css" href="css/style.css" />
      <link rel="stylesheet" type="text/css" href="css/critical.css" />
      <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400"/>
      <!--                      script                                       -->
      <script src="js/jquery.min.js"></script>
      <script src="js/jquery.once.js"></script>
      <script src="js/drupal.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/es_dNYhQGVAOhMJCAxlNL1aHF3vfJWSIvo1OthwwbycR8U.js"></script>
      <script src="js/jquery.magnific-popup.min.js"></script>
      <script src="js/tweme.js"></script>
      <script src="js/jquery.matchHeight-min.js"></script>
      <?=include_once('header.php');?>

   </head>
   <?php
      include('h_objetivos.php');

      
      $result = file_get_contents("json/cf_metadata.json");

      $metadata = json_decode($result, true);
      $indicadores_id = array();
      foreach($metadata["results"] as $value) {
        if (array_key_exists($value["Nombre_del_objetivo"],$indicadores)) array_push($indicadores[$value["Nombre_del_objetivo"]],$value);
        $indicadores_id[$value["Clave"]] = $value;
      }
      ?>
   <body class="html front not-logged-in no-sidebars page-inicio navbar-is-fixed-top bootstrap-anchors-processed" data-gr-c-s-loaded="true">
    <header class="header text-white">
        <div class="jumbotron" style="background:white;">
          <!-- Banner -->
      <div class="region region-header" style="background:#474747">
			<section id="block-block-1" class="block block-block clearfix">
				<div class="block block-block clearfix inner">
						<div>
							<h2>Fondeo colectivo en datos</h2>
						</div>
						<div>
							<p>La Comisión Nacional Bancaria y de Valores y la Asociación de Plataformas de Fondeo Colectivo, para impulsar proyectos vía</p>
              <p> crowdfunding o de financiamiento/fondeo colectivo en México </p>
						</div>
				</div>
        <div>
        </br>
        </br>
        </br>
        </br>
        </br>
        </br>
        </br>
        </br>
        </div>

			</section>
    </div>

            <div class="region region-header text-black">
             <div class="container">

              <section id="block-block-2" class="block block-block clearfix">
                <div class="jumbotron-block col-xs-1 col-sm-12 text-black">
                  <h4 >Indicadores</h4>
                  <p style="color:black">Consulta datos de más de X indicadores sobre la evolución del financiamiento colectivo en México.</p>
                  <p>Primero selecciona un tipo de financiamiento colectivo, y posteriormente elige un indicador:</p>

                </div>
                <?php
                  echo('<div class="jumbotron-block col-sm-12 col-xs-12">');
                  $i = 0;
                  foreach($indicadores as $key => $objetivo) {
                    if ($i == 9) {
                      echo("</div>");
                      echo('<div class="jumbotron-block col-sm-12 col-xs-12">');
                    }
                    if (count($objetivo) < 1) {
                      $empty_class = " ind-empty";
                      $tt = ' data-toggle="tooltip" data-placement="right" title="Próximamente" ';
                    }
                    else {
                      $empty_class = "";
                      $tt = "";
                    }
                    echo ('<div class="col-xs-12 noselect indicador-group'.$empty_class.'" value="'.$i.'"'.$tt.'>
                        <div class="row indicador-row"><div class="col-xs-12">
                        <div class="objetivo-name">
                        <img src="img/'.$objetivo_icons[$key].'.png"/>
                        <strong>'.($i+1).'. </strong>'.$objetivo_nombres[$key].'
                        </div>
                        </div>
                        </div><div style="display: none;" class="row listed-indicadores"><div class="listed-indicadores-title">INDICADORES</div>');
                    foreach($objetivo as $indicador) {
                      echo ( '<div onmousedown="visit_indicador(\''.$i.'\',\''.$indicador["Clave"].'\')" class="listed-indicador"><div class="col-xs-12">'.$indicador["Nombre_del_indicador"]."</div></div>" );
                    }
                    echo ('</div></div>');
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
        </div>
        <div class="header-bottom">
          <div class="container">
          </div>
        </div>
      </header>

      <section class="clearfix">
         <div class="container">
            <div class="row">
               <section class="col-xs-12 col-sm-10">
                <h4>Explora </h4>
                <p>Consulta datos de más de X indicadores sobre la evolución del financiamiento colectivo en México </p>
                <p>Primero selecciona un tipo de financiamiento y posteriormente elige un indicador: </p>
               </section>
               <section class="col-xs-12 col-sm-2">
                <button type="button" > Ir a la sección </button>
               </section>
            </div>
         </div>
      </section>
    <?php include("footer.php"); ?>
   </body>
</html>
