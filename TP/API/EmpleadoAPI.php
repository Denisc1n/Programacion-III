<?php
include_once("Entidades/Token.php");
include_once("Entidades/Empleado.php");

class EmpleadoAPI extends Empleado
{
    public function LoginEmpleado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros["usuario"];
        $clave = $parametros["clave"];
        $empleado = Empleado::Login($usuario, $clave);

        if ($empleado["perfil"] != "") {

            $token = Token::CodificarToken($empleado["id"], $empleado["nombre"], $usuario, $empleado["perfil"]);
            $respuesta = array("Estado" => "OK", "Mensaje" => "Bienvenido: " . $empleado["nombre"], "Token" => $token, "empleado" => $empleado);
        } else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Usuario o clave incorrectos.");
        }
        Empleado::ActualizarLogin($empleado["id"]);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function AltaEmpleado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros["nombre"];
        $usuario = $parametros["usuario"];
        $clave = $parametros["clave"];
        $perfil = $parametros["perfil"];

        $respuesta = Empleado::AltaEmpleados($nombre, $usuario, $clave, $perfil);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ListaEmpleados($request, $response, $args)
    {
        $respuesta = Empleado::ListarEmpleados();
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function BajaEmpleado($request, $response, $args)
    {
        $id = $args["id"];
        $respuesta = Empleado::BajaEmpleados($id);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function SuspenderEmpleado($request, $response, $args)
    {
        $id = $args["id"];
        $respuesta = Empleado::SuspenderEmpleados($id);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ModificarEmpleado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros["id"];
        $nombre = $parametros["nombre"];
        $usuario = $parametros["usuario"];
        $clave = $parametros["clave"];
        $perfil = $parametros["perfil"];

        $respuesta = Empleado::ModificarEmpleados($id, $nombre, $usuario, $clave, $perfil);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function CantOperacionesSector($request, $response, $args)
    {
        $sectores = Empleado::CantidadOperacionesPorSector();
        $respuesta = array();

        foreach ($sectores as $sector) {
            $respuesta[] = array("Sector: " . $sector["sector"], "Cantidad Operaciones: " . $sector["CantidadOperaciones"]);
        }

        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function CantOperacionesSectorEmpleado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $sector = $parametros["sector"]; //parametro
        $respuesta = array();

        $sectorEmpleados = Empleado::CantidadOperacionesEmpleadosPorSector($sector);

        foreach ($sectorEmpleados as $sectorEmpleado) {
            $respuesta[] = array("Empleado: " . $sectorEmpleado["nombre"], "Cantidad Operaciones: " . $sectorEmpleado["CantidadOperaciones"]);
        }

        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

        public function CantOperacionesEmpleado($request, $response, $args)
    {
        $id = $args["id"];
        $empleado = Empleado::CantidadOperacionesEmpleado($id);

        $respuesta = array("Empleado: " . $empleado["nombre"], "Cantidad Operaciones: " . $empleado["CantidadOperaciones"]);

        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ListaEmpleadosFechasLogin($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $fecha1 = $parametros["fecha1"];
        $fecha2 = $parametros["fecha2"];
        $respuesta = Empleado::ListarLogin($fecha1, $fecha2);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function ListaEmpleadosFechasRegistro($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $fecha1 = $parametros["fecha1"];
        $fecha2 = $parametros["fecha2"];
        $respuesta = Empleado::ListarRegistro($fecha1, $fecha2);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }
}


?>