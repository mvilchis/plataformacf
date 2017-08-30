<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Explora | Sistema de monitoreo de fondeo colectivo en México</title>
  <?php $page = "explora"; ?>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link href="https://framework-gb.cdn.gob.mx/assets/styles/main.css" rel="stylesheet">
  <link rel="shortcut icon" href="https://framework-gb.cdn.gob.mx/favicon.ico">
  <link rel="stylesheet" type="text/css" href="css/plataformacf.css" />
  <link href="https://framework-gb.cdn.gob.mx/assets/styles/main.css" rel="stylesheet">

  <link rel="import" href="bower_components/polymer/polymer.html">
  <link rel="import" href="https://cdn.datos.gob.mx/bower_components/dgm-navbar/dgm-navbar.html">
  <link rel="import" href="https://cdn.datos.gob.mx/bower_components/dgm-footer/dgm-footer.html">
  <script type="text/javascript" src="bower_components/webcomponentsjs/webcomponents-lite.min.js"></script>

  <!--                      leaflet                                       -->
  <style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css');
    @import url('https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css');
  </style>
  <link rel="stylesheet" type="text/css" href="css/leaflet-search.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js"></script>
  <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/turf/v2.0.0/turf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.5/leaflet.js"></script>
  <script src="js/leaflet-search.min.js"></script>
  <script src="js/leaflet.easyPrint.js"></script>
  <script src="json/nacion.json"></script>
  <script src="json/entidad.json"></script>
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
  <!-- PHP LOAD JSON FILES -->
  <?php
    include('h_objetivos.php');
    $o_id=pg_escape_string($_GET["o"]);
    $i_id=pg_escape_string($_GET["i"]);

    $result = file_get_contents("json/cf_metadata.json");
    $metadata = json_decode($result, true);
    $indicadores_id = array();
    foreach ($metadata["results"] as $value) {
        if (array_key_exists($value["Nombre_del_objetivo"], $indicadores)) {
            array_push($indicadores[$value["Nombre_del_objetivo"]], $value);
        }
        $indicadores_id[$value["Clave"]] = $value;
    }
    foreach ($indicadores as $key => $obj) {
        if (count($obj) < 1) {
            unset($indicadores[$key]);
        }
    }

    $result = file_get_contents("json/cf_geo.json");
    $metadata_desag = json_decode($result, true);
    $desagregacion = array();
    foreach ($metadata_desag["results"] as $value) {
        if (!array_key_exists($value["DesGeo"], $desagregacion)) {
            $desagregacion[$value["DesGeo"]] = array();
        }
        array_push($desagregacion[$value["DesGeo"]], $value["id"]);
    }
    $desagregacion_by_obj = array();
    foreach ($metadata["results"] as $value) {
        if (!array_key_exists($value["Nombre_del_objetivo"], $desagregacion_by_obj)) {
            $desagregacion_by_obj[$value["Nombre_del_objetivo"]] = array();
        }
        $des = array('N','E','M');
        foreach ($des as $d) {
            if (!array_key_exists($d, $desagregacion_by_obj[$value["Nombre_del_objetivo"]])) {
                $desagregacion_by_obj[$value["Nombre_del_objetivo"]][$d] = array();
            }
            if (in_array($value["Clave"], $desagregacion[$d])) {
                array_push($desagregacion_by_obj[$value["Nombre_del_objetivo"]][$d], $value["Clave"]);
            }
        }
    }
    $result = file_get_contents("json/cf_grupos.json");
    $metadata_grupos = json_decode($result, true);
    $grupos = array();
    foreach ($metadata_grupos["results"] as $value) {
        if (!array_key_exists($value["id"], $grupos)) {
            $grupos[$value["id"]] = array();
        }
        array_push($grupos[$value["id"]], $value);
    }
  ?>
  <!-- <PHP LOAD JSON FILES END-->
  <!--  Header text -->
  <div>
    <div class="container" style="padding-top:60px;">
      <div class="col-sm-12">
        <div>
          <h2><b>Explora</b></h2>
        </div>
      </div>
      <div class="col-sm-12 texto_parrafo" >
        <p >Esta sección permite visualizar los indicadores de los cuales se dispone información para los 4 tipos de financiamiento/fondeo colectivo que existen en México. Igualmente ofrece la posibilidad de filtrar la información por tipo de desagregación y unidades territoriales menores, en el caso de que ésta se encuentre disponible, y exportarla para su manipulación al igual que los materiales gráficos que se generen por el usuario. </p>
        <p>
          (Nota: El total Nacional puede no coincidir con la suma de los estados, ya que existen proyectos no categorizados geográficamente)
        </p>
      </div>
    </div>
  </div>
  <!-- < End header text-->

    <div class="region region-content-noncontainer">
      <section id="block-block-5" class="block block-block clearfix">
        <div id='loading_wrap' style='position:fixed; height:100%; width:100%; overflow:hidden; top:0; left:0;'>
          <div style="margin-right: 50px;">Cargando datos...
          </div>
        </div>

        <page class="explora">
          <section class="objective-selector">
            <div class="container" style="padding-bottom:20px;padding-top:20px;">
              <div class="col-xs-12 col-sm-1 vcenter">
                Tipo
              </div>
              <div class="col-xs-12 col-sm-5">
                <div class="objective-selector-caption">
                  Selecciona un tipo de indicador
                </div>
                <select id="select-objetivo-a">
                  <option>--</option>
                </select>
              </div>
              <div class="col-xs-12 col-sm-1 vcenter">
                Indicador
              </div>
              <div class="col-xs-12 col-sm-5">
                <div class="objective-selector-caption">
                  Selecciona un indicador
                </div>
                <select id="select-indicador-a">
                  <option>--</option>
                </select>
              </div>
            </div>
          </section>
          <div class="container" style="padding-bottom:30px;">
            <div class="vcenter">
              Selecciona un trimestre:
            </div>
            <div class="slider" id="year_slider"></div>
          </div>
          <div id="map">
            <div class="infobox" style="display: none;">
              <div class="row">
                <div class="name-box col-xs-9">
                  <div class="unit-name">--</div>
                  <div class="edo-name">--</div>
                </div>
                <div class="edo-image col-xs-3">--</div>
              </div>
              <div class="row values-row" style="display: block;">
                <table>
                  <tr>
                  <td class="col-xs-4 indicador-valor">--</td>
                          <td class="col-xs-8 indicador-nombre">--</td>
                        </tr>
                      </table>
                    </div>
                    <div id="infobox-line-chart"></div>
                    <div class="map-legend">
                      <table id="legend-colors">
                        <tbody>
                          <tr>
                            <td class="legend-color legend-color-0" onmouseover="highlightFromLegend('0')" onmouseout="clearHighlight();" style="background-color: #ddfff6;"></td>
                            <td class="legend-color legend-color-1" onmouseover="highlightFromLegend('1')" onmouseout="clearHighlight();" style="background-color: #7fd;"></td>
                            <td class="legend-color legend-color-2" onmouseover="highlightFromLegend('2')" onmouseout="clearHighlight();" style="background-color: #00cc99;"></td>
                            <td class="legend-color legend-color-3" onmouseover="highlightFromLegend('3')" onmouseout="clearHighlight();" style="background-color: #086;"></td>
                          </tr>
                          <tr>
                            <td class="legend-breaks legend-breaks-0"></td>
                            <td class="legend-breaks legend-breaks-1"></td>
                            <td class="legend-breaks legend-breaks-2"></td>
                            <td class="legend-breaks legend-breaks-3"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="filters col-xs-12">
                    <div class="container">
                      <form class="form-inline" role="form">
                        <label class="filter-header">Filtrar por</label>
                        <div style="display: none;" class="form-group form-group-grupo">
                          <label class="filter-header">GRUPO</label>
                          <select id="filter-grupo" class="filter-group filter-grupo">
                            <option class="filter-item" value="">-- Todos --</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="filter-header">DESAGREGACIÓN</label>
                          <select id="filter-geo" class="filter-group filter-geo">
                               <option class="filter-item">Nacional</option>
                               <option class="filter-item">Estatal</option>
                               <option class="filter-item">Municipal</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="filter-header">Ver por:</label>
                          <label class="filter-header">Trimestral</label>
                          <div class="form-group form-group-grupo">

                          <label class="switch">
                            <input type="checkbox" id="trim_to_ac">
                            <span class="check-slider round"></span>
                          </label>
                        </div>
                        <label class="filter-header">Acumulado</label>

                      </div>

                      </form>
                    </div>
                  </div>
                </div>

                <?php include('section_stats.php'); ?>
                <?php include('section_datatable.php'); ?>
              </page>

              <?php include('api_graphics.php');  ?>
            </section>
            <!-- /.block -->
          </div>
          <section class="main">
             <div class="container">
                <div class="row">
                   <section class="main-col col-md-12">
                      <div class="region region-content">
                         <section id="block-system-main" class="block block-system clearfix">
                            <div id="node-1" class="node node-page clearfix" about="/explora" typeof="foaf:Document">
                               <span property="dc:title" content="Explora" class="rdf-meta element-hidden"></span><span property="sioc:num_replies" content="0" datatype="xsd:integer" class="rdf-meta element-hidden"></span>
                               <div class="content">
                               </div>
                            </div>
                         </section>
                         <!-- /.block -->
                      </div>
                   </section>
                </div>
              </div>
          </section>
          <?php include("footer.php"); ?>
          <script src="js/bootstrap.min.js"></script>
    </body>


    <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
    <script src="bower_components/underscore/underscore-min.js"></script>
    <script src="bower_components/bootstrap-year-calendar/js/bootstrap-year-calendar.min.js"></script>
    <script src="bower_components/moment/min/moment.min.js"></script>
    <script src="bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

    <?php  include('acknowledgment.php');?>
    </body>
    </html>
