<!DOCTYPE html>
<html lang="es" class="no-js js gr__localhost">
   <head profile="http://www.w3.org/1999/xhtml/vocab">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="canonical" href="indicadores">
      <title>Indicadores | Objetivos de Desarrollo Sostenible</title>
      <style>
         @import url("sites/all/modules/system/system.base.css?nuxtcd");
         @import url("sites/all/modules/field/theme/field.css?nuxtcd");
         @import url("sites/all/modules/views/css/views.css?nuxtcd");
         @import url("sites/all/modules/ctools/css/ctools.css?nuxtcd");
         @import url("sites/all/modules/panels/css/panels.css?nuxtcd");
         @import url("sites/all/themes/tweme/js/jquery.magnific-popup.css?nuxtcd");
         @import url("sites/all/themes/bootstrap/css/overrides.css?nuxtcd");
         @import url("sites/all/themes/tweme/common.css?nuxtcd");
         @import url("sites/all/themes/tweme/style.css?nuxtcd");
      </style>
      <link type="text/css" rel="stylesheet" href="sites/all/themes/bootstrap/css/bootstrap.min.css" media="all">
      <link href="sites/all//css" rel="stylesheet" type="text/css">
      <script src="sites/all/themes/tweme/js/jquery.min.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.once.js"></script>
      <script src="sites/all/themes/tweme/js/drupal.js"></script>
      <script src="sites/all/themes/bootstrap/js/bootstrap.min.js"></script>
      <script src="sites/all/themes/tweme/js/es_dNYhQGVAOhMJCAxlNL1aHF3vfJWSIvo1OthwwbycR8U.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.magnific-popup.min.js"></script>
      <script src="sites/all/themes/tweme/js/tweme.js"></script>
      <script src="sites/all/themes/tweme/js/jquery.matchHeight-min.js"></script>
   </head>
   <body class="html not-front not-logged-in no-sidebars page-node page-node- page-node-7 node-type-page navbar-is-fixed-top bootstrap-anchors-processed" data-gr-c-s-loaded="true">
      <?php include("header.php"); ?>
      <section class="main">
         <div class="container">
         <div class="row">
         <section class="main-col col-md-12">
            <div class="region region-content">
            <section id="block-system-main" class="block block-system clearfix">
               <div id="node-7" class="node node-page clearfix" about="/indicadores" typeof="foaf:Document">
               <span property="dc:title" content="Indicadores" class="rdf-meta element-hidden"></span><span property="sioc:num_replies" content="0" datatype="xsd:integer" class="rdf-meta element-hidden"></span>
               <div class="content">
                  <div class="field field-name-body field-type-text-with-summary field-label-hidden">
                     <div class="field-items">
                        <div class="field-item even" property="content:encoded">
                           <div class="row">
                              <div class="col-xs-12">
                                 Consulta datos de más de 100 indicadores correspondientes a los Objetivos de Desarrollo Sostenible.<br>Primero selecciona un objetivo, y posteriormente elige un indicador:
                              </div>
                           </div>
                           <?php
                              include('h_objetivos.php');
                              
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
                              
                              $i = 0;
                              foreach($indicadores as $key => $objetivo) {
                              	echo ('<div class="row indicador-header"><div class="col-xs-1"><img src="sites/all/themes/tweme/assets/sdg_icons/'.$objetivo_icons[$key].'.png"/></div><div class="col-xs-11"><h4><strong>'.($i+1).'.</strong> '.$objetivo_nombres[$key].'</h4></div></div>');
                              	foreach($objetivo as $indicador) {
                              		echo ( '<div class="row indicador-page-row" onmousedown="visit_indicador(\''.$i.'\',\''.$indicador["Clave"].'\')" ><div class="col-xs-1"></div><div class="col-xs-11">'.$indicador["Nombre_del_indicador"]."</div></div>" );
                              	}
                              	$i++;
                              }
                              
                              ?> 
                           <script type="text/javascript">
                              function visit_indicador(o,i) {
                              	window.location.href='explora?o='+o+'&i='+i;
                              }
                           </script>
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