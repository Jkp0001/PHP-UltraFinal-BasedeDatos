<?php 
    class Cest{
        public int $idCesta;
        public string $usuario;
        public int $precioTotal;

        function __construct(int $idCesta,
                            string $usuario,
                            int $precioTotal
                            ){
            $this -> idCesta = $idCesta;
            $this -> usuario = $usuario;
            $this -> precioTotal = $precioTotal;;
        }
    }
?>
