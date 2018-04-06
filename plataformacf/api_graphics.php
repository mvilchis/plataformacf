<!-- include the jQuery and jQuery UI scripts -->
<script src="https://code.jquery.com/jquery-2.1.1.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css"
<script src="bower_components/jQuery-ui-Slider-Pips/dist/jquery-ui-slider-pips.js"></script>
<link href="bower_components/jQuery-ui-Slider-Pips/dist/jquery-ui-slider-pips.css" rel="stylesheet">
<script type="text/javascript">
var jQuery_2_1_1 = $.noConflict(true);
</script>

<!-- plus a jQuery UI theme, here I use "flick" -->

<!--                  READ DATA                             -->
<?php
  include('h_objetivos.php');
  $o_id=pg_escape_string($_GET["o"]);
  $i_id=pg_escape_string($_GET["i"]);
  $result = file_get_contents("bucket/json/cf_metadata.json");
  $metadata = json_decode($result, true);
  $indicadores_id = array();
  foreach($metadata["results"] as $value) {
    if (array_key_exists($value["Nombre_del_objetivo"],$indicadores))
      array_push($indicadores[$value["Nombre_del_objetivo"]],$value);
   $indicadores_id[$value["Clave"]] = $value;
  }

  foreach($indicadores as $key => $obj) {
    if (count($obj) < 1) unset($indicadores[$key]);
  }

  $result = file_get_contents("bucket/json/cf_geo.json");
  $metadata_desag = json_decode($result, true);
  $desagregacion = array();
  foreach($metadata_desag["results"] as $value) {
    if (!array_key_exists($value["DesGeo"],$desagregacion))
      $desagregacion[$value["DesGeo"]] = array();
    array_push($desagregacion[$value["DesGeo"]],$value["id"]);
  }

  $desagregacion_by_obj = array();
  foreach($metadata["results"] as $value) {
    if (!array_key_exists($value["Nombre_del_objetivo"],$desagregacion_by_obj))
      $desagregacion_by_obj[$value["Nombre_del_objetivo"]] = array();
    $des = array('N','E','M');
    foreach ($des as $d) {
      if (!array_key_exists($d,$desagregacion_by_obj[$value["Nombre_del_objetivo"]]))
        $desagregacion_by_obj[$value["Nombre_del_objetivo"]][$d] = array();
      if (in_array($value["Clave"],$desagregacion[$d]))
        array_push($desagregacion_by_obj[$value["Nombre_del_objetivo"]][$d],$value["Clave"]);
    }
  }

  $result = file_get_contents("bucket/json/cf_grupos.json");
  $metadata_grupos = json_decode($result, true);
  $grupos = array();
  foreach($metadata_grupos["results"] as $value) {
    if (!array_key_exists($value["id"],$grupos))
      $grupos[$value["id"]] = array();
    array_push($grupos[$value["id"]],$value);
  }

  $result = file_get_contents("bucket/json/estado_cve.json");
  $estados = json_decode($result, true);
?>
<!--                END  READ DATA                            -->

<!--                javascript auxiliar functions               -->

