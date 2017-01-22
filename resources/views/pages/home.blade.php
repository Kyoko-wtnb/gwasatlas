@extends('layouts.master')
@section('head')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    // $('#snp2genebtn').on('click', function(){
    //   window.location.href="{{ Config::get('app.subdir') }}/snp2gene";
    // });
    //
    // $('#gene2funcbtn').on('click', function(){
    //   window.location.href="{{ Config::get('app.subdir') }}/gene2func";
    // });

  });
</script>
@stop
@section('content')
<div class="container" style="padding-top:50px;">
  <div style="text-align: center;">
    <h2>GWAS ATLAS</h2>
    <h2>Database of publiclly available summary statistics of genome-wide association studies.</h2>
  </div>
  <br/>
  <p>FUMA is a platform that can be used to annotate, prioritize and visualize and interpret GWAS results.
  <br/>
    The <a href="{{ Config::get('app.subdir') }}/snp2gene">SNP2GENE</a> function takes GWAS summary statistics or a list of rsid’s as input,
    and provides extensive functional annotation for all SNPs in genomic areas identified by lead SNPs.
    <br/>
    The <a href="{{ Config::get('app.subdir') }}/gene2func">GENE2FUNC</a> function takes a list of geneids (as identified by SNP2GENE or as provided manually)
    and annotates genes in biological context
    <br/>
    Please log in to use FUMA.
    If you have't registered yet, you can do from <a href="{{ url('/register') }}">here</a>.
    <br/>
    When using FUMA, please acknowledge Watanabe et al. xxx
  </p>
  <br/>

  <!-- <div class="row">
    <div class="col-md-6 col-xs-6 col-sm-6" style="text-align:center; padding: 20px;">
      <div style="background-color: #dfdfdf; padding-top:20px; padding-bottom:20px;">
        <button id="snp2genebtn" class="btn btn-primary">SNP2GENE</button>
        <br/><br/>
      </div>
    </div>
    <div class="col-md-6 col-xs-6 col-sm-6" style="text-align:center; padding: 20px;">
      <div style="background-color: #dfdfdf; padding-top:20px; padding-bottom:20px;">
        <button id="gene2funcbtn" class="btn btn-success">GENE2FUNC</button>
        <br/><br/>
      </div>
    </div>
  </div> -->

</div>
</br>
@stop
