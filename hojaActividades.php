<?php 
    
    include("cabecera.php") ; /* INCLUIMOS EL ARCHIVO QUE CONTIENE EL HEAD DE LA PAGINA HTML */
    include("conexion.php"); /* INCLUIMOS EL ARCHIVO DE CONEXION A LA BD */
    include("validaSession.php"); /* ARCHIVO QUE VALIDA SI LA SESSION SIGUE ACTIVA */
    
    $meses=['','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $mes_consultar=date("n"); //mes actual
    $ano_actual=date("Y"); //año actual    
    $anio[0]=$ano_actual;
    $total_horas_registradas_dia; //ARRAY QUE SUMA LA CANTIDAD DE HORAS REGISTRADAS POR DIA Y QUE SE MUESTRAN EN LA FILA "TOTAL HORAS"
    $i=1;

    //se forma la fecha, para extraher la cantidad de dias del mes
    if( ($_POST["ano"]!="") && ($_POST["mes"]!="")) /* SE CAPTURA EL MES Y AÑO DE LA UNA HOJA DE ACTIVIDADES A CONSULTAR */
    {
        $fecha_consultar=$_POST["ano"]."-".$_POST["mes"]."-01";
        $mes_consultar=$_POST["mes"];
    }
    else   // SI SE ESTA CARGANDO LA PAGINA POR PRIMERA VEZ, SE CARGA EL MES Y AÑO ACTUAL
    {
        $_POST["ano"]=$ano_actual;
        $_POST["mes"]=$mes_consultar;
        $fecha_consultar=$ano_actual."-".$mes_consultar."-01";
    }

    //SE CONSULTA LA CANTIDAD DE DIAS DEL MES 
    $cantidad_dias_mes_consultar=date( 't', strtotime( $fecha_consultar ) );    

    //CONSULTA LOS AÑOS ASOCIADOS A LAS ACTIVIDADES REGISTRADAS POR EL USUARIO
    $sql="SELECT DISTINCT(EXTRACT(YEAR FROM fecha_actividad)) anio FROM `Actividades` WHERE id_usuario=".$_SESSION["idUsuario"]." and (YEAR FROM fecha_actividad)) != ".$anio[0];
    $valores=mysqli_query($con, $sql);        
    while($datos= mysqli_fetch_array($valores))
    {   
        $anio[$i]=$datos["anio"];
        $i++;
    }  
    
    //SE CONSULTAN LoS PROYECTOS ASOCIADOS A LAS ACTIVIDADES REGISTRADAS POR EL USUARIO
    $sql_proyectos_usuario="SELECT DISTINCT(A.id_proyecto) id_proyecto, P.nombre_proyecto  FROM Actividades A 
    inner JOIN Proyectos P on A.id_proyecto = P.id_proyecto
    WHERE A.id_usuario=".$_SESSION["idUsuario"]." and year(A.fecha_actividad)=".$_POST["ano"]." 
    and month(A.fecha_actividad)=".$_POST["mes"]."";

    $valores_proyectos_usuario=mysqli_query($con, $sql_proyectos_usuario);        
    echo $sql_proyectos_usuario;
    

?>

<header>
    <div id="logo_contenedor">
        <img src="img/logo.png" alt="Logo" id="logo" >
    </div>

    <div id="herramientas_usuario" >
        <div> <img src="img/usu.png" alt="Imagen usuario" id="herramientas_usuario_imagenUsuario" > <span class="herramientas_usuario_texto" ><?php echo $_SESSION["nombreUsuario"] ?></span> </div>
        <div><a href="cerrarSession.php"> <img src="img/cerrar.png" alt="Cerrar sesiòn" class="icono_navegacion" title="Cerrar sesiòn" > <span class="herramientas_usuario_texto" > Cerrar sesiòn</a></span></div>
    </div>
</header>

<main>
    
        <div id="filtros_mes_anio">
            <form action="#" method="post" >
                <span>Mes</span> 
                <select name="mes" id="mes">
                    <?php
                        //SE IMPRIMEN LOS MESES 
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
                        //SE IMPRIMEN LOS AÑOS
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
                        <td rowspan="2" class="tabla_datos_actividades_titulo" >Proyectos</td>
                        <td colspan="<?=$cantidad_dias_mes_consultar+2 ?>" class="tabla_datos_actividades_titulo" >Dias / horas</td>                        
                    </tr>
                    <tr>
                        <?php
                            
                            $total_horas_registradas_dia= array($cantidad_dias_mes_consultar); //CREA EL ARRAY CON EL TAMAÑO QUE TENGA LOS DIAS DEL MES
                            for($i=1; $i<=$cantidad_dias_mes_consultar;$i++)
                            {
                                //CARGA EL ARRAY QUE SUMA LA CANTIDAD DE HORAS REGISTRADAS POR DIA Y QUE SE MUESTRAN EN LA FILA "TOTAL HORAS", CON VALOR 0
                                $total_horas_registradas_dia[$i]=0; //CARGA EL ARRAY QUE TOTALIZA LA CANTIDAD DE HORAS REGISTRADAS POR MES

                                //MUESTRA LOS DIAS DEL MES
                        ?>
                                <td class="tabla_datos_td_dias_mes"><?=$i ?></td>
                        <?php
                            }
                        ?>
                        <td></td>
                        <td></td>
                    </tr>
                    
                        <?php
                            //SE RECORRE LOS PORYECTOS ADOCIADOS A LAS ACTIVIDADES REGISTRADAS POR EL USUARIO
                            while($datos_proyectos_usuario= mysqli_fetch_array($valores_proyectos_usuario))
                            {   
                                //CONSULTA LAS ACTIVIDADES REGISTRADAS POR EL USUARIO, ASOCIADAS AL PROYECTO
                                $sql_actividades_usuario="SELECT A.*, P.nombre_proyecto, day(fecha_actividad) dia FROM `Actividades` A
                                inner JOIN Proyectos P on A.id_proyecto = P.id_proyecto WHERE A.id_usuario=".$_SESSION["idUsuario"]." and year(A.fecha_actividad)=".$_POST["ano"]." 
                                and month(A.fecha_actividad)=".$_POST["mes"]." AND A.id_proyecto=".$datos_proyectos_usuario["id_proyecto"]." order BY(fecha_actividad)";
//echo $sql_actividades_usuario."<br>";
                                $valores_actividades_usuario=mysqli_query($con, $sql_actividades_usuario);        

                                ?>
                                <tr>
                                <td class="tabla_datos_actividades_proyectos"  ><?php echo $datos_proyectos_usuario["nombre_proyecto"]; ?></td>

                                <?php   
                                        //ALMACEN EN datos_actividades LA INFORMACION DE LA ACTIVIDAD REGISTRADA
                                        $datos_actividades=mysqli_fetch_array($valores_actividades_usuario);

                                        //RECORRE LOS DIAS DEL MES
                                        for($i=1; $i<=$cantidad_dias_mes_consultar;$i++)
                                        {   
                                            //SI EL DIA FACTURADO CONCUERDA CON EL QUE SE ESTA RECORRIENDO 
                                            if($i == ( (int) $datos_actividades["dia"]))
                                            {
                                                $total_horas_registradas_dia[$i]+=( (int) $datos_actividades["cantidad_horas_actividad"]); //REALIZA LA SUMATORIA DE LAS HORAS REGISTRADAS X DIA

                                                //IMPRIME LA CANTIDAD DE HORAS REGISTRADAS
                                                echo "<td class='tabla_datos_td_dia_horas'> <span title='Actividad: \n". $datos_actividades["descripcion_actividad"]."' >". $datos_actividades["cantidad_horas_actividad"]."</span></td>";                                            

                                                //ALMACEN EN datos_actividades LA INFORMACION DE LA ACTIVIDAD REGISTRADA
                                                $datos_actividades=mysqli_fetch_array($valores_actividades_usuario);
                                            }
                                            else
                                            {
                                                echo "<td></td>";
                                            }
                                            
                                        }        
                                    ?>  
                                        <td> <a href=""><img src="img/editar.png" alt="Editar" title="Editar Actividades"  class="icono_navegacion"> </a></td>
                                        <td> <a href=""><img src="img/eliminar.png" alt="Eliminar" title="Eliminar Actividades"  class="icono_navegacion"> </a> </td>
                                    
                                <tr>
                                <?php
                            } 
                        ?>
                        
                    
                    <tr>
                        <td  class="tabla_datos_actividades_titulo">Total horas</td>
                        <?php
                            //IMPRIME LA SUMATORIA DE LA CANTIDAD DE HORAS REGISTRADAS POR DIA
                            for($i=1; $i<=$cantidad_dias_mes_consultar;$i++)
                            {  
                        ?>
                                <td class="tabla_datos_actividades_td_total_horas" >
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