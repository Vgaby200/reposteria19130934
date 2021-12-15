<?php
  include("login/conexionL.php");
  $conn = connect();

  if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT ID, CORREO, CONTRA FROM registro WHERE ID = :ID');
    $records->bindParam(':ID', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
      $user = $results;
    }
  }
?>

    <?php if(!empty($user)): ?>
      <div align=right> Bienvenido <?php $user['CORREO']; ?>
      <a href="salir.php">SALIR</a>
    <?php else: 
        require 'cabecera.php';
    endif; ?>
  </body>
</html>