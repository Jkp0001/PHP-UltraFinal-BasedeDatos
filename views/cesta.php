<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/Cesta.css" >
    <?php require '../util/bbdd_Amazon.php' ?>
</head>
<body>
<div class="container">
        <h1>Mi Cesta</h1>
        <table class="table table-striped" style="border: 1px solid black">
            <thead class="table table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Imágen</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Precio Total</th>
                </tr>
            </thead>
            <tbody>
    <?php

    session_start();
    if(isset($_SESSION["idCesta"])){
        $idCesta = $_SESSION["idCesta"];
    } //Con esto pillo la idCesta del login

    if(isset($_SESSION["usuario"])){
        $usuario = $_SESSION["usuario"];
    }else{
        $usuario = "invitado";
    }

    


    if(isset($idCesta)){
        $sql = "SELECT p.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen FROM productos p INNER JOIN ProductosCestas pc ON p.idProducto = pc.idProducto WHERE pc.idCesta = $idCesta";
        $resultado = $conexion -> query($sql);
    
        if($resultado -> num_rows === 0){
            $error = "No hay productos en la cesta";
        }else{
            $totalCesta = 0;
            
            while($fila = $resultado -> fetch_assoc()){ /*pilla una fila y hace un array (clave = nombre variable y valor = datos)*/ 
                echo "
                    <tr>
                        <td>".$fila["nombreProducto"]."</td>   
                        <td>" ?> <img src="<?php echo $fila["imagen"];?>" alt="<?php $fila["nombreProducto"];?>"> </td>
                        <?php
                            echo "
                                <td>".$fila["precio"]."€/u</td>
                                <td>".$fila["cantidad"]."uds</td>";
                                $total = (floatval($fila['precio']) * floatval($fila['cantidad']));
                                echo "<td>". $total."€</td>";
                                    $totalCesta = $totalCesta + $total;
                                    $sql = "UPDATE cestas SET precioTotal='$totalCesta' WHERE idCesta='$idCesta'";
                                    $conexion -> query($sql);
                                "</tr>
                                <br>";
                                
            }
        }
    }    
        ?>
    
    </tbody> 
    </table>
    </div>
    <a href="index.php">Home</a>
    
    <form action="" method="post">
        <?php
            if($usuario != 'invitado'){
                echo "<input type='submit' value='Finalizar Pedido' class='btn btn-warning'>";
            }
        ?>
    </form>
    
    <?php 
    
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $sql = "INSERT INTO Pedidos (usuario,precioTotal) VALUES ('$usuario','$totalCesta')";
            $conexion -> query($sql);
            $idPedido = $conexion ->query("SELECT idPedido FROM Pedidos where usuario = '$usuario'");
            $idPedido = $idPedido->fetch_assoc()["idPedido"];

            $idProductos="SELECT idProducto,cantidad FROM ProductosCestas WHERE idCesta='".$_SESSION['idCesta']."';";
            $resultado = $conexion-> query($idProductos);
            $lineaPedido= 1;
            while($fila = $resultado->fetch_assoc()){
                $precio = $conexion ->query("SELECT precio FROM Productos WHERE idProducto='".$fila["idProducto"]."'");
                $precio = $precio->fetch_assoc()["precio"];
                $sql = "INSERT INTO LineasPedidos  VALUES ('$lineaPedido','".$fila["idProducto"]."', '$idPedido', '".$precio."', '".$fila['cantidad']."')";
                $conexion -> query($sql);
                $lineaPedido = $lineaPedido +1;
            }
                // Vaciar la cesta ProductosCestas
            $sqlProductosCestas = "DELETE FROM ProductosCestas WHERE idCesta = '$idCesta'";
            $conexion->query($sqlProductosCestas);

                // Pongo el Precio Total a 0
            $sqlCesta = "UPDATE Cestas SET precioTotal=0 WHERE idCesta = '$idCesta'";
            $conexion->query($sqlCesta);
        }
    ?>
</body>
</html>

