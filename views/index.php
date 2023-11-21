<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/estilosIndex.css" >
    <?php require '../util/bbdd_Amazon.php' ?>
    <?php require '../util/Product.php' ?>


</head>

<body style="background:#FAEBD7">

    <?php
        session_start();
        if(isset($_SESSION["usuario"])){
            $usuario = $_SESSION["usuario"];
            if(isset($_SESSION["rol"])){
                $rol = $_SESSION["rol"];
            } else {
                $rol = null;
            }
        } else {
            $_SESSION["usuario"] = "invitado";
            $usuario = $_SESSION["usuario"];
            $rol = null;
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $idProducto = $_POST["idProducto"];            
            $idCesta = $_SESSION["idCesta"];
            $valor = $_POST["valor"];

            if(isset($idCesta)){
                $sql= "SELECT EXISTS(SELECT 1 FROM ProductosCestas WHERE idProducto='$idProducto' AND idCesta='$idCesta')";
                $res = $conexion->query($sql);
                $existe = mysqli_fetch_row($res)[0];

                if($existe){
                    $sql="SELECT cantidad FROM ProductosCestas WHERE idProducto = '$idProducto'";
                    $res = $conexion->query($sql);
                    $cantidad = mysqli_fetch_row($res)[0];
                    $cantidadActualizada = intval($valor) + intval($cantidad);
                    $sql = "UPDATE ProductosCestas SET cantidad = '$cantidadActualizada' WHERE idProducto='$idProducto' AND idCesta='$idCesta'";
                    $conexion -> query($sql);
                }else{
                    $sql = "INSERT INTO ProductosCestas (idProducto, idCesta, cantidad) VALUES ('$idProducto','$idCesta',$valor)";
                    $conexion->query($sql);
                }
            }

        } 
        
    ?>

    <header>
        <img src="img/Amazon_logo.svg.webp" alt="logo." style="width : 225px; heigth : 50px; padding-top: 10px;" >
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="cesta.php">Carrito</a></li> <!-- SI ERES INVITADO NO SALE -->
                <li><a href="Register.php">Registrarme</a></li>
                <?php
                if($rol == "Admin"){
                    echo "<li><a href='productos.php'>Insertar Producto</a></li>";
                }
                if($usuario != 'invitado'){
                    echo "<li><a href='../util/cerrar_sesion.php'>Cerrar Sesion</a></li>";
                }
                if($usuario != null){
                    echo "<li><a href='iniciar_sesion.php'>Iniciar Sesión</a></li>";
                }?>
            </ul>
        </nav>
    </header>

        <div class="container">
            <h2 >Welcome <?php echo $usuario ?></h2>
        </div>

    <?php
        $sql = "SELECT * FROM Productos";
        $resultado = $conexion -> query($sql);
        $productos = [];

        while($fila = $resultado -> fetch_assoc()){
            $idProductos = $fila["idProducto"];
            $nombreProductos = $fila["nombreProducto"];
            $precios = $fila["precio"];
            $descripciones = $fila["descripcion"];
            $cantidades = $fila["cantidad"];
            $imagenes = $fila["imagen"];

            $nuevo_producto = new Product($idProductos,$nombreProductos,$precios,$descripciones,$cantidades,$imagenes);
            array_push($productos,$nuevo_producto);
        }
    ?>

    <div class="container">
        <h1 class="text-center">Listado de Productos</h1> <!-- bootstrap -->
        <table class="table table-striped" style="border: 1px solid black">
            <thead class="table table-dark">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Imágen</th>
                    <th>🛒</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($productos as $producto) { ?>
                    <tr>
                        <td> <?php echo $producto -> idProducto ?> </td>
                        <td> <?php echo $producto -> nombreProducto ?> </td>
                        <td> <?php echo $producto -> precio ?> </td>
                        <td> <?php echo $producto -> descripcion ?> </td>
                        <td> <?php echo $producto -> cantidad ?> </td>
                        <td>
                            <img width='100px' height='110px' src="<?php echo $producto -> imagen ?>">
                        </td>
                        <td>
                            <form class="carrito" action="" method="post">
                            <input type="hidden" name="idProducto" value="<?php echo $producto -> idProducto ?>">

                            <input class="btn btn-warning" type="submit" value="Añadir a la cesta">
                            <select class="selection" style="width : 50px; heigth : 1px" name="valor">
                                <option value="1">1</option>
                                <option value="2" selected>2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody> 
        </table>
    </div> 



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>