<script type="text/javascript">
  //Global variables:
  var firstrun = true,
      data_grouped = null,
      data_grouped_b = null,
      years = null,
      subids = null,
      years_b = null,
      choro_layer = null,
      active_geom = null,
      map_locked = false;
  //Variables de control
  var active_unit = null, //Estatal o Nacional
      active_year = null,  // Current year
      active_group = null, // Grupo
      is_acumulado = null,//Acumulado o trimestral
      active_feature= null, //Estado actual, o nacional
      active_indicador = null; //Proyecto, monto acumulado, etc
  //Constantes
  var NULL_CONSTANT = "NN";

  //Init all variables to null value
  function init_all_values(){
    active_year        = NULL_CONSTANT;
    active_group       = NULL_CONSTANT;
    active_feature     = NULL_CONSTANT;
    active_unit        = NULL_CONSTANT;
    active_indicador   = NULL_CONSTANT;
  }

  //Add comma each three decimal numbers
  function commaSeparateNumber(x){
    if (x >1000000){
      y = Math.floor(x/1000000);
      var parts=y.toString().split(".");
      parts[0]=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g,",");
      return parts.join(".")+"MM";
    }else {
    var parts=x.toString().split(".");
    parts[0]=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g,",");
    return parts.join(".");
  }
  }

  //Set global variables
  function set_active_year(value){
      active_year = value;
  }
  function set_active_group(value){
    active_group = value;
  }
  function set_active_feature(value){
    active_feature = value;
  }
  function set_active_unit(value) {
    active_unit = value;
  }
  function set_active_indicador(value) {
    active_indicador = value;
  }
  //Leaflet customize
  function highlightFromLegend(i) {
    $("svg path.class-"+i).addClass("highlighted");

  }

  function clearHighlight() {
    $("path").removeClass("highlighted");
  }

  function on_mouseover(e,feature) {
    var layer = e.target;
    layer.setStyle({
      weight: 2,
      color: '#666',
      dashArray: '',
      fillOpacity: 0.7
    });
    if (!L.Browser.ie && !L.Browser.opera) {
      layer.bringToFront();
    }
    set_active_feature(feature);
    initInfobox(feature);
  }



  // From indicator, export to csv file
  // Use global variables: active_group, active_unit, active_indicador
  //                       active_unit
  function exportToCsv(){
      (function ($) {
        var filters = {'Categoria':active_group,
                      'Agregacion':active_unit};
        d3.csv("bucket/to_csv/raw_csv/"+active_indicador+".csv", function(csv) {
          csv = csv.filter(function(row) {
                  return ['Categoria','Agregacion'].reduce(
                          function(pass, column) {
                              return pass && (
                                  !filters[column] ||
                                  row[column] === filters[column] ||
                                  filters[column].indexOf(row[column]) >= 0
                              );
                          }, true);
                })
        var result,
            categoria,
            agregacion;
        if (active_unit == 'N') {
          agregacion = 'Nacional';
          result = "Año,Trimestre,Indicador,Categoria,Valor,Agregacion\r\n"
        }else {
          agregacion = 'Estatal';
          result = "Año,Trimestre,Indicador,Categoria,Valor,Agregacion,Estado,Clave_inegi\r\n";
        }
        var indicador = metadata_groupedbyid[active_indicador]['Nombre_del_indicador'];
        indicador    += "-"+metadata_groupedbyid[active_indicador]['Nombre_del_objetivo'];
        $.each(indicadores_grupos[active_indicador],function (key,tmp) {
          if (active_group == tmp['id2']){
            categoria =tmp['id3'] ; //Buscamos el nombre de la categoria en la lista
          }
        });
        $.each(csv,function (key,value) {
          result += value['Año']+","+value['Trimestre'];
          result += ","+indicador+","+categoria;
          result += ","+value['Valor']+','+agregacion;
          if (active_unit == 'E') {
            result += ','+estados[value['Estado']]['nombre'];
            result += ','+estados[value['Estado']]['clave'];
          }
          result +='\r'+'\n';
        });
        var blob = new Blob([result]);
        if (window.navigator.msSaveOrOpenBlob)
        // IE hack; see http://msdn.microsoft.com/en-us/library/ie/hh779016.aspx
          window.navigator.msSaveBlob(blob, "reporte.csv");
        else {
          var a = window.document.createElement("a");
          a.href = window.URL.createObjectURL(blob, {type: "text/plain"});
          a.download = "reporte.csv";
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        }
      });
    }(jQuery_2_1_1));
  }

  //From graph to image:
  // Use global variables: 0
  function exportToImage(i, type_image) {
    (function ($) {
      $('#chart svg .c3-axis path').css('fill-opacity',0);
      $('#chart svg .c3-axis path').css('stroke','#ccc');
      $('#chart svg g.c3-lines path.c3-line').css('fill-opacity',0);
      $('#chart svg .c3-axis text').css('font-family','Open Sans, Arial, sans-serif');
      $('#chart svg g.c3-lines path.c3-line').css('stroke-opacity','1');
      $('#chart svg g.c3-lines path.c3-line').css('opacity','1');
      $('#chart svg g.tick line').css('stroke','#efefef');
    var svg = $("#chart svg")[0];
    var serializer = new XMLSerializer();
    var str = serializer.serializeToString(svg);
    var script_to_run = "svg2pdf/svg2pdf";
    switch(type_image) {
      case "1":
        script_to_run="svg2pdf/svg2png";
      break;
      case "2":
        script_to_run="svg2pdf/svg2pdf"
      break;
      case "3":
        script_to_run="svg2pdf/svg2jpeg"
      break;
      case "4":
        script_to_run="svg2pdf/svg"
      break;
   }
    $.post(script_to_run, str)
      .done(function(data) {
        window.open(data);
        if (i == "line") {
          render_line();
        }
        if (i == "gm") {
          gm();
        }
      });
    }(jQuery_2_1_1));
  }

  //Init infobox
  // Use global variables: active_unit, active_year
  function initInfobox(feature) {
    (function ($) {
      $(".infobox").show();
      if (active_unit == "N") {
        $(".unit-name").html("México <hr>" );
        $(".edo-name").html("Nacional");
        $(".edo-image").html("");
      }
      else if (active_unit == "E") {
          $(".unit-name").html(feature.properties.nom_ent);
          $(".edo-name").html("<hr>");
          $(".edo-image").html("<img width=40 height=40 src='img/estados/"+feature.properties.cve+".png'/>");
      }
      if (typeof feature.properties[active_year] != null)
        $(".indicador-valor").html(commaSeparateNumber(Math.round(feature.properties[active_year]*10)/10));
      else
      $(".indicador-valor").html("N/A");
      $(".indicador-nombre").html("<span style='min-width: 75px;'>"+$("select#select-indicador-a option:selected").text() + " ("+ active_year+")</span>");
      var line_columns = [];
        line_columns_years = ["x"];
      row = [feature.properties.nom_ent];
      $.each(years, function(key,year) {
        line_columns_years.push(year);
        if (typeof feature.properties[year] != 'undefined')
          row.push(Math.round(feature.properties[year]*10)/10);
        else row.push(null);
      });
      line_columns.push(line_columns_years);
      line_columns.push(row);
      if (years[0].indexOf("-") != -1)
        date_format = '%Y-%m';
      else date_format = '%Y';
      var chart = c3.generate({
        bindto   : '#infobox-line-chart',
        padding  : { top: 10,  left: 30, right: 10},
        data     : { x: 'x', xFormat: date_format, columns: line_columns },
        axis     : {x: { type: 'categorized',
                         tick: { format:date_format,  rotate: 45, multiline: false }
                       },
                    },
        color     : { pattern: ['#00cc99']},
        size      : { width: 500, height: 160 },
        legend    : { show: false},
        grid      : { x: { show: false }, y: { show: true}}
      });
    }(jQuery_2_1_1));
  }

  //Render year bar
  // Use global variables: 0
  function renderYearBar(years,default_year) {
  	(function($) {
		  var extensionMethods = {
        pips: function( settings ) {
          options = {
            first: 	"number",
            last: 	"number",
            rest: 	"pip"
  				};
  				$.extend( options, settings );
          // get rid of all pips that might already exist.
  				this.element.addClass('ui-slider-pips').find( '.ui-slider-pip' ).remove();
          // we need teh amount of pips to create.
  				var pips = this.options.max - this.options.min;
          // for every stop in the slider, we create a pip.
  				for( i=0; i<=pips; i++ ) {
            // hold a span element for the pip
            if (years[i][5]=="1"){ //only year
              var s = $('<span class="ui-slider-pip"><span class="ui-slider-number">'+years[i].slice(0,4) +'</span></span>');
            }else { //only number
              var s = $('<span class="ui-slider-pip"><span class="ui-slider-number">'+years[i].slice(5,6) +'</span></span>');
            }
  					s.css({ left: '' + (100/pips)*i + '%'  });
  						// append the span to the slider.
  						this.element.append( s );
  					}
  			}
  		};
  		$.extend(true, $['ui']['slider'].prototype, extensionMethods);

    if (default_year == NULL_CONSTANT){
      var this_year = years.length-1;
      active_year = years[this_year];
    } else {
      for (i = 0; i < years.length; i++){
        if (years[i] == default_year){
          var this_year = i;
          break;
        }
      }
    }
    $('.slider').slider({ min:0,
                          max:years.length-1,
                          animate:true,
                          value:this_year,
                          range: "min",
                          slide: function(event, ui) {
                            change_active_year(years[ui.value]);
                          }
                      });

    $('.slider').slider('pips');
    })(jQuery_2_1_1);

  }
