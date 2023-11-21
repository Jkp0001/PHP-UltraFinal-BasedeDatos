<?php
    $servidor='localhost';
    $usuario = 'root';
    $contraseña = '123456';
    $base_de_datos = 'bbdd_Amazon';

    $conexion = new Mysqli($servidor,$usuario,$contraseña,$base_de_datos) or die("Error de conexión");
    
?>