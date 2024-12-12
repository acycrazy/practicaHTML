#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: qualificar una assignatura, seleccio assignatura</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Qualificar una assignatura"); 
 ?>
  <form action="qualificarAssignaturaNotes.php" method="post">
  <p><label>Assignatura:</label>
      <select name="assignatura">
<?php 
    $assignatures = "SELECT codi, nom FROM Assignatures WHERE codi IN (SELECT UNIQUE assignatura FROM Matricula) order by nom";
    $comanda = oci_parse($conn, $assignatures);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['CODI'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "      </select></p>";
  ?>      
  <p><label>&nbsp;</label><input type = "submit" value="Qualificar"></p>
  </form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
