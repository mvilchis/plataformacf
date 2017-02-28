<?php
	
include('h_objetivos.php');
	
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,"http://api.datos.gob.mx/v1/cf.metadata?pageSize=999999");
$result=curl_exec($ch);
curl_close($ch);
$metadata = json_decode($result, true);
$indicadores_id = array();
foreach($metadata["results"] as $value) {
	if (array_key_exists($value["Nombre_del_objetivo"],$indicadores)) array_push($indicadores[$value["Nombre_del_objetivo"]],$value);
	$indicadores_id[$value["Clave"]] = $value;
}
?>

<div class="jumbotron-block col-xs-12 col-sm-8">
	<h4 style="font-weight: 700; color: #00cc99;">Crowdfunding MX</h4>
	<h3>Sistema de Información sobre Financiamiento Colectivo</h3>
	<p>Es una iniciativa del Fondo Multilateral de Inversiones (FOMIN) del Banco Interamericano de Desarrollo (BID) ejecutado por la Universidad Anáhuac a través del Centro IDEARSE con la participación de Nacional Financiera, el Instituto Nacional del Emprendedor, la Oficina de Estrategia Digital de la Presidencia de la República, la Comisión Nacional Bancaria y de Valores y la Asociación de Plataformas de Fondeo Colectivo, para impulsar proyectos vía crowdfunding o de financiamiento/fondeo colectivo en México.</p>
	<p style="margin-top: 20px;">
		<span style="margin-right: 20px;"><img width=133 height=35 src="<?php echo path_to_theme(); ?>/assets/presidencia.png" /></span>
		<span style="margin-right: 20px;"><img width=133 height=35 src="<?php echo path_to_theme(); ?>/assets/Vectores/logo-header2.png" /></span>
		
	</p>
</div>
<div class="jumbotron-block col-xs-12">
	<p>
		Consulta datos de más de X indicadores sobre la evolución del financiamiento colectivo en México. <br/>Primero selecciona un tipo de financiamiento colectivo, y posteriormente elige un indicador:
	</p>
</div>
<?php
	echo('<div class="jumbotron-block col-sm-6 col-xs-12">');
	$i = 0;
	foreach($indicadores as $key => $objetivo) {
		if ($i == 9) {
			echo("</div>");
			echo('<div class="jumbotron-block col-sm-6 col-xs-12">');
		}
		if (count($objetivo) < 1) {
			$empty_class = " ind-empty";
			$tt = ' data-toggle="tooltip" data-placement="right" title="Próximamente" ';
		}
		else {
			$empty_class = "";
			$tt = "";
		}
		echo ('<div class="col-xs-12 noselect indicador-group'.$empty_class.'" value="'.$i.'"'.$tt.'><div class="row indicador-row"><div class="col-xs-1"><img src="'.path_to_theme().'/assets/sdg_icons/'.$objetivo_icons[$key].'.png"/></div><div class="col-xs-11"><div class="objetivo-name"><strong>'.($i+1).'. </strong>'.$objetivo_nombres[$key].'</div></div></div><div style="display: none;" class="row listed-indicadores"><div class="listed-indicadores-title">INDICADORES</div>');
		foreach($objetivo as $indicador) {
			echo ( '<div onmousedown="visit_indicador(\''.$i.'\',\''.$indicador["Clave"].'\')" class="listed-indicador"><div class="col-xs-12">'.$indicador["Nombre_del_indicador"]."</div></div>" );
		}
		echo ('</div></div>');
		$i++;
	}
?>
</div>
<script type='text/javascript'>
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
