<?php
include_once("Entidades/Token.php");
include_once("Entidades/Encuesta.php");

class EncuestaAPI extends Encuesta
{
    public function RegistrarEncuesta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $puntuacionMesa = $parametros["puntuacionMesa"];
        $codigoMesa = $parametros["codigoMesa"];
        $puntuacionRestaurante = $parametros["puntuacionRestaurante"];
        $puntuacionMozo = $parametros["puntuacionMozo"];
        $puntuacionCocinero = $parametros["puntuacionCocinero"];
        $comentario = $parametros["comentario"];

        $respuesta = Encuesta::Registrar($puntuacionMesa, $codigoMesa, $puntuacionRestaurante, $puntuacionMozo, $puntuacionCocinero, $comentario);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ListarEncuestas($request, $response, $args)
    {
        $respuesta = Encuesta::Listar();
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ListarEncuestasEntreFechas($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $fecha1 = $parametros["fecha1"];
        $fecha2 = $parametros["fecha2"];
        $respuesta = Encuesta::ListarEntreFechas($fecha1, $fecha2);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }
}

?>