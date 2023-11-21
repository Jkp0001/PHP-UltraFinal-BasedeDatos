<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require 'util.php' ?>
    <?php require 'bbdd_Amazon.php' ?>

</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_usuario = depurar($_POST["usuario"]);
        /* $temp_nombre = depurar($_POST["nombre"]);
        $temp_apellido = depurar($_POST["apellido"]); */
        $temp_edad = depurar($_POST["edad"]);
        $temp_contrasena = depurar($_POST["contrasena"]);



        #Validación del user
        if (strlen($temp_usuario) == 0) { // CONTROLO QUE EL TRING LENGTH
            $err_usuario = "El nombre del usuario es obligatorio";
        } else {
            $regex = "/^[a-zA-Z_][a-zA-Z_]{4,12}$/"; //empieza por 1 letra min o mayusq o _ y despues tiene k tener entre 3 y 7 letras, num o _
            if (!preg_match($regex, $temp_usuario)) { //CONTROLO QUE EL PATRÓN SE CUMPLE
                $err_usuario = "El nombre de usuario debe contener de 4 a 8 caracteres o números o barrabaja";
            } else {
                $usuario = $temp_usuario;
            }
        }

        #Validación contraseña
        if (strlen($temp_contrasena) == 0) {
            $err_contrasena = "La contraseña es obligatoria";
        } else {
            $regex = "/^[a-zA-Z0-9\s]{8,55}$/";
            if (!preg_match($regex, $temp_contrasena)) {
                $err_contrasena = "La contraseña debe contener mínimo 8 caractéres";
            } else {
                $contrasena = $temp_contrasena;
            }
        }

        if (strlen($temp_edad) == 0) {
            $err_edad = "La fecha es obligatoria";
        } else {
            $fechaActual = date("Y-m-d");
            list($anio_actual, $mes_actual, $dia_actual) = explode('-', $fechaActual);
            list($anio_nacimiento, $mes_nacimiento, $dia_nacimiento) = explode('-', $temp_edad);

            if ($anio_actual - $anio_nacimiento < 18) {
                $err_edad = "No puede ser menor de edad";
            } elseif ($anio_actual - $anio_nacimiento == 18) {
                if ($mes_actual - $mes_nacimiento < 0) {
                    $err_edad = "No puede ser menor de edad";
                } elseif ($mes_actual - $mes_nacimiento == 0) {
                    if ($dia_actual - $dia_nacimiento < 0) {
                        $err_edad = "No puede ser menor de edad";
                    } else {
                        $edad = $tem_edad;
                    }
                } elseif ($mes_actual - $mes_nacimiento > 0) {
                    $edad = $tem_edad;
                }
            } elseif ($anio_actual - $anio_nacimiento > 18) {
                $edad = $tem_edad;
            }
        }
    }
    ?>
    <form action="" method="post">
        <label>Usuario: </label>
        <input type="text" name="usuario">
        <?php if (isset($err_usuario)) echo $err_usuario ?>
        <br>
        <label>Contraseña: </label>
        <input type="password" name="contrasena"><br>
        <?php if (isset($err_contrasena)) echo $err_contrasena ?>
        <br>
        <label>Fecha de Nacimiento: </label>
        <input type="date" name="edad"><br>
        <?php if (isset($err_edad)) echo $err_edad ?>
        <br><br>
        <input type="submit" value="Enviar">
    </form>
    <?php if (isset($usuario) && isset($contrasena)) {
        echo "<h3>$usuario</h3>";
        echo "<h3>$edad</h3>";
        echo "<h3>$contrasena</h3>";
    }

    $sql = "INSERT INTO Usuarios (usuario,contrasena,fechaNacimiento) VALUES ('$usuario','$contrasena','$edad')";

    $conexion->query($sql);
    ?>
</body>

</html>