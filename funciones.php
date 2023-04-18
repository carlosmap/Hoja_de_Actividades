<?php        
    //RRAY QUE CONTIENE LA INICIAL DE LOS DIAS DE LA SEMANA, CON EL FIN DE MOSTRARLOS DEBAJO DEL DIA DEL MES CONSULTADO
    $dias_semana=  array("D","L","M","Mi","J","V","S");

    //FUNCION QUE RETORNA EL DIA DE LA SEMANA (0=DOMINGO, 1=LUNES, 2=MARTES, ... , 6=SABADO)
    //UTILIZADA PARA PINTAR LOS DIAS QUE SON SABADO Y DOMINGO EN LOS DIAS Y ACTIVIDADES DEL MES
    //css_dia =RETONA LA CLASE CCS A UTILIZAR EN EL DIA CONSULTADO 
    //dia_semana = RETONA EL DIA DE LA SEMANA, PARA DIBUJAR DEBAJO DEL DIA, EL DIA DE LA SEMANA CORRESPONDIENTE (LUNES, MARTES....)
    function dia_semana($mes, $dia, $ano)
    {
        ///CONSULTAMOS EL DIA DE LA SEMANA, PARA PINTAR LOS SABADOS Y DOMINGOS
        $dia_semana= date('w', mktime (0, 0, 0, $mes, $dia, $ano));
        if( ($dia_semana !=6) && ($dia_semana !=0) ) //ENTRE SEMANA
            $css_dia="tabla_datos_td_dias_mes";
        else //FIN DE SEMANA
            $css_dia="tabla_datos_td_dias_mes_feriados";

        return array($css_dia, $dia_semana) ;
    }

?>            