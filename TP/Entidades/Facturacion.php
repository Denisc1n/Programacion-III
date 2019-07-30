<?php
include_once("AccesoDatos.php");
include_once("Facturacion/FPDF/fpdf.php");
include_once("Facturacion/PHPExcel/Classes/PHPExcel.php");

class Facturacion
{
    public $id;
    public $importe;
    public $codigoMesa;
    public $fecha;

    public static function Generar($importe, $codigoMesa)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $respuesta = "";
        try {
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $fecha = date('Y-m-d H:i:s');
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO factura (importe, codigoMesa, fecha) 
                                                            VALUES (:importe, :codigoMesa, :fecha);");

            $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
            $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
            $consulta->bindValue(':importe', $importe, PDO::PARAM_INT);

            $consulta->execute();

            $respuesta = array("Estado" => "OK", "Mensaje" => "Factura generada correctamente.");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    public static function ListarTodos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $respuesta = "";
        try {
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM factura;");

            $consulta->execute();

            $respuesta = $consulta->fetchAll(PDO::FETCH_CLASS, "Facturacion");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    public static function ListarEntreFechas($fecha1, $fecha2)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $respuesta = "";
        try {
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM factura WHERE fecha BETWEEN :fecha1 AND :fecha2;");
            $consulta->bindValue(':fecha1', $fecha1, PDO::PARAM_STR);
            $consulta->bindValue(':fecha2', $fecha2, PDO::PARAM_STR);
            $consulta->execute();

            $respuesta = $consulta->fetchAll(PDO::FETCH_CLASS, "Facturacion");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $respuesta;
        }
    }

    public static function PDF()
    {
        $pdf = new FPDF("P", "mm", "A4");
        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 12);
        //ancho de la celda, alto de la celda, contenido de la celda, borde (1 o 0), salto de linea (1 o 0), alineacion (L C R)
        $pdf->Cell(150, 10, 'La comanda', 1, 1, "C");
        $pdf->Cell(50, 10, 'Fecha', 1, 0, "C");
        $pdf->Cell(50, 10, 'Codigo mesa', 1, 0, "C");
        $pdf->Cell(50, 10, 'Importe', 1, 1, "C");

        $facturas = Facturacion::ListarTodos();
        foreach ($facturas as $factura) {
            $pdf->Cell(50, 10, $factura->fecha, 1, 0, "C");
            $pdf->Cell(50, 10, $factura->codigoMesa, 1, 0, "C");
            $pdf->Cell(50, 10, "$" . $factura->importe, 1, 1, "C");
        }

        $pdf->Output("F", "./Facturacion/Facturacion.pdf", true);
        return array("Estado" => "OK", "Mensaje" => "PDF generado.");
    }

    public static function Excel()
    {
        $excel = new PHPExcel();
        $excel->getProperties()
            ->setCreator("La comanda")
            ->setTitle("Facturacion");
            //->setDescription("Listado de Ventas");

        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()
            ->getColumnDimension('A')
            ->setAutoSize(true);

        $excel->getActiveSheet()
            ->getColumnDimension('B')
            ->setAutoSize(true);

        $excel->getActiveSheet()
            ->getColumnDimension('C')
            ->setAutoSize(true);

        $excel->getActiveSheet()->setTitle("Facturacion");

        $excel->getActiveSheet()->setCellValue("A1", "Fecha");
        $excel->getActiveSheet()->setCellValue("B1", "Codigo Mesa");
        $excel->getActiveSheet()->setCellValue("C1", "Importe");

        $facturas = Facturacion::ListarTodos();
        $fila = 2;
        foreach ($facturas as $factura) {
            $excel->getActiveSheet()->setCellValue("A$fila", $factura->fecha);
            $excel->getActiveSheet()->setCellValue("B$fila", $factura->codigoMesa);
            $excel->getActiveSheet()->setCellValue("C$fila", $factura->importe);
            $fila++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename-"./Facturacion/Facturacion.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, "Excel2007");
        $writer->save("./Facturacion/Facturacion.xlsx");
        return array("Estado" => "OK", "Mensaje" => "Excel generado.");
    }
}

?>