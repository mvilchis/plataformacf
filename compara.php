<!DOCTYPE html>
<html lang="es" class="no-js js gr__104_196_231_10">
   <head profile="http://www.w3.org/1999/xhtml/vocab">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="canonical" href="compara">
      <title>Compara | Objetivos de Desarrollo Sostenible</title>
      <style>
         @import url("sites/all/modules/system/system.base.css?nuxtcd");
         @import url("sites/all/modules/field/theme/field.css?nuxtcd");
         @import url("sites/all/modules/views/css/views.css?nuxtcd");
         @import url("sites/all/modules/ctools/css/ctools.css?nuxtcd");
         @import url("sites/all/modules/panels/css/panels.css?nuxtcd");
         @import url('https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.5/leaflet.css');
         @import url("sites/all/themes/tweme/js/leaflet-search.min.css?nuxtcd");
         @import url("sites/all/themes/tweme/js/dragit.css?nuxtcd");
         @import url("sites/all/themes/tweme/js/jquery.magnific-popup.css?nuxtcd");
         @import url("sites/all/themes/bootstrap/css/overrides.css?nuxtcd");
         @import url("sites/all/themes/tweme/common.css?nuxtcd");
         @import url("sites/all/themes/tweme/style.css?nuxtcd");
      </style>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js"></script>
      <link type="text/css" rel="stylesheet" href="sites/all/themes/bootstrap/css/bootstrap.min.css" media="all">
      <link href="sites/all/css" rel="stylesheet" type="text/css">
      <script src="sites/all/themes/tweme/js/jquery.min.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.once.js"></script>
      <script src="sites/all/themes/tweme/js/drupal.js"></script>
      <script src="sites/all/themes/bootstrap/js/bootstrap.min.js"></script>
      <script src="sites/all/themes/tweme/js/es_dNYhQGVAOhMJCAxlNL1aHF3vfJWSIvo1OthwwbycR8U.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.5/leaflet.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.svg.min.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.svgdom.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
      <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/turf/v2.0.0/turf.min.js"></script>
      <script src="sites/all/themes/tweme/js/dragit.js"></script>
      <script type="text/javascript" src="sites/all/themes/tweme/js/geom/nacion.json"></script>
      <script type="text/javascript" src="sites/all/themes/tweme/js/geom/entidad.json"></script>
      <script type="text/javascript" src="sites/all/themes/tweme/js/geom/municipio.json"></script>
      <script src="sites/all/themes/tweme/js/jquery.magnific-popup.min.js"></script>
      <script src="sites/all/themes/tweme/js/tweme.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.matchHeight-min.js"></script>
   </head>
   <body class="html not-front not-logged-in no-sidebars page-node page-node- page-node-8 node-type-page navbar-is-fixed-top bootstrap-anchors-processed" data-gr-c-s-loaded="true">
      <?php include("header.php"); ?>
      <header class="header">
         <div class="jumbotron">
            <div class="container">
               <div class="col-xs-12 col-sm-6 page-caption">
                  Esta sección permite llevar a cabo el cruce de dos indicadores seleccionados por el usuario, a fin de que pueda analizar de manera sencilla y visual la correlación existente entre dos variables. Igualmente se puede llevar a cabo un seguimiento del comportamiento de los dos indicadores seleccionados en el tiempo por medio de un gráfico y exportar la información, mapas y gráficos para su uso.
               </div>
            </div>
         </div>
      </header>
      <div class="region region-content-noncontainer">
         <section id="block-block-6" class="block block-block clearfix">
            <style>
               @import url('https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css');
            </style>
            <script type="text/javascript" src="sites/all/themes/tweme/js/leaflet-search.min.js"></script>
            <style>
               #gm-chart {
               padding: 30px;
               margin-left: -40px;
               }
               #gm-chart text {
               font-family: 'Open Sans', 'Helvetica Neue', 'Helvetica', sans-serif;
               font-size: 10px;
               }
               #gm-chart .dot {
               stroke: #000;
               }
               #gm-chart .axis path, .axis line {
               fill: none;
               stroke: #aaa;
               shape-rendering: crispEdges;
               }
               #gm-chart .country.label  {
               font-size: 24px;
               font-weight: 700;
               fill: #ddd;
               }
               #gm-chart .year.label {
               font-size: 120px;
               font-weight: 700;
               fill: #ddd;
               }
               #gm-chart .year.label.active {
               fill: #aaa;
               }
               .overlay {
               fill: none;
               pointer-events: all;
               cursor: ew-resize;
               }
            </style>
            <div id='loading_wrap' style='position:fixed; height:100%; width:100%; overflow:hidden; top:0; left:0;'>
               <div style="margin-right: 50px;">Cargando datos...</div>
            </div>
            <page class="explora">
               <section class="objective-selector">
                  <div class="container">
                     <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12 col-sm-2"><span class="circle-letter">A</span><span>Desagregación</span></div>
                        <div class="col-xs-12 col-sm-4">
                           <select id="filter-geo" class="filter-geo">
                              <option value="N">Nacional</option>
                              <option value="E" selected>Estatal</option>
                              <option value="M">Municipal</option>
                           </select>
                        </div>
                     </div>
                     <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12 col-sm-2"><span class="circle-letter">B</span><span>Tipo de plataforma</span></div>
                        <div class="col-xs-12 col-sm-4">
                           <select id="select-objetivo-a">
                           </select>
                        </div>
                        <div class="col-xs-12 col-sm-1">Indicador</div>
                        <div class="col-xs-12 col-sm-5">
                           <select id="select-indicador-a">
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-12 col-sm-2"><span class="circle-letter">C</span><span>Tipo de plataforma</span></div>
                        <div class="col-xs-12 col-sm-4">
                           <select id="select-objetivo-b">
                           </select>
                        </div>
                        <div class="col-xs-12 col-sm-1">Indicador</div>
                        <div class="col-xs-12 col-sm-5">
                           <select id="select-indicador-b">
                           </select>
                        </div>
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
                     <div class="row">
                        <div class="col-xs-4 indicador-valor">--</div>
                        <div class="col-xs-8 indicador-nombre">--</div>
                     </div>
                     <div class="row">
                        <div class="col-xs-4 indicador-valor-b" style="color: #e6a583;">--</div>
                        <div class="col-xs-8 indicador-nombre-b">--</div>
                     </div>
                     <div id="infobox-line-chart"></div>
                     <div class="map-legend">
                        <table id="legend-colors">
                           <tbody>
                              <tr>
                                 <td class="legend-breaks legend-breaks-b-0"></td>
                                 <td class="legend-color legend-color-00" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('00')" style="background-color: #f2eee4;"></td>
                                 <td class="legend-color legend-color-01" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('01')"  style="background-color: #f1d2b2;"></td>
                                 <td class="legend-color legend-color-02" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('02')"  style="background-color: #e6a583;"></td>
                              </tr>
                              <tr>
                                 <td class="legend-breaks legend-breaks-b-1"></td>
                                 <td class="legend-color legend-color-10" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('10')"  style="background-color: #ccdfcc;"></td>
                                 <td class="legend-color legend-color-11" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('11')"  style="background-color: #cbc39a;"></td>
                                 <td class="legend-color legend-color-12" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('12')"  style="background-color: #c0976b;"></td>
                              </tr>
                              <tr>
                                 <td class="legend-breaks legend-breaks-b-2"></td>
                                 <td class="legend-color legend-color-20" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('20')"  style="background-color: #95c497;"></td>
                                 <td class="legend-color legend-color-21" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('21')"  style="background-color: #95a865;"></td>
                                 <td class="legend-color legend-color-22" data-container="body" onmouseout="clearHighlight();" onmouseover="highlightFromLegend('22')"  style="background-color: #897c36;"></td>
                              </tr>
                              <tr>
                                 <td></td>
                                 <td class="legend-breaks legend-breaks-0"></td>
                                 <td class="legend-breaks legend-breaks-1"></td>
                                 <td class="legend-breaks legend-breaks-2"></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div onmousedown="exportToPDF('map');" class="btn btn-map-export btn-outline">Exportar mapa</div>
               </div>
               <section class="year-selector">
                  <div class="year-select">
                  </div>
               </section>
               <div class="filters-map">
                  <div class="row">
                     <div class="container">
                        <div class="col-xs-12">
                           <form style="display: none; margin-top: 30px;" id="form-filter-entidad-mpal" class="form-inline" role="form">
                              <div class="form-group form-entidad-mpal">
                                 <label class="filter-header">Filtrar por entidad federativa:</label>
                                 <select id="filter-entidad-mpal" class="filter-group">
                                    <option value="1">Aguascalientes</option>
                                    <option value="2">Baja California</option>
                                    <option value="3">Baja California Sur</option>
                                    <option value="4">Campeche</option>
                                    <option value="5">Coahuila</option>
                                    <option value="6">Colima</option>
                                    <option value="7">Chiapas</option>
                                    <option value="8">Chihuahua</option>
                                    <option value="9">Distrito Federal</option>
                                    <option value="10">Durango</option>
                                    <option value="11">Guanajuato</option>
                                    <option value="12">Guerrero</option>
                                    <option value="13">Hidalgo</option>
                                    <option value="14">Jalisco</option>
                                    <option value="15">Estado de México</option>
                                    <option value="16">Michoacán</option>
                                    <option value="17">Morelos</option>
                                    <option value="18">Nayarit</option>
                                    <option value="19">Nuevo León</option>
                                    <option value="20">Oaxaca</option>
                                    <option value="21">Puebla</option>
                                    <option value="22">Querétaro</option>
                                    <option value="23">Quintana Roo</option>
                                    <option value="24">San Luis Potosí</option>
                                    <option value="25">Sinaloa</option>
                                    <option value="26">Sonora</option>
                                    <option value="27">Tabasco</option>
                                    <option value="28">Tamaulipas</option>
                                    <option value="29">Tlaxcala</option>
                                    <option value="30">Veracruz</option>
                                    <option value="31">Yucatán</option>
                                    <option value="32">Zacatecas</option>
                                 </select>
                              </div>
                           </form>
                        </div>
                        <div class="gm-section col-xs-12 col-sm-9">
                           <div id="gm-chart"></div>
                           <div id="chart-slider"></div>
                           <div onmousedown="exportToPDF('gm');" class="btn btn-line-export btn-outline">Exportar gráfica</div>
                        </div>
                        <div class="gm-caption gm-section col-xs-12 col-sm-3">
                           <p>La información de las dos variables seleccionadas para visualizar en el mapa y la gráfica de esta sección no necesariamente están disponible para los mismos años. Por ejemplo, las mediciones de una variable pueden ser 2012 y 2015, y las de la otra 2013 a 2015, sin interrupciones.</p>
                           <p>En caso de que esto ocurra, la barra ubicada debajo del mapa mostrará todos los años para los cuales haya datos disponibles para el primer indicador seleccionado. El mapa visualizará el valor del primer indicador para el año seleccionado, así como los datos del año más cercano para el segundo indicador. En la gráfica, los valores faltantes serán remplazados por valores calculados utilizando una interpolación lineal.</p>
                        </div>
                     </div>
                  </div>
               </div>
               <?php include('section_datatable.php'); ?>
            </page>
            <?php include('api_graphics.php'); ?>
         </section>
         <!-- /.block -->
      </div>
      <section class="main">
         <div class="container">
            <div class="row">
               <section class="main-col col-md-12">
                  <div class="region region-content">
                     <section id="block-system-main" class="block block-system clearfix">
                        <div id="node-8" class="node node-page clearfix" about="/compara" typeof="foaf:Document">
                           <span property="dc:title" content="Compara" class="rdf-meta element-hidden"></span><span property="sioc:num_replies" content="0" datatype="xsd:integer" class="rdf-meta element-hidden"></span>
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