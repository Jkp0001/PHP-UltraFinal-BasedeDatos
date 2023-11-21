<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <?php require '../util/util.php' ?>
    <?php require '../util/bbdd_Amazon.php' ?>
</head>
<body>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $temp_nombrePRoducto = depurar($_POST["nombreProducto"]);
        $temp_precio = depurar($_POST["precio"]);
        $temp_descripcion = depurar($_POST["descripcion"]);
        $temp_cantidad = depurar($_POST["cantidad"]);
        

        //imagen
        $nombre_fichero = $_FILES["imagen"]["name"];
        
        // termian imagen

        #Validación del nombreProducto
        if(strlen($temp_nombrePRoducto)==0){ // CONTROLO QUE EL STRING LENGTH no sea 0
            $err_nombreProducto = "El nombreProducto vacío";
        }else{
            $regex = '/^[a-zA-Z0-9\s]{2,40}+$/'; // se permite letras, números y espacios en blanco
            if(!preg_match($regex, $temp_nombrePRoducto)){ //CONTROLO QUE EL PATRÓN SE CUMPLE
                $err_nombreProducto = "El nombre del producto debe contener máximo 40 carácteres.";
            }else{
                $nombreProducto = $temp_nombrePRoducto;
            }
        }

        #Validación del precio
        if($temp_precio<=0 || $temp_precio>99999.99){
            $err_precio = "El precio no puede ser menor o igual a 0 o mayor de 99999.99";
        }else{
            $precio = $temp_precio;
        }
        
        #Validación de la descripcion
        if(strlen($temp_descripcion)==0){ // CONTROLO QUE EL STRING LENGTH no sea 0
            $err_descripcion = "No hay descripción";
        }else{
            if(strlen($temp_descripcion)>255){ //CONTROLO QUE EL PATRÓN SE CUMPLE
                $err_descripcion = "La descripción debe contener máximo 255 carácteres.";
            }else{
                $descripcion = $temp_descripcion;
            }
        }

        #Validación del cantidad
        if($temp_cantidad<0 || $temp_cantidad>99999){
            $err_cantidad = "La cantidad no puede ser menor a 0 o mayor que 99999";
        }else{
            $cantidad = $temp_cantidad;
        }

        #   Validación imgaen

        if($nombre_fichero){

            $ruta_temporal = $_FILES["imagen"]["tmp_name"];

            $ruta = "img/" . $nombre_fichero;
            echo $_FILES["imagen"]["size"];
            if(($_FILES["imagen"]["type"] == 'image/jpg' || $_FILES["imagen"]["type"] == 'image/jpeg' || $_FILES["imagen"]["type"] == 'image/png') && ($_FILES["imagen"]["size"] <= 5242800)){

                move_uploaded_file($ruta_temporal, $ruta);

            }
        }else{
            echo "Campo obligatorio";
        }
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Producto: </label>
        <input type="text" name="nombreProducto">
        <?php if(isset($err_nombreProducto)) echo $err_nombreProducto ?>

        <br>

        <label>Precio: </label>
        <input type="text" name="precio">
        <?php if(isset($err_precio)) echo $err_precio ?>

        <br>

        <label>Descripción: </label>
        <input type="text" name="descripcion"><br>        
        <?php if(isset($err_descripcion)) echo $err_descripcion ?>

        <br>

        <label>Cantidad: </label>
        <input type="text" name="cantidad"><br>        
        <?php if(isset($err_cantidad)) echo $err_cantidad ?>

        <br><br>

        <div class="mb-3">
                <label class="from-label">Imagen</label>
                <input class="from-control" type="file" name="imagen">
        </div>

        <input class="btn btn-primary" type="submit" value="Enviar">
    </form>

    <?php 
        if(isset($nombreProducto) && isset($precio) && isset($descripcion) && isset($cantidad)) {
            echo "<h3>$nombreProducto</h3>";
            echo "<h3>$precio</h3>";
            echo "<h3>$descripcion</h3>";
            echo "<h3>$cantidad</h3>";
            echo "<h3>$ruta</h3>";
            
        
            $sql = "INSERT INTO Productos (nombreProducto,precio,descripcion,cantidad,imagen) 
            VALUES ('$nombreProducto','$precio','$descripcion','$cantidad','$ruta')";

            $conexion->query($sql);

        }
    ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>