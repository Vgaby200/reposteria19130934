<?php
require 'global/conexion.php';
require 'global/conexion2.php';
require 'templates/cabecera.php';
session_start();

$mensaje="";
if(isset($_POST['btnAccion'])){
    switch($_POST['btnAccion']){
        case 'Agregar':
            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $ID=openssl_decrypt($_POST['id'],COD,KEY);
                $mensaje.="ID CORRECTO".$ID."<br/>";
            }else{$mensaje.="ID INCORRECTO".$ID."<br/>";}
            if(is_string(openssl_decrypt($_POST['postre'],COD,KEY))){
                $POSTRE=openssl_decrypt($_POST['postre'],COD,KEY);
                $mensaje.="POSTRE CORRECTO".$POSTRE."<br/>";
            }else{$mensaje.="NOMBRE INCORRECTO".$POSTRE."<br/>";break;}
            if(is_numeric(openssl_decrypt($_POST['cantidad'],COD,KEY))){
                $CANTIDAD=openssl_decrypt($_POST['cantidad'],COD,KEY);
                $mensaje.="CANTIDAD CORRECTO".$CANTIDAD."<br/>";
            }else{$mensaje.="CANTIDAD INCORRECTO".$CANTIDAD."<br/>";break;}
            if(is_numeric(openssl_decrypt($_POST['precio'],COD,KEY))){
                $PRECIO=openssl_decrypt($_POST['precio'],COD,KEY);
                $mensaje.="PRECIO CORRECTO".$PRECIO."<br/>";
            }else{$mensaje.="PRECIO INCORRECTO".$PRECIO."<br/>";break;}

            if(!isset($_SESSION['CARRITO'])){//agregar el primer producto al carrito
                $producto=array('ID'=>$ID,'POSTRE'=>$POSTRE,'CANTIDAD'=>$CANTIDAD,'PRECIO'=>$PRECIO);
                //$_SESSION['CARRITO'][0]=$producto;
                $sql=$pdo->prepare("INSERT INTO lista (id,postre,precio,cantidad) VALUES ('$ID','$POSTRE','$PRECIO','$CANTIDAD');");
               // $sql->execute();
                echo "<script>alert('Producto agregado al carrito')</script>";
            }else{
                $idProductos=array_column($_SESSION['CARRITO'],"ID");//cuando el producto ya está en el carrito
                if(in_array($ID, $idProductos)){
                    $producto=array('ID'=>$ID,'POSTRE'=>$POSTRE,'CANTIDAD'=>$CANTIDAD,'PRECIO'=>$PRECIO);
                    //$_SESSION['CARRITO'][$ID]=$producto;
                    $sql=$pdo->prepare("UPDATE lista SET cantidad='$CANTIDAD' WHERE id='$ID';");
                    $sql->execute();
                    echo "<script>alert('Este producto ya fue agregado al carrito')</script>";
                }
                else{
                $NumeroProductos=count($_SESSION['CARRITO']);
                $producto=array('ID'=>$ID,'POSTRE'=>$POSTRE,'CANTIDAD'=>$CANTIDAD,'PRECIO'=>$PRECIO);
                //$_SESSION['CARRITO'][$NumeroProductos]=$producto;
                $sql=$pdo->prepare("INSERT INTO lista (id,postre,precio,cantidad) VALUES ('$ID','$POSTRE','$PRECIO','$CANTIDAD');");
                $sql->execute();
                echo "<script>alert('Postre agregado al pedido')</script>";
                }
            }
            //$mensaje=print_r($_SESSION,true);
            $_SESSION['CARRITO'][$ID]=$producto;
        break;

        case 'Eliminar':
            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $ID=openssl_decrypt($_POST['id'],COD,KEY);
                foreach($_SESSION['CARRITO'] as $indice=>$producto){
                    if($producto['ID']==$ID){
                        unset($_SESSION['CARRITO'][$indice]);
                        echo "<script>alert('Elemento eliminado')</script>";
                        $sql1=$pdo->prepare("DELETE FROM lista WHERE id='$ID';");
                        $sql1->execute();
                    }
                }
                //$mensaje.="ID CORRECTO".$ID."<br/>";
            }else{$mensaje.="ID INCORRECTO".$ID."<br/>";}
            
        break;

        case 'Proceder':
            $conexion=conectar();
            $carritoids=array();
            foreach ($_SESSION['CARRITO'] as $indice => $productoids) {
                array_push($carritoids, $productoids['ID']);
            }
            for ($i=0; $i < count($carritoids); $i++) { 
                $update="UPDATE inventario SET disp = disp - (SELECT cantidad FROM lista WHERE id = '$carritoids[$i]') WHERE id = '$carritoids[$i]';";
                $queryup=mysqli_query($conexion,$update);
            }
        break;
    }
}

?>