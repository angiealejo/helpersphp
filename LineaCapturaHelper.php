<?php 

class LineaCapturaHelper{

	public static function Juliana($dias)
	{		
		$hoy = cal_to_jd(CAL_GREGORIAN, date('n'), date('j'), date('Y'));
		$base = cal_to_jd(CAL_GREGORIAN, 12, 31, 2015);
		$diaActual = date('D');
		$fecha = ($hoy - $base)+$dias;
		$fecha = str_pad($fecha, 4, '0', STR_PAD_LEFT);

		//Alternativa
		//obtenemos la fecha de vigencia
		$base = 2016;
		$vigencia = explode('-',date('d-m-Y', strtotime(date('d-m-Y').' + '.$dias.' days')));
		//empezamos a calcular
		$fechaJuliana = ($vigencia[0]-1)+(($vigencia[1]-1)*31)+(($vigencia[2]-$base)*372);
		$fechaJuliana = str_pad($fechaJuliana, 4, '0', STR_PAD_LEFT);
			
		return $fechaJuliana;	
	}
	
	public static function validarImporte($importe)
	{
		$total = 0;
		$arreglo =[7,3,1];
		$importe = array_reverse(str_split(number_format($importe, 2, '.', '')));
		$i = 0;
		foreach ($importe as $n)
		{
			if($n !='.')
			{		
				$total += $n * $arreglo[$i];
				if ($i == count($arreglo) - 1)
					{
						$i = 0;
					}else
					{
						$i++;
					}
			}
		}
			
		return $total % 10;	
	}

	public static function validarDigitos($linea)
	{
		$suma = 0;
		$arreglo =[11, 13, 17, 19, 23];
		$linea = array_reverse(str_split($linea));
		$i = 0;
		foreach ($linea as $n)
		{
			$suma += $n * $arreglo[$i];
			if ($i == count($arreglo) - 1)
			{
				$i = 0;
			}else
			{
				$i++;
			}
		}
		$total = ($suma % 97)+1;
		$total = str_pad($total,2,'0',STR_PAD_LEFT);
		return $total;	
	}

	public static function CalcularLinea($concepto, $municipio, $importe, $fechaLimite)
	{
		$fechaJulianaLimite = self::Juliana($fechaLimite);
		$digitoImporte = self::validarImporte($importe);
		$numeroConsecutivo = Referencias::obtenerConsecutivo(date('Y'),$municipio, $concepto);
		
		$referencia = '';
		$referencia .= '1';
		$referencia .= $concepto;
		$referencia .= $municipio;
		$referencia .= date('Y') . date('n') . date('j');
		$referencia .= $numeroConsecutivo;
		$referencia .= $fechaJulianaLimite;
		$referencia .= $digitoImporte;
		$referencia .= '2';

		$digitosValidadores = self::validarDigitos($referencia);

		$referencia .= $digitosValidadores;

		return $referencia;

	}

}