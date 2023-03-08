<?php

    session_start();

    $_SESSION['nombreUsuario'] = NULL;
    $_SESSION['idUsuario'] = NULL;  
    session_destroy();  

    header('Location: index.php');
    die(); 

?>