<?php

use Symfony\Component\Config\Definition\Exception\Exception;
namespace app\libraries;

class ActualizacionHelper {

    /**
     * Calcula el indice de actualizacion del impuesto predial
     * Según el código fiscal de la federación vigente a la fecha de desarrollo
     * http://info4.juridicas.unam.mx/ijure/fed/6/28.htm
     *
     * @param $anioPago: anio actual
     * @param $mesPago: mes actual
     * @param $anioDebioPagar: anio del cual se efectua el pago
     * @param $mesDebioPagar: diciembre para todos los casos
     * @return float
     * @throws \INPCInexistenteException
     */
    public static function calcularFactor($anioPago, $mesPago, $anioDebioPagar, $mesDebioPagar){

        //Se realiza condicion para tomar el INPC del mes anterior si esta dentro de los primeros 10 dias.

        $diaActual = date('d');
        if($diaActual <= 10)--$mesPago;

        if($anioDebioPagar == date('Y')) return 1;

        //Se obtiene el INPC del periodo anterior al mes del pago.
        /* fecha: fecha actual
         * anioINPCFin: ano de la fecha en que se hace el pago
         * mesINPCFin: mes de la fecha en que se hace el pago
         */

        $fecha = strtotime($anioPago."-".$mesPago."-01 - 1 month");
        $anioINPCFin = date("Y",$fecha);
        $mesINPCFin = date("m",$fecha);
       
        $inpcObj = \inpc::where('anio', $anioINPCFin)->where('mes',$mesINPCFin)->first();

        if(!$inpcObj){
           throw new \INPCInexistenteException("No se encuentra el INPC para el periodo $anioINPCFin - $mesINPCFin");
           // $inpcPago=1;
        }
        $inpcPago = $inpcObj->inpc;
        /*else{
            $inpcPago = $inpcObj->inpc;

        }
*/
        $fecha = strtotime($anioDebioPagar."-".$mesDebioPagar."-01 - 1 month");
        $anioINPCDebioPagar = date("Y",$fecha);
        $mesINPCDebioPagar = date("m",$fecha);

        $inpcDebioObj = \inpc::where('anio', $anioINPCDebioPagar)->where('mes',$mesINPCDebioPagar)->first();
        if(!$inpcDebioObj || $inpcDebioObj->inpc == 0 ){
            throw new \INPCInexistenteException("No se encuentra el INPC para el periodo $anioINPCDebioPagar - $mesINPCDebioPagar");
          //$inpcDebioPagar=1;

        }
        $inpcDebioPagar = $inpcDebioObj->inpc;
        /*else{
            $inpcDebioPagar = $inpcDebioObj->inpc;

        }
*/

        $factorActualizacion = $inpcPago / $inpcDebioPagar;

        return $factorActualizacion;
    }


}