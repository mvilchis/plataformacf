<section class="datatable">
	<div style="margin-top: 30px;margin-bottom: 30px;width:1550px" class="container">
		<div class="col-xs-12 col-sm-12">
			<div class="stat-column-header "><h1>Datos</h1></div>
		</div>
		<?php if ($page == "compara"): ?>
		<div class="row"><div class="circle-letter">A</div></div>
		<?php endif; ?>
		<div class="col-xs-12">
			<table id="datos" class="datos" style="width: 100%;">
				<thead>
					<th>Nombre</th>
					<th>Tipo de Financiamiento</th>
					<th>Tipo de Financiamiento</th>
					<th>Formato</th>
					<th></th>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<?php if ($page == "compara"): ?>
		<div style='margin-top:30px;' class="row"><div class="circle-letter">B</div></div>
		<div class="col-xs-12">
			<table id="datos-b" class="datos" style="width: 100%;">
				<thead>
					<th>Nombre</th>
					<th>Tipo de Financiamiento</th>
					<th>Tipo de Financiamiento</th>
					<th>Formato</th>
					<th></th>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
</section>
