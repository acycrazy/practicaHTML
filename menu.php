#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: menú</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';
  iniciaSessio();
  // emmagatzem usuari i password en una sessió per tenir-los disponibles 
  if (!empty($_POST['username'])) { // Hem arribat des de index.html
    $_SESSION['usuari'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    // ara comprovem usuari i password intentant establir connexió amb Oracle    
    connecta($conn);
  }
?>
  <h1>Exemple PHP+SQL Oracle+HTML curs 24/25</h1>
  <h2>Operacions disponibles</h2>
  <p> <a class="menu" href="mostrarAssignatures.php">1) Mostrar totes les assignatures</a></p>
  <p> <a class="menu" href="afegirAssignatura.php">2) Afegir una nova assignatura</a></p>
  <p> <a class="menu" href="qualificarAssignatura.php">3) Qualificar assignatura</a></p>
  <p> <a class="menu" href="mostrarExpedient.php">4) Mostrar expedient alumnes per edat</a></p>  
  <p> <a class="menu" href="donarAltavehicle.php">a) Donar d’alta un vehicle</a></p>
  <?php peu("Tornar a la pàgina de login","index.html");?>
</body>
</html>
