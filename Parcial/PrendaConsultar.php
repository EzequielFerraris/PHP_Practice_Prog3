<?php 
include_once "TiendaAlta.php";
include_once "ArchivosJSON.php";

class ConsultasPrenda
{
    private array $catalogo;
    private string $_prendaConsultada;
    private string $_tipoConsultado;
    private string $_colorConsultado;
    private bool $_valid = false;
    private bool $NoExisteNombre = true;
    private bool $NoExisteTipo = true;
    private bool $nombreYtipoExisten = false; 

    public function __construct(string $nombre, string $tipo, string $color)
    {
        $this->catalogo= ArchivosJSON::leerJSON(TiendaAlta::$ruta, TiendaAlta::$nombreArchivo);
        if(Validador::es_string($nombre) && Validador::es_string($tipo))
        {
            $this->_valid = true;
        }
        
        $this->_prendaConsultada = $nombre;
        $this->_tipoConsultado = $tipo;
        $this->_colorConsultado = $color;
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
                if($this->_prendaConsultada == $p1["nombre"] && $this->_tipoConsultado == $p1["tipo"])
                {
                    $this->nombreYtipoExisten = true;
                    break;
                }
                else if($this->_prendaConsultada == $p1["nombre"])
                {
                    $this->NoExisteNombre = false;
                }
                else if ($this->_tipoConsultado == $p1["tipo"])
                {
                    $this->NoExisteTipo = false;   
                }
            }

            if($this->nombreYtipoExisten)
            {
                echo "Existe el nombre y el tipo en un mismo producto";
            }
            else if($this->NoExisteNombre && $this->NoExisteTipo)
            {
                echo "No existe el nombre ni el tipo.";
            }
            else if ($this->NoExisteNombre)
            {
                echo "No existe el nombre";
            }   
            else if (!$this->NoExisteTipo)
            {
                echo "No existe el tipo";
            }
        }
        
    }

    public static function consultaCantidad(string $sabor, string $tipo) : int
    {
        $lista =  ArchivosJSON::leerJSON(TiendaAlta::$ruta, TiendaAlta::$nombreArchivo);
        $cantidad = 0;

        foreach($lista as $key => $val)
        {
            if($val["nombre"] == $sabor && $val["tipo"] == $tipo)
            {
                $cantidad = $val["stock"];
                break;
            }
        }

        return $cantidad;

    }
}

?>