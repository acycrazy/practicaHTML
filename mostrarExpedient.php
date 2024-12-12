#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: mostrar alumnes a partir d'una data</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Mostrar expedient alumnes per edat"); 
?>
  <h2>Indicar data de tall</h2>
  <p>Es mostraran els expedients dels alumnes nascuts a partir de la data indicada (inclosa):</p>
  <form action="mostrarExpedient_BD.php" method="post">
    <p><label>data de naixement de tall:</label><input type="date" name="dataTall"></p>
    <p><label>&nbsp</label><input type = "submit" value="Mostrar expedients"></p>
  </form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
