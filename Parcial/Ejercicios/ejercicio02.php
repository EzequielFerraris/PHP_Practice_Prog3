<?php
    /*
    Obtenga la fecha actual del servidor (función date) y luego imprímala dentro de la página con
    distintos formatos (seleccione los formatos que más le guste). Además indicar que estación del
    año es. Utilizar una estructura selectiva múltiple.
    */ 

    date_default_timezone_set("America/Argentina/Buenos_Aires");


    $fecha = array(0 => date("d m Y"), 1 => date("D m Y"), 3 => date("d/m/Y"));
    $estacion = "";

    switch (date("m")) 
    {
        case 1:
        case 2: 
            $estacion = "Verano";
        break;
        case 3:
            if (date("d") >= 20)
                {$estacion = "Otoño";}
            else
                {$estacion = "Verano";}
        break;
        case 4:
        case 5:
            $estacion = "Otoño";
        break;
        case 6:
            if (date("d") >= 20)
                {$estacion = "Invierno";}
        break;
        case 7:
        case 8:
            $estacion = "Invierno";
        break;
        case 9:
            if (date("d") >= 21)
                {$estacion = "Primavera";}
            else
                {$estacion = "Invierno";}
        case 10:
        case 11:
            $estacion = "Primavera";
        break;
        case 12:
            if (date("d") >= 21)
                {$estacion = "Verano";}
            else
                {$estacion = "Primavera";}
        break;
    }

    foreach($fecha as $v) 
    {
        echo $v;
        echo "<br/>";
        echo "Estación: " . $estacion;
        echo "<br/>";
    }
    
    
?>