<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';

include_once './API/EmpleadoAPI.php';
include_once './API/MesaAPI.php';
include_once './API/PedidoAPI.php';
include_once './API/FacturaAPI.php';
include_once './API/EncuestaAPI.php';

include_once './MDW/MDWEmpleado.php';
include_once './MDW/MDWparaOperaciones.php';
include_once './MDW/MWparaCORS.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

//Empleado
$app->group('/empleado', function () {

    $this->post('/login[/]', \EmpleadoAPI::class . ':LoginEmpleado');

    $this->post('/registrar[/]', \EmpleadoAPI::class . ':AltaEmpleado')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->get('/listar[/]', \EmpleadoAPI::class . ':ListaEmpleados')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->delete('/suspender/{id}[/]', \EmpleadoAPI::class . ':SuspenderEmpleado')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->delete('/{id}[/]', \EmpleadoAPI::class . ':BajaEmpleado')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->post('/modificar[/]', \EmpleadoAPI::class . ':ModificarEmpleado')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->get('/operacionesSector[/]', \EmpleadoAPI::class . ':CantOperacionesSector')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->post('/operacionesSectorEmpleado[/]', \EmpleadoAPI::class . ':CantOperacionesSectorEmpleado')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

     $this->get('/operacionesEmpleado/{id}[/]', \EmpleadoAPI::class . ':CantOperacionesEmpleado')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');       

    $this->post('/fechasLogin[/]', \EmpleadoAPI::class . ':ListaEmpleadosFechasLogin')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->post('/fechasRegistro[/]', \EmpleadoAPI::class . ':ListaEmpleadosFechasRegistro')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

});



//Mesas
$app->group('/mesas', function () {

    $this->post('/registrar[/]', \MesaAPI::class . ':RegistrarMesa')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/listar[/]', \MesaAPI::class . ':ListarMesas')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->delete('/{codigo}[/]', \MesaAPI::class . ':BajaMesa')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->post('/foto[/]', \MesaAPI::class . ':ActualizarFotoMesa')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarMozo');

    $this->get('/cerrar/{codigo}[/]', \MesaAPI::class . ':Cerrada')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/masUsada[/]', \MesaAPI::class . ':MesaMasUsada')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/menosUsada[/]', \MesaAPI::class . ':MesaMenosUsada')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/masFacturacion[/]', \MesaAPI::class . ':MesaMasFacturacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/menosFacturacion[/]', \MesaAPI::class . ':MesaMenosFacturacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/mayorImporte[/]', \MesaAPI::class . ':MesaMayorImporte')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/menorImporte[/]', \MesaAPI::class . ':MesaMenorImporte')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->post('/facturacionEntreFechas[/]', \MesaAPI::class . ':MesaFacturacionEntreFechas')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/mejorPuntuacion[/]', \MesaAPI::class . ':MesaMejorPuntuacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

    $this->get('/peorPuntuacion[/]', \MesaAPI::class . ':MesaPeorPuntuacion')
        ->add(\MDWEmpleado::class . ':ValidarSocio');

})->add(\MDWEmpleado::class . ':ValidarToken');




//Pedido
$app->group('/pedido', function () {

    $this->post('/registrar[/]', \PedidoAPI::class . ':RegistrarPedido')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarMozo')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->delete('/{codigo}[/]', \PedidoAPI::class . ':CancelarPedido')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarMozo')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->get('/listarTodos[/]', \PedidoAPI::class . ':ListarTodosPedidos')
        ->add(\MDWEmpleado::class . ':FiltrarPedidos')
        ->add(\MDWEmpleado::class . ':ValidarToken');
    /*    
    $this->get('/listarPendientes[/]', \PedidoAPI::class . ':ListarPedidoPendientes')
        ->add(\MDWEmpleado::class . ':ValidarMozo')
        ->add(\MDWEmpleado::class . ':ValidarToken');*/


    $this->post('/tomarPedido[/]', \PedidoAPI::class . ':TomarPedidoPendiente')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->post('/pedidoListo[/]', \PedidoAPI::class . ':InformarPedidoListo')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarMozo')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->post('/servirPedido[/]', \PedidoAPI::class . ':ServirPedido')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarMozo')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->post('/cobrarPedido[/]', \PedidoAPI::class . ':CobrarPedidosMesa')
        ->add(\MDWparaOperaciones::class . ':SumarOperacion')
        ->add(\MDWEmpleado::class . ':ValidarMozo')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->get('/tiempoRestante/{codigoPedido}[/]', \PedidoAPI::class . ':TiempoRestantePedido');

    $this->get('/masVendido[/]', \PedidoAPI::class . ':LoMasVendido')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->get('/menosVendido[/]', \PedidoAPI::class . ':LoMenosVendido')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->get('/listarFueradeTiempo[/]', \PedidoAPI::class . ':ListarPedidosFueradeTiempo')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->get('/listarCancelados[/]', \PedidoAPI::class . ':ListarPedidosCancelados')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

});



//Facturacion
$app->group('/facturas', function () {

    $this->get('/listarPDF[/]', \FacturaAPI::class . ':ListarVentasPDF');

    $this->get('/listarExcel[/]', \FacturaAPI::class . ':ListarVentasExcel');

    $this->post('/listarEntreFechas[/]', \FacturaAPI::class . ':ListarFacturasEntreFechas');

})->add(\MDWEmpleado::class . ':ValidarSocio')
  ->add(\MDWEmpleado::class . ':ValidarToken');




//Encuesta
$app->group('/encuesta', function () {

    $this->post('/registrar[/]', \EncuestaAPI::class . ':RegistrarEncuesta');

    $this->get('/listar[/]', \EncuestaAPI::class . ':ListarEncuestas')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');

    $this->post('/listarEntreFechas[/]', \EncuestaAPI::class . ':ListarEncuestasEntreFechas')
        ->add(\MDWEmpleado::class . ':ValidarSocio')
        ->add(\MDWEmpleado::class . ':ValidarToken');
});

$app->run();

?>