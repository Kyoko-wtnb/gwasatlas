<?php

namespace atlas\Http\Controllers;

use Illuminate\Http\Request;
use atlas\Http\Requests;
use Illuminate\Support\Facades\DB;
use atlas\Http\Controllers\Controller;
use File;

class MultiController extends Controller
{
	public function getSummary(Request $request){
		$ids = $request->input('ids');
		$ids = explode(":", $ids);

		$tmp = DB::select('SELECT id, PMID, Year, Domain, Trait, N, Nhits, SNPh2, SNPh2_z FROM gwasDB');
		$tmp = json_decode(json_encode($tmp), true);
		$dbData = [];
		foreach($tmp as $r){
			if(in_array($r['id'], $ids)){
				$dbData[] = $r;
			}
		}
		unset($tmp);

		// summary of selected GWAS
		$sum = [];
		$sum['GWAS'] = count($ids);
		$sum['Trait'] = count(array_unique(preg_replace("/(.+) \(.+\)/", "$1", array_column($dbData, 'Trait'))));
		$sum['Domain'] = count(array_unique(array_column($dbData, 'Domain')));
		$sum['Study'] = count(array_unique(array_column($dbData, 'PMID')));
		$sum['maxN'] = max(array_column($dbData, 'N'));
		$sum['minN'] = min(array_column($dbData, 'N'));
		$sum['avgN'] = round(array_sum(array_column($dbData, 'N'))/count($dbData), 2);

		// h2 and riskloci
		$plotData = [];
		$tmpdata = [];
		foreach($dbData as $r){
			$plotData[] = [$r['id'], $r['Year'], $r['Domain'], $r['Trait'], $r['N']/1000, $r['Nhits'], $r['SNPh2'], $r['SNPh2_z']];
			$tmpdata[] = [$r['id'], $r['Year'], $r['N']/1000, $r['Nhits'], $r['SNPh2'], $r['SNPh2_z']];
		}

		$script = storage_path().'/scripts/getPlotCor.py';
		$plotCor = shell_exec("python $script '".json_encode(['data'=>$tmpdata])."'");
		if($plotCor == ""){$plotCor = [];}
		else{$plotCor = json_decode($plotCor);}

		$out = ["sum"=>$sum, "plotData"=>['data'=>$plotData, 'cor'=>$plotCor]];
		return json_encode($out);
	}

	public function getGC(Request $request){
		$ids = $request->input('ids');
		$script = storage_path().'/scripts/getGCheat.py';
		$host = config('database.connections.mysql.host');
		$user = config('database.connections.mysql.username');
		$pass = config('database.connections.mysql.password');
		$db = config('database.connections.mysql.database');
		$gc = shell_exec("python $script $host $user $pass $db $ids");
		return $gc;
	}

	public function getGenes(Request $request){
		$ids = $request->input('ids');
		$script = storage_path().'/scripts/getGeneheat.py';
		$host = config('database.connections.mysql.host');
		$user = config('database.connections.mysql.username');
		$pass = config('database.connections.mysql.password');
		$db = config('database.connections.mysql.database');
		$magma = shell_exec("python $script $host $user $pass $db $ids 2.5e-6");
		return $magma;
	}

	public function getLociOverlap(Request $request){
		$ids = $request->input('ids');
		$script = storage_path().'/scripts/getLociOverlap.py';
		$host = config('database.connections.mysql.host');
		$user = config('database.connections.mysql.username');
		$pass = config('database.connections.mysql.password');
		$db = config('database.connections.mysql.database');
		$lociOver = shell_exec("python $script $host $user $pass $db $ids");
		return $lociOver;
	}

	public function getGenesPleiotropy(Request $request){
		$ids = $request->input('ids');
		$script = storage_path().'/scripts/getGenesPleiotropy.py';
		$host = config('database.connections.mysql.host');
		$user = config('database.connections.mysql.username');
		$pass = config('database.connections.mysql.password');
		$db = config('database.connections.mysql.database');
		$genes = shell_exec("python $script $host $user $pass $db $ids");
		return $genes;
	}

	public function imgdown(Request $request){
		$t = time();
		$svg = $request->input('data');
		$type = $request->input('type');
		$fileName = $request->input('fileName');
		$filedir = config('app.datadir').'/tmp_plot/'.$t.'/';
		File::makeDirectory($filedir, $mode = 0755, $recursive = true);
		$svgfile = $filedir.'temp.svg';
		$outfile = $filedir.$fileName.'_'.date('Ymd_His', $t).'.'.$type;

		$svg = preg_replace("/\),rotate/", ")rotate", $svg);
		$svg = preg_replace("/,skewX\(.+?\)/", "", $svg);
		$svg = preg_replace("/,scale\(.+?\)/", "", $svg);
		if($type=="svg"){
			file_put_contents($svgfile, $svg);
			File::move($svgfile, $outfile);
		}else{
			$image = new \Imagick();
			$image->setResolution(300,300);
			$image->readImageBlob('<?xml version="1.0"?>'.$svg);
			$image->setImageFormat($type);
			$image->writeImage($outfile);
			return response() -> download($outfile);
		}
		return response() -> download($outfile);
    }
}
