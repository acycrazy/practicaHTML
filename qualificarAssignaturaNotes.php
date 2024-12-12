#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: qualificar una assignatura, posar notes</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  $consulta="SELECT nom FROM Assignatures WHERE codi=:codiAssignatura";
  $comanda = oci_parse($conn, $consulta);
  oci_bind_by_name($comanda,":codiAssignatura",$_POST['assignatura']);
  $exit = oci_execute($comanda);
  $fila= oci_fetch_array($comanda);
  capcalera("Qualificar l'assignatura ".$fila['NOM']); 
?>
  <form action="qualificarAssignaturaNotes_BD.php" method="post">
<?php 
  $assignatures = "SELECT a.codi, a.cognoms || ', '|| nom AS nom, m.nota 
                     FROM Alumnes a JOIN Matricula m ON a.codi=m.alumne
                     WHERE m.assignatura=:assignatura";
  $comanda = oci_parse($conn, $assignatures);
  oci_bind_by_name($comanda,":assignatura",$_POST['assignatura']);
  $exit=oci_execute($comanda);
  if (!$exit){
      mostraErrorExecucio($comanda);
  }
  while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
      echo "    <p><label>".$fila['CODI']." - ".$fila['NOM'].": </label>";
      echo ' <input type="number" value="'.$fila['NOTA'].'" name="'.$fila['CODI'].'"></p>'."\n";
  }
  echo '    <p><input type = "hidden" name="assignatura" value="'.$_POST['assignatura'].'"></p>';
?>      
    <p><label>&nbsp;</label><input type = "submit" value="Qualificar"></p>
  </form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
