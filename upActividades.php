<?php 
    include("cabecera.php") ;
    include("conexion.php");
    include("validaSession.php");

    $meses=['','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];


    //CONSULTA LAS ACTIVIDADES DEL AÑO Y MES SELECCIONADO PARA MOSTRARLAS EN LA TABLA DE LA PARTE INFERIOR
    $sql_actividades_horas="SELECT A.*, P.nombre_proyecto, day(fecha_actividad) dia 
    , ( select count(*) FROM `Actividades` A1 where A.id_usuario=A1.id_usuario and year(A1.fecha_actividad)=".$_GET["y"]." and month(A1.fecha_actividad)=".$_GET["m"]." and day(A1.fecha_actividad)=day(A.fecha_actividad) ) cant_registros_dia
    FROM `Actividades` A 
    inner JOIN Proyectos P on A.id_proyecto = P.id_proyecto 
    WHERE A.id_usuario=".$_SESSION["idUsuario"]." and year(A.fecha_actividad)=".$_GET["y"]."  and month(A.fecha_actividad)=".$_GET["m"]." and P.id_proyecto=".$_GET["p"]." order BY(fecha_actividad)";
    $valores_actividades_horas = $valores_actividades_horas2= mysqli_query($con, $sql_actividades_horas);  
    //$valores_actividades_horas2=&$valores_actividades_horas;

    //SI SE HA PULSADO EL BOTON DE ELIMINAR O ACTUALIZAR
    if(($_POST["ban"]==1))
    {
        $error_editando_eliminando="0"; //VARIABLE QUE NOS PERMITE SABER SI SE HA PRESENTADO UN ERROR AL EDITAR O ELIMINAR ALGUNA ACTIVIDAD

        mysqli_query($con,"BEGIN TRANSACTION");

        //EDITAR ACTIVIDADES
        if(($_GET["a"]=="1") && ($_POST["ban"]==1))
        {
            while($datos_actividades_horas= mysqli_fetch_array($valores_actividades_horas))
            {
                
                    $sql_update_actividades_horas="UPDATE Actividades SET descripcion_actividad='".$_POST["actividad".$datos_actividades_horas["dia"] ]."' WHERE id_proyecto=".$_GET["p"]." AND id_usuario=".$_SESSION["idUsuario"]." and year(fecha_actividad)=".$_GET["y"]."  and month(fecha_actividad)=".$_GET["m"] ." and day(fecha_actividad)=".$datos_actividades_horas["dia"];
                    $update_actividades_horas=mysqli_query($con, $sql_update_actividades_horas);   
                    
                    if(trim(mysqli_error($con))!="")
                    {   
                        $error_editando_eliminando="1";                                                
                        break;
                    }
    //              echo $sql_update_actividades_horas." -- <br>" .mysqli_error($con)."<br><br>";
            }
        }

        //ELIMINAR ACTIVIDADES
        if(($_GET["a"]=="2")  && ($_POST["ban"]==1))
        {   
            while($datos_actividades_horas= mysqli_fetch_array($valores_actividades_horas))
            {
                //echo $_POST[$datos_actividades_horas["dia"]]."---".$datos_actividades_horas["dia"]." <BR>";

                if($_POST[$datos_actividades_horas["dia"]]!="")
                {
                    $sql_delete_actividades_horas="DELETE FROM Actividades WHERE id_proyecto=".$_GET["p"]." AND id_usuario=".$_SESSION["idUsuario"]." and year(fecha_actividad)=".$_GET["y"]."  and month(fecha_actividad)='".$_GET["m"] ."' and day(fecha_actividad)=".$datos_actividades_horas["dia"];
                    $del_actividades_horas=mysqli_query($con, $sql_delete_actividades_horas);   

//echo $sql_delete_actividades_horas."<br>".mysqli_error($con);
                    if(trim(mysqli_error($con))!="")
                    {   
                        $error_editando_eliminando="1";                                                
                        break;
                    }
                }
            }
            //   if($datos_actividades_horas["dia"])
        }

        //SI SE HA PRESENTADO UN ERROR AL ELIMINAR O EDITAR LAS ACTIVIDADES
        if($error_editando_eliminando==1)
        {
            echo "<span id='operacion_error'> Error durante la operaciòn. Por favor contacte al area de sistemas </span>"; 
            mysqli_query($con,"ROLLBACK TRANSACTION");
        }
        else
        {
            echo "<span id='operacion_ok' >Operaciòn realizada con exito </span>";  
            mysqli_query($con,"COMMIT TRANSACTION");
        }

        

        header("refresh:5;url=hojaActividades.php?ano=".$_GET["y"]."&mes=".$_GET["m"]);
        die(); 
    }

    //SI SE VAN A ELIMINAR ACTIVIDADES
    if($_GET["a"]=="2")
        $disabled="disabled";


?>
    <div id="index_form">
        <form action="#" method="POST" >
            <div>                
                <div class="tabla_datos_actividades_titulo"><?=($_GET["a"]=="1") ? "Editar" : "Eliminar" ?> Actividades</div>
            </div>

            <div>
                <div id="contenedor_boton_regresar" ><a href="hojaActividades.php?ano=<?=$_GET["y"] ?>&mes=<?=$_GET["m"] ?>" > <img src="img/regresar.png" alt="Regresar" title="Regresar" class="icono_navegacion" ></a></div>
                <label class="index_form_label_campo_formulario" >Proyecto</label>
                
                <?php               
                    $sql_proyectos="SELECT * FROM `Proyectos` WHERE id_proyecto =".$_GET["p"];
                    $valores=mysqli_query($con, $sql_proyectos);        
                    while($datos= mysqli_fetch_array($valores))
                    {   
    ?>                    
                        <div  class="tabla_datos_actividades_td_total_horas" ><?=$datos["nombre_proyecto"]  ?></div>
    <?php
                        
                    }    

    ?>
                
            </div>
            <div>
                <label for="fecha" class="index_form_label_campo_formulario" >Mes                  
                </label>
                <div  class="tabla_datos_actividades_td_total_horas" ><?=$meses[( (int) $_GET["m"]) ] ?></div>
            </div>
            <div>
                <label for="horas" class="index_form_label_campo_formulario" >Año</label>
                <div  class="tabla_datos_actividades_td_total_horas" ><?=$_GET["y"] ?></div>
            </div>
            <div>
                <div>&nbsp;</div>
            </div>
            <div>
                <div>
                    <table>
                        <tr>                            
                            <td class="tabla_datos_actividades_titulo" >Dia</td>
                            <td class="tabla_datos_actividades_titulo">Horas</td>
                            <td class="tabla_datos_actividades_titulo">Actividad</td>
                        <?php 
                            //SI SE VAN A ELIMINAR ACTIVIDADES
                            if($_GET["a"]=="2")
                            {
                        ?>
                                <td class="tabla_datos_actividades_titulo"></td>
                        <?php       
                            }
                        ?>                            
                        </tr>
            <?php            
            
                while($datos_actividades_horas= mysqli_fetch_array($valores_actividades_horas2))
                {
                    //CONSULTAMOS EL DIA DE LA SEMANA, CON EL FIN DE PINTAR LOS SABADOS Y DOMINGOS
                    $css_dia=dia_semana($_GET["m"], $datos_actividades_horas["dia"], $_GET["y"]);                    
            ?>                        
                        <tr>                            
                            <td class="<?=$css_dia[0] ?>" ><?=$datos_actividades_horas["dia"]."<br> <label class='inicial_dia_semana' > ".$dias_semana[$css_dia[1]]."</label>" ?></td>
                            <td class="tabla_datos_actividades_td_total_horas" ><?=$datos_actividades_horas["cantidad_horas_actividad"] ?></td>
                            <td><textarea name="actividad<?=$datos_actividades_horas["dia"] ?>" id="actividad<?=$datos_actividades_horas["dia"] ?>" cols="34" rows="3" <?=$disabled ?> required ><?=$datos_actividades_horas["descripcion_actividad"] ?></textarea></td>
                        <?php 
                            //SI SE VAN A ELIMINAR ACTIVIDADES
                            if($_GET["a"]=="2")
                            {
                        ?>
                                <td> <input type="checkbox" id="<?=$datos_actividades_horas["dia"] ?>" name="<?=$datos_actividades_horas["dia"] ?>" ></td>
                        <?php       
                            }
                        ?>
                        </tr>
            <?php 
                }
            ?>                        
                    </table>                    
                </div>           
                
            </div>
            <div>
                <input type="submit" value="<?=($_GET["a"]=="1") ? "Editar" : "Eliminar" ?>" id="registrar" name="registrar" class="botonSubmit" >
                <input type="hidden" id="ban" name="ban" value="1" >
            </div>
        
        </form>
    </div>



<?php 
    include("piePagina.php");
    include("cerrarConexion.php");
 ?>