</script>



<script type="text/javascript">
  // Initialize variables
  var metadata_grouped = <?php echo json_encode($indicadores); ?>;
  var metadata_groupedbyid = <?php echo json_encode($indicadores_id); ?>;
  var indicadores_by_desagregacion = <?php echo json_encode($desagregacion); ?>;
  var indicadores_by_objdes = <?php echo json_encode($desagregacion_by_obj); ?>;
  var indicadores_grupos = <?php echo json_encode($grupos); ?>;
  var estados = <?php echo json_encode($estados); ?>;
  var geom_grouped = { "N": nacion,
                     "E": entidad,
                      };
  var searchControl = null;
  // ################### leaflet RENDER ############################
  var map = new L.Map('map', { maxZoom: 14,
                           minZoom: 5,
                           scrollWheelZoom: false
                            }).setView(new L.LatLng(24.75,-101.5),5);
  var basemap = new L.TileLayer("http://{s}.google.com/vt/?hl=es&x={x}&y={y}&z={z}&s={s}&apistyle=s.t%3A5|p.l%3A53%2Cs.t%3A1314|p.v%3Aoff%2Cp.s%3A-100%2Cs.t%3A3|p.v%3Aon%2Cs.t%3A2|p.v%3Aoff%2Cs.t%3A4|p.v%3Aoff%2Cs.t%3A3|s.e%3Ag.f|p.w%3A1|p.l%3A100%2Cs.t%3A18|p.v%3Aoff%2Cs.t%3A49|s.e%3Ag.s|p.v%3Aon|p.s%3A-19|p.l%3A24%2Cs.t%3A50|s.e%3Ag.s|p.v%3Aon|p.l%3A15&style=47,37", {
  subdomains: ['mt0','mt1','mt2','mt3'],
  zIndex: -1,
  detectRetina: true,
  scrollWheelZoom: false
  });

  // Export map to image
  L.easyPrint({title: 'Exporta mapa',
             elementsToHide: 'footer,.filters,h2,.texto_parrafo,.region-header,.datatable,.dgm-footer,.objective-selector-caption,\
             .objective-selector-caption,.vcenter,#select-objetivo-a,#select-indicador-a,.navbar-crowdfunding,#acknowledgment,.infobox,#f_1',
             sizeModes: ['A4Landscape'],
              }).addTo(map);
  map.addLayer(basemap);
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    map.dragging.disable();
  }

  //change_acumulate
  //global: is_acumulado, active_indicador
  function change_acumulate(tmp_indicator){
      (function ($) {
        if($("#trim_to_ac").is(':checked')){
          $.ajax({
            async: false,
            type: 'GET',
            url: 'bucket/json/partition/'+tmp_indicator+'.json',
            success: function(data) {
              // exists code
              set_active_indicador(tmp_indicator);
              is_acumulado = true;
              var tmp = document.getElementsByClassName("check-slider")[0];
              tmp.style.backgroundColor= "#4FD9B2";
              var year_bar_tmp = document.getElementsByClassName("ui-widget-header")[0];
              year_bar_tmp.style.background="#69D8CF";
              var year_button_tmp = document.getElementsByClassName("ui-slider-handle")[0];
              year_button_tmp.style.background="#DDDDDD";
            },
            error: function(data){
              // not exists code
              is_acumulado = false;
              var checkbox = document.getElementById("trim_to_ac");
              checkbox.checked = false;
              //block check slider
              var tmp = document.getElementsByClassName("check-slider")[0];
              tmp.style.backgroundColor= "#a3a3a3";
              $('#notAcumulado .modal-body p').text('').append('' +
                                  '<span><center>Lo sentimos</center></span></br>' +
                                  '<span><center>No existen datos acumulados para el indicador seleccionado.</center></span>');
              $('#notAcumulado').modal('show');
              $('#closeNotAcumulado').on('click', function() {
                $('#notAcumulado').modal('hide');
              });
            }
          });
        } else{
          is_acumulado = false;
          set_active_indicador($("select#select-indicador-a option:selected").val());
          //Check if acumulado exist
          $.ajax({
            async: false,
            type: 'GET',
            url: 'bucket/json/partition/'+active_indicador+'1.json',
            error: function(data){
              var tmp = document.getElementsByClassName("check-slider")[0];
              tmp.style.backgroundColor= "#a3a3a3";
            },
            success: function(data) {
              var tmp = document.getElementsByClassName("check-slider")[0];
              tmp.style.backgroundColor= "#4FD9B2";
            }
            });
        }
      }(jQuery_2_1_1));
    }

  // ##### Begin api functions
  //Render graph
  // Global variables: years, active_geom
  function render_line() {
    var line_columns = [];
    var graph_width;
    line_columns_years = ["x"];
    (function ($) {
      graph_width = $('.col-xs-8').css('weight');
      $.each(years, function(key,year) {
        line_columns_years.push(year);
      });
      line_columns.push(line_columns_years);
      line_colors = [];
      $.each(active_geom.features, function(key,feature) {
        row = [feature.properties.nom_ent];
        $.each(years, function(ykey,year) {
          if (typeof feature.properties[year] != 'undefined')
            row.push(feature.properties[year]);
          else row.push(null);
        });
        line_columns.push(row);
        line_colors.push('#ccc');
      });
      prom_row = ["Promedio nacional"];
      $.each(years, function(ykey,year) {
        prom_val = 0;
        $.each(active_geom.features, function(key,feature) {
          prom_val = prom_val + feature.properties[year];
        });
        prom_row.push(prom_val/active_geom.features.length);
      });
      line_columns.push(prom_row);
      line_colors.push('#f00');
    }(jQuery_2_1_1));
    if (years[0].indexOf("-") != -1)
      date_format = '%Y-%m';
    else date_format = '%Y';
    var chart = c3.generate({
      data:           { x: 'x', xFormat: date_format, columns: line_columns},
      padding:        { left: 40, right: 30},
      point:          { show: true },
      axis:           { x: {type: 'categorized',
                              tick: { format: date_format, rotate: 45, multiline: false }
                            },
                      },
      color:          { pattern: line_colors },
      //size:           { width: 700  },
      legend:         { show: false },
      tooltip:        { grouped: false,
                        format: { title: function (d) { return d.name; },
                                  value: function (value, ratio, id) {
                                                (function ($) {
                                                  if (id != "Promedio nacional") {
                                                    $( "svg g.c3-chart-line g.c3-circles circle" ).css('fill','#ccc');
                                                    $( "svg g.c3-chart-line path.c3-line" ).css('stroke','#ccc');
                                                    $( "svg g.c3-chart-line g.c3-circles-Promedio-nacional circle" ).css('fill','#f00');
                                                    $( "svg g.c3-chart-line path.c3-line-Promedio-nacional" ).css('stroke','#f00');
                                                    $( "svg g.c3-chart-line g.c3-circles-" +id.replace(/ /g,'-') + " circle" ).css('fill','#00cc99');
                                                    $( "svg g.c3-chart-line path.c3-line-" +id.replace(/ /g,'-') ).css('stroke','#00cc99');
                                                  } else {
                                                    $( "svg g.c3-chart-line g.c3-circles circle" ).css('fill','#ccc');
                                                    $( "svg g.c3-chart-line path.c3-line" ).css('stroke','#ccc');
                                                    $( "svg g.c3-chart-line g.c3-circles-Promedio-nacional circle" ).css('fill','#f00');
                                                    $( "svg g.c3-chart-line path.c3-line-Promedio-nacional" ).css('stroke','#f00');
                                                  }
                                                  }(jQuery_2_1_1));
                                                  return commaSeparateNumber(Math.round(value*10)/10);
                                }
                        }
                      },
      grid:           { x: { show: false }, y: { show: true }}
    });
  }

  //Render map
  // Global variables:active_geom, active_year, active_feature
  // Set variables: active_feature
  // Call initInfobox, render_line()
  function render_map(features) {
    var re = new RegExp("^([0-9]{4,})");
    (function ($) {
      var min_value= 100000000000;
      var max_value= -1;
      $.each(features,function (key,value) {
        $.each(value["properties"], function(key,value) {
          if (re.test(key)) {
            if (value > max_value)
              max_value = value;
            if (value < min_value)
              min_value = value;
          }
        });
      });
      nb_breaks = [0];
      for (i = 1; i <= 4; i++) {
        nb_breaks.push(((max_value-min_value)/4)*i);
      }
      $("td.legend-breaks-0").html(commaSeparateNumber(Math.round(nb_breaks[0])) + " - " + commaSeparateNumber(Math.round(nb_breaks[1])));
      $("td.legend-breaks-1").html(commaSeparateNumber(Math.round(nb_breaks[1])) + " - " + commaSeparateNumber(Math.round(nb_breaks[2])));
      $("td.legend-breaks-2").html(commaSeparateNumber(Math.round(nb_breaks[2])) + " - " + commaSeparateNumber(Math.round(nb_breaks[3])));
      $("td.legend-breaks-3").html(commaSeparateNumber(Math.round(nb_breaks[3])) + " - " + commaSeparateNumber(Math.round(nb_breaks[4])));
      }(jQuery_2_1_1));

    function fill_color(v) {
      if (v == null) return "#ccc";
      else if (v >= nb_breaks[3]) return '#086';
      else if (v >= nb_breaks[2]) return '#00cc99';
      else if (v >= nb_breaks[1]) return '#7fd';
      else if (v >= nb_breaks[0]) return '#ddfff6';
    }
    function category(v) {
      //if (active_unit == "N") return "0";
      if (v == null) return "#ccc";
      else if (v >= nb_breaks[3]) return "3";
      else if (v >= nb_breaks[2]) return "2";
      else if (v >= nb_breaks[1]) return "1";
      else if (v >= nb_breaks[0]) return "0";
    }
    if (choro_layer != null) map.removeLayer(choro_layer);
    choro_layer = L.geoJson(active_geom, {
      style: function(feature) {
        return {
          fillColor: fill_color(feature.properties[active_year]),
          className: "class-"+String(category(feature.properties[active_year])),
          weight: 0.5,
          opacity: 1,
          color: '#005540',
          fillOpacity: 0.5
        };
      },
      onEachFeature: function(feature, layer) {
        if (active_unit == "E")
          feature.properties.name = feature.properties.nom_ent + " (" + commaSeparateNumber(Math.round(feature.properties[active_year]*10)/10) + ")";
        layer.on({
          mousedown: function(e) {
            if (map_locked == true) {
              map_locked = false;
              (function ($) { $(".infobox").css("border","none"); }(jQuery_2_1_1));
            } else {
              map_locked = true;
              (function ($) { $(".infobox").css("border","5px solid #00cc99"); }(jQuery_2_1_1));
            }
            on_mouseover(e,feature);
          },
          mouseover: function(e) {
            if (map_locked != true) on_mouseover(e,feature);
          },
          mouseout: function(e) {
            choro_layer.resetStyle(e.target);
          }
        });
      }
    });
    map.addLayer(choro_layer);
    (function ($) {
      if (active_feature == NULL_CONSTANT) {
        var first_key = Object.keys(choro_layer["_layers"])[0],
        feature = choro_layer["_layers"][first_key]["feature"];
        set_active_feature(feature);
      }
      initInfobox(active_feature);
    }(jQuery_2_1_1));

    if (searchControl != null)
      map.removeControl(searchControl);
    searchControl = new L.Control.Search({layer: choro_layer, propertyName: 'name', circleLocation:false});
    searchControl.on('search_locationfound', function(e) {
              map.fitBounds(e.layer.getBounds());
              choro_layer.eachLayer(function(layer) {  //restore feature color
                choro_layer.resetStyle(layer);
                e.layer.setStyle({fillColor: '#3f0', color: '#0f0'});
              });
            }).on('search_collapsed', function(e) {
              choro_layer.eachLayer(function(layer) {  //restore feature color
                choro_layer.resetStyle(layer);
              });
            });
    map.addControl( searchControl );  //inizialize search control
    render_line();
    (function ($) { $("#loading_wrap").fadeOut(); }(jQuery_2_1_1));
  }

  //change_active_year
  // Global variables: years, active_year,active_geom
  // Set variables: active_year,
  // Call render_map
  function change_active_year(y) {
    if (years.indexOf(y) < 0) {
      y = years[years.length-1];
    }
    set_active_year(y);
		asc = false;
		active_geom.features = active_geom.features.sort(function(a, b) {
	        if (asc) return (a.properties[active_year] > b.properties[active_year]) ? 1 : ((a.properties[active_year] < b.properties[active_year]) ? -1 : 0);
	        else return (b.properties[active_year] > a.properties[active_year]) ? 1 : ((b.properties[active_year] < a.properties[active_year]) ? -1 : 0);
	    });
		for (i = 0; i < 3; i++) {
			(function ($) {
				if (active_unit == "E") $(".top-municipios-group-"+i+" .top-municipios-name").html(active_geom.features[i].properties.nom_ent);
				if (active_unit != "N") $(".top-municipios-group-"+i+" .top-municipios-pct").html(commaSeparateNumber(Math.round(active_geom.features[i].properties[active_year]*10)/10));
			}(jQuery_2_1_1));
		}
    render_map(active_geom.features);
  }

  //change_active_unit
  // global variables geom_grouped
  // Call change_active_year
  function change_active_unit(u) {
    if (u != active_unit){
      //We have to change feature, we dont now wich
      set_active_unit(u);
      active_geom = geom_grouped[active_unit];
      set_active_feature(active_geom['features'][0]);
    }else {
      set_active_unit(u);
      active_geom = geom_grouped[active_unit];
    }
    (function ($) {
      if (active_unit == "N") {
        $("#form-filter-entidad-mpal").hide();
        $(".stat-column-header-chart").html("<h2>Indicador a nivel nacional</h2>");
      }
      if (active_unit == "E") {
        $("#form-filter-entidad-mpal").hide();
        $(".stat-column-header-chart").html("<h2>Indicador a nivel estatal</h2>");
        $(".stat-column-header-top").html("Top 3 Estados");
      }
      if (active_unit == "N")
        $("#stat-tables").hide();
      else
        $("#stat-tables").show();
      $.each(active_geom.features, function(key, unit) {
        $.each(years, function (key,year) {
          if (typeof data_grouped[year] != 'undefined') {
            if (typeof data_grouped[year][active_unit] != 'undefined') {
              if (typeof data_grouped[year][active_unit][active_group] != 'undefined') {
                if (typeof data_grouped[year][active_unit][active_group][unit.properties.cve] != 'undefined') {
                  if ($.isNumeric(data_grouped[year][active_unit][active_group][unit.properties.cve].valor))
                    unit.properties[year] = parseFloat(data_grouped[year][active_unit][active_group][unit.properties.cve].valor);
                  else
                    unit.properties[year] = null;
                } else
                 unit.properties[year] = null;
              }else
                unit.properties[year] = null;
            }else
              unit.properties[year] = null;
          }else
          unit.properties[year] = null;
        });
      });
    }(jQuery_2_1_1));
    change_active_year(active_year);

  }


  //change_active_group
  // Global variables: active_unit
  // Call change_active_unit
  function change_active_group(g) {
    set_active_group(g);
    change_active_unit(active_unit);
  }

  //change_active_indicator download new data
  // global: active_indicador, active_unit
  // Call change_active_unit, renderYearBar
  function change_active_indicator( default_group, default_unit, default_year) {
    cont = true;
    (function ($) {
      if ($('select#select-indicador-a option').length == 0) {
        cont = false;
      }
    }(jQuery_2_1_1));
    if (cont == false) {
      (function ($) {
        $("#loading_wrap").fadeOut();
      }(jQuery_2_1_1));
      alert("Para uno or más de los objetivos seleccionados, ningún indicador coincide con la desagregación geográfica y objetivo que ha seleccionado. Por favor, seleccione un objetivo diferente.");
      return 0;
    }
    //Check if we have active_unit to conserve
    if (default_unit != NULL_CONSTANT) {
      set_active_unit(default_unit);
    }
    if (default_group != NULL_CONSTANT){
      set_active_group(default_group);
    }
    data_grouped = {};
    data_grouped_b = {};
    subids = [];
    years = [];
    units = [];
    (function ($) {
    $.getJSON('bucket/json/partition/'+active_indicador+'.json', {}, function (data) {
      $.each(data, function(key, valor) {
        if (valor["t"] != "NA" && valor["DesGeo"] != "NA" && valor["cve"] != "NA") {
          // Month present
          if (parseInt(valor["m"]) != 0) {
            time_val = parseInt(valor["t"]) + "-" + parseInt(valor["m"]);
          } else {   // Month not present
            time_val = String(parseInt(valor["t"]));
          }
          if (typeof subids[valor["DesGeo"]] === 'undefined') {
            subids[valor["DesGeo"]] = [];
          };
          if (subids[valor["DesGeo"]].indexOf(valor["id2"]) == -1) {
            subids[valor["DesGeo"]].push(valor["id2"])
          };
          if (years.indexOf(time_val) == -1) {
            years.push(time_val)
          };
          if (units.indexOf(valor["DesGeo"]) == -1)
            units.push(valor["DesGeo"]);
          if (typeof data_grouped[time_val] === 'undefined')
            data_grouped[time_val] = {};
          if (typeof data_grouped[time_val][valor["DesGeo"]] === 'undefined')
            data_grouped[time_val][valor["DesGeo"]] = [];
          if (typeof data_grouped[time_val][valor["DesGeo"]][valor["id2"]] === 'undefined')
            data_grouped[time_val][valor["DesGeo"]][valor["id2"]] = [];
          data_grouped[time_val][valor["DesGeo"]][valor["id2"]][String(parseInt(valor["cve"]))] = valor;
        }
      });
    // Organize years
    years.sort();
    // Organize units
    $(".filter-geo").html('');
      if (units.indexOf("N") != -1) {
        $(".filter-geo").append('<option value="N" class="filter-item filtro-geo-N">Nacional</option>');
        $(".stat-column-header-chart").html("Indicador a nivel nacional");
        if (active_unit == NULL_CONSTANT)
          set_active_unit("N");
      }
      if (units.indexOf("E") != -1) {
        $(".filter-geo").append('<option value="E" class="filter-item filtro-geo-E">Estatal</option>');
        $(".stat-column-header-chart").html("Indicador a nivel estatal");
        $(".stat-column-header-top").html("Top 3 Estados");
        if (active_unit == NULL_CONSTANT)
          set_active_unit("E");
      }

      $('select.filter-geo option[value='+ active_unit +']').attr('selected', 'selected');
      change_active_unit(active_unit);
        // Create filters (if applicable)
        if (active_indicador in indicadores_grupos) {
          $("select.filter-grupo").empty();
          $.each(indicadores_grupos[active_indicador], function(k, v) {
            if (subids[active_unit].indexOf(v.id2) != -1)
              $("select.filter-grupo").append('<option value="'+v.id2+'">'+v.id3+'</option>');
          })
          $(".form-group-grupo").show();
        } else {
          set_active_group("a");
          $("select.filter-grupo").empty();
          $("select.filter-grupo").append('<option value="" selected>-- Todos --</option>');
          $(".form-group-grupo").hide();
        }
        $('select#filter-grupo option[value="'+ active_group +'"]').attr('selected', 'selected');
        // Add to Datos table
        $("table#datos tbody tr").remove();
        $("table#description tbody tr").remove();

        metadatos_a = metadata_groupedbyid[active_indicador];
        $("table#datos tbody").append(
          "<tr class='indicador-a datos-indicador datos-indicador-descarga'>\
            <td class='nombre-ind'>"+metadatos_a["Nombre_del_indicador"]+"</td>\
            <td>"+metadatos_a["Dependencia"]+"</td>\
            <td>"+metadatos_a["Nombre_del_objetivo"]+"</td>\
            <td><span class='fformat'>CSV</span></td>\
            <td style='min-width:80px;'>\
            <center>\
              <a target='_blank'>\
                <img onmousedown='exportToCsv()' width=35 height=36 src='img/icon-circle-arrow-right-gray.png' />\
              </a>\
            </center>\
            </td>\
          </tr>");
          $("table#datos tbody").append(
            "<tr class='indicador-a datos-indicador datos-indicador-descarga'>\
              <td class='nombre-ind'>Base de datos completa</td>\
              <td>"+metadatos_a["Dependencia"]+"</td>\
              <td>Todos</td>\
              <td><span class='fformat'>CSV</span></td>\
              <td style='min-width:80px;'>\
              <center>\
                <a target='_blank' href='bucket/to_csv/raw_csv/all_data.csv'>\
                  <img width=35 height=36 src='img/icon-circle-arrow-right-gray.png' />\
                </a>\
              </center>\
              </td>\
              </tr>");

        $("table#description tbody").append(
          "<tr class='indicador-a datos-indicador'>\
            <td>  <div>"+metadatos_a["Descripcion"]+"</div></td>\
            <td>  <div>"+metadatos_a["Cobertura"]+"</div></td>\
            <td>  <div>"+metadatos_a["Periodicidad"]+"</div></td>\
            <td>  <div>"+metadatos_a["RangoTiempo"]+new Date().getFullYear()+"</div></td>\
            <td></td> \
          </tr>" );
        renderYearBar(years,default_year);
        if (is_acumulado) {
          var year_bar_tmp = document.getElementsByClassName("ui-widget-header")[0];
          year_bar_tmp.style.background="#69D8CF";
          var year_button_tmp = document.getElementsByClassName("ui-slider-handle")[0];
          year_button_tmp.style.background="#DDDDDD";

        }else {
          var year_bar_tmp = document.getElementsByClassName("ui-widget-header")[0];
          year_bar_tmp.style.background="white";
          var year_button_tmp = document.getElementsByClassName("ui-slider-handle")[0];
          year_button_tmp.getElementsByClassName("ui-slider-handle")[0];
          year_button_tmp.style.background="#69D8CF";
        }
      });
    }(jQuery_2_1_1));
  }

  //Call change_active_indicator
  function populate_indicador_a() {
    (function ($) {
      $("select#select-indicador-a").empty();
      if (firstrun == true) {
        o_id = '<?php echo $o_id; ?>';

        if (o_id != "") {
          $('select#select-objetivo-a option:nth-child('+(parseInt(o_id)+1)+')').attr('selected', 'selected');
        }else {
          $('select#select-objetivo-a option:nth-child(1)').attr('selected', 'selected');
        }
      }
      $.each(metadata_grouped[$("select#select-objetivo-a option:selected").val()], function(key, indicador) {
        if (indicador.Clave.length ==3 ){
          $("select#select-indicador-a").append("<option value='"+indicador.Clave+"'>"+indicador.Nombre_del_indicador+"</option>");
        }
      });
      if (firstrun == true) {
        o_id  = '<?php echo $o_id; ?>';
        i_id = '<?php echo $i_id; ?>';
        if (o_id != "") {
          if (i_id != "") {
            $('select#select-indicador-a option[value='+ i_id +']').attr('selected', 'selected');
          }else {
            $('select#select-indicador-a option:nth-child(1)').attr('selected', 'selected');
          }
        } else {
          $('select#select-indicador-a option[value='+ 'i62' +']').attr('selected', 'selected');
        }
        firstrun = false;
      }
      //First time, default_group = a
      set_active_indicador($("select#select-indicador-a option:selected").val());
      change_active_indicator("a", NULL_CONSTANT,NULL_CONSTANT);
    }(jQuery_2_1_1));
  }

  function init_all() {
    init_all_values();
    (function ($) {
      $("select#select-objetivo-a").off();
      // Indicador A
      if (firstrun == false) {
        current_obj_a = $("select#select-objetivo-a option:selected").val();
      }
      $("select#select-objetivo-a").empty();
      $.each(metadata_grouped, function(objetivo, indicadores) {
        $("select#select-objetivo-a").append("<option value='"+objetivo+"'>"+objetivo+"</option>");
      });
      $("select#select-objetivo-a").change(function() {
       (function ($) { $("#loading_wrap").fadeIn(); }(jQuery_2_1_1));
       init_all();
       populate_indicador_a();
     });
      if (firstrun == true) {
        $('select#select-objetivo-a option[value="Poner fin a la pobreza en todas sus formas en todo el mundo"]').attr('selected', 'selected');
      } else {
        if ($("select#select-objetivo-a option[value='"+current_obj_a+"']").length > 0)
          $("select#select-objetivo-a option[value='"+current_obj_a+"']").attr('selected', 'selected');
        else
          $("select#select-objetivo-a option:first").attr('selected','selected');
      }
      populate_indicador_a();
    }(jQuery_2_1_1));

  }

  (function ($) {
    init_all();
    $("select#select-indicador-a").change(function() {
      (function ($) { $("#loading_wrap").fadeIn(); }(jQuery_2_1_1));
      active_indicador = $("select#select-indicador-a option:selected").val();
      var tmp_indicator =active_indicador+"1";
      change_acumulate(tmp_indicator);
      change_active_indicator(active_group,active_unit,active_year);
    });
    $("select#filter-grupo").change(function() {
      change_active_group($("select#filter-grupo option:selected").val());
    });
    $("#filter-entidad-mpal").change(function() {
      render_line();
    });
    $("select#filter-geo").change(function() {
      change_active_unit($("select#filter-geo option:selected").val());

    });
    $("#trim_to_ac").change(function() {
        var tmp_indicator =active_indicador+"1";
        change_acumulate(tmp_indicator);
        change_active_indicator(active_group,active_unit,active_year);

    });
    $("ul.menu li.leaf:nth-child(2) a").mousedown(function() {
      o=$("select#select-objetivo-a option:selected").index();
      i=active_indicador;
      $("ul.menu li.leaf:nth-child(2) a").attr("href","/explora?o="+o+"&i="+i);
    });
    $("ul.menu li.leaf:nth-child(3) a").mousedown(function() {
      o=$("select#select-objetivo-a option:selected").index();
      i=active_indicador;
      $("ul.menu li.leaf:nth-child(3) a").attr("href","/compara?o="+o+"&i="+i);
    })

  }(jQuery_2_1_1));

  // END API FUNCTIONS

</script>
