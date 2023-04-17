<?php 
    include("cabecera.php") ;
    include("conexion.php");
    include("validaSession.php");


    $meses=['','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $dia=0; // VARIABLE QUE PERMITE ESTABLECER SI EL CICLO HA CAMBIADO DE DIA EN LA TABLA INFERIOR "Actividades registradas en"

    //CONSULTA LAS ACTIVIDADES DEL AÑO Y MES SELECCIONADO PARA MOSTRARLAS EN LA TABLA DE LA PARTE INFERIOR
    $sql_actividades_horas="SELECT A.*, P.nombre_proyecto, day(fecha_actividad) dia 
    , ( select count(*) FROM `Actividades` A1 where A.id_usuario=A1.id_usuario and year(A1.fecha_actividad)=".$_GET["y"]." and month(A1.fecha_actividad)=".$_GET["m"]." and day(A1.fecha_actividad)=day(A.fecha_actividad) ) cant_registros_dia
    FROM `Actividades` A 
    inner JOIN Proyectos P on A.id_proyecto = P.id_proyecto 
    WHERE A.id_usuario=".$_SESSION["idUsuario"]." and year(A.fecha_actividad)=".$_GET["y"]."  and month(A.fecha_actividad)=".$_GET["m"]." order BY(fecha_actividad)";
    $valores_actividades_horas=mysqli_query($con, $sql_actividades_horas);       


    
    if(($_POST["proyecto"]!="") && ($_POST["fecha"]!="") && ($_POST["horas"]!="")&& ($_POST["actividad"]!="") )
    {   
        $sql_validaregistro="SELECT count(*) cant_actividades FROM `Actividades` where fecha_actividad='".$_POST["fecha"]."' and id_usuario='".$_SESSION["idUsuario"]."' and id_proyecto='".$_POST["proyecto"]."'";
        $datos_validaregistro = mysqli_query($con, $sql_validaregistro);
        $datos_validaregistro= mysqli_fetch_array($datos_validaregistro);
        if( ((int) $datos_validaregistro["cant_actividades"]) == 0)
        {               
            $sql_registro_actividad="INSERT INTO `Actividades` (`id_usuario`, `id_proyecto`, `fecha_actividad`, `descripcion_actividad`, `cantidad_horas_actividad`, 
            `fecha_registro_actividad`) VALUES ('".$_SESSION["idUsuario"]."', '".$_POST["proyecto"]."', '".$_POST["fecha"]."', '".$_POST["actividad"]."', '".$_POST["horas"]."', '". date("Y-m-d")."');";
            if(!mysqli_query($con, $sql_registro_actividad))
            {   

                echo "<span id='operacion_error'> Error durante la operaciòn. Por favor contacte al area de sistemas </span>"; 
            }
            else
            {
                echo "<span id='operacion_ok' >Operaciòn realizada con exito </span>";               
            }
            header("refresh:5;url=hojaActividades.php?ano=".$_GET["y"]."&mes=".$_GET["m"]);
            die(); 
        }
        else
        {
            
            echo "<span id='operacion_error'>Ya hay una actividad registrada en el proyecto, para la fecha seleccionada.<br>Por favor seleccione una fecha o proyecto diferente. </span>"; 
        }
    }
    else
    {
        if(isset($_POST["proyecto"]) && isset($_POST["fecha"]) && isset($_POST["horas"]) && isset($_POST["actividad"]) )
            echo "<span id='operacion_error' >Por favor diligenciar todos los campos solicitados </span>";
    }
