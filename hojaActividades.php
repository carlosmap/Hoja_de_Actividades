<?php 
    include("cabecera.php") ;
    include("conexion.php");
    include("validaSession.php");
    
    $meses=['','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $mes_consultar=date("n"); //mes actual
    $ano_actual=date("Y"); //año actual    
    $anio[0]=$ano_actual;
    $total_horas_registradas_dia;
    $i=0;

    //se forma la fecha, para extraher la cantidad de dias del mes
    if( ($_POST["ano"]!="") && ($_POST["mes"]!=""))
    {
        $fecha_consultar=$_POST["ano"]."-".$_POST["mes"]."-01";
        $mes_consultar=$_POST["mes"];
    }
    else    
    {
        $_POST["ano"]=$ano_actual;
        $_POST["mes"]=$mes_consultar;
        $fecha_consultar=$ano_actual."-".$mes_consultar."-01";
    }
    $cantidad_dias_mes_consultar=date( 't', strtotime( $fecha_consultar ) );    


    $sql="SELECT DISTINCT(EXTRACT(YEAR FROM fecha_actividad)) anio FROM `Actividades` WHERE id_usuario=".$_SESSION["idUsuario"];
    $valores=mysqli_query($con, $sql);        
    while($datos= mysqli_fetch_array($valores))
    {   
        $anio[$i]=$datos["anio"];
        $i++;
    }  
    
    


    $sql_proyectos_usuario="SELECT A.*, P.nombre_proyecto, day(fecha_actividad) dia FROM `Actividades` A
    inner JOIN Proyectos P on A.id_proyecto = P.id_proyecto WHERE A.id_usuario=".$_SESSION["idUsuario"]." and year(A.fecha_actividad)=".$_POST["ano"]." 
    and month(A.fecha_actividad)=".$_POST["mes"]."  order BY(fecha_actividad)";
    $valores_proyectos_usuario=mysqli_query($con, $sql_proyectos_usuario);        
    //echo $sql_proyectos_usuario;
    

?>

<header>
    <div id="logo_contenedor">
        <img src="img/logo.png" alt="Logo" id="logo" >
    </div>

    <div id="herramientas_usuario" >
        <div> <img src="img/usu.png" alt="Imagen usuario" id="herramientas_usuario_imagenUsuario" > <span class="herramientas_usuario_texto" ><?php echo $_SESSION["nombreUsuario"] ?></span> </div>
        <div><a href="cerrarSession.php"> <img src="img/cerrar.png" alt="Cerrar sesiòn" id="herramientas_usuario_boton_cerrar" > <span class="herramientas_usuario_texto" > Cerrar sesiòn</a></span></div>
    </div>
</header>

<main>
    
        <div id="filtros_mes_anio">
            <form action="#" method="post" >
                <span>Mes</span> 
                <select name="mes" id="mes">
                    <?php
                        for ($i=1; $i<count($meses); $i++ )
                        {
                            $selected="";
                            if($i==$mes_consultar)
                                $selected="selected";                        
                    ?>
                            <option value="<?=$i; ?>" <?=$selected ?>  ><?=$meses[$i]; ?></option>
                    <?php
                        }
                    ?>
                </select>

                <span>Año</span>
                <select name="ano" id="ano">
                <?php
                        for ($i=0; $i<count($anio); $i++ )
                        {
                            $selected="";
                            if($anio[$i]==$ano_actual)
                                $selected="selected";                        
                    ?>                
                            <option value="<?=$anio[$i]; ?>" <?=$selected ?>  ><?=$anio[$i]; ?></option>
                    <?php
                        }
                    ?>             
                </select>

                <button class="boton"  >Consultar</button>
            </form>
        </div>    

        <div id="tabla_datos_actividades" >
            <table border="1"  >
                <tbody>
                    <tr>
                        <td rowspan="2">Proyectos</td>
                        <td colspan="<?=$cantidad_dias_mes_consultar+2 ?>">Dias / horas</td>                        
                    </tr>
                    <tr>
                        <?php
                            $total_horas_registradas_dia= array($cantidad_dias_mes_consultar);
                            for($i=1; $i<=$cantidad_dias_mes_consultar;$i++)
                            {
                                $total_horas_registradas_dia[$i]=0; //CARGA EL ARRAY QUE TOTALIZA LA CANTIDAD DE HORAS REGISTRADAS POR MES
                        ?>
                                <td class="tabla_datos_td_dias_mes"><?=$i ?></td>
                        <?php
                            }
                        ?>
                        <td></td>
                        <td></td>
                    </tr>
                    
                        <?php
                            while($datos_proyectos_usuario= mysqli_fetch_array($valores_proyectos_usuario))
                            {   
                                ?>
                                <tr>
                                <td><?php echo $datos_proyectos_usuario["nombre_proyecto"]; ?></td>

                                <?php
                                    for($i=1; $i<=$cantidad_dias_mes_consultar;$i++)
                                    {
                                
                                        
                                        if($i == ( (int) $datos_proyectos_usuario["dia"]))
                                        {
                                            $total_horas_registradas_dia[$i]+=( (int) $datos_proyectos_usuario["cantidad_horas_actividad"]); //REALIZA LA SUMATORIA DE LAS HORAS REGISTRADAS X DIA

                                            echo "<td class='tabla_datos_td_dia_horas'>". $datos_proyectos_usuario["cantidad_horas_actividad"]."</td>";                                            
                                        }
                                        else
                                        {
                                            echo "<td></td>";
                                        }
                                        
                                        ?></td>
                                <?php
                                    }
                                ?>
                                    <td></td>
                                    <td></td>
                                <tr>
                                <?php
                            } 
                        ?>
                        
                    
                    <tr>
                        <td>Total horas</td>
                        <?php
                            for($i=1; $i<=$cantidad_dias_mes_consultar;$i++)
                            {  
                        ?>
                                <td>
                        <?php
                                if($total_horas_registradas_dia[$i]!=0)                                            
                                    echo $total_horas_registradas_dia[$i];
                        ?>
                                </td>
                        <?php
                            }
                        ?>                        
                    </tr>
                </tbody>
            </table>           

        </div>

        <div id="index_form" >
            <button class="boton" onclick="location.href='registroActividad.php'" >Registrar Actividad</button>
        </div>
</main>


<?php 
    include("piePagina.php");
    include("cerrarConexion.php");
 ?>