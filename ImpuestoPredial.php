<?php

namespace app\libraries;

class ImpuestoPredial {

	public $valorFiscal;
	public $excedente;
	public $cuotaFija;
	public $factor;
	public $smv;
	public $impuestoPredial;

	public static function Calcular($anio, $valorCatastral, $tipoPredio){
		
		switch ($anio) {
			case 2010:
				$smv = 55.84;
				break;

			case 2011:
				$smv = 58.13;
				break;

			case 2012:
				$smv =  59.08;
				break;

			case 2013:
				$smv = 61.38;
				break;

			case 2014:
				$smv = 63.77;
				break;

			case 2015:
				$smv = 70.1;
				break;

			case 2016:
				$smv = 73.04;
				break;

			return $smv;
		}

        
        if ($tipoPredio=="U"){

        	$impuestoMinimo = $smv * 4;

        }elseif ($tipoPredio=="R"){

        	$impuestoMinimo = $smv * 3;

        }

		$valorFiscal = $valorCatastral * 0.2;

		if ($valorFiscal>=0 && $valorFiscal<=10000){

			$excedente = $valorFiscal;
			$factor = 0.7;
			$cuotaFija = 0;

		}elseif ($valorFiscal>=10001 && $valorFiscal<=30000) {

			$excedente = $valorFiscal-10001;
			$factor = 0.8;
			$cuotaFija = 70;

		}elseif ($valorFiscal>=30000 && $valorFiscal<=50000) {

			$excedente = $valorFiscal-30001;
			$factor = 0.9;
			$cuotaFija = 230;
 
 		}elseif ($valorFiscal>=50001 && $valorFiscal<=70000) {
 			
 			$excedente = $valorFiscal-50001;
			$factor = 1.0;
			$cuotaFija = 410;

 		}elseif ($valorFiscal>=70001 && $valorFiscal<=9999999.99) {
 			$excedente = $valorFiscal-70001;
			$factor = 1.1;
			$cuotaFija = 610;

 		}


 		$impuestoPredial = ($excedente * $factor / 100) + $cuotaFija;
 		if($impuestoPredial<$impuestoMinimo){
 			$impuestoPredial=$impuestoMinimo;
 		}
 		$impuestoPredial = round($impuestoPredial,0, PHP_ROUND_HALF_UP);

 		return $impuestoPredial;


	}

}