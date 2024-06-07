<?php 
include_once "HeladeriaAlta.php";
include_once "ArchivosJSON.php";

class ConsultaHelado
{
    private array $catalogo;
    private string $saborConsultado;
    private string $tipoConsultado;
    private bool $_valid = false;
    private bool $saborNoTipo = false;
    private bool $TipoNoSabor = false;
    private bool $saboryTipo = false; 

    public function __construct(string $sabor, string $tipo)
    {
        $this->catalogo= ArchivosJSON::leerJSON(Heladeria::$ruta, Heladeria::$nombreArchivo);
        if(Validador::es_string($sabor) && Validador::es_string($tipo))
        {
            $this->_valid = true;
        }
        
        $this->saborConsultado = $sabor;
        $this->tipoConsultado = $tipo;
    }

    public function consulta()
    {
        
        if(!$this->_valid)
        {
            echo "La consulta no es válida. Alguno de los parámetros es inválido.";
        }
        else if (count($this->catalogo) < 1)
        {
            echo "Actualmente no hay catálogo para consultar.";
        }
        else
        {
            foreach($this->catalogo as $val => $p1)
            {
                if($this->saborConsultado == $p1["sabor"] & $this->tipoConsultado == $p1["tipo"])
                {
                    $this->saboryTipo = true;
                    break;
                }
                else if($this->saborConsultado == $p1["sabor"])
                {
                    $this->saborNoTipo = true;
                }
                else if ($this->tipoConsultado == $p1["tipo"])
                {
                    $this->TipoNoSabor = true;   
                }
            }

            if($this->saboryTipo)
            {
                echo "Existe el sabor y el tipo en un mismo producto";
            }
            else if(!$this->saborNoTipo && !$this->TipoNoSabor)
            {
                echo "No existe el sabor ni el tipo.";
            }
            else if (!$this->TipoNoSabor)
            {
                echo "No existe el tipo";
            }   
            else if (!$this->saborNoTipo)
            {
                echo "No existe el sabor";
            }
        }
        
    }

    public static function consultaCantidad(string $sabor, string $tipo) : int
    {
        $lista =  ArchivosJSON::leerJSON(Heladeria::$ruta, Heladeria::$nombreArchivo);
        $cantidad = 0;

        foreach($lista as $key => $val)
        {
            if($val["sabor"] == $sabor && $val["tipo"] == $tipo)
            {
                $cantidad = $val["stock"];
                break;
            }
        }

        return $cantidad;

    }
}

?>