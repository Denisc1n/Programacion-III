<?php
include_once("Entidades/Token.php");
include_once("Entidades/Facturacion.php");

class FacturaAPI extends Facturacion
{

    public function ListarVentasPDF($request, $response, $args)
    {
        $respuesta = Facturacion::PDF();
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ListarVentasExcel($request, $response, $args)
    {
        $respuesta = Facturacion::Excel();
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ListarFacturasEntreFechas($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $fecha1 = $parametros["fecha1"];
        $fecha2 = $parametros["fecha2"];
        $respuesta = Facturacion::EntreFechas($fecha1, $fecha2);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }
}

?>