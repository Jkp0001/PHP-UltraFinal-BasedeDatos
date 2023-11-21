<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <?php require '../util/bbdd_Amazon.php' ?>
    <link rel="stylesheet" href="styles/Loginstyle.css">

</head>
<body>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario =($_POST["usuario"]);
            $contrasena =($_POST["contrasena"]);

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $conexion -> query($sql);

            if($resultado -> num_rows === 0){
              echo "<script>alert('Usuario Incorrecto')</script>";
            }else{

                while($fila = $resultado -> fetch_assoc()){ //coje una tabla y cada fila la transforma en una array
                    $contrasena_cifrada = $fila["contrasena"];
                    $rol = $fila["rol"];                    
                }
    
                $acceso_valido = password_verify($contrasena, $contrasena_cifrada);

                $sql = "SELECT idCesta FROM Cestas WHERE usuario = '$usuario'";
                $resultado = $conexion -> query($sql);

                while($fila = $resultado -> fetch_assoc()) {
                    $idCesta = $fila["idCesta"];
                }

                if($acceso_valido){
                    session_start();
                    $_SESSION["usuario"] = $usuario;
                    $_SESSION["rol"] = $rol;
                    $_SESSION["idCesta"] = $idCesta;
                    //$_SESSION["loksea"] = $lokesea;
                    header('location: index.php');
                }else{
                    echo "<script>alert('Contraseña Incorrecta')</script>";
                }
            }
        }

    ?>
<!-- partial:index.partial.html -->
<div class="page">
  <div class="container">
    <div class="left">
      <div class="login">Log In</div>
      <div class="eula">Inicia sesión para una mejor experiencia.</div>
    </div>
    <div class="right">
      <svg viewBox="0 0 320 300">
        <defs>
          <linearGradient
                          inkscape:collect="always"
                          id="linearGradient"
                          x1="13"
                          y1="193.49992"
                          x2="307"
                          y2="193.49992"
                          gradientUnits="userSpaceOnUse">
            <stop
                  style="stop-color:#ff00ff;"
                  offset="0"
                  id="stop876" />
            <stop
                  style="stop-color:#ff0000;"
                  offset="1"
                  id="stop878" />
          </linearGradient>
        </defs>
        <path d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143" />
      </svg>
      <div class="form">
        <form action="" method="post">
        <label for="usuario">Usuario</label>
        <input type="text" id="email" name="usuario">
        <label for="contrasena">Contraseña</label>
        <input type="password" id="password" name="contrasena">
        <input type="submit" id="submit" value="Submit">
        </form>
      </div>
    </div>
  </div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js'></script>
  <script src="styles/Loginscript.js"></script>
</body>
</html>