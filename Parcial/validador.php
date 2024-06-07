
<?php

abstract class Validador
{

    public function __construct(){}

    public static function es_entero_positivo(int $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_INT) === 0 || !filter_var($par, FILTER_VALIDATE_INT) === false)
        {
            if($par >= 0)
            {
                $result = true;
            }
        }

        return $result;
    }

    public static function es_entero(int $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_INT) === 0 || !filter_var($par, FILTER_VALIDATE_INT) === false)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_float(float $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_FLOAT) === 0 || !filter_var($par, FILTER_VALIDATE_FLOAT) === false)
        {
            $result = true;
        }

        return $result;
    }
    public static function es_mail_valido(string $par) : bool
    {
        $result = false;

        if(!filter_var($par, FILTER_VALIDATE_EMAIL) == false)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_string(string $par) : bool
    {
        $result = false;

        if(is_string($par))
        {
            $result = true;
        }

        return $result;
    }

    public static function es_date(DateTime $par) : bool
    {
        $result = false;

        if($par instanceof DateTime)
        {
            $result = true;
        }
        
        return $result;
    }

    

}


?>