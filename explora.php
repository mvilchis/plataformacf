<!DOCTYPE html>
<html lang="es" class="no-js js gr__104_196_231_10">
   <head profile="http://www.w3.org/1999/xhtml/vocab">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="canonical" href="explora">
      <title>Explora | Objetivos de Desarrollo Sostenible</title>
      <style>
         @import url("sites/all/modules/system/system.base.css?nuxtcd");
         @import url("sites/all/modules/field/theme/field.css?nuxtcd");
         @import url("sites/all/modules/views/css/views.css?nuxtcd");
         @import url("sites/all/modules/ctools/css/ctools.css?nuxtcd");
         @import url("sites/all/modules/panels/css/panels.css?nuxtcd");
         @import url('https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.5/leaflet.css');
         @import url("sites/all/themes/tweme/js/leaflet-search.min.css?nuxtcd");
         @import url('https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css');
         @import url("sites/all/themes/tweme/js/jquery.magnific-popup.css?nuxtcd");
         @import url("sites/all/themes/bootstrap/css/overrides.css?nuxtcd");
         @import url("sites/all/themes/tweme/common.css?nuxtcd");
         @import url("sites/all/themes/tweme/style.css?nuxtcd");
      </style>
      <link type="text/css" rel="stylesheet" href="sites/all/themes/bootstrap/css/bootstrap.min.css" media="all">
      <link href="sites/all/css" rel="stylesheet" type="text/css">
      <script src="sites/all/themes/tweme/js/jquery.min.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.once.js"></script>
      <script src="sites/all/themes/tweme/js/drupal.js"></script>
      <script src="sites/all/themes/bootstrap/js/bootstrap.min.js"></script>
      <script src="sites/all/themes/tweme/js/es_dNYhQGVAOhMJCAxlNL1aHF3vfJWSIvo1OthwwbycR8U.js"></script>
      <script src="sites/all/themes/tweme/js/fuse.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.5/leaflet.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.svg.min.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.svgdom.min.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.inline.min.js"></script>
      <script src="sites/all/themes/tweme/js/leaflet-search.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js"></script>
      <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/turf/v2.0.0/turf.min.js"></script>
      <script src="sites/all/themes/tweme/js/geom/nacion.json"></script>
      <script src="sites/all/themes/tweme/js/geom/entidad.json"></script>
      <script src="sites/all/themes/tweme/js/geom/municipio.json"></script>
      <script src="sites/all/themes/tweme/js/jquery.magnific-popup.min.js"></script>
      <script src="sites/all/themes/tweme/js/tweme.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.matchHeight-min.js"></script>
   </head>
   <?php
      include('h_objetivos.php');
      
      $o_id=pg_escape_string($_GET["o"]);
      $i_id=pg_escape_string($_GET["i"]);
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,"https://api.datos.gob.mx/v1/cf.metadata?pageSize=99999");
      $result=curl_exec($ch);
      curl_close($ch);
      $metadata = json_decode($result, true);
      $indicadores_id = array();
      foreach($metadata["results"] as $value) {
      	if (array_key_exists($value["Nombre_del_objetivo"],$indicadores)) array_push($indicadores[$value["Nombre_del_objetivo"]],$value);
      	$indicadores_id[$value["Clave"]] = $value;
      }
      
      foreach($indicadores as $key => $obj) {
      	if (count($obj) < 1) unset($indicadores[$key]);
      }
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,"https://api.datos.gob.mx/v1/cf.geo?pageSize=999999");
      $result=curl_exec($ch);
      curl_close($ch);
      
      $metadata_desag = json_decode($result, true);
      $desagregacion = array();
      foreach($metadata_desag["results"] as $value) {
      	if (!array_key_exists($value["DesGeo"],$desagregacion)) $desagregacion[$value["DesGeo"]] = array();
      	array_push($desagregacion[$value["DesGeo"]],$value["id"]);
      }
      
      $desagregacion_by_obj = array();
      foreach($metadata["results"] as $value) {
      	if (!array_key_exists($value["Nombre_del_objetivo"],$desagregacion_by_obj)) $desagregacion_by_obj[$value["Nombre_del_objetivo"]] = array();
      	$des = array('N','E','M');
      	foreach ($des as $d) {
      		if (!array_key_exists($d,$desagregacion_by_obj[$value["Nombre_del_objetivo"]])) $desagregacion_by_obj[$value["Nombre_del_objetivo"]][$d] = array();
      		if (in_array($value["Clave"],$desagregacion[$d])) array_push($desagregacion_by_obj[$value["Nombre_del_objetivo"]][$d],$value["Clave"]);
      	}
      }
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,"https://api.datos.gob.mx/v1/cf.grupos?pageSize=999999");
      $result=curl_exec($ch);
      curl_close($ch);
      
      $metadata_grupos = json_decode($result, true);
      $grupos = array();
      foreach($metadata_grupos["results"] as $value) {
      	if (!array_key_exists($value["id"],$grupos)) $grupos[$value["id"]] = array();
      	array_push($grupos[$value["id"]],$value);
      }
      
      ?>
   <body class="html not-front not-logged-in no-sidebars page-node page-node- page-node-1 node-type-page navbar-is-fixed-top bootstrap-anchors-processed" data-gr-c-s-loaded="true">
      <script type="text/javascript"></script>
      <?php include("header.php"); ?>
      <header class="header">
         <div class="jumbotron">
            <div class="container">
               <div class="col-xs-12 col-sm-6 page-caption">
                  Esta sección permite visualizar los indicadores de los cuales se dispone información para los 4 tipos de financiamiento/fondeo colectivo que existen en México. Igualmente ofrece la posibilidad de filtrar la información por tipo de desagregación y unidades territoriales menores, en el caso de que ésta se encuentre disponible, y exportarla para su manipulación al igual que los materiales gráficos que se generen por el usuario.
               </div>
            </div>
         </div>
      </header>
      <div class="region region-content-noncontainer">
         <section id="block-block-5" class="block block-block clearfix">
            <div id='loading_wrap' style='position:fixed; height:100%; width:100%; overflow:hidden; top:0; left:0;'>
               <div style="margin-right: 50px;">Cargando datos...</div>
            </div>
            <page class="explora">
               <section class="objective-selector">
                  <div class="container">
                     <div class="col-xs-12 col-sm-1 vcenter">Tipo</div>
                     <div class="col-xs-12 col-sm-5">
                        <div class="objective-selector-caption">Selecciona un tipo de plataforma</div>
                        <select id="select-objetivo-a">
                           <option>6 Erradicar pobreza</option>
                        </select>
                     </div>
                     <div class="col-xs-12 col-sm-1 vcenter">Indicador</div>
                     <div class="col-xs-12 col-sm-5">
                        <div class="objective-selector-caption">Selecciona un indicador</div>
                        <select id="select-indicador-a">
                           <option>Proporción de población en pobreza</option>
                        </select>
                     </div>
                  </div>
               </section>
               <div id="map">
                  <div class="infobox" style="display: none;">
                     <div style="margin-bottom: 8px;" class="row">
                        <div class="name-box col-xs-9">
                           <div class="unit-name">--</div>
                           <div class="edo-name">--</div>
                        </div>
                        <div class="edo-image col-xs-3">--</div>
                     </div>
                     <div class="row values-row">
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
                  <div onmousedown="exportToPDF('map');" class="btn btn-map-export btn-outline">Exportar mapa</div>
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
                        </form>
                     </div>
                  </div>
               </div>
               <section class="year-selector">
                  <div class="year-select">
                  </div>
               </section>
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
      <script src="sites/all/themes/bootstrap/js/bootstrap.js"></script>
   </body>
</html>