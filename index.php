<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
include 'carrusel.html';

?>


<br>
    
<div class="row">
 <?php
        $sentencia=$pdo->prepare("SELECT * FROM inventario");
        $sentencia->execute();
        $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        //print_r($listaProductos);
 ?>




<?php foreach($listaProductos as $producto){ ?>
    <div class="col-4"> 
        
    <div class="card">
        
    <img título="<?php echo $producto['postre'];?>" 
    alt="<?php echo $producto['postre'];?>" 
    class="card-img-top" src="<?php echo $producto['imagen'];?>" height="350px">
    <div class="card-body">
    <span><?php echo $producto['postre'];?></span>
    <h5 class="card-title"><?php echo $producto['precio'];?></h5>
     <form action="" method="post">
                
        <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['id'],COD,KEY);?>">
        <input type="hidden" name="postre" id="postre" value="<?php echo openssl_encrypt($producto['postre'],COD,KEY);?>">
        <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['precio'],COD,KEY);?>">
        <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,COD,KEY);?>">
        <button class="btn btn-primary" name="btnAccion" value="Agregar" type="submit">Añadir pedido</button>
        </form>
        </div>
        </div>
        </div>
    <?php } ?>

    </div>



    
<?php include 'templates/pie.php';?>

