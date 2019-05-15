<?php
include_once 'classes\accesodatos.php';
class AlumnoDAO
{
    public $nombre;
    public $apellido;
    public $dni;
    public $legajo;
    public $photoid;

    public function __construct($inputLegajo, $inputNombre,$inputApellido,$inputDNI)
        {
            $this->legajo = $inputLegajo;
            $this->nombre = $inputNombre;
            $this->apellido = $inputApellido;
            $this->dni = $inputDNI;
        }


    public function MostrarDatos()
    {
            return $this->legajo." - ".$this->nombre." - ".$this->apellido." - ".$this->dni." - ".$this->photoid;
    }
    
    public static function TraerTodosLosAlumnos()
    {    
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM alumno");        
        
        $consulta->execute();
        
        $consulta->setFetchMode(PDO::FETCH_INTO, new AlumnoDAO);                                                

        return $consulta; 
    }
    
    public function InsertarAlumno()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        var_dump($this);
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO alumno (legajo, nombre,apellido,dni,photoid)"
                                                    . "VALUES(:legajo, :nombre, :apellido, :dni,:photoid)");
        
        $consulta->bindValue(':legajo', $this->legajo, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_INT);
        $consulta->bindValue(':photoid', $this->photoid, PDO::PARAM_STR);

        $salida = $consulta->execute();   
        echo "ID:".$salida;
    }
    
    public static function ModificarAlumno($legajo,$nombre, $apellido, $dni, $photoid)
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE alumno SET nombre = :nombre, apellido = :apellido, 
                                                        dni = :dni, photoid=:photoid WHERE legajo = :legajo");
        
        $consulta->bindValue(':legajo', $this->legajo, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_INT);
        $consulta->bindValue(':photoid', $this->photoid, PDO::PARAM_STR);

        return $consulta->execute();

    }

    public static function EliminarAlumno($al)
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM cds WHERE legajo = :legajo");
        
        $consulta->bindValue(':legajo', $al->legajo, PDO::PARAM_INT);

        return $consulta->execute();

    }
    
}