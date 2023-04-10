<?php
    include("conexion.php");

    if(trim($_POST["usuario"] != "") AND trim($_POST["password"] != "") ){
        $sql=" SELECT U.id_usuario, U.nombre_usuario, UP.password_usuario FROM Usuarios U
        INNER JOIN Usuarios_password UP on UP.id_usuario=U.id_usuario     
        WHERE U.login_usuario = '".$_POST["usuario"]."' and UP.estado_usuario_password=1 and U.estado_usuario=1 ";
        $valores=mysqli_query($con, $sql);        
        $datos= mysqli_fetch_array($valores);

        if( password_verify($_POST["password"] , $datos["password_usuario"]) )
        {   

            session_start();
            $_SESSION["nombreUsuario"]=$datos["nombre_usuario"];
            $_SESSION["idUsuario"]=$datos["id_usuario"];
            header("Location: hojaActividades.php");
            die(); 
        }
        else
        {
            
             header("Location:index.php?response=1");
            die(); 
            
        }
    }    
    else
    {
        
        header("Location:index.php");
        die(); 
        
    }
    
    include("cerrarConexion.php");
?>