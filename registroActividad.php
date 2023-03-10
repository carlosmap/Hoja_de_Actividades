<?php 
    include("cabecera.php") ;
    include("conexion.php");
    include("validaSession.php");

    
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
            header("refresh:5;url=hojaActividades.php");
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
</span>
    <div id="index_form">
        <form action="#" method="POST" >
            <div>                
                <div id="titulo_accion">Registrar Actividad</div>
            </div>

            <div>
                <div id="contenedor_boton_regresar" ><a href="hojaActividades.php" > <img src="img/regresar.png" alt="Regresar" title="Regresar" class="icono_navegacion" ></a></div>
                <label >Proyecto</label>

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
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" required value="<?=$_POST["fecha"]; ?>" >
            </div>
            <div>
                <label for="horas">Cantidad de horas</label>
                <input type="number" id="horas" name="horas" min="1" max="24" size="3" required value="<?=$_POST["horas"]; ?>" >
            </div>
            <div>
                <div>
                    <label for="actividad">Actividad</label>
                </div>           
                <textarea name="actividad" id="actividad" cols="40" rows="10" required ><?=$_POST["actividad"]; ?></textarea>
            </div>
            <div>
                <input type="submit" value="Registrar" id="registrar" name="registrar" class="botonSubmit" >
            </div>
        
        </form>
    </div>
    

<?php 
    include("piePagina.php");
    include("cerrarConexion.php");
 ?>