#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Mostrar Vehicles</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Llistat de Vehicles"); 

  $consulta = "SELECT v.codi, v.descripcio, v.color, v.combustible, v.consum, 
                       (v.consum * c.preuUnitat) AS cost_per_100km, 
                       u.nom || ' ' || u.cognoms AS propietari
                  FROM Vehicles v
                  JOIN Combustibles c ON v.combustible = c.descripcio
                  JOIN Usuaris u ON v.propietari = u.alias
                 ORDER BY v.codi";

  $comanda = oci_parse($conn, $consulta);
  if (!$comanda) { mostraErrorParser($conn, $consulta); } // Mostrem error i avortem

  $exit = oci_execute($comanda);
  if (!$exit) { mostraErrorExecucio($comanda); } // Mostrem error i avortem

  $numColumnes = oci_num_fields($comanda);

  // Mostrem les capçaleres
  echo "<table border='1'>\n";
  echo "  <tr>";
  for ($i = 1; $i <= $numColumnes; $i++) {
      echo "<th>" . htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>"; 
  }
  echo "</tr>\n";

  // Recorrem les files
  while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
      echo "  <tr>";
      foreach ($fila as $element) {
          echo "<td>" . ($element !== null ? htmlentities($element, ENT_QUOTES) : "&nbsp;") . "</td>";
      }
      echo "</tr>\n";
  }
  echo "</table>\n";

  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal", "menu.php");
?>
</body>
</html>