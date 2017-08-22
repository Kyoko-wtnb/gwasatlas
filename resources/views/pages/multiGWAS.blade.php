@extends('layouts.master')
@section('head')
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.0/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
<script src="//labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
<script type="text/javascript" src="//d3js.org/queue.v1.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<script type="text/javascript">
	$.ajaxSetup({
		headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')}
	});
	var subdir = "{{ Config::get('app.subdir') }}";
</script>
<script type="text/javascript" src="{!! URL::asset('js/multiGWAS.js') !!}"></script>
<script type="text/javascript" src="{!! URL::asset('js/sidebar.js') !!}"></script>
<link rel="stylesheet" href="{!! URL::asset('css/style.css') !!}">
@stop

@section('content')
<div style="padding-top: 50px; padding-right: 50px; padding-left: 50px;">
	<!-- GWAS selectionz -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Select GWAS</h4>
		</div>
		<div class="panel-body">
			<div class="container">
				Filter GWAS<br/>
				<strong>By trait categories</strong><br/>
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-4">
						Domain:<br/>
						<select class="selectpicker" name="Domain" id="Domain" onchange='Selection("Domain");'>
							<option value=null>-- Please select Domain of interest --</option>
						</select>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4">
						Chapter level:<br/>
						<select class="selectpicker" name="Chapter" id="Chapter" onchange='Selection("Chapter");'>
							<option value=null>-- Please select Chapter of interest --</option>
						</select>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4">
						Subchapter level:<br/>
						<select class="selectpicker" name="Subchapter" id="Subchapter" onchange='Selection("Subchapter");'>
							<option value=null>-- Please select Subchapter of interest --</option>
						</select>
					</div>
				</div>
				<br/>
				<span class="form-inline">
					<strong>By trait</strong><br/>
					<select class="selectpicker" data-width="75%" name="Trait" id="Trait" onchange='Selection("Trait");'>
						<option value=null>-- Please select Domain of interest --</option>
					</select><br/>
				</span>
				<br/>
				<span class="form-inline">
					<strong>By published year</strong><br/>
					From <input type="number" class="form-control" id="yearFrom" name="yearFrom" onkeydown="SelectEnter(this)">
					to <input type="number" class="form-control" id="yearTo" name="yearTo" onkeydown="SelectEnter(this)">
				</span>
				<br/><br/>
				<span class="form-inline">
					<strong>By total sample size</strong><br/>
					Minimum: <input type="number" class="form-control" id="nMin" name="nMin" onkeydown="SelectEnter(this)">
					Maximum: <input type="number" class="form-control" id="nMax" name="nMax" onkeydown="SelectEnter(this)">
				</span>
				<br/>
			</div>

			<div class="row" style="padding:25px;">
				<!-- <div class="col-md-8 col-sm-8 col-xs-8"> -->
					<!-- <span class="info" style="font-size:14px;"><i class="fa fa-info"></i>
						Click row to select a GWAS or
					</span>
					<button class="btn btn-sm">select all GWAS in the table</button><br/> -->
					<table id="selectTable" class="display compact dt-body-right dt-head-center" width="90%" cellspacing="0" style="display: block; overflow-x: auto; font-size:12px;">
						<thead>
							<th>ID</th>
							<th>PMID</th>
							<th>Year</th>
							<th>Domain</th>
							<th>Chapter level</th>
							<th>Subchapter level</th>
							<th>Trait</th>
							<th>Population</th>
							<th>N</th>
						</thead>
					</table>
				<!-- </div> -->
				<!-- <div class="col-md-4 col-sm-4 col-xs-4">
					Selected GWAS<br/>
					<textarea rows="20" cols="50"></textarea>
				</div> -->
			</div>
			<button class="btn" id="processGWAS">Compare displayed GWAS</button>
			<div id="msg"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Summary of selected GWAS</h4>
				</div>
				<div class="panel-body col4BoxBody" id="sumBody" style="text-align:center; overfloat:auto;">
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Year vs Sample size</h4>
				</div>
				<div class="panel-body col4BoxBody" id="yearVSnBody" style="text-align:center; overfloat:auto;">
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Number of risk loci vs Sample size</h4>
				</div>
				<div class="panel-body col4BoxBody" id="lociVSnBody" style="text-align:center; overfloat:auto;">
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">SNP heritability vs Sample size</h4>
				</div>
				<div class="panel-body col4BoxBody" id="h2VSnBody" style="text-align:center; overfloat:auto;">
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">SNP heritability vs Number of risk loci</h4>
				</div>
				<div class="panel-body col4BoxBody" id="h2VSlociBody" style="text-align:center; overfloat:auto;">
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Domain Color-code</h4>
				</div>
				<div class="panel-body col4BoxBody" id="colorBody" style="text-align:center; overfloat:auto;">
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="gc_magma_row">
		<div class="col-md-6 col-sm-6 col-xs-6" id="gcCol">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Genetic Correlation</h4>
				</div>
				<div class="panel-body col6BoxBody" id="gcBody" style="overflow: auto;">
					<div>
						<span class="form-inline">Sort traits by:
							<select id="gcOrder" class="forn-control">
								<option value="alph">Alphabetically</option>
								<option value="domain">Domain</option>
								<option value="clst">Cluster</option>
							</select>
						</span>
					</div>
					<div id="gcPlot" style="text-align:center;"></div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6" id="magmaCol">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">MAGMA genes overlap</h4>
				</div>
				<div class="panel-body col6BoxBody" id="magmaBody" style="overflow: auto;">
					<div>
						<span class="form-inline">Sort traits by:
							<select id="magmaOrder" class="forn-control">
								<option value="alph">Alphabetically</option>
								<option value="domain">Domain</option>
								<option value="clst">Cluster</option>
							</select>
						</span>
					</div>
					<div id="magmaPlot" style="text-align:center;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Genomic risk loci</h4>
		</div>
		<div class="panel-body" id="riskLociOverBody" style="text-align:center;">
		</div>
	</div>
</div>

@stop
