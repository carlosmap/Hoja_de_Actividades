<?php include("cabecera.php") ?>

<main>

    <div id="index_form">
        <form action="validar.php" method="POST" >
            <div >
                <img src="img/logo.png" alt="Logo" id="logo" >
            </div>
            <div>
                <input type="text" id="usuario" name="usuario" placeholder="Usuario" autofocus class="campoTexto" required  >
            </div>
            <div>
                <input type="password" name="password" id="password" placeholder="Password" class="campoTexto" required >
            </div>
            <div>
                <input type="submit" name="iniciarSession" id="iniciarSession" value="Iniciar sesiòn" class="botonSubmit" > 
            </div>

            <div>
                <span>
                    <?php                     
                        if(($_GET["response"]==1))
                        { ?>
                            <span class="msj_error" > Por favor revisa tu usuario y password</span>
                    <?php
                        }
                    ?>
                </span>
            </div>
        </form>   
    </div>      
</main>


<?php 
    include("piePagina.php") ;
    include("cerrarConexion.php");
?>