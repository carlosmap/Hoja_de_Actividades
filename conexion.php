<?php

$hostname="127.0.0.1";
$username="debian-sys-maint";
$password="IOOkRu6joSMzXmEh";
$dbname="HojaActividades";
$con="";

if (!($con = mysqli_connect($hostname,$username, $password)))	{
    echo "Error 101, por favor contacte al administrador del sistema  ";    
    exit();
}
if (!mysqli_select_db($con, $dbname)) {
    echo "Error 102, por favor contacte al administrador del sistema  ";
    exit();
}

?>