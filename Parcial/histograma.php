<?php

class Histograma
{
    public static function generarHistograma(array $arr, string $key)
    {
        $histograma = array();

        foreach($arr as $item)
        {
            if(array_key_exists($item[$key], $histograma))
            {
                $histograma[$key] += 1;
            }
            else
            {
                $histograma[$key] = 0;
            }
        }
    }

    

    public static function filtrarArrayPorValor(array $arr, string $key, $value) : bool
    {
        $resultado = false;

        foreach($arr as $e)
        {
            if($e[$key] == $value)
            {
                $resultado = true;
            }
        }

        return $resultado;
    }

    public static function filtrarEntreFechas(array $valor, string $fecha_inicio, string $fecha_fin) : bool
    {
        $resultado = false;
        $i = strtotime($fecha_inicio);
        $f = strtotime($fecha_fin);
        $fechaConsiderada = strtotime($valor["fecha"]);
        if($fechaConsiderada >= $i && $fechaConsiderada <= $f)
        {
            $resultado = true;
        }

        return $resultado;
    }

    public static function chequearFecha(array $a, string $fecha)
    {
        $resultado = false;
        $i = strtotime($a["fecha"]);
        $f = strtotime($fecha);
        if($i == $f)
        {
            $resultado = true;
        }

        return $resultado;
    }

    public static function compararNombres($a, $b) 
    {
        return strcasecmp($a["nombre"], $b["nombre"]);
    }

    public static function compararPrecios($a, $b) 
    {
        return strcasecmp($a["precio"], $b["precio"]);
    }
}


?>