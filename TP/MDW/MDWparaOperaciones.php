<?php
class MDWparaOperaciones
{
    public static function SumarOperacion($request, $response, $next)
    {
        $payload = $request->getAttribute("payload")["Payload"];

        Empleado::SumarOperacionEmpleado($payload->id);

        return $next($request, $response);
    }
}
?>