?>

    <div id="index_form">
        <form action="#" method="POST" >
            <div>                
                <div class="tabla_datos_actividades_titulo">Registrar Actividad</div>
            </div>

            <div>
                <div id="contenedor_boton_regresar" ><a href="hojaActividades.php?ano=<?=$_GET["y"] ?>&mes=<?=$_GET["m"] ?>" > <img src="img/regresar.png" alt="Regresar" title="Regresar" class="icono_navegacion" ></a></div>
                <label class="index_form_label_campo_formulario" >Proyecto</label>
                

                <select name="proyecto" id="proyecto" required >
                    <option value="">Seleccione un proyecto</option>
                <?php               
                    $sql_proyectos="SELECT * FROM `Proyectos` WHERE estado_proyecto =1";
                    $valores=mysqli_query($con, $sql_proyectos);        
                    while($datos= mysqli_fetch_array($valores))
                    {   
                        $selected="";
                        if($_POST["proyecto"]==$datos["id_proyecto"])
                            $selected="selected";
    ?>                    
                        <option value="<?=$datos["id_proyecto"]; ?>" <?=$selected ?> ><?=$datos["nombre_proyecto"]; ?></option>
    <?php
                        
                    }    

    ?>
                </select>
                
            </div>
            <div>
                <label for="fecha" class="index_form_label_campo_formulario" >Fecha                  
                </label>
                <input type="date" id="fecha" name="fecha" required value="<?=$_POST["fecha"]; ?>" min="<?=$_GET["y"]."-".(($_GET["m"] < 10 )? "0".$_GET["m"] : $_GET["m"]  )."-01" ?>" max="<?=date("Y-m-t", strtotime($_GET["y"]."-".$_GET["m"]."-01")) ?>" >
            </div>
            <div>
                <label for="horas" class="index_form_label_campo_formulario" >Cantidad de horas</label>
                <input type="number" id="horas" name="horas" min="1" max="24" size="3" required value="<?=$_POST["horas"]; ?>" >
            </div>
            <div>
                <div>
                    <label for="actividad" class="index_form_label_campo_formulario" >Actividad</label>
                </div>           
                <textarea name="actividad" id="actividad" cols="40" rows="10" required ><?=$_POST["actividad"]; ?></textarea>
            </div>
            <div>
                <input type="submit" value="Registrar" id="registrar" name="registrar" class="botonSubmit" >
            </div>
        
        </form>
    </div>
    

    <footer>
    <div id="tabla_descripcion_actividades" >
        <table border="1"  >
            <tr>
                <td  class="tabla_datos_actividades_titulo"  colspan="4" >Actividades registradas en <?=$meses[( (int) $_GET["m"])]; ?> del <?=$_GET["y"] ?> </td>
            </tr>
            <tr>
                <td class="tabla_datos_actividades_titulo" >Dia</td>
                <td class="tabla_datos_actividades_titulo" >Proyecto</td>
                <td class="tabla_datos_actividades_titulo" >Horas</td>
                <td class="tabla_datos_actividades_titulo" >Actividad</td>                
            </tr>
            <?php                
                while($datos_actividades_horas= mysqli_fetch_array($valores_actividades_horas))
                {
            ?>
                    <tr>
                        <?php
                            // SI CAMBIA DE DIA, SE REINICIA LA VARIABLE dia
                            if(($dia!=$datos_actividades_horas["dia"]) )                            
                                $dia=0;                                 

                            //SI LA CANTIDAD DE REGISTROS DE ACTIVIDADES ASOCIADOS AL DIA, SON MAS DE UNO Y ES EL PRIMER REGISTRO DEL DIA 
                            //SE FORMA LA PRIMERA CELDA DE LA FILA, CON LA CANTIDAD DE REGISTROS DE ACTIVIDADES, ASOCIADOS AL DIA EN EL rowspan
                            if( (( (int) $datos_actividades_horas["cant_registros_dia"] )>1) && ($dia==0) )
                            {
                                $dia=$datos_actividades_horas["dia"]; 
                                
                        ?>
                            <td rowspan="<?=$datos_actividades_horas["cant_registros_dia"] ?>" class="tabla_datos_td_dias_mes" ><?=$datos_actividades_horas["dia"] ?></td>
                        <?php
                            }                            

                            //SI SOLO HAY UN REGISTRO DE ACTIVIDADES ASOCIADO AL DIA
                            if((( (int) $datos_actividades_horas["cant_registros_dia"] )==1) && ($dia==0)  )
                            {
                            ?>
                                <td class="tabla_datos_td_dias_mes" ><?=$datos_actividades_horas["dia"] ?></td>
                        <?php
                            
                            }                                           
                        ?>                           

                        <td class="tabla_datos_actividades_proyectos" ><?=$datos_actividades_horas["nombre_proyecto"] ?></td>
                        <td class="tabla_datos_actividades_td_total_horas"   ><?=$datos_actividades_horas["cantidad_horas_actividad"] ?></td>
                        <td class="tabla_descripcion_actividades_actividad" ><?=$datos_actividades_horas["descripcion_actividad"] ?></td>                        
                    </tr>
            <?php 
                }
            ?>
            
        </table>
    </div>
</footer>

<?php 
    include("piePagina.php");
    include("cerrarConexion.php");
 ?>