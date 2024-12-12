#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: mostrar totes les assignatures</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Mostrar expedient alumnes nascuts a partir del ".$_POST['dataTall']); 
  $consulta="SELECT codi, cognoms||', '|| nom AS nom, 
                    trunc(months_between(current_date,naixement)/12) as edat
               FROM Alumnes
               WHERE naixement>=to_date(:dataTall,'YYYY-MM-DD')
               ORDER BY naixement"; 
  $comanda = oci_parse($conn, $consulta);
  if (!$comanda) { mostraErrorParser($conn,$consulta);} // mostrem error i avortem
  oci_bind_by_name($comanda,":dataTall",$_POST['dataTall']);
  $exit=oci_execute($comanda);
  if (!$exit) { mostraErrorExecucio($comanda);} // mostrem error i avortem
  // recorrem els alumnes i mostrem els expedients
  $consultaExpedient="SELECT a.codi, a.nom, a.credits, m.convocatoria, m.nota
                       FROM Assignatures a JOIN Matricula m ON m.assignatura=a.codi
                       WHERE m.alumne=:alumne";
  $comandaExpedient=oci_parse($conn,$consultaExpedient);
  $consultaMitjana="SELECT al.codi, TO_CHAR(ROUND(SUM(a.credits*m.nota)/SUM(a.credits),2),'99.99') AS mitjana
                       FROM Assignatures a JOIN Matricula m ON m.assignatura=a.codi
                            JOIN Alumnes al ON m.alumne=al.codi
                       WHERE m.alumne=:alumne
                       GROUP BY al.codi";
  $comandaMitjana=oci_parse($conn,$consultaMitjana);    
  while (($fila1 = oci_fetch_array($comanda, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<h3>".$fila1['CODI']." ".$fila1['NOM']." (".$fila1['EDAT']." anys)</h3>\n";
    oci_bind_by_name($comandaExpedient,":alumne",$fila1['CODI']);
    $exit = oci_execute($comandaExpedient);
    $fila2 = oci_fetch_array($comandaExpedient, OCI_ASSOC+OCI_RETURN_NULLS);
    if($fila2==false){
      echo "<p>No està matriculat a cap assignatura</p>\n";
    } else {
      echo "<table>\n";
      echo "<tr><th>Codi</th><th>Assignatura</th><th>Crèdits</th>
            <th>Convocatòria</th><th>Nota</th></tr>\n";
      while ($fila2 != false) {
        echo "<tr><td>".$fila2['CODI']."</td><td>".$fila2['NOM']."</td><td>".
             $fila2['CREDITS']."</td><td>".$fila2['CONVOCATORIA']."</td><td>".
             $fila2['NOTA']."</td></tr>\n";
        $fila2 = oci_fetch_array($comandaExpedient, OCI_ASSOC+OCI_RETURN_NULLS);
      }
      echo "</table>\n";
      oci_bind_by_name($comandaMitjana,":alumne",$fila1['CODI']);
      oci_execute($comandaMitjana);
      $fila3 = oci_fetch_array($comandaMitjana, OCI_ASSOC+OCI_RETURN_NULLS);
      echo "<p><b>Mitjana: ".$fila3['MITJANA']."</b></p>\n";
    }
  }
  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>
