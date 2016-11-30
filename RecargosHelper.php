<?php

use Symfony\Component\Config\Definition\Exception\Exception;
namespace app\libraries;

class RecargosHelper {
	
	public static function CalcularRecargo($municipio, $anioPago, $mesPago, $anioDebioPagar, $mesDebioPagar){


		$fecha = strtotime($anioPago."-".$mesPago."-01 - 1 month");
		$anioActual = date("Y", $fecha);
		$mun = "008";
		//$anio = 2015;

		$recargosTotales = 0;
		if ($anioDebioPagar===$anioActual){
			return $recargosTotales;
		}
		
		for ($i=$anioDebioPagar; $i<=$anioActual; $i++)
		{
			$recargo = \RecargosModel::where('municipio', $municipio)->where('anio', $anioDebioPagar)->first();
			//$porcentaje =1;
			//$recargo = 1;
			$porcentaje = $recargo->porcentaje;

			$recargosAnio = $mesDebioPagar * $porcentaje;

			$recargosTotales+= $recargosAnio;

			if($recargosTotales>100){
				$recargosTotales=100;
			}
		}

		return $recargosTotales;

	}

}