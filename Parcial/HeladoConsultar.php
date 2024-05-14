<?php 
include_once "HeladeriaAlta.php";
include_once "ArchivosJSON.php";

class Consulta
{
    private array $catalogo;
    private $saborConsultado;
    private $tipoConsultado;
    private $saborNoTipo = false;
    private $TipoNoSabor = false;
    private $saboryTipo = false;    

    public function __construct(string $sabor, string $tipo)
    {
        $this->catalogo= ArchivosJSON::leerJSON("./", "heladeria.json");
        $this->saborConsultado = $sabor;
        $this->$tipo = $tipo;
    }

    public function consulta()
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

$nuevaConsulta = new Consulta($_POST["sabor"], $_POST["tipo"]);
$nuevaConsulta->consulta();